"""
X-Scaffold ML Model Training Script
=====================================
Trains a Bagging Classifier for student mastery level prediction.

Model Architecture:
- Base Estimator: Decision Tree
- Ensemble Method: Bagging (Bootstrap Aggregating)
- Task: 4-class classification (at_risk, developing, proficient, advanced)

Features (11):
- score_percentage, hard_question_accuracy, hint_usage_percentage
- avg_confidence, answer_changes_rate, tab_switches_rate
- avg_time_per_question, review_percentage, avg_first_action_latency
- clicks_per_question, performance_trend

Target:
- mastery_level: 0=at_risk, 1=developing, 2=proficient, 3=advanced

Research Justification:
- Bagging reduces variance and prevents overfitting
- Robust to noisy educational data
- Handles class imbalance through bootstrap sampling
- Provides feature importance through aggregation

Author: X-Scaffold Research Team
Date: January 2026
"""

import numpy as np
import pandas as pd
import joblib
import json
import os
from datetime import datetime

# Scikit-learn imports
from sklearn.model_selection import train_test_split, cross_val_score, StratifiedKFold
from sklearn.ensemble import BaggingClassifier
from sklearn.tree import DecisionTreeClassifier
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import (
    accuracy_score, precision_score, recall_score, f1_score,
    classification_report, confusion_matrix, roc_auc_score
)
from sklearn.inspection import permutation_importance

import warnings
warnings.filterwarnings('ignore')

# ============================================================
# CONFIGURATION
# ============================================================

FEATURE_COLUMNS = [
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

TARGET_COLUMN = 'mastery_level'

CLASS_NAMES = ['at_risk', 'developing', 'proficient', 'advanced']

# Model hyperparameters (tuned for educational data)
BAGGING_CONFIG = {
    'n_estimators': 50,           # Number of base estimators
    'max_samples': 0.8,           # 80% of samples per estimator
    'max_features': 1.0,          # 100% of features per estimator (use all)
    'bootstrap': True,            # Use bootstrap sampling
    'bootstrap_features': False,  # Don't bootstrap features
    'oob_score': True,            # Compute out-of-bag score
    'random_state': 42,
    'n_jobs': -1                  # Use all CPU cores
}

BASE_ESTIMATOR_CONFIG = {
    'max_depth': 8,               # Limit tree depth
    'min_samples_split': 10,      # Require 10 samples to split
    'min_samples_leaf': 5,        # Require 5 samples per leaf
    'random_state': 42
}

# ============================================================
# TRAINING FUNCTIONS
# ============================================================

def load_dataset(filepath: str) -> tuple:
    """Load and prepare dataset for training."""
    
    print(f"Loading dataset from: {filepath}")
    df = pd.read_csv(filepath)
    
    print(f"  Total samples: {len(df)}")
    print(f"  Features: {len(FEATURE_COLUMNS)}")
    
    X = df[FEATURE_COLUMNS].values
    y = df[TARGET_COLUMN].values
    
    # Print class distribution
    print("\n  Class distribution:")
    for i, name in enumerate(CLASS_NAMES):
        count = (y == i).sum()
        print(f"    {name}: {count} ({count/len(y)*100:.1f}%)")
    
    return X, y, df


def train_bagging_classifier(X_train, y_train):
    """Train Bagging Classifier with Decision Tree base estimators."""
    
    print("\n" + "=" * 50)
    print("TRAINING BAGGING CLASSIFIER")
    print("=" * 50)
    
    # Create base estimator
    base_estimator = DecisionTreeClassifier(**BASE_ESTIMATOR_CONFIG)
    
    # Create Bagging Classifier
    model = BaggingClassifier(
        estimator=base_estimator,
        **BAGGING_CONFIG
    )
    
    print("\nModel Configuration:")
    print(f"  Base Estimator: DecisionTreeClassifier")
    print(f"  Number of Estimators: {BAGGING_CONFIG['n_estimators']}")
    print(f"  Max Samples per Estimator: {BAGGING_CONFIG['max_samples']*100}%")
    print(f"  Max Features per Estimator: {BAGGING_CONFIG['max_features']*100}%")
    print(f"  Bootstrap Sampling: {BAGGING_CONFIG['bootstrap']}")
    
    # Train model
    print("\nTraining...")
    start_time = datetime.now()
    model.fit(X_train, y_train)
    training_time = (datetime.now() - start_time).total_seconds()
    
    print(f"  Training completed in {training_time:.2f} seconds")
    
    if hasattr(model, 'oob_score_'):
        print(f"  Out-of-Bag Score: {model.oob_score_:.4f}")
    
    return model


def evaluate_model(model, X_test, y_test, X_train, y_train):
    """Comprehensive model evaluation."""
    
    print("\n" + "=" * 50)
    print("MODEL EVALUATION")
    print("=" * 50)
    
    # Predictions
    y_pred = model.predict(X_test)
    y_pred_proba = model.predict_proba(X_test)
    
    # Training accuracy
    train_acc = accuracy_score(y_train, model.predict(X_train))
    test_acc = accuracy_score(y_test, y_pred)
    
    print(f"\n  Training Accuracy: {train_acc:.4f}")
    print(f"  Test Accuracy:     {test_acc:.4f}")
    print(f"  Overfitting Gap:   {train_acc - test_acc:.4f}")
    
    # Detailed metrics
    print("\n  Per-Class Metrics:")
    print("-" * 50)
    
    precision = precision_score(y_test, y_pred, average=None)
    recall = recall_score(y_test, y_pred, average=None)
    f1 = f1_score(y_test, y_pred, average=None)
    
    for i, name in enumerate(CLASS_NAMES):
        print(f"    {name:12s}  P: {precision[i]:.3f}  R: {recall[i]:.3f}  F1: {f1[i]:.3f}")
    
    # Overall metrics
    print("-" * 50)
    print(f"    {'Macro Avg':12s}  P: {precision.mean():.3f}  R: {recall.mean():.3f}  F1: {f1.mean():.3f}")
    print(f"    {'Weighted Avg':12s}  P: {precision_score(y_test, y_pred, average='weighted'):.3f}  "
          f"R: {recall_score(y_test, y_pred, average='weighted'):.3f}  "
          f"F1: {f1_score(y_test, y_pred, average='weighted'):.3f}")
    
    # Confusion Matrix
    print("\n  Confusion Matrix:")
    cm = confusion_matrix(y_test, y_pred)
    print("  " + "-" * 45)
    print(f"  {'':12s} | " + " | ".join([f"{n:10s}" for n in CLASS_NAMES]))
    print("  " + "-" * 45)
    for i, name in enumerate(CLASS_NAMES):
        row = " | ".join([f"{v:10d}" for v in cm[i]])
        print(f"  {name:12s} | {row}")
    
    # Cross-validation
    print("\n  Cross-Validation (5-fold):")
    cv = StratifiedKFold(n_splits=5, shuffle=True, random_state=42)
    cv_scores = cross_val_score(model, np.vstack([X_train, X_test]), 
                                 np.hstack([y_train, y_test]), 
                                 cv=cv, scoring='accuracy')
    print(f"    Mean CV Accuracy: {cv_scores.mean():.4f} (+/- {cv_scores.std()*2:.4f})")
    
    # Return metrics for saving
    metrics = {
        'training_accuracy': float(train_acc),
        'test_accuracy': float(test_acc),
        'overfitting_gap': float(train_acc - test_acc),
        'precision_macro': float(precision.mean()),
        'recall_macro': float(recall.mean()),
        'f1_macro': float(f1.mean()),
        'f1_weighted': float(f1_score(y_test, y_pred, average='weighted')),
        'cv_mean': float(cv_scores.mean()),
        'cv_std': float(cv_scores.std()),
        'per_class': {
            name: {
                'precision': float(precision[i]),
                'recall': float(recall[i]),
                'f1': float(f1[i])
            } for i, name in enumerate(CLASS_NAMES)
        },
        'confusion_matrix': cm.tolist()
    }
    
    return metrics


def compute_feature_importance(model, X_train, y_train, X_test, y_test):
    """Calculate and display feature importance."""
    
    print("\n" + "=" * 50)
    print("FEATURE IMPORTANCE ANALYSIS")
    print("=" * 50)
    
    # Method 1: Aggregate from base estimators (for tree-based)
    print("\n  [Method 1] Mean Decrease in Impurity (Gini):")
    
    # Get feature importances from all base estimators
    all_importances = []
    for estimator in model.estimators_:
        all_importances.append(estimator.feature_importances_)
    all_importances = np.array(all_importances)
    mean_importance = all_importances.mean(axis=0)
    std_importance = all_importances.std(axis=0)
    
    # Create list of (index, importance) and sort
    importance_list = [(i, mean_importance[i], std_importance[i]) for i in range(len(FEATURE_COLUMNS))]
    importance_list.sort(key=lambda x: x[1], reverse=True)
    
    print("  " + "-" * 50)
    gini_ranks = {}
    for rank, (idx, imp, std) in enumerate(importance_list, 1):
        name = FEATURE_COLUMNS[idx]
        gini_ranks[idx] = rank
        bar = "█" * int(imp * 50)
        print(f"    {rank:2d}. {name:25s} {imp:.4f} (±{std:.4f}) {bar}")
    
    # Method 2: Permutation Importance
    print("\n  [Method 2] Permutation Importance (on test set):")
    
    perm_importance = permutation_importance(
        model, X_test, y_test, 
        n_repeats=10, 
        random_state=42,
        n_jobs=-1
    )
    
    # Create list of (index, importance) and sort
    perm_list = [(i, perm_importance.importances_mean[i], perm_importance.importances_std[i]) 
                 for i in range(len(FEATURE_COLUMNS))]
    perm_list.sort(key=lambda x: x[1], reverse=True)
    
    print("  " + "-" * 50)
    perm_ranks = {}
    for rank, (idx, imp, std) in enumerate(perm_list, 1):
        name = FEATURE_COLUMNS[idx]
        perm_ranks[idx] = rank
        bar = "█" * int(imp * 100)
        print(f"    {rank:2d}. {name:25s} {imp:.4f} (±{std:.4f}) {bar}")
    
    # Return importance dict for saving
    importance_dict = {
        'gini_importance': {},
        'permutation_importance': {}
    }
    
    for i, feat_name in enumerate(FEATURE_COLUMNS):
        importance_dict['gini_importance'][feat_name] = {
            'mean': float(mean_importance[i]),
            'std': float(std_importance[i]),
            'rank': gini_ranks.get(i, len(FEATURE_COLUMNS))
        }
        importance_dict['permutation_importance'][feat_name] = {
            'mean': float(perm_importance.importances_mean[i]),
            'std': float(perm_importance.importances_std[i]),
            'rank': perm_ranks.get(i, len(FEATURE_COLUMNS))
        }
    
    return importance_dict


def save_model_artifacts(model, scaler, metrics, importance, output_dir: str):
    """Save model and all related artifacts."""
    
    print("\n" + "=" * 50)
    print("SAVING MODEL ARTIFACTS")
    print("=" * 50)
    
    os.makedirs(output_dir, exist_ok=True)
    
    # Save model
    model_path = os.path.join(output_dir, 'xscaffold_bagging_model.pkl')
    joblib.dump(model, model_path)
    print(f"  ✅ Model saved: {model_path}")
    
    # Save scaler
    scaler_path = os.path.join(output_dir, 'xscaffold_scaler.pkl')
    joblib.dump(scaler, scaler_path)
    print(f"  ✅ Scaler saved: {scaler_path}")
    
    # Save feature names
    features_path = os.path.join(output_dir, 'feature_names.json')
    with open(features_path, 'w') as f:
        json.dump({
            'features': FEATURE_COLUMNS,
            'target': TARGET_COLUMN,
            'class_names': CLASS_NAMES
        }, f, indent=2)
    print(f"  ✅ Feature names saved: {features_path}")
    
    # Save metrics
    metrics_path = os.path.join(output_dir, 'model_metrics.json')
    with open(metrics_path, 'w') as f:
        json.dump(metrics, f, indent=2)
    print(f"  ✅ Metrics saved: {metrics_path}")
    
    # Save feature importance
    importance_path = os.path.join(output_dir, 'feature_importance.json')
    with open(importance_path, 'w') as f:
        json.dump(importance, f, indent=2)
    print(f"  ✅ Feature importance saved: {importance_path}")
    
    # Save model config
    config_path = os.path.join(output_dir, 'model_config.json')
    with open(config_path, 'w') as f:
        json.dump({
            'model_type': 'BaggingClassifier',
            'base_estimator': 'DecisionTreeClassifier',
            'bagging_config': BAGGING_CONFIG,
            'base_estimator_config': BASE_ESTIMATOR_CONFIG,
            'training_date': datetime.now().isoformat(),
            'sklearn_version': '1.3.0'
        }, f, indent=2)
    print(f"  ✅ Config saved: {config_path}")


def main():
    """Main training pipeline."""
    
    print("\n" + "=" * 60)
    print("X-SCAFFOLD ML TRAINING PIPELINE")
    print("Bagging Classifier for Mastery Level Prediction")
    print("=" * 60)
    
    # Paths
    script_dir = os.path.dirname(os.path.abspath(__file__))
    dataset_path = os.path.join(script_dir, 'xscaffold_student_dataset.csv')
    output_dir = script_dir
    
    # Step 1: Load dataset
    X, y, df = load_dataset(dataset_path)
    
    # Step 2: Split data
    print("\nSplitting dataset (80% train, 20% test)...")
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )
    print(f"  Training samples: {len(X_train)}")
    print(f"  Test samples: {len(X_test)}")
    
    # Step 3: Scale features
    print("\nScaling features (StandardScaler)...")
    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)
    
    # Step 4: Train model
    model = train_bagging_classifier(X_train_scaled, y_train)
    
    # Step 5: Evaluate model
    metrics = evaluate_model(model, X_test_scaled, y_test, X_train_scaled, y_train)
    
    # Step 6: Feature importance
    importance = compute_feature_importance(
        model, X_train_scaled, y_train, X_test_scaled, y_test
    )
    
    # Step 7: Save artifacts
    save_model_artifacts(model, scaler, metrics, importance, output_dir)
    
    print("\n" + "=" * 60)
    print("TRAINING COMPLETE")
    print("=" * 60)
    print(f"\n  Final Test Accuracy: {metrics['test_accuracy']:.4f}")
    print(f"  F1 Score (Weighted): {metrics['f1_weighted']:.4f}")
    print(f"  Cross-Val Accuracy:  {metrics['cv_mean']:.4f} (±{metrics['cv_std']*2:.4f})")
    print("\n  Model ready for deployment!")
    
    return model, scaler, metrics


if __name__ == "__main__":
    model, scaler, metrics = main()