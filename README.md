# 🤖 QUALICALL

### AI-Powered Call Analysis and Evaluation Platform

QUALICALL is an intelligent platform designed to analyze and evaluate call-center conversations using Artificial Intelligence.

The platform allows users to upload audio conversations, transcribe them, analyze their content, generate summaries, and evaluate the quality of the conversation.

---

## 📸 Project Preview

### Dashboard

![QUALICALL Dashboard](docs/images/dashboard.png)

### Audio Upload

![Audio Upload](docs/images/upload.png)

### Conversation Transcription

![Transcription](docs/images/transcription.png)

### AI Evaluation

![AI Evaluation](docs/images/evaluation.png)

---

## 🎥 Demo

▶️ **Watch the project demonstration**

[🎬 QUALICALL Demo](docs/demo/qualicall-demo.mp4)

---

## ✨ Main Features

- 🎙️ Upload call recordings
- 📝 Automatic speech-to-text transcription
- 🤖 AI-powered conversation analysis
- 📊 Conversation quality evaluation
- 🧠 AI-generated summaries
- 🔎 Identification of conversation topics
- 📈 Quality scoring
- 🌍 Multilingual interface
- 🔐 Authentication and authorization
- 👥 User and agent management

---

## 🏗️ Project Architecture

```text
                    ┌─────────────────┐
                    │   Vue.js Client │
                    │    Vuetify UI   │
                    └────────┬────────┘
                             │
                             │ REST API
                             ▼
                    ┌─────────────────┐
                    │ Laravel Backend │
                    │   Sanctum Auth  │
                    └────────┬────────┘
                             │
                    ┌────────┴────────┐
                    │                 │
                    ▼                 ▼
             ┌─────────────┐   ┌─────────────┐
             │   MySQL     │   │  FastAPI    │
             │  Database   │   │ AI Service  │
             └─────────────┘   └──────┬──────┘
                                      │
                              ┌───────┴────────┐
                              │                │
                              ▼                ▼
                           Whisper         Gemini AI
