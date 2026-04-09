from transformers import pipeline
import re

class SentimentAnalyzer:
    def __init__(self):
        # Use a faster, smaller model for quick analysis
        self.model = pipeline(
            "sentiment-analysis",
            model="cardiffnlp/twitter-roberta-base-sentiment-latest",
            tokenizer="cardiffnlp/twitter-roberta-base-sentiment-latest",
            max_length=512,
            truncation=True
        )
    
    def clean_text(self, text):
        """Clean tweet text for better sentiment analysis"""
        # Remove URLs
        text = re.sub(r'http\S+', '', text)
        # Remove mentions
        text = re.sub(r'@\w+', '', text)
        # Remove extra spaces
        text = ' '.join(text.split())
        return text.strip()
    
    def analyze_text(self, text):
        try:
            # Clean the text first
            cleaned_text = self.clean_text(text)
            
            if not cleaned_text or len(cleaned_text) < 5:
                return {'score': 0.0, 'label': 'neutral', 'confidence': 0.0}
            
            result = self.model(cleaned_text)[0]
            
            # Convert to numeric score (-1 to 1)
            if result['label'] == 'positive':
                score = result['score']
            elif result['label'] == 'negative':
                score = -result['score']
            else:  # neutral
                score = 0.0
                
            return {
                'score': round(score, 4),
                'label': result['label'],
                'confidence': round(result['score'], 4)
            }
        except Exception as e:
            print(f"Sentiment analysis error: {e}")
            return {'score': 0.0, 'label': 'neutral', 'confidence': 0.0}

# Global analyzer instance
analyzer = SentimentAnalyzer()