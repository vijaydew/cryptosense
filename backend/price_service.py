import requests
from datetime import datetime

class PriceService:
    def __init__(self):
        self.base_url = "https://api.coingecko.com/api/v3"
    
    def get_crypto_prices(self, coins=['bitcoin', 'ethereum', 'cardano', 'solana', 'dogecoin']):
        try:
            url = f"{self.base_url}/simple/price"
            params = {
                'ids': ','.join(coins),
                'vs_currencies': 'usd',
                'include_24hr_change': 'true'
            }
            
            response = requests.get(url, params=params)
            data = response.json()
            
            prices = {}
            for coin in coins:
                if coin in data:
                    prices[coin] = {
                        'price': data[coin]['usd'],
                        'change_24h': data[coin].get('usd_24h_change', 0),
                        'timestamp': datetime.utcnow()
                    }
            
            return prices
        except Exception as e:
            print(f"Price API error: {e}")
            return {}