"""
NLP Preprocessing module for the FinPulse Sentiment Analysis system.

Handles text cleaning, tokenization, stop-word removal, stemming,
language detection, and word counting for fintech app reviews.
"""

import re
import nltk
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer, WordNetLemmatizer
from langdetect import detect, LangDetectException

# Download required NLTK data (done once on first import)
nltk.download('punkt', quiet=True)
nltk.download('punkt_tab', quiet=True)
nltk.download('stopwords', quiet=True)
nltk.download('wordnet', quiet=True)


class TextPreprocessor:
    """
    Preprocesses raw customer review text using NLP techniques:
    - Text cleaning (URLs, HTML, special chars)
    - Tokenization
    - Stop-word removal
    - Lemmatization
    - Language detection
    """

    def __init__(self):
        self.stop_words = set(stopwords.words('english'))
        self.stemmer = PorterStemmer()
        self.lemmatizer = WordNetLemmatizer()

        # Nigerian slang / fintech-specific stop words to keep
        # (these carry sentiment and should NOT be removed)
        self.keep_words = {
            'not', 'no', 'never', 'bad', 'good', 'great', 'worst',
            'best', 'love', 'hate', 'terrible', 'excellent', 'poor',
            'slow', 'fast', 'failed', 'scam', 'stolen', 'missing',
            'crash', 'bug', 'error', 'fix', 'broken', 'useless',
            'amazing', 'wonderful', 'horrible', 'awful', 'fantastic',
        }
        # Remove sentiment-carrying words from the stop word list
        self.stop_words -= self.keep_words

    def clean_text(self, text: str) -> str:
        """Remove URLs, HTML tags, special characters, and normalize whitespace."""
        if not text or not isinstance(text, str):
            return ""

        # Remove URLs
        text = re.sub(r'https?://\S+|www\.\S+', '', text)

        # Remove HTML tags
        text = re.sub(r'<[^>]+>', '', text)

        # Remove email addresses
        text = re.sub(r'\S+@\S+', '', text)

        # Remove @mentions and #hashtags (keep the word after)
        text = re.sub(r'[@#](\w+)', r'\1', text)

        # Remove emojis and non-ASCII characters but keep basic punctuation
        text = re.sub(r'[^\w\s.,!?\'"-]', '', text)

        # Normalize repeated characters (e.g., "goooood" → "good")
        text = re.sub(r'(.)\1{2,}', r'\1\1', text)

        # Normalize whitespace
        text = re.sub(r'\s+', ' ', text).strip()

        return text

    def tokenize(self, text: str) -> list[str]:
        """Tokenize text into words."""
        try:
            return word_tokenize(text.lower())
        except Exception:
            return text.lower().split()

    def remove_stopwords(self, tokens: list[str]) -> list[str]:
        """Remove stop words while preserving sentiment-carrying words."""
        return [
            token for token in tokens
            if token not in self.stop_words and len(token) > 1
        ]

    def lemmatize(self, tokens: list[str]) -> list[str]:
        """Apply lemmatization to tokens."""
        return [self.lemmatizer.lemmatize(token) for token in tokens]

    def detect_language(self, text: str) -> str:
        """Detect the language of the text."""
        try:
            if len(text.strip()) < 10:
                return "en"  # Default to English for very short texts
            return detect(text)
        except LangDetectException:
            return "unknown"

    def preprocess(self, text: str) -> dict:
        """
        Full preprocessing pipeline:
        1. Clean text
        2. Detect language
        3. Tokenize
        4. Remove stop words
        5. Lemmatize
        6. Rejoin into cleaned text

        Returns dict with cleaned_text, language, word_count.
        """
        if not text or not text.strip():
            return {
                "cleaned_text": "",
                "language": "unknown",
                "word_count": 0,
            }

        # Step 1: Clean raw text
        cleaned = self.clean_text(text)

        # Step 2: Detect language (on cleaned but un-tokenized text)
        language = self.detect_language(cleaned)

        # Step 3: Tokenize
        tokens = self.tokenize(cleaned)

        # Step 4: Remove stop words
        tokens = self.remove_stopwords(tokens)

        # Step 5: Lemmatize
        tokens = self.lemmatize(tokens)

        # Step 6: Rejoin
        cleaned_text = " ".join(tokens)
        word_count = len(tokens)

        return {
            "cleaned_text": cleaned_text,
            "language": language,
            "word_count": word_count,
        }
