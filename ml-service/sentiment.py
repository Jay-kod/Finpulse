"""
Sentiment Analysis module for the FinPulse system.

Uses NLTK's VADER (Valence Aware Dictionary and sEntiment Reasoner) for
sentiment scoring, which is specifically designed for social media and
review text — perfect for fintech app customer reviews.
"""

import nltk
from nltk.sentiment.vader import SentimentIntensityAnalyzer

nltk.download('vader_lexicon', quiet=True)


class SentimentAnalyzer:
    """
    Analyzes sentiment of customer review text using VADER.

    Returns positive, negative, neutral, and compound scores.
    Compound score ranges from -1 (most negative) to +1 (most positive):
      - compound >= 0.05  → Positive
      - compound <= -0.05 → Negative
      - otherwise         → Neutral
    """

    def __init__(self):
        self.analyzer = SentimentIntensityAnalyzer()

        # Add Nigerian fintech-specific lexicon entries
        # These words carry strong sentiment in the context of fintech reviews
        custom_lexicon = {
            # Positive fintech terms
            'seamless': 2.5,
            'cashback': 2.0,
            'instant': 1.8,
            'reliable': 2.0,
            'smooth': 2.0,
            'credited': 1.5,
            'successful': 2.0,
            'legit': 2.0,
            'secure': 1.5,
            'upgraded': 1.5,
            'recommend': 2.5,

            # Negative fintech terms
            'debit': -1.0,
            'debited': -1.5,
            'reversed': -1.0,
            'reversal': -1.5,
            'failed': -2.5,
            'scam': -3.5,
            'scammed': -3.5,
            'fraudulent': -3.0,
            'fraud': -3.0,
            'stolen': -3.0,
            'disappeared': -2.5,
            'missing': -2.0,
            'deducted': -2.0,
            'crashed': -2.5,
            'crash': -2.0,
            'glitch': -2.0,
            'glitchy': -2.0,
            'unresponsive': -2.0,
            'useless': -3.0,
            'rubbish': -3.0,
            'wahala': -2.0,  # Nigerian pidgin for "trouble/problem"
            'nonsense': -2.5,

            # Nigerian pidgin sentiment expressions
            'sha': 0.0,  # Neutral filler
            'abi': 0.0,  # Neutral question tag
            'abeg': -0.5,  # "please" (often used in frustration)
            'wetin': 0.0,  # "what"
            'dey': 0.0,  # "is/are"
            'commot': -1.0,  # "remove" (often negative context)
            'wahala': -2.0,  # "problem/trouble"
            'carry go': 1.5,  # "keep going" (approval)
        }

        for word, score in custom_lexicon.items():
            self.analyzer.lexicon[word] = score

    def analyze(self, text: str) -> dict:
        """
        Analyze sentiment of the given text.

        Args:
            text: The review text to analyze (preferably cleaned).

        Returns:
            dict with positive, negative, neutral, and compound scores.
        """
        if not text or not text.strip():
            return {
                "positive": 0.0,
                "negative": 0.0,
                "neutral": 1.0,
                "compound": 0.0,
            }

        scores = self.analyzer.polarity_scores(text)

        return {
            "positive": round(scores['pos'], 4),
            "negative": round(scores['neg'], 4),
            "neutral": round(scores['neu'], 4),
            "compound": round(scores['compound'], 4),
        }

    def get_label(self, compound_score: float) -> str:
        """Convert compound score to a human-readable label."""
        if compound_score >= 0.05:
            return "Positive"
        elif compound_score <= -0.05:
            return "Negative"
        else:
            return "Neutral"
