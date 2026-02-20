"""
X-Scaffold Dataset Generator (v2.0 - Cholesky from Real Data)
==============================================================
Generates realistic synthetic student performance data using Cholesky
decomposition to preserve the correlation structure from real collected data.

METHODOLOGY:
1. Load 51 real student records from Mock Exam Application
2. Calculate correlation matrix from real data
3. Use Cholesky decomposition to generate correlated samples
4. Apply realistic constraints and LMS classification

This approach addresses methodological concerns by:
- Grounding synthetic data in real student behavior patterns
- Preserving authentic inter-feature correlations
- Maintaining statistical properties from actual educational data

Features (11 total):
- Tier 1 (6 features used in LMS calculation):
  1. score_percentage, 2. hard_question_accuracy, 3. hint_usage_percentage
  4. avg_confidence, 5. answer_changes_rate, 6. tab_switches_rate

- Tier 2 (5 additional ML predictors):
  7. avg_time_per_question, 8. review_percentage, 9. avg_first_action_latency
  10. clicks_per_question, 11. performance_trend

Author: X-Scaffold Research Team
Date: February 2026 (Updated)
"""

import numpy as np
import pandas as pd
from scipy import stats
import os
import warnings
warnings.filterwarnings('ignore')

# Set random seed for reproducibility
np.random.seed(42)

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

# Feature constraints (min, max)
FEATURE_CONSTRAINTS = {
    'score_percentage': (0, 100),
    'hard_question_accuracy': (0, 100),
    'hint_usage_percentage': (0, 100),
    'avg_confidence': (1, 5),
    'answer_changes_rate': (0, 5),
    'tab_switches_rate': (0, 10),
    'avg_time_per_question': (1, 300),
    'review_percentage': (0, 100),
    'avg_first_action_latency': (0.5, 60),
    'clicks_per_question': (1, 50),
    'performance_trend': (-1, 1)
}


def load_real_data(filepath: str = None) -> pd.DataFrame:
    """
    Load the real student data collected from Mock Exam Application.
    
    Returns:
        DataFrame with 51 real student records
    """
    if filepath is None:
        script_dir = os.path.dirname(os.path.abspath(__file__))
        filepath = os.path.join(script_dir, 'student_research_data.csv')
    
    if not os.path.exists(filepath):
        raise FileNotFoundError(
            f"Real data file not found: {filepath}\n"
            "Please ensure student_research_data.csv is in the ml_model directory."
        )
    
    df = pd.read_csv(filepath)
    print(f"✅ Loaded {len(df)} real student records from Mock Exam Application")
    return df


def generate_synthetic_from_real(real_df: pd.DataFrame, n_students: int = 2000) -> pd.DataFrame:
    """
    Generate synthetic data using Cholesky decomposition on REAL correlation matrix.
    
    This method:
    1. Extracts correlation structure from real student data
    2. Uses Cholesky decomposition to preserve correlations
    3. Generates new samples with the same statistical properties
    
    Args:
        real_df: DataFrame with real student data
        n_students: Number of synthetic students to generate
    
    Returns:
        DataFrame with synthetic student profiles
    """
    print(f"\n[1/5] Extracting statistics from {len(real_df)} real records...")
    
    # Get statistics from REAL data
    means = real_df[FEATURE_COLUMNS].mean()
    stds = real_df[FEATURE_COLUMNS].std()
    corr_matrix = real_df[FEATURE_COLUMNS].corr()
    
    print(f"[2/5] Performing Cholesky decomposition on real correlation matrix...")
    
    # Add small regularization for numerical stability
    regularized_corr = corr_matrix.values + np.eye(len(FEATURE_COLUMNS)) * 0.001
    
    # Cholesky decomposition
    L = np.linalg.cholesky(regularized_corr)
    
    print(f"[3/5] Generating {n_students} correlated samples...")
    
    # Generate uncorrelated standard normal samples
    uncorrelated = np.random.normal(0, 1, (n_students, len(FEATURE_COLUMNS)))
    
    # Apply Cholesky to induce real correlation structure
    correlated = uncorrelated @ L.T
    
    # Scale to original distribution (real means and stds)
    synthetic_data = pd.DataFrame(
        correlated * stds.values + means.values,
        columns=FEATURE_COLUMNS
    )
    
    print(f"[4/5] Applying realistic constraints...")
    
    # Apply feature-specific constraints
    for col, (min_val, max_val) in FEATURE_CONSTRAINTS.items():
        if col in synthetic_data.columns:
            synthetic_data[col] = synthetic_data[col].clip(min_val, max_val).round(2)
    
    print(f"[5/5] Calculating LMS and mastery levels...")
    
    return synthetic_data


def calculate_lms(df: pd.DataFrame) -> pd.DataFrame:
    """
    Calculate Learning Mastery Score (LMS) using the hybrid formula.
    
    The weights were derived through a two-stage process:
    
    Stage 1 — Literature-Based Initial Formula:
        LMS = 0.50×S + 0.15×Hd + 10×Ccal + 10×Ks + 10×Af − 15×Hu^1.5
        (Pardos & Baker, 2014; Chi et al., 2018; Aleven et al., 2016)
    
    Stage 2 — Data-Driven Correlation Analysis (N=56 real students):
        Four unsupervised methods (PCA, Entropy, Factor Analysis, CRITIC)
        applied to the 6 core features to derive empirical weights.
        Results: Hd(26.6%), S(25.6%), C(16.5%), Ts(15.2%), Hu(11.0%), Ac(5.1%)
        (Jolliffe, 2002; Shannon, 1948; Diakoulaki et al., 1995)
    
    Stage 3 — Refined Hybrid Formula (literature-inspired, data-validated):
        LMS = 0.30×S + 0.25×(Hd×100) + 15×Ccal + 15×Af − 10×Hu − 5×Ac
    
    Where:
    - S = score_percentage (0-100) — reduced from 50% to 30% per data analysis
    - Hd = hard_question_accuracy / 100 — increased from 15% to 25% (data: #1 differentiator)
    - Ccal = Calibration bonus based on avg_confidence alignment — increased from 10 to 15
    - Af = Attention factor bonus based on tab_switches_rate — increased from 10 to 15
    - Hu = hint_usage_percentage / 100 — reduced from 15 to 10
    - Ac = answer_changes_rate penalty — reduced from 10 to 5 (data: low variance)
    """
    df = df.copy()
    
    # S - Score percentage (30% weight — data shows tied with Hd, not dominant)
    S = df['score_percentage']
    
    # Hd - Hard question accuracy (25% weight — data: #1 differentiator, r=0.87 with S)
    Hd = df['hard_question_accuracy'] / 100
    
    # Ccal - Calibration bonus (confidence alignment with performance)
    expected_confidence = 1 + (df['score_percentage'] / 25)  # 1-5 scale based on score
    confidence_diff = np.abs(df['avg_confidence'] - expected_confidence)
    Ccal = np.where(confidence_diff <= 1, 1, 0)
    
    # Af - Attention factor (low tab switches = focused = bonus)
    # Data: r=0.50 with hard_question_accuracy — stronger than expected
    Af = np.clip(1 - (df['tab_switches_rate'] - 1) / 2.0, 0, 1)
    
    # Hu - Hint usage penalty (reduced from 15 to 10 — data: 11.0% weight)
    Hu = df['hint_usage_percentage'] / 100
    
    # Ac - Answer changes penalty (reduced from 10 to 5 — data: only 5.1% weight,
    # 75% of students have near-zero variance on this feature)
    Ac = np.clip((df['answer_changes_rate'] - 0.5) / 1.0, 0, 1)
    
    # Calculate LMS using hybrid formula
    df['learning_mastery_score'] = (
        0.30 * S +
        0.25 * (Hd * 100) +
        15 * Ccal +
        15 * Af -
        10 * Hu -
        5 * Ac
    ).round(1)
    
    # Clip to 0-100 range
    df['learning_mastery_score'] = df['learning_mastery_score'].clip(0, 100)
    
    # Classify mastery level based on LMS
    def classify_mastery(lms):
        if lms < 36:
            return 0  # at_risk
        elif lms < 56:
            return 1  # developing
        elif lms < 76:
            return 2  # proficient
        else:
            return 3  # advanced
    
    df['mastery_level'] = df['learning_mastery_score'].apply(classify_mastery)
    
    # Add mastery level names for reference
    mastery_names = {0: 'at_risk', 1: 'developing', 2: 'proficient', 3: 'advanced'}
    df['mastery_level_name'] = df['mastery_level'].map(mastery_names)
    
    return df


def add_student_ids(df: pd.DataFrame) -> pd.DataFrame:
    """Add unique student IDs."""
    df = df.copy()
    df.insert(0, 'student_id', [f'STU{str(i+1).zfill(4)}' for i in range(len(df))])
    return df


def generate_dataset(n_students: int = 2000, output_path: str = None) -> pd.DataFrame:
    """
    Main function to generate the complete X-Scaffold dataset.
    
    Uses Cholesky decomposition on real student data to preserve
    authentic correlation structures in synthetic generation.
    
    Args:
        n_students: Number of students to generate
        output_path: Path to save CSV (optional)
    
    Returns:
        Complete DataFrame with all features and targets
    """
    print("=" * 60)
    print("X-SCAFFOLD DATASET GENERATOR (v2.0 - Cholesky from Real Data)")
    print("=" * 60)
    
    # Step 1: Load real data
    real_df = load_real_data()
    
    # Step 2: Generate synthetic data using Cholesky on real correlations
    df = generate_synthetic_from_real(real_df, n_students)
    
    # Step 3: Calculate LMS
    df = calculate_lms(df)
    
    # Step 4: Add student IDs
    df = add_student_ids(df)
    
    # Step 5: Verify and report
    print("\n" + "-" * 40)
    print("GENERATION COMPLETE")
    print("-" * 40)
    
    print("\n📊 Class Distribution:")
    distribution = df['mastery_level_name'].value_counts()
    for level, count in distribution.items():
        pct = count / len(df) * 100
        print(f"  {level:12s}: {count:4d} ({pct:5.1f}%)")
    
    print("\n📈 LMS Statistics:")
    print(f"  Mean LMS:   {df['learning_mastery_score'].mean():.1f}")
    print(f"  Std LMS:    {df['learning_mastery_score'].std():.1f}")
    print(f"  Min LMS:    {df['learning_mastery_score'].min():.1f}")
    print(f"  Max LMS:    {df['learning_mastery_score'].max():.1f}")
    
    # Compare with real data (reuse already loaded real_df)
    real_df_lms = calculate_lms(real_df.copy())
    print("\n🔍 Comparison with Real Data:")
    print(f"  Real Mean LMS:      {real_df_lms['learning_mastery_score'].mean():.1f}")
    print(f"  Synthetic Mean LMS: {df['learning_mastery_score'].mean():.1f}")
    
    # Save if path provided
    if output_path:
        final_columns = [
            'student_id',
            'score_percentage', 'hard_question_accuracy', 'hint_usage_percentage',
            'avg_confidence', 'answer_changes_rate', 'tab_switches_rate',
            'avg_time_per_question', 'review_percentage', 'avg_first_action_latency',
            'clicks_per_question', 'performance_trend',
            'learning_mastery_score', 'mastery_level', 'mastery_level_name'
        ]
        df_export = df[final_columns]
        df_export.to_csv(output_path, index=False)
        print(f"\n✅ Dataset saved to: {output_path}")
    
    print("\n" + "=" * 60)
    print("DATASET GENERATION COMPLETE")
    print("Methodology: Cholesky decomposition on 51 real student records")
    print("=" * 60)
    
    return df


if __name__ == "__main__":
    # Generate dataset with 2000 students
    df = generate_dataset(
        n_students=2000,
        output_path="xscaffold_student_dataset.csv"
    )
