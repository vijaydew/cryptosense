import tweepy
import os
import time
from datetime import datetime, timedelta
from dotenv import load_dotenv

load_dotenv()

class TwitterService:
    def __init__(self):
        print("🔄 Initializing Twitter Service...")
        
        # Debug: Check if environment variable is loaded
        bearer_token = os.getenv('TWITTER_BEARER_TOKEN')
        print(f"🔐 Bearer Token Status: {'✅ SET' if bearer_token else '❌ NOT SET'}")
        
        if not bearer_token:
            print("❌ CRITICAL: TWITTER_BEARER_TOKEN not found in .env file")
            print("💡 Make sure your .env file contains: TWITTER_BEARER_TOKEN=your_token_here")
            self.client = None
            return
            
        try:
            self.client = tweepy.Client(bearer_token=bearer_token)
            print("✅ Twitter client initialized successfully")
        except Exception as e:
            print(f"❌ Twitter client initialization failed: {e}")
            self.client = None
        
        self.crypto_keywords = {
            'bitcoin': ['bitcoin', 'btc', '#bitcoin', '#btc'],
            'ethereum': ['ethereum', 'eth', '#ethereum', '#eth'],
            'general': ['crypto', 'cryptocurrency', 'blockchain', '#crypto']
        }
        print("✅ Crypto keywords loaded")
    
    def search_crypto_tweets(self, max_results=3):
        """Search for tweets about major cryptocurrencies"""
        print(f"🔍 Starting tweet search (max_results: {max_results})...")
        
        # DEBUG POINT 1: Check if client is available
        if not self.client:
            print("❌ Twitter client not available - cannot search tweets")
            return []
        
        try:
            print("⏳ Adding 2-second delay to avoid rate limits...")
            time.sleep(2)  # 2 second delay between requests
            
            # Build search query
            query = "(bitcoin OR btc OR #bitcoin OR #btc OR ethereum OR eth OR #ethereum OR #eth OR crypto OR cryptocurrency) lang:en -is:retweet"
            print(f"📝 Search Query: {query}")
            
            # DEBUG POINT 2: API Call
            print("📡 Making Twitter API call...")
            
            # FIXED: Removed duplicate 'author_id' from tweet_fields
            tweets = self.client.search_recent_tweets(
                query=query,
                max_results=max_results,
                tweet_fields=['created_at', 'author_id', 'text', 'public_metrics'],  # REMOVED duplicate author_id
                expansions=['author_id'],
                user_fields=['username', 'name']
            )
            print("✅ Twitter API call completed")
            
            # DEBUG POINT 3: Check API response
            if not tweets:
                print("❌ Twitter API returned no response")
                return []
                
            print(f"📊 API Response Metadata: {getattr(tweets, 'meta', 'No metadata')}")
            
            tweet_data = []
            
            # DEBUG POINT 4: Check if we have tweet data
            if tweets.data:
                print(f"🎉 Found {len(tweets.data)} tweets in response")
                
                # DEBUG POINT 5: Check user expansions
                users = {}
                if tweets.includes and 'users' in tweets.includes:
                    users = {user.id: user for user in tweets.includes.get('users', [])}
                    print(f"👥 Found {len(users)} user profiles")
                else:
                    print("ℹ️ No user expansions in response")
                
                # Process each tweet
                for i, tweet in enumerate(tweets.data):
                    print(f"  Processing tweet {i+1}/{len(tweets.data)}...")
                    
                    author = users.get(tweet.author_id)
                    author_username = author.username if author else 'unknown'
                    
                    tweet_info = {
                        'tweet_id': str(tweet.id),
                        'text': tweet.text,
                        'created_at': tweet.created_at,
                        'author_id': str(tweet.author_id),
                        'author_username': author_username,
                        'likes': tweet.public_metrics['like_count'],
                        'retweets': tweet.public_metrics['retweet_count'],
                        'platform': 'twitter'
                    }
                    
                    tweet_data.append(tweet_info)
                    print(f"    ✅ Added tweet from @{author_username}: {tweet_info['text'][:80]}...")
                
                print(f"📦 Successfully processed {len(tweet_data)} tweets")
            else:
                print("❌ No tweet data in API response")
                
                # DEBUG POINT 6: Check for errors
                if hasattr(tweets, 'errors') and tweets.errors:
                    print(f"🚨 Twitter API Errors: {tweets.errors}")
                
                if hasattr(tweets, 'meta') and tweets.meta:
                    print(f"📈 Response Meta: {tweets.meta}")
            
            return tweet_data
            
        except tweepy.TooManyRequests as e:
            print(f"🚨 RATE LIMIT EXCEEDED: {e}")
            print("💡 Solution: Wait 15-30 minutes or apply for Academic Research access")
            return []
            
        except tweepy.Unauthorized as e:
            print(f"🚨 AUTHENTICATION ERROR: {e}")
            print("💡 Check your Bearer Token in .env file")
            return []
            
        except tweepy.BadRequest as e:
            print(f"🚨 BAD REQUEST: {e}")
            print("💡 Check your query parameters")
            return []
            
        except Exception as e:
            print(f"🚨 UNEXPECTED ERROR: {type(e).__name__}: {e}")
            return []
    
    def classify_cryptocurrency(self, text):
        """Simple keyword-based cryptocurrency classification"""
        print(f"🔍 Classifying cryptocurrency for text: {text[:5]}...")
        
        text_lower = text.lower()
        
        for crypto, keywords in self.crypto_keywords.items():
            if any(keyword in text_lower for keyword in keywords):
                print(f"✅ Classified as: {crypto}")
                return crypto
        
        print("✅ Classified as: general")
        return 'general'

# Test function
def test_twitter_service():
    """Test the Twitter service independently"""
    print("🧪 TESTING TWITTER SERVICE...")
    print("=" * 5)
    
    service = TwitterService()
    
    if service.client:
        print("🚀 Testing tweet search...")
        tweets = service.search_crypto_tweets(max_results=5)
        print(f"🎯 Test Results: Found {len(tweets)} tweets")
        
        if tweets:
            print("\n📋 Sample Tweets:")
            for i, tweet in enumerate(tweets[:2]):  # Show first 2
                crypto = service.classify_cryptocurrency(tweet['text'])
                print(f"  {i+1}. [{crypto}] @{tweet['author_username']}: {tweet['text'][:80]}...")
    else:
        print("❌ Twitter service not available for testing")
    
    print("=" * 5)

if __name__ == "__main__":
    test_twitter_service()