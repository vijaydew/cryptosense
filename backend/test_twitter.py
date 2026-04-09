import sys
import os
sys.path.append('.')

from tweeter_service import TwitterService

def test_twitter():
    print("🧪 Testing Twitter service...")
    twitter = TwitterService()
    tweets = twitter.search_crypto_tweets(max_results=10)
    print(f"📊 Test result: Found {len(tweets)} tweets")
    
    if tweets:
        for i, tweet in enumerate(tweets[:3]):  # Show first 3 tweets
            print(f"🐦 Tweet {i+1}: {tweet['text'][:100]}...")

if __name__ == "__main__":
    test_twitter()