from sqlalchemy import Column, Integer, String, Text, Float, ForeignKey
from database import Base




class Topic(Base):
    __tablename__ = "topics"

    id = Column(Integer, primary_key=True, index=True)

    conversation_id = Column(Integer)

    name = Column(String(255))

    start_time = Column(Float)

    end_time = Column(Float)


class ResumeTopic(Base):
    __tablename__ = "resume_topic"

    id = Column(Integer, primary_key=True, index=True)
    conversation_id = Column(Integer)
    topic_id = Column(Integer)
    text = Column(Text)


class ResumeGeneral(Base):
    __tablename__ = "resume_general"

    id = Column(Integer, primary_key=True)
    conversation_id = Column(Integer)
    text = Column(Text)
    
class Dialogue(Base):
    __tablename__ = "dialogues"

    id = Column(Integer, primary_key=True, index=True)

    actor = Column(String(255), nullable=True)

    text = Column(Text, nullable=True)

    start = Column(Float, nullable=True)

    end = Column(Float, nullable=True)

    conversation_id = Column(Integer)
    
class Conversation(Base):
    
    __tablename__ = "conversations"

    id = Column(Integer, primary_key=True, index=True)

    name = Column(String(255), nullable=True)

    path = Column(String(255), nullable=True)

    duration = Column(String(255), nullable=True)

    note_ai = Column(Float, nullable=True)

    is_transcripted = Column(Integer, default=0)

    is_evaluate = Column(Integer, default=0)
    
class Evaluation(Base):
    
    __tablename__ = "evaluations"

    id = Column(Integer, primary_key=True)

    conversation_id = Column(Integer)

    score_global = Column(Integer)

    politesse_score = Column(Integer)

    clarte_score = Column(Integer)

    respect_script_score = Column(Integer)

    satisfaction_client_score = Column(Integer)

    points_forts = Column(Text)

    points_faibles = Column(Text)

    commentaire_ai = Column(Text)