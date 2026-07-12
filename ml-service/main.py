"""
FinPulse ML Microservice — FastAPI Application

Provides three API endpoints for the Laravel backend:
  - POST /api/preprocess  → NLP text preprocessing
  - POST /api/classify    → ML topic/intent/bug classification
  - POST /api/sentiment   → VADER sentiment analysis
  - GET  /health          → Health check

Tech Stack (as described in the project report):
  - Python FastAPI for the API layer
  - scikit-learn for machine learning (TF-IDF, Logistic Regression, SVM, Naïve Bayes)
  - NLTK for NLP preprocessing and VADER sentiment analysis
"""

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import time
import sys

# Local modules
from preprocessing import TextPreprocessor
from classifier import ReviewClassifier
from sentiment import SentimentAnalyzer


# ============================================================
# FastAPI Application Setup
# ============================================================

app = FastAPI(
    title="FinPulse ML Service",
    description="NLP & Machine Learning microservice for Nigerian fintech review sentiment analysis",
    version="1.0.0",
)

# Allow requests from the Laravel backend
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


# ============================================================
# Initialize ML components on startup
# ============================================================

print("=" * 60)
print("  FinPulse ML Service — Initializing...")
print("=" * 60)

start_time = time.time()

preprocessor = TextPreprocessor()
classifier = ReviewClassifier()
sentiment_analyzer = SentimentAnalyzer()

elapsed = time.time() - start_time
print(f"\n  All components initialized in {elapsed:.2f}s")
print("=" * 60)


# ============================================================
# Request/Response Models
# ============================================================

class PreprocessRequest(BaseModel):
    text: str

class PreprocessResponse(BaseModel):
    cleaned_text: str
    language: str
    word_count: int

class ClassifyRequest(BaseModel):
    cleaned_text: str

class ClassifyResponse(BaseModel):
    topic: str
    intent: str
    is_bug: bool

class SentimentRequest(BaseModel):
    cleaned_text: str

class SentimentResponse(BaseModel):
    positive: float
    negative: float
    neutral: float
    compound: float


# ============================================================
# API Endpoints
# ============================================================

@app.get("/health")
def health_check():
    """Health check endpoint for the Laravel backend."""
    return {
        "status": "healthy",
        "service": "FinPulse ML Service",
        "version": "1.0.0",
        "components": {
            "preprocessor": "ready",
            "classifier": "ready",
            "sentiment_analyzer": "ready",
        },
        "models": classifier.get_model_info(),
    }


@app.post("/api/preprocess", response_model=PreprocessResponse)
def preprocess_text(request: PreprocessRequest):
    """
    NLP Preprocessing endpoint.

    Receives raw review text and returns:
    - cleaned_text: Preprocessed text (tokenized, stop-words removed, lemmatized)
    - language: Detected language code
    - word_count: Number of meaningful words after preprocessing
    """
    try:
        result = preprocessor.preprocess(request.text)
        return PreprocessResponse(**result)
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Preprocessing failed: {str(e)}")


@app.post("/api/classify", response_model=ClassifyResponse)
def classify_text(request: ClassifyRequest):
    """
    ML Classification endpoint.

    Receives cleaned review text and returns:
    - topic: Category (Transaction, Customer Support, App Performance, Security, Network, Fees & Charges)
    - intent: User intent (Complaint, Praise, Suggestion, Question)
    - is_bug: Whether the review is reporting a bug/technical issue
    """
    try:
        result = classifier.classify(request.cleaned_text)
        return ClassifyResponse(**result)
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Classification failed: {str(e)}")


@app.post("/api/sentiment", response_model=SentimentResponse)
def analyze_sentiment(request: SentimentRequest):
    """
    Sentiment Analysis endpoint.

    Receives cleaned review text and returns:
    - positive: Positive sentiment score (0-1)
    - negative: Negative sentiment score (0-1)
    - neutral: Neutral sentiment score (0-1)
    - compound: Overall sentiment (-1 to +1)
    """
    try:
        result = sentiment_analyzer.analyze(request.cleaned_text)
        return SentimentResponse(**result)
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Sentiment analysis failed: {str(e)}")


@app.post("/api/analyze-full")
def analyze_full(request: PreprocessRequest):
    """
    Convenience endpoint that runs the entire pipeline in one call.

    Useful for testing — preprocesses, classifies, and scores sentiment in one request.
    """
    try:
        # Step 1: Preprocess
        preprocessed = preprocessor.preprocess(request.text)

        # Step 2: Classify (using cleaned text)
        classified = classifier.classify(preprocessed["cleaned_text"])

        # Step 3: Sentiment (using cleaned text)
        sentiment = sentiment_analyzer.analyze(preprocessed["cleaned_text"])

        # Determine overall sentiment label
        sentiment_label = sentiment_analyzer.get_label(sentiment["compound"])

        return {
            "original_text": request.text,
            "preprocessing": preprocessed,
            "classification": classified,
            "sentiment": {
                **sentiment,
                "label": sentiment_label,
            },
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Full analysis failed: {str(e)}")


# ============================================================
# Run with: uvicorn main:app --host 127.0.0.1 --port 8000 --reload
# ============================================================

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="127.0.0.1", port=8000, reload=True)
