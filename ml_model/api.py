"""
X-Scaffold Prediction API
==========================
Flask API for serving ML predictions with SHAP explanations.
Model trained on teacher-labelled target variable (non-circular).

Endpoints:
- POST /predict - Get mastery level prediction + SHAP values
- GET /health - Health check

Author: X-Scaffold Research Team
Date: March 2026 (Updated — teacher-labelled model)
"""

import os
import json
import numpy as np
import joblib
from flask import Flask, request, jsonify
from flask_cors import CORS

# SHAP for explainability
import shap

app = Flask(__name__)
CORS(app)  # Enable CORS for Laravel to call this API

# ============================================================
# LOAD MODEL ARTIFACTS
# ============================================================

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))

# Load trained model (XGBoost Classifier)
MODEL_PATH = os.path.join(SCRIPT_DIR, 'xscaffold_xgboost_model.pkl')
model = joblib.load(MODEL_PATH)

# Load scaler
SCALER_PATH = os.path.join(SCRIPT_DIR, 'xscaffold_scaler.pkl')
scaler = joblib.load(SCALER_PATH)

# Load feature names
FEATURES_PATH = os.path.join(SCRIPT_DIR, 'feature_names.json')
with open(FEATURES_PATH, 'r') as f:
    config = json.load(f)
    FEATURE_NAMES = config['features']
    CLASS_NAMES = config['class_names']

# Initialize SHAP explainer
# XGBoost is natively supported by SHAP TreeExplainer (fast + exact)
print("Initializing SHAP TreeExplainer...")
try:
    explainer = shap.TreeExplainer(model)
    SHAP_AVAILABLE = True
    print("✅ SHAP TreeExplainer ready")
except Exception as e:
    print(f"⚠️ SHAP initialization failed: {e}")
    explainer = None
    SHAP_AVAILABLE = False

print(f"✅ Model loaded: {os.path.basename(MODEL_PATH)}")
print(f"✅ Features: {len(FEATURE_NAMES)}")
print(f"✅ Classes: {CLASS_NAMES}")

# ============================================================
# HELPER FUNCTIONS
# ============================================================

def format_shap_explanation(shap_values: np.ndarray, feature_names: list, 
                             prediction: int, raw_features: list) -> dict:
    """
    Format SHAP values into human-readable explanation with domain awareness.
    
    Uses pedagogical thresholds from the LMS formula to classify features
    as strengths or weaknesses, rather than relying on raw SHAP sign alone.
    
    Args:
        shap_values: SHAP output array (1, n_features, n_classes)
        feature_names: List of feature name strings
        prediction: Predicted class index
        raw_features: Original (unscaled) feature values in the same order
    
    Returns:
        dict matching Blade template schema:
        {contributions: {feature: {value, contribution, description}},
         top_positive: [...], top_negative: [...], natural_language: "..."}
    """
    
    # Get SHAP values for the predicted class
    class_shap_values = shap_values[0, :, prediction]
    
    # Feature descriptions for display (matching local fallback)
    descriptions = {
        'score_percentage': 'Percentage of correct answers',
        'hard_question_accuracy': 'Accuracy on difficult questions',
        'hint_usage_percentage': 'Percentage of questions where hints were used',
        'avg_confidence': 'Mean self-reported confidence (1-5)',
        'answer_changes_rate': 'Average answer changes per question',
        'tab_switches_rate': 'Average tab switches per question',
        'avg_time_per_question': 'Mean time spent per question (seconds)',
        'review_percentage': 'Percentage of questions marked for review',
        'avg_first_action_latency': 'Mean time to first interaction (seconds)',
        'clicks_per_question': 'Average clicks per question',
        'performance_trend': 'Accuracy change (2nd half - 1st half)',
    }
    
    # Domain-aware thresholds from the LMS formula
    # Each entry: (good_check, bad_check, value_format)
    # good_check: lambda that returns True if the raw value is pedagogically good
    # bad_check: lambda that returns True if the raw value is pedagogically bad
    feature_semantics = {
        # Higher = Better
        'score_percentage':         (lambda v: v >= 70.0, lambda v: v < 50.0, lambda v: f"{round(v, 1)}%"),
        'hard_question_accuracy':   (lambda v: v >= 60.0, lambda v: v < 40.0, lambda v: f"{round(v, 1)}%"),
        'performance_trend':        (lambda v: v >= 5.0,  lambda v: v <= -10.0, lambda v: f"{'+' if v >= 0 else ''}{round(v, 1)}%"),
        # Lower = Better (inverse)
        'hint_usage_percentage':    (lambda v: v <= 20.0, lambda v: v >= 50.0, lambda v: f"{round(v, 1)}%"),
        'answer_changes_rate':      (lambda v: v <= 0.3,  lambda v: v >= 1.0,  lambda v: f"{round(v, 2)}"),
        'tab_switches_rate':        (lambda v: v <= 1.0,  lambda v: v >= 2.5,  lambda v: f"{round(v, 2)}"),
        # Range = Best
        'avg_time_per_question':    (lambda v: 30 <= v <= 120, lambda v: v < 30 or v > 120, lambda v: f"{round(v, 1)}s"),
        'review_percentage':        (lambda v: 10 <= v <= 40,  lambda v: v < 10 or v > 40,  lambda v: f"{round(v, 1)}%"),
        'avg_first_action_latency': (lambda v: 1 <= v <= 5,    lambda v: v < 1 or v > 5,    lambda v: f"{round(v, 1)}s"),
        'clicks_per_question':      (lambda v: 2 <= v <= 8,    lambda v: v < 2 or v > 8,    lambda v: f"{round(v, 1)}"),
        # Contextual (confidence depends on score alignment — simplified here)
        'avg_confidence':           (lambda v: v >= 3.5,  lambda v: v < 2.0,  lambda v: f"{round(v, 1)}/5"),
    }
    
    # Build contributions dict matching the Blade template schema
    contributions = {}
    strengths = []
    weaknesses = []
    
    for i, name in enumerate(feature_names):
        shap_val = float(class_shap_values[i])
        raw_val = float(raw_features[i])
        
        semantics = feature_semantics.get(name)
        if semantics:
            is_good_fn, is_bad_fn, fmt_fn = semantics
            formatted_value = fmt_fn(raw_val)
            is_good = is_good_fn(raw_val)
            is_bad = is_bad_fn(raw_val)
        else:
            formatted_value = str(round(raw_val, 2))
            is_good = shap_val > 0
            is_bad = shap_val < 0
        
        contributions[name] = {
            'value': formatted_value,
            'contribution': round(shap_val, 3),
            'description': descriptions.get(name, name.replace('_', ' ').title()),
        }
        
        # Classify as strength/weakness using domain thresholds
        if is_good and not is_bad:
            strengths.append((name, abs(shap_val)))
        elif is_bad and not is_good:
            weaknesses.append((name, abs(shap_val)))
    
    # Sort by SHAP magnitude and take top 3
    strengths.sort(key=lambda x: x[1], reverse=True)
    weaknesses.sort(key=lambda x: x[1], reverse=True)
    
    top_positive = [f for f, _ in strengths[:3]]
    top_negative = [f for f, _ in weaknesses[:3]]
    
    # Generate natural language summary
    summary_parts = []
    if top_positive:
        pos_text = ", ".join([
            f"{f} ({contributions[f]['value']})" for f in top_positive
        ])
        summary_parts.append(f"Strengths: {pos_text}")
    
    if top_negative:
        neg_text = ", ".join([
            f"{f} ({contributions[f]['value']})" for f in top_negative
        ])
        summary_parts.append(f"Areas for improvement: {neg_text}")
    
    natural_language = ". ".join(summary_parts) + "." if summary_parts else "Standard performance patterns observed."
    
    return {
        'contributions': contributions,
        'top_positive': top_positive,
        'top_negative': top_negative,
        'natural_language': natural_language
    }


def validate_features(data: dict) -> tuple:
    """Validate and extract features from request data."""
    
    missing = [f for f in FEATURE_NAMES if f not in data]
    if missing:
        return None, f"Missing features: {missing}"
    
    try:
        features = [float(data[f]) for f in FEATURE_NAMES]
        return features, None
    except (ValueError, TypeError) as e:
        return None, f"Invalid feature values: {e}"


# ============================================================
# API ENDPOINTS
# ============================================================

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint."""
    return jsonify({
        'status': 'healthy',
        'model': 'xscaffold_xgboost_model',
        'target_source': 'teacher_labelled',
        'features_count': len(FEATURE_NAMES),
        'classes': CLASS_NAMES,
        'shap_available': SHAP_AVAILABLE
    })


@app.route('/predict', methods=['POST'])
def predict():
    """
    Predict mastery level with SHAP explanation.
    
    Request body (JSON):
    {
        "score_percentage": 72.5,
        "hard_question_accuracy": 65.0,
        "hint_usage_percentage": 20.0,
        "avg_confidence": 3.8,
        "answer_changes_rate": 0.3,
        "tab_switches_rate": 0.8,
        "avg_time_per_question": 85.0,
        "review_percentage": 40.0,
        "avg_first_action_latency": 4.5,
        "clicks_per_question": 4.2,
        "performance_trend": 8.0
    }
    
    Response:
    {
        "prediction": {
            "mastery_level": 2,
            "mastery_level_name": "proficient",
            "confidence": 0.85,
            "probabilities": {...}
        },
        "explanation": {
            "contributions": {...},
            "top_positive": [...],
            "top_negative": [...],
            "natural_language": "..."
        }
    }
    """
    
    # Parse request
    data = request.get_json()
    if not data:
        return jsonify({'error': 'No JSON data provided'}), 400
    
    # Validate features
    features, error = validate_features(data)
    if error:
        return jsonify({'error': error}), 400
    
    # Scale features
    features_array = np.array(features).reshape(1, -1)
    features_scaled = scaler.transform(features_array)
    
    # Get prediction
    prediction = int(model.predict(features_scaled)[0])
    probabilities = model.predict_proba(features_scaled)[0]
    confidence = float(probabilities[prediction])
    
    response = {
        'prediction': {
            'mastery_level': prediction,
            'mastery_level_name': CLASS_NAMES[prediction],
            'confidence': round(confidence, 4),
            'probabilities': {
                CLASS_NAMES[i]: round(float(p), 4) 
                for i, p in enumerate(probabilities)
            }
        }
    }
    
    # Add SHAP explanation if available
    if SHAP_AVAILABLE:
        try:
            shap_values = explainer.shap_values(features_scaled)
            explanation = format_shap_explanation(
                shap_values, FEATURE_NAMES, prediction, features
            )
            response['explanation'] = explanation
        except Exception as e:
            response['explanation'] = {
                'error': f'SHAP computation failed: {str(e)}',
                'natural_language': 'Explanation unavailable for this prediction.'
            }
    else:
        # Fallback: use feature importance from model
        response['explanation'] = {
            'natural_language': 'SHAP explanations not available. Use feature importance instead.',
            'note': 'Install shap package for detailed explanations.'
        }
    
    return jsonify(response)


@app.route('/batch_predict', methods=['POST'])
def batch_predict():
    """
    Batch prediction for multiple students.
    
    Request body (JSON):
    {
        "students": [
            {"score_percentage": 72.5, ...},
            {"score_percentage": 45.0, ...}
        ]
    }
    """
    
    data = request.get_json()
    if not data or 'students' not in data:
        return jsonify({'error': 'No students data provided'}), 400
    
    students = data['students']
    results = []
    
    for i, student in enumerate(students):
        features, error = validate_features(student)
        if error:
            results.append({'index': i, 'error': error})
            continue
        
        features_array = np.array(features).reshape(1, -1)
        features_scaled = scaler.transform(features_array)
        
        prediction = int(model.predict(features_scaled)[0])
        probabilities = model.predict_proba(features_scaled)[0]
        
        results.append({
            'index': i,
            'student_id': student.get('student_id', f'student_{i}'),
            'mastery_level': prediction,
            'mastery_level_name': CLASS_NAMES[prediction],
            'confidence': round(float(probabilities[prediction]), 4)
        })
    
    return jsonify({'results': results})


# ============================================================
# MAIN
# ============================================================

if __name__ == '__main__':
    print("\n" + "=" * 50)
    print("X-SCAFFOLD PREDICTION API")
    print("=" * 50)
    print(f"Starting server on http://localhost:5500")
    print("Endpoints:")
    print("  GET  /health  - Health check")
    print("  POST /predict - Single prediction with SHAP")
    print("  POST /batch_predict - Batch predictions")
    print("=" * 50 + "\n")
    
    app.run(host='0.0.0.0', port=5500, debug=False)
