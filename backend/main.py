from fastapi import FastAPI, Depends, HTTPException, BackgroundTasks
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy.orm import Session
from datetime import datetime, timedelta
import asyncio
import json

from database import get_db, Tweet, SentimentSummary, CryptoPrice
from sentiment_analyzer import analyzer
from tweeter_service import TwitterService
from price_service import PriceService

app = FastAPI(
    title="Crypto Sentiment API",
    version="1.0.0",
    description="Real-time cryptocurrency sentiment analysis from Twitter"
)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Initialize services
tweeter_service = TwitterService()
price_service = PriceService()

@app.get("/")
async def root():
    return {
        "message": "Crypto Sentiment Analysis API",
        "status": "active",
        "version": "1.0.0"
    }

@app.get("/collect/twitter")
async def collect_twitter_data(background_tasks: BackgroundTasks, db: Session = Depends(get_db)):
    """Collect and analyze Twitter data in background"""
    background_tasks.add_task(collect_and_analyze_tweets, db)
    return {"status": "started", "message": "Twitter data collection started in background"}

async def collect_and_analyze_tweets(db: Session):
    """Background task to collect and analyze tweets"""
    try:
        print("Starting Twitter data collection...")
        
        # Collect tweets
        tweets = tweeter_service.search_crypto_tweets(max_results=50)
        print(f"Collected {len(tweets)} tweets")
        
        new_tweets = 0
        for tweet_data in tweets:
            # Check if tweet already exists
            existing = db.query(Tweet).filter(Tweet.tweet_id == tweet_data['tweet_id']).first()
            if existing:
                continue
            
            # Analyze sentiment
            sentiment = analyzer.analyze_text(tweet_data['text'])
            
            # Classify cryptocurrency
            crypto = tweeter_service.classify_cryptocurrency(tweet_data['text'])
            
            # Save to database
            tweet = Tweet(
                tweet_id=tweet_data['tweet_id'],
                text=tweet_data['text'],
                sentiment_score=sentiment['score'],
                sentiment_label=sentiment['label'],
                cryptocurrency=crypto,
                created_at=tweet_data['created_at'],
                author_id=tweet_data['author_id'],
                author_username=tweet_data['author_username'],
                likes=tweet_data['likes'],
                retweets=tweet_data['retweets'],
                is_processed=True
            )
            db.add(tweet)
            new_tweets += 1
        
        db.commit()
        
        # Update sentiment summary
        update_sentiment_summary(db)
        
        # Update prices
        update_crypto_prices(db)
        
        print(f"Successfully processed {new_tweets} new tweets")
        return new_tweets
        
    except Exception as e:
        print(f"Error in background task: {e}")
        db.rollback()

def update_sentiment_summary(db: Session):
    """Update sentiment summary for dashboard"""
    try:
        # Get data from last 24 hours
        time_threshold = datetime.utcnow() - timedelta(hours=24)
        
        tweets = db.query(Tweet).filter(Tweet.created_at >= time_threshold).all()
        
        if tweets:
            total_tweets = len(tweets)
            positive_count = len([t for t in tweets if t.sentiment_label == 'positive'])
            negative_count = len([t for t in tweets if t.sentiment_label == 'negative'])
            neutral_count = len([t for t in tweets if t.sentiment_label == 'neutral'])
            avg_sentiment = sum(t.sentiment_score for t in tweets) / total_tweets
            
            summary = SentimentSummary(
                timestamp=datetime.utcnow(),
                total_tweets=total_tweets,
                positive_count=positive_count,
                negative_count=negative_count,
                neutral_count=neutral_count,
                average_sentiment=avg_sentiment,
                cryptocurrency='all'
            )
            db.add(summary)
            db.commit()
    except Exception as e:
        print(f"Error updating sentiment summary: {e}")

def update_crypto_prices(db: Session):
    """Update cryptocurrency prices"""
    try:
        prices = price_service.get_crypto_prices()
        
        for crypto, price_data in prices.items():
            price_record = CryptoPrice(
                cryptocurrency=crypto,
                price=price_data['price'],
                change_24h=price_data['change_24h'],
                timestamp=datetime.utcnow()
            )
            db.add(price_record)
        
        db.commit()
    except Exception as e:
        print(f"Error updating prices: {e}")

@app.get("/dashboard/data")
async def get_dashboard_data(db: Session = Depends(get_db)):
    """Get all data needed for the dashboard"""
    try:
        # Get latest sentiment summary
        summary = db.query(SentimentSummary).order_by(SentimentSummary.timestamp.desc()).first()
        
        # Get recent tweets with sentiment
        recent_tweets = db.query(Tweet).order_by(Tweet.created_at.desc()).limit(20).all()
        
        # Get current prices
        current_prices = db.query(CryptoPrice).order_by(CryptoPrice.timestamp.desc()).limit(5).all()
        
        # Get sentiment history for charts (last 10 summaries)
        sentiment_history = db.query(SentimentSummary).order_by(SentimentSummary.timestamp.desc()).limit(10).all()
        
        return {
            "summary": {
                "total_tweets": summary.total_tweets if summary else 0,
                "positive_count": summary.positive_count if summary else 0,
                "negative_count": summary.negative_count if summary else 0,
                "neutral_count": summary.neutral_count if summary else 0,
                "average_sentiment": summary.average_sentiment if summary else 0,
                "last_updated": summary.timestamp.isoformat() if summary else None
            },
            "recent_tweets": [
                {
                    "id": tweet.id,
                    "text": tweet.text,
                    "sentiment": tweet.sentiment_label,
                    "score": tweet.sentiment_score,
                    "crypto": tweet.cryptocurrency,
                    "author": tweet.author_username,
                    "timestamp": tweet.created_at.isoformat(),
                    "likes": tweet.likes,
                    "retweets": tweet.retweets
                } for tweet in recent_tweets
            ],
            "prices": [
                {
                    "cryptocurrency": price.cryptocurrency,
                    "price": price.price,
                    "change_24h": price.change_24h,
                    "timestamp": price.timestamp.isoformat()
                } for price in current_prices
            ],
            "sentiment_history": [
                {
                    "timestamp": summary.timestamp.isoformat(),
                    "average_sentiment": summary.average_sentiment,
                    "positive_count": summary.positive_count,
                    "negative_count": summary.negative_count
                } for summary in sentiment_history
            ]
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/tweets/recent")
async def get_recent_tweets(limit: int = 50, db: Session = Depends(get_db)):
    """Get recent tweets with sentiment analysis"""
    tweets = db.query(Tweet).order_by(Tweet.created_at.desc()).limit(limit).all()
    
    return {
        "tweets": [
            {
                "id": tweet.id,
                "text": tweet.text,
                "sentiment": tweet.sentiment_label,
                "score": tweet.sentiment_score,
                "cryptocurrency": tweet.cryptocurrency,
                "author": tweet.author_username,
                "timestamp": tweet.created_at.isoformat(),
                "likes": tweet.likes,
                "retweets": tweet.retweets
            } for tweet in tweets
        ]
    }

if __name__ == "__main__":
    print("🚀 Starting Crypto Sentiment API Server...")
    print("📊 Sentiment analyzer loaded successfully")
    print("🐦 Twitter service initialized")
    print("💾 JSON database ready")
    print("🌐 Server starting on http://localhost:1999")  
    print("Press Ctrl+C to stop the server")
        
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=1999)  