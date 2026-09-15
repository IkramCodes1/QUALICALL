from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi import UploadFile, File
from pydantic import BaseModel

from database import SessionLocal
from models import Topic, ResumeTopic, ResumeGeneral , Dialogue ,Evaluation,Conversation

import shutil
import os
import json
import whisper
import google.generativeai as genai
import re

from dotenv import load_dotenv

app = FastAPI()
# python -m uvicorn main:app --reload --port 8080 
# python main.py


# =========================================================
# CORS
# =========================================================

app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:5173"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# =========================================================
# ENV
# =========================================================

load_dotenv()

genai.configure(
    api_key=os.getenv("GEMINI_API_KEY")
)

# =========================================================
# MODELS
# =========================================================

gemini_model = genai.GenerativeModel("gemini-2.5-flash")

whisper_model = whisper.load_model("base")

# =========================================================
# REQUEST MODEL
# =========================================================

class AudioRequest(BaseModel):
    conversation_id: int
    path: str

# =========================================================
# ROOT
# =========================================================

@app.get("/")
async def root():
    return {"message": "Hello FastAPI!"}

# =========================================================
# OPTIONAL UPLOAD TEST
# =========================================================

@app.post("/upload")
async def upload_audio(file: UploadFile = File(...)):

    try:

        file_path = f"audios/{file.filename}"

        with open(file_path, "wb") as buffer:
            shutil.copyfileobj(file.file, buffer)

        return {
            "success": True,
            "path": file_path
        }

    except Exception as e:

        return {
            "success": False,
            "error": str(e)
        }

# =========================================================
# MAIN PROCESS ENDPOINT
# =========================================================

@app.post("/process-audio")
async def process_audio_api(data: AudioRequest):

    try:

        analysis = process_audio(
            data.path,
            data.conversation_id
        )

        return {
            "success": True,
            "data": analysis
        }

    except Exception as e:

        print("ERROR:", str(e))

        return {
            "success": False,
            "error": str(e)
        }

# =========================================================
# TRANSCRIPTION
# =========================================================

def transcribe_audio(audio_path):
    
    result = whisper_model.transcribe(
        audio_path,
        word_timestamps=False
    )

    segments = result["segments"]

    dialogues = []

    for segment in segments:

        dialogues.append({
            "actor": "Speaker",
            "text": segment["text"],
            "start": round(segment["start"], 2),
            "end": round(segment["end"], 2)
        })

    return dialogues

# =========================================================
# GEMINI ANALYSIS
# =========================================================

def analyze_conversation(transcription):
    
    prompt = f"""
Tu es un assistant expert en analyse des conversations téléphoniques.

OBJECTIFS :
1. Générer un résumé général.
2. Identifier les topics principaux.
3. Générer un résumé pour chaque topic.
4. Évaluer la qualité de l’appel.

IMPORTANT :
- Réponds uniquement avec du JSON brut.
- Ne pas utiliser markdown.
- Ne pas utiliser ```json.
- Aucun texte avant ou après le JSON.
- Les topics doivent suivre l’ordre chronologique.
- Maximum 6 topics.
- Résumé en français.
- Tous les scores doivent être entre 0 et 100.

FORMAT JSON :

{{
    "general_summary": "string",

    "evaluation": {{

        "score_global": number,

        "politesse_score": number,

        "clarte_score": number,

        "respect_script_score": number,

        "satisfaction_client_score": number,

        "points_forts": "string",

        "points_faibles": "string",

        "commentaire_ai": "string"
    }},

    "topics": [
        {{
            "topic": "string",
            "start_time": number,
            "end_time": number,
            "summary": "string"
        }}
    ]
}}

TRANSCRIPTION:
{transcription}
"""

    try:

        response = gemini_model.generate_content(
            prompt,
            generation_config={
                "temperature": 0,
                "response_mime_type": "application/json"
            }
        )

        # RAW RESPONSE
        cleaned_text = response.text.strip()

        # REMOVE MARKDOWN
        cleaned_text = cleaned_text.replace("```json", "")
        cleaned_text = cleaned_text.replace("```", "")

        # EXTRACT JSON ONLY
        match = re.search(r'\{.*\}', cleaned_text, re.DOTALL)

        if match:
            cleaned_text = match.group()

        print("\n=== GEMINI RESPONSE ===\n")
        print(cleaned_text)

        return json.loads(cleaned_text)

    except Exception as e:

        print("\nERREUR GEMINI :")
        print(e)

        return {
            "general_summary": "Erreur analyse Gemini",

            "evaluation": {
                "score_global": 0,
                "politesse_score": 0,
                "clarte_score": 0,
                "respect_script_score": 0,
                "satisfaction_client_score": 0,
                "points_forts": "",
                "points_faibles": "",
                "commentaire_ai": "Erreur IA"
            },

            "topics": []
        }

# =========================================================
# SAVE TO DB
# =========================================================



def save_transcription_to_db(dialogues, conversation_id):
    
    db = SessionLocal()

    try:

        print("SAVE TRANSCRIPTION START")

        # DELETE OLD
        db.query(Dialogue).filter(
            Dialogue.conversation_id == conversation_id
        ).delete()

        db.commit()

        print("OLD TRANSCRIPTION DELETED")

        # SAVE NEW
        for dialogue in dialogues:

            print("INSERT:", dialogue)

            new_dialogue = Dialogue(
                conversation_id=conversation_id,
                actor=dialogue["actor"],
                text=dialogue["text"],
                start=dialogue["start"],
                end=dialogue["end"]
            )

            db.add(new_dialogue)

        db.commit()

        print("TRANSCRIPTION SAVED SUCCESS")

    except Exception as e:

        db.rollback()

        print("DB ERROR:", str(e))

    finally:
        db.close()

def save_analysis_to_db(analysis, conversation_id):
    
    db = SessionLocal()

    try:

        print("SAVE ANALYSIS START")

        db.query(ResumeGeneral).filter(
            ResumeGeneral.conversation_id == conversation_id
        ).delete()

        db.query(Topic).filter(
            Topic.conversation_id == conversation_id
        ).delete()

        db.query(ResumeTopic).filter(
            ResumeTopic.conversation_id == conversation_id
        ).delete()

        db.commit()

        print("OLD ANALYSIS DELETED")

        # GENERAL SUMMARY
        general_resume = ResumeGeneral(
            conversation_id=conversation_id,
            text=analysis["general_summary"]
        )

        db.add(general_resume)
        db.commit()

        print("GENERAL SUMMARY SAVED")

        # TOPICS
        for topic in analysis["topics"]:

            print("INSERT TOPIC:", topic)

            new_topic = Topic(
                start_time=topic["start_time"],
                end_time=topic["end_time"],
                conversation_id=conversation_id,
                name=topic["topic"]
            )

            db.add(new_topic)
            db.commit()

            db.refresh(new_topic)

            topic_resume = ResumeTopic(
                conversation_id=conversation_id,
                topic_id=new_topic.id,
                text=topic["summary"]
            )

            db.add(topic_resume)
            db.commit()

        print("TOPICS SAVED SUCCESS")

    except Exception as e:

        db.rollback()

        print("DB ANALYSIS ERROR:", str(e))

    finally:
        db.close()

def save_evaluation_to_db(analysis, conversation_id):
    
    db = SessionLocal()

    try:

        db.query(Evaluation).filter(
            Evaluation.conversation_id == conversation_id
        ).delete()

        db.commit()

        evaluation = analysis["evaluation"]

        new_evaluation = Evaluation(

            conversation_id=conversation_id,

            score_global=evaluation["score_global"],

            politesse_score=evaluation["politesse_score"],

            clarte_score=evaluation["clarte_score"],

            respect_script_score=evaluation["respect_script_score"],

            satisfaction_client_score=evaluation["satisfaction_client_score"],

            points_forts=evaluation["points_forts"],

            points_faibles=evaluation["points_faibles"],

            commentaire_ai=evaluation["commentaire_ai"]
        )

        db.add(new_evaluation)

        db.commit()

        print("EVALUATION SAVED")

    except Exception as e:

        db.rollback()

        print("EVALUATION ERROR:", str(e))

    finally:
        db.close()
        
 
# =========================================================
# MAIN PROCESS FUNCTION
# =========================================================

def process_audio(audio_path, conversation_id):
    
    print("===================================")
    print("TRANSCRIPTION CHECK...")
    print("===================================")

    # GET TRANSCRIPTION FROM DB
    transcription = get_transcription_from_db(
        conversation_id
    )

    # =========================================
    # TRANSCRIPTION EXISTS IN DB
    # =========================================

    if transcription.strip() != "":

        print("TRANSCRIPTION FROM DATABASE")

    # =========================================
    # NEW TRANSCRIPTION WITH WHISPER
    # =========================================

    else:

        print("NEW TRANSCRIPTION WITH WHISPER")

        transcription_data = transcribe_audio(
            audio_path
        )

        print("\n=== TRANSCRIPTION ===\n")
        print(transcription_data)

        # SAVE TRANSCRIPTION
        save_transcription_to_db(
            transcription_data,
            conversation_id
        )

        # CONVERT LIST TO TEXT
        transcription = ""

        for item in transcription_data:

            transcription += item["text"] + " "

   
    # =====================================================
    # GEMINI ANALYSIS
    # =====================================================

    print("\n===================================")
    print("ANALYSE GEMINI EN COURS...")
    print("===================================")

    analysis = analyze_conversation(
        transcription
    )

    # SAVE ANALYSIS
    save_analysis_to_db(
        analysis,
        conversation_id
    )

    # SAVE EVALUATION
    save_evaluation_to_db(
        analysis,
        conversation_id
    )

    # UPDATE STATUS
    update_conversation_status(
        conversation_id,
        analysis
    )

    print("\n=== RESULTAT FINAL ===\n")

    print(json.dumps(
        analysis,
        indent=4,
        ensure_ascii=False
    ))

    return analysis


# =========================================================
# GET TRANSCRIPTION FROM DB
# =========================================================

def get_transcription_from_db(conversation_id):

    db = SessionLocal()

    try:

        dialogues = db.query(Dialogue).filter(
            Dialogue.conversation_id == conversation_id
        ).all()

        transcription = ""

        for dialogue in dialogues:

            transcription += dialogue.text + " "

        return transcription

    except Exception as e:

        print("GET TRANSCRIPTION ERROR:", str(e))

        return ""

    finally:
        db.close()


# =========================================================
# UPDATE CONVERSATION STATUS
# =========================================================

def update_conversation_status(conversation_id, analysis):

    db = SessionLocal()

    try:

        conversation = db.query(Conversation).filter(
            Conversation.id == conversation_id
        ).first()

        if conversation:

            # TRANSCRIPTION DONE
            conversation.is_transcripted = True

            # EVALUATION DONE
            conversation.is_evaluate = True

            # AI SCORE
            conversation.note_ai = analysis["evaluation"]["score_global"]

            db.commit()

            print("STATUS UPDATED SUCCESS")

    except Exception as e:

        db.rollback()

        print("STATUS UPDATE ERROR:", str(e))

    finally:
        db.close()

# =========================================================
# TEST LOCAL
# =========================================================

if __name__ == "__main__":

    audio_path = "audios/test11.wav"

    print(os.path.exists(audio_path))

    process_audio(audio_path, 1)