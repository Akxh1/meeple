"""
X-Scaffold Prediction Script
==============================
Standalone prediction script for testing and command-line usage.

Usage:
    python predict.py --features '{"score_percentage": 72.5, ...}'
    python predict.py --csv students.csv --output predictions.csv

Author: X-Scaffold Research Team
Date: January 2026
"""

import os
import sys
import json
import argparse
import numpy as np
import pandas as pd
import joblib

# ============================================================
# CONFIGURATION
# ============================================================

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))

FEATURE_NAMES = [
    'score_percentage',
    'hard_question_accuracy', 
    'hint_usage_percentage',
    'avg_confidence',
    'answer_changes_rate',
    'tab_switches_rate',
    'avg_time_per_question',
    'review_percentage',
    'avg_first_action_latency',
    'clicks_per_question',
    'performance_trend'
]

CLASS_NAMES = ['at_risk', 'developing', 'proficient', 'advanced']


def load_model():
    """Load model and scaler."""
    
    model_path = os.path.join(SCRIPT_DIR, 'xscaffold_bagging_model.pkl')
    scaler_path = os.path.join(SCRIPT_DIR, 'xscaffold_scaler.pkl')
    
    if not os.path.exists(model_path):
        raise FileNotFoundError(f"Model not found: {model_path}\nRun train_model.py first.")
    
    if not os.path.exists(scaler_path):
        raise FileNotFoundError(f"Scaler not found: {scaler_path}\nRun train_model.py first.")
    
    model = joblib.load(model_path)
    scaler = joblib.load(scaler_path)
    
    return model, scaler


def predict_single(features_dict: dict, model, scaler) -> dict:
    """
    Make a single prediction.
    
    Args:
        features_dict: Dictionary with all 11 features
        model: Trained Bagging Classifier
        scaler: StandardScaler
    
    Returns:
        Prediction result dictionary
    """
    
    # Validate features
    missing = [f for f in FEATURE_NAMES if f not in features_dict]
    if missing:
        raise ValueError(f"Missing features: {missing}")
    
    # Extract features in correct order
    features = [float(features_dict[f]) for f in FEATURE_NAMES]
    features_array = np.array(features).reshape(1, -1)
    
    # Scale
    features_scaled = scaler.transform(features_array)
    
    # Predict
    prediction = int(model.predict(features_scaled)[0])
    probabilities = model.predict_proba(features_scaled)[0]
    
    return {
        'mastery_level': prediction,
        'mastery_level_name': CLASS_NAMES[prediction],
        'confidence': round(float(probabilities[prediction]), 4),
        'probabilities': {
            CLASS_NAMES[i]: round(float(p), 4) 
            for i, p in enumerate(probabilities)
        }
    }


def predict_batch(df: pd.DataFrame, model, scaler) -> pd.DataFrame:
    """
    Make batch predictions from a DataFrame.
    
    Args:
        df: DataFrame with feature columns
        model: Trained model
        scaler: StandardScaler
    
    Returns:
        DataFrame with predictions added
    """
    
    df = df.copy()
    
    # Extract features
    X = df[FEATURE_NAMES].values
    X_scaled = scaler.transform(X)
    
    # Predict
    predictions = model.predict(X_scaled)
    probabilities = model.predict_proba(X_scaled)
    
    # Add to DataFrame
    df['predicted_level'] = predictions
    df['predicted_level_name'] = [CLASS_NAMES[p] for p in predictions]
    df['prediction_confidence'] = [
        round(float(probabilities[i, predictions[i]]), 4) 
        for i in range(len(predictions))
    ]
    
    return df


def main():
    """Main entry point."""
    
    parser = argparse.ArgumentParser(
        description='X-Scaffold Mastery Level Prediction'
    )
    
    parser.add_argument(
        '--features', 
        type=str,
        help='JSON string with feature values for single prediction'
    )
    
    parser.add_argument(
        '--csv',
        type=str,
        help='Path to CSV file for batch prediction'
    )
    
    parser.add_argument(
        '--output',
        type=str,
        default='predictions.csv',
        help='Output path for batch predictions (default: predictions.csv)'
    )
    
    args = parser.parse_args()
    
    # Load model
    print("Loading model...")
    model, scaler = load_model()
    print("✅ Model loaded")
    
    if args.features:
        # Single prediction
        try:
            features = json.loads(args.features)
        except json.JSONDecodeError as e:
            print(f"❌ Invalid JSON: {e}")
            sys.exit(1)
        
        result = predict_single(features, model, scaler)
        
        print("\n" + "=" * 40)
        print("PREDICTION RESULT")
        print("=" * 40)
        print(f"  Mastery Level: {result['mastery_level_name'].upper()}")
        print(f"  Confidence:    {result['confidence']*100:.1f}%")
        print("\n  Class Probabilities:")
        for name, prob in result['probabilities'].items():
            bar = "█" * int(prob * 30)
            print(f"    {name:12s}: {prob*100:5.1f}% {bar}")
        
        print("\n" + json.dumps(result, indent=2))
        
    elif args.csv:
        # Batch prediction
        print(f"Loading data from: {args.csv}")
        df = pd.read_csv(args.csv)
        print(f"  Samples: {len(df)}")
        
        df_result = predict_batch(df, model, scaler)
        df_result.to_csv(args.output, index=False)
        
        print(f"\n✅ Predictions saved to: {args.output}")
        
        # Summary
        print("\n" + "=" * 40)
        print("PREDICTION SUMMARY")
        print("=" * 40)
        summary = df_result['predicted_level_name'].value_counts()
        for level, count in summary.items():
            print(f"  {level:12s}: {count:4d} ({count/len(df)*100:5.1f}%)")
        
    else:
        # Interactive example
        print("\nNo input provided. Running example prediction...")
        
        example = {
            'score_percentage': 72.5,
            'hard_question_accuracy': 65.0,
            'hint_usage_percentage': 18.0,
            'avg_confidence': 3.8,
            'answer_changes_rate': 0.35,
            'tab_switches_rate': 0.9,
            'avg_time_per_question': 82.0,
            'review_percentage': 38.0,
            'avg_first_action_latency': 4.8,
            'clicks_per_question': 4.5,
            'performance_trend': 7.5
        }
        
        print("\nExample Input:")
        for k, v in example.items():
            print(f"  {k}: {v}")
        
        result = predict_single(example, model, scaler)
        
        print("\n" + "=" * 40)
        print("PREDICTION RESULT")
        print("=" * 40)
        print(f"  Mastery Level: {result['mastery_level_name'].upper()}")
        print(f"  Confidence:    {result['confidence']*100:.1f}%")


if __name__ == "__main__":
    main()
