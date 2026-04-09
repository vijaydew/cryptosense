from sqlalchemy import create_engine, Column, Integer, String, DateTime, Float, Text, Boolean
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
import os
from dotenv import load_dotenv

load_dotenv()

# Database URL - We'll use SQLite for now (easy to migrate to MySQL later)
DATABASE_URL = "sqlite:///./crypto_sentiment.db"

engine = create_engine(DATABASE_URL, connect_args={"check_same_thread": False})
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

class Tweet(Base):
    __tablename__ = "tweets"
    
    id = Column(Integer, primary_key=True, index=True)
    tweet_id = Column(String(100), unique=True, index=True)  # Twitter's ID
    text = Column(Text)
    sentiment_score = Column(Float)
    sentiment_label = Column(String(20))  # positive, negative, neutral
    cryptocurrency = Column(String(50))  # bitcoin, ethereum, general
    created_at = Column(DateTime)
    author_id = Column(String(100))
    author_username = Column(String(100))
    is_processed = Column(Boolean, default=False)
    likes = Column(Integer, default=0)
    retweets = Column(Integer, default=0)

class SentimentSummary(Base):
    __tablename__ = "sentiment_summary"
    
    id = Column(Integer, primary_key=True, index=True)
    timestamp = Column(DateTime)
    total_tweets = Column(Integer)
    positive_count = Column(Integer)
    negative_count = Column(Integer)
    neutral_count = Column(Integer)
    average_sentiment = Column(Float)
    cryptocurrency = Column(String(50))

class CryptoPrice(Base):
    __tablename__ = "crypto_prices"
    
    id = Column(Integer, primary_key=True, index=True)
    cryptocurrency = Column(String(50))
    price = Column(Float)
    change_24h = Column(Float)
    timestamp = Column(DateTime)

# Create tables
Base.metadata.create_all(bind=engine)

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()