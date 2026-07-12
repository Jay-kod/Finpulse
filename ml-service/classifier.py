"""
ML Classification module for the FinPulse system.

Provides Topic Classification, Intent Detection, and Bug Detection
for fintech customer reviews using TF-IDF + Machine Learning classifiers.

On first run, trains models on built-in labeled data and saves them.
On subsequent runs, loads pre-trained models from disk.
"""

import os
import re
import joblib
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.linear_model import LogisticRegression
from sklearn.svm import LinearSVC
from sklearn.naive_bayes import MultinomialNB
from sklearn.pipeline import Pipeline
from sklearn.model_selection import train_test_split
from sklearn.metrics import classification_report


# Directory to store trained model files
MODEL_DIR = os.path.join(os.path.dirname(__file__), "models")
os.makedirs(MODEL_DIR, exist_ok=True)


# ============================================================
# LABELED TRAINING DATA
# Curated dataset of Nigerian fintech review examples
# ============================================================

TOPIC_TRAINING_DATA = [
    # --- Transaction ---
    ("Money was sent but not received", "Transaction"),
    ("Transfer failed but money was deducted", "Transaction"),
    ("I transferred money and it was successful", "Transaction"),
    ("My transaction is still pending after 2 hours", "Transaction"),
    ("Payment was reversed after 3 days", "Transaction"),
    ("I can't send money to other banks", "Transaction"),
    ("Double debit on my account", "Transaction"),
    ("Instant transfer is very fast", "Transaction"),
    ("My money disappeared during transfer", "Transaction"),
    ("Cashback received after transaction", "Transaction"),
    ("Bill payment went through smoothly", "Transaction"),
    ("Airtime purchase was instant", "Transaction"),
    ("I was charged twice for one transaction", "Transaction"),
    ("The transfer to GTBank failed", "Transaction"),
    ("OPay to OPay transfer is free and fast", "Transaction"),
    ("I paid for DSTV but it didn't credit", "Transaction"),
    ("Withdrawal from ATM was successful", "Transaction"),
    ("They reversed my money after 24 hours", "Transaction"),

    # --- Customer Support ---
    ("Customer service never responds", "Customer Support"),
    ("I've been waiting for support for 3 days", "Customer Support"),
    ("The chat support is useless", "Customer Support"),
    ("No one is responding to my complaint", "Customer Support"),
    ("Called customer care but no answer", "Customer Support"),
    ("They resolved my issue quickly", "Customer Support"),
    ("Support team helped me recover my funds", "Customer Support"),
    ("Live chat is always offline", "Customer Support"),
    ("I sent email but no response", "Customer Support"),
    ("Their customer care is the worst", "Customer Support"),
    ("Agent was helpful and polite", "Customer Support"),
    ("Please help me resolve this issue", "Customer Support"),
    ("Twitter support responded fast", "Customer Support"),
    ("Nobody is attending to my complaints", "Customer Support"),

    # --- App Performance ---
    ("App keeps crashing when I open it", "App Performance"),
    ("The app is very slow to load", "App Performance"),
    ("Interface is smooth and beautiful", "App Performance"),
    ("App freezes during payment", "App Performance"),
    ("New update made the app faster", "App Performance"),
    ("The app is lagging on my phone", "App Performance"),
    ("Login page takes forever to load", "App Performance"),
    ("App drains my battery too fast", "App Performance"),
    ("Smooth user interface and design", "App Performance"),
    ("App crashes after the latest update", "App Performance"),
    ("The dashboard loads quickly now", "App Performance"),
    ("Face ID login is very convenient", "App Performance"),
    ("Too many bugs in this app", "App Performance"),
    ("Best designed banking app", "App Performance"),

    # --- Security ---
    ("My account was hacked", "Security"),
    ("Someone withdrew money without my knowledge", "Security"),
    ("BVN verification is good for security", "Security"),
    ("I don't trust this app with my money", "Security"),
    ("Two-factor authentication works great", "Security"),
    ("They blocked my account for no reason", "Security"),
    ("OTP never arrives on time", "Security"),
    ("Account verification took too long", "Security"),
    ("I feel safe using this app", "Security"),
    ("Unauthorized transaction on my account", "Security"),
    ("Scammers are using this app", "Security"),
    ("KYC process is straightforward", "Security"),
    ("My PIN was compromised", "Security"),
    ("They asked me to verify my identity again", "Security"),

    # --- Network ---
    ("Network error when trying to pay", "Network"),
    ("Server is always down", "Network"),
    ("Connection timeout during transfer", "Network"),
    ("App works fine on WiFi but not on data", "Network"),
    ("Server maintenance too frequent", "Network"),
    ("The app needs stable internet to work", "Network"),
    ("Network issues causing failed payments", "Network"),
    ("Cannot connect to server", "Network"),
    ("Keeps saying network error", "Network"),
    ("Works perfectly even on slow network", "Network"),
    ("Session expired keeps showing", "Network"),
    ("Server is down again", "Network"),

    # --- Fees & Charges ---
    ("Transfer charges are too high", "Fees & Charges"),
    ("Free transfers is the best feature", "Fees & Charges"),
    ("They increased the transaction fees", "Fees & Charges"),
    ("No charges for OPay to OPay", "Fees & Charges"),
    ("Hidden charges everywhere", "Fees & Charges"),
    ("Charges are reasonable compared to banks", "Fees & Charges"),
    ("Why am I being charged for receiving money", "Fees & Charges"),
    ("Low transfer fees is why I use this app", "Fees & Charges"),
    ("The maintenance fee is unnecessary", "Fees & Charges"),
    ("Free transactions up to 50,000 naira", "Fees & Charges"),
]

INTENT_TRAINING_DATA = [
    # --- Complaint ---
    ("This app is terrible, my money is gone", "Complaint"),
    ("I can't login to my account", "Complaint"),
    ("Transaction failed for the third time", "Complaint"),
    ("Worst app ever, always crashing", "Complaint"),
    ("My account was debited but no credit", "Complaint"),
    ("Stop stealing people's money", "Complaint"),
    ("Your app is not working again", "Complaint"),
    ("I've lost money because of this app", "Complaint"),
    ("This is frustrating and unacceptable", "Complaint"),
    ("How can you deduct money without service", "Complaint"),
    ("Very disappointed with this service", "Complaint"),
    ("Useless app with too many errors", "Complaint"),
    ("Return my money immediately", "Complaint"),
    ("Why is my reversal taking so long", "Complaint"),

    # --- Praise ---
    ("Best app for money transfer in Nigeria", "Praise"),
    ("I love this app so much", "Praise"),
    ("Fast, reliable and trustworthy", "Praise"),
    ("Excellent service, keep it up", "Praise"),
    ("This app has changed my life", "Praise"),
    ("Smooth and easy to use", "Praise"),
    ("The best fintech app hands down", "Praise"),
    ("Great job on the new update", "Praise"),
    ("I recommend this to everyone", "Praise"),
    ("Amazing experience every time", "Praise"),
    ("Thank you for the good service", "Praise"),
    ("5 stars well deserved", "Praise"),
    ("Very convenient and user friendly", "Praise"),
    ("Kudos to the development team", "Praise"),

    # --- Suggestion ---
    ("Please add dark mode to the app", "Suggestion"),
    ("You should reduce the transfer fees", "Suggestion"),
    ("It would be nice to have virtual cards", "Suggestion"),
    ("Please improve your customer support", "Suggestion"),
    ("Add fingerprint login option", "Suggestion"),
    ("You should allow scheduled payments", "Suggestion"),
    ("Please fix the notification system", "Suggestion"),
    ("Add budget tracking feature", "Suggestion"),
    ("It would be helpful to have spending categories", "Suggestion"),
    ("Please make the app work offline for balance check", "Suggestion"),
    ("Consider adding investment options", "Suggestion"),
    ("You need to improve the UI design", "Suggestion"),

    # --- Question ---
    ("How do I upgrade my account?", "Question"),
    ("What is the transfer limit?", "Question"),
    ("Is there a way to reverse a transaction?", "Question"),
    ("Can I use this app abroad?", "Question"),
    ("How long does reversal take?", "Question"),
    ("Where can I find my account number?", "Question"),
    ("Why was my account blocked?", "Question"),
    ("What are the charges for bank transfer?", "Question"),
    ("How do I contact customer support?", "Question"),
    ("Does this app support USSD?", "Question"),
    ("When will the server be back up?", "Question"),
    ("Is my money safe in this app?", "Question"),
]

BUG_TRAINING_DATA = [
    # --- Bug reports (is_bug = True) ---
    ("App crashes when I try to send money", True),
    ("Login screen goes blank after entering PIN", True),
    ("The app shows wrong balance", True),
    ("Notification shows wrong transaction amount", True),
    ("Fingerprint login not working after update", True),
    ("App freezes on the transfer page", True),
    ("Double debit bug happened again", True),
    ("Screen goes white when opening app", True),
    ("Cannot scroll down on transaction history", True),
    ("Button not responding on payment page", True),
    ("Camera not opening for QR scan", True),
    ("App keeps logging me out randomly", True),
    ("Dark mode makes some text invisible", True),
    ("Receipts showing wrong date", True),
    ("Amount shows as negative on dashboard", True),
    ("Error 500 when loading profile", True),
    ("Copy button for account number not working", True),
    ("Push notifications not coming through", True),
    ("App stuck on loading screen", True),
    ("OTP input field not accepting numbers", True),

    # --- Not bug reports (is_bug = False) ---
    ("Great app, works perfectly", False),
    ("I love using this for transfers", False),
    ("Please reduce the charges", False),
    ("Best app in Nigeria", False),
    ("Customer service is poor", False),
    ("Transaction was successful and fast", False),
    ("I don't trust this app", False),
    ("Network is always bad", False),
    ("When will new features be added?", False),
    ("The interface is beautiful", False),
    ("I recommend this app", False),
    ("Transfer to GTBank was instant", False),
    ("Support resolved my issue", False),
    ("Charges are too high", False),
    ("My money was reversed after 2 days", False),
    ("Server always down at night", False),
    ("How can I upgrade my tier?", False),
    ("Please add dark mode", False),
    ("Five stars for great service", False),
    ("I need help with my account", False),
]


class ReviewClassifier:
    """
    ML classifier for fintech customer reviews.

    Provides three classification tasks:
    1. Topic Classification (Logistic Regression + TF-IDF)
    2. Intent Detection (LinearSVC + TF-IDF)
    3. Bug Detection (Naïve Bayes + TF-IDF)
    """

    def __init__(self):
        self.topic_model = None
        self.intent_model = None
        self.bug_model = None
        self._load_or_train_models()

    def _load_or_train_models(self):
        """Load models from disk or train new ones."""
        topic_path = os.path.join(MODEL_DIR, "topic_model.pkl")
        intent_path = os.path.join(MODEL_DIR, "intent_model.pkl")
        bug_path = os.path.join(MODEL_DIR, "bug_model.pkl")

        if (os.path.exists(topic_path) and
            os.path.exists(intent_path) and
            os.path.exists(bug_path)):
            print("Loading pre-trained models...")
            self.topic_model = joblib.load(topic_path)
            self.intent_model = joblib.load(intent_path)
            self.bug_model = joblib.load(bug_path)
            print("Models loaded successfully.")
        else:
            print("Training new models...")
            self._train_models()
            print("Models trained and saved.")

    def _train_models(self):
        """Train all three classifiers and save to disk."""

        # --- Topic Classification (Logistic Regression) ---
        topic_texts = [t[0] for t in TOPIC_TRAINING_DATA]
        topic_labels = [t[1] for t in TOPIC_TRAINING_DATA]

        self.topic_model = Pipeline([
            ('tfidf', TfidfVectorizer(
                max_features=5000,
                ngram_range=(1, 2),
                min_df=1,
                sublinear_tf=True,
            )),
            ('clf', LogisticRegression(
                max_iter=1000,
                C=1.0,
                class_weight='balanced',
                random_state=42,
            )),
        ])
        self.topic_model.fit(topic_texts, topic_labels)

        # Print training accuracy
        topic_accuracy = self.topic_model.score(topic_texts, topic_labels)
        print(f"  Topic model training accuracy: {topic_accuracy:.2%}")

        # --- Intent Detection (LinearSVC) ---
        intent_texts = [t[0] for t in INTENT_TRAINING_DATA]
        intent_labels = [t[1] for t in INTENT_TRAINING_DATA]

        self.intent_model = Pipeline([
            ('tfidf', TfidfVectorizer(
                max_features=5000,
                ngram_range=(1, 2),
                min_df=1,
                sublinear_tf=True,
            )),
            ('clf', LinearSVC(
                C=1.0,
                class_weight='balanced',
                max_iter=1000,
                random_state=42,
            )),
        ])
        self.intent_model.fit(intent_texts, intent_labels)

        intent_accuracy = self.intent_model.score(intent_texts, intent_labels)
        print(f"  Intent model training accuracy: {intent_accuracy:.2%}")

        # --- Bug Detection (Multinomial Naïve Bayes) ---
        bug_texts = [t[0] for t in BUG_TRAINING_DATA]
        bug_labels = [t[1] for t in BUG_TRAINING_DATA]

        self.bug_model = Pipeline([
            ('tfidf', TfidfVectorizer(
                max_features=3000,
                ngram_range=(1, 2),
                min_df=1,
                sublinear_tf=True,
            )),
            ('clf', MultinomialNB(alpha=0.1)),
        ])
        self.bug_model.fit(bug_texts, bug_labels)

        bug_accuracy = self.bug_model.score(bug_texts, bug_labels)
        print(f"  Bug model training accuracy: {bug_accuracy:.2%}")

        # Save models
        joblib.dump(self.topic_model, os.path.join(MODEL_DIR, "topic_model.pkl"))
        joblib.dump(self.intent_model, os.path.join(MODEL_DIR, "intent_model.pkl"))
        joblib.dump(self.bug_model, os.path.join(MODEL_DIR, "bug_model.pkl"))

    def classify(self, text: str) -> dict:
        """
        Classify a review text.

        Args:
            text: The cleaned/preprocessed review text.

        Returns:
            dict with topic, intent, and is_bug.
        """
        if not text or not text.strip():
            return {
                "topic": "General",
                "intent": "Complaint",
                "is_bug": False,
            }

        topic = self.topic_model.predict([text])[0]
        intent = self.intent_model.predict([text])[0]
        is_bug = self.bug_model.predict([text])[0]

        return {
            "topic": topic,
            "intent": intent,
            "is_bug": bool(is_bug),
        }

    def get_model_info(self) -> dict:
        """Return information about the trained models."""
        return {
            "topic_model": {
                "algorithm": "Logistic Regression",
                "vectorizer": "TF-IDF",
                "training_samples": len(TOPIC_TRAINING_DATA),
                "classes": list(set(t[1] for t in TOPIC_TRAINING_DATA)),
            },
            "intent_model": {
                "algorithm": "LinearSVC (Support Vector Machine)",
                "vectorizer": "TF-IDF",
                "training_samples": len(INTENT_TRAINING_DATA),
                "classes": list(set(t[1] for t in INTENT_TRAINING_DATA)),
            },
            "bug_model": {
                "algorithm": "Multinomial Naïve Bayes",
                "vectorizer": "TF-IDF",
                "training_samples": len(BUG_TRAINING_DATA),
                "classes": ["Bug Report", "Not Bug"],
            },
        }
