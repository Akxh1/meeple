"""
X-Scaffold Dataset Generator (v3.0 - Teacher-Labelled)
========================================================
Generates realistic synthetic student performance data using Cholesky
decomposition to preserve the correlation structure from real collected data.

TARGET VARIABLE: Teacher-assigned mastery labels (external, non-circular).
FALLBACK: Formulaic LMS classification (when teacher labels unavailable).

METHODOLOGY:
1. Load 81 real student records with teacher-assigned mastery labels
2. Calculate correlation matrix from real data
3. Use Cholesky decomposition to generate correlated feature samples
4. Assign mastery labels to synthetic samples via KNN (nearest real neighbour)

This approach addresses the circular target variable issue by:
- Using an independent, teacher-assigned target variable
- Propagating teacher judgement to synthetic samples via feature-space proximity
- Keeping the LMS formula as a fallback for deployments without teacher data

Features (11 total):
- Tier 1 (6 features used in LMS fallback):
  1. score_percentage, 2. hard_question_accuracy, 3. hint_usage_percentage
  4. avg_confidence, 5. answer_changes_rate, 6. tab_switches_rate

- Tier 2 (5 additional ML predictors):
  7. avg_time_per_question, 8. review_percentage, 9. avg_first_action_latency
  10. clicks_per_question, 11. performance_trend

Date: March 2026 (Updated — switched from formulaic LMS to teacher labels)
"""

import numpy as np
import pandas as pd
from sklearn.neighbors import KNeighborsClassifier
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

# Teacher label mapping
TEACHER_LABEL_MAP = {
    'At Risk': 0,
    'Developing': 1,
    'Proficient': 2,
    'Advanced': 3
}

MASTERY_NAMES = {0: 'at_risk', 1: 'developing', 2: 'proficient', 3: 'advanced'}


def load_real_data(filepath: str = None, use_teacher_labels: bool = True) -> pd.DataFrame:
    """
    Load the real student data collected from Mock Exam Application.
    
    Args:
        filepath: Optional path to CSV file
        use_teacher_labels: If True, load teacher-reviewed data (81 records).
                           If False, load original data (51 records) for LMS fallback.
    
    Returns:
        DataFrame with real student records
    """
    if filepath is None:
        script_dir = os.path.dirname(os.path.abspath(__file__))
        if use_teacher_labels:
            filepath = os.path.join(script_dir, 'student_research_data_teacher_reviewed.csv')
        else:
            filepath = os.path.join(script_dir, 'student_research_data.csv')
    
    if not os.path.exists(filepath):
        raise FileNotFoundError(
            f"Real data file not found: {filepath}\n"
            "Please ensure the data file is in the ml_model directory."
        )
    
    df = pd.read_csv(filepath)
    
    if use_teacher_labels:
        if 'Teacher Rating' not in df.columns:
            raise ValueError(
                "Teacher Rating column not found in dataset. "
                "Set use_teacher_labels=False to use LMS fallback."
            )
        # Map teacher labels to numeric mastery levels
        df['mastery_level'] = df['Teacher Rating'].map(TEACHER_LABEL_MAP)
        df['mastery_level_name'] = df['mastery_level'].map(MASTERY_NAMES)
        print(f"✅ Loaded {len(df)} real student records with TEACHER-ASSIGNED labels")
    else:
        print(f"✅ Loaded {len(df)} real student records (LMS fallback mode)")
    
    return df


def generate_synthetic_features(real_df: pd.DataFrame, n_students: int = 2000) -> pd.DataFrame:
    """
    Generate synthetic feature data using Cholesky decomposition on REAL correlation matrix.
    
    This method:
    1. Extracts correlation structure from real student data
    2. Uses Cholesky decomposition to preserve correlations
    3. Generates new samples with the same statistical properties
    
    Note: This generates FEATURES ONLY. Labels are assigned separately.
    
    Args:
        real_df: DataFrame with real student data
        n_students: Number of synthetic students to generate
    
    Returns:
        DataFrame with synthetic student feature profiles (no labels yet)
    """
    print(f"\n[1/4] Extracting statistics from {len(real_df)} real records...")
    
    # Get statistics from REAL data
    means = real_df[FEATURE_COLUMNS].mean()
    stds = real_df[FEATURE_COLUMNS].std()
    corr_matrix = real_df[FEATURE_COLUMNS].corr()
    
    print(f"[2/4] Performing Cholesky decomposition on real correlation matrix...")
    
    # Add small regularization for numerical stability
    regularized_corr = corr_matrix.values + np.eye(len(FEATURE_COLUMNS)) * 0.001
    
    # Cholesky decomposition
    L = np.linalg.cholesky(regularized_corr)
    
    print(f"[3/4] Generating {n_students} correlated samples...")
    
    # Generate uncorrelated standard normal samples
    uncorrelated = np.random.normal(0, 1, (n_students, len(FEATURE_COLUMNS)))
    
    # Apply Cholesky to induce real correlation structure
    correlated = uncorrelated @ L.T
    
    # Scale to original distribution (real means and stds)
    synthetic_data = pd.DataFrame(
        correlated * stds.values + means.values,
        columns=FEATURE_COLUMNS
    )
    
    print(f"[4/4] Applying realistic constraints...")
    
    # Apply feature-specific constraints
    for col, (min_val, max_val) in FEATURE_CONSTRAINTS.items():
        if col in synthetic_data.columns:
            synthetic_data[col] = synthetic_data[col].clip(min_val, max_val).round(2)
    
    return synthetic_data


def assign_teacher_labels_via_knn(synthetic_df: pd.DataFrame, real_df: pd.DataFrame,
                                   n_neighbors: int = 5) -> pd.DataFrame:
    """
    Assign mastery labels to synthetic samples using K-Nearest Neighbours.
    
    Each synthetic sample inherits the teacher-assigned label of its closest
    real records in feature space (distance-weighted voting).
    
    This preserves teacher judgement rather than reapplying the formulaic LMS,
    breaking the circular target variable problem.
    
    Args:
        synthetic_df: DataFrame with synthetic features (no labels)
        real_df: DataFrame with real features + teacher mastery_level
        n_neighbors: Number of neighbours for KNN voting
    
    Returns:
        DataFrame with mastery_level and mastery_level_name columns added
    """
    print(f"\n[KNN] Propagating teacher labels to synthetic samples (K={n_neighbors})...")
    
    df = synthetic_df.copy()
    
    # Fit KNN on real data with teacher labels
    knn = KNeighborsClassifier(n_neighbors=n_neighbors, weights='distance', metric='euclidean')
    knn.fit(real_df[FEATURE_COLUMNS].values, real_df['mastery_level'].values)
    
    # Predict labels for synthetic samples
    df['mastery_level'] = knn.predict(df[FEATURE_COLUMNS].values)
    df['mastery_level_name'] = df['mastery_level'].map(MASTERY_NAMES)
    
    # Report KNN accuracy on real data (leave-one-out estimate)
    from sklearn.model_selection import cross_val_score
    loo_scores = cross_val_score(
        KNeighborsClassifier(n_neighbors=n_neighbors, weights='distance'),
        real_df[FEATURE_COLUMNS].values,
        real_df['mastery_level'].values,
        cv=min(5, len(real_df)),
        scoring='accuracy'
    )
    print(f"[KNN] Label propagation complete")
    print(f"[KNN] KNN cross-val accuracy on real data: {loo_scores.mean():.1%} "
          f"(confirms teacher labels are learnable)")
    
    return df


def calculate_lms(df: pd.DataFrame) -> pd.DataFrame:
    """
    FALLBACK: Calculate Learning Mastery Score (LMS) using the hybrid formula.
    
    Used when teacher labels are not available. This is the formulaic approach
    that was the original (circular) target variable.
    
    The weights were derived through a two-stage process:
    
    Stage 1 — Literature-Based Initial Formula:
        LMS = 0.50×S + 0.15×Hd + 10×Ccal + 10×Ks + 10×Af − 15×Hu^1.5
        (Pardos & Baker, 2014; Chi et al., 2018; Aleven et al., 2016)
    
    Stage 2 — Data-Driven Correlation Analysis (N=56 real students):
        Four unsupervised methods (PCA, Entropy, Factor Analysis, CRITIC)
        applied to the 6 core features to derive empirical weights.
    
    Stage 3 — Refined Hybrid Formula (literature-inspired, data-validated):
        LMS = 0.30×S + 0.25×(Hd×100) + 15×Ccal + 15×Af − 10×Hu − 5×Ac
    """
    df = df.copy()
    
    # S - Score percentage (30% weight)
    S = df['score_percentage']
    
    # Hd - Hard question accuracy (25% weight)
    Hd = df['hard_question_accuracy'] / 100
    
    # Ccal - Calibration bonus (confidence alignment with performance)
    expected_confidence = 1 + (df['score_percentage'] / 25)
    confidence_diff = np.abs(df['avg_confidence'] - expected_confidence)
    Ccal = np.where(confidence_diff <= 1, 1, 0)
    
    # Af - Attention factor (low tab switches = focused = bonus)
    Af = np.clip(1 - (df['tab_switches_rate'] - 1) / 2.0, 0, 1)
    
    # Hu - Hint usage penalty
    Hu = df['hint_usage_percentage'] / 100
    
    # Ac - Answer changes penalty
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
    df['mastery_level_name'] = df['mastery_level'].map(MASTERY_NAMES)
    
    return df


def add_student_ids(df: pd.DataFrame) -> pd.DataFrame:
    """Add unique student IDs."""
    df = df.copy()
    df.insert(0, 'student_id', [f'STU{str(i+1).zfill(4)}' for i in range(len(df))])
    return df


def generate_dataset(n_students: int = 2000, output_path: str = None,
                     use_teacher_labels: bool = True) -> pd.DataFrame:
    """
    Main function to generate the complete X-Scaffold dataset.
    
    Uses Cholesky decomposition on real student data to generate synthetic
    features, then assigns mastery labels via:
    - Teacher labels (default): KNN propagation from teacher-rated real records
    - LMS fallback: Formulaic classification when teacher data unavailable
    
    Args:
        n_students: Number of students to generate
        output_path: Path to save CSV (optional)
        use_teacher_labels: Use teacher labels (True) or LMS formula (False)
    
    Returns:
        Complete DataFrame with all features and targets
    """
    mode = "TEACHER-LABELLED" if use_teacher_labels else "LMS FALLBACK"
    print("=" * 60)
    print(f"X-SCAFFOLD DATASET GENERATOR (v3.0 - {mode})")
    print("=" * 60)
    
    # Step 1: Load real data
    real_df = load_real_data(use_teacher_labels=use_teacher_labels)
    
    # Step 2: If using LMS fallback, calculate LMS labels for real data
    if not use_teacher_labels:
        real_df = calculate_lms(real_df)
    
    # Step 3: Generate synthetic features using Cholesky on real correlations
    df = generate_synthetic_features(real_df, n_students)
    
    # Step 4: Assign labels
    if use_teacher_labels:
        df = assign_teacher_labels_via_knn(df, real_df)
    else:
        df = calculate_lms(df)
    
    # Step 5: Add student IDs
    df = add_student_ids(df)
    
    # Step 6: Verify and report
    print("\n" + "-" * 40)
    print("GENERATION COMPLETE")
    print("-" * 40)
    
    print("\n📊 Class Distribution:")
    distribution = df['mastery_level_name'].value_counts()
    for level, count in distribution.items():
        pct = count / len(df) * 100
        print(f"  {level:12s}: {count:4d} ({pct:5.1f}%)")
    
    # Compare with real data distribution
    print("\n🔍 Comparison with Real Data:")
    real_dist = real_df['mastery_level_name'].value_counts()
    for level in ['at_risk', 'developing', 'proficient', 'advanced']:
        real_pct = real_dist.get(level, 0) / len(real_df) * 100
        synth_pct = distribution.get(level, 0) / len(df) * 100
        print(f"  {level:12s}: Real={real_pct:5.1f}%  Synthetic={synth_pct:5.1f}%")
    
    # Save if path provided
    if output_path:
        final_columns = [
            'student_id',
            'score_percentage', 'hard_question_accuracy', 'hint_usage_percentage',
            'avg_confidence', 'answer_changes_rate', 'tab_switches_rate',
            'avg_time_per_question', 'review_percentage', 'avg_first_action_latency',
            'clicks_per_question', 'performance_trend',
            'mastery_level', 'mastery_level_name'
        ]
        # Include learning_mastery_score only if LMS fallback was used
        if 'learning_mastery_score' in df.columns:
            final_columns.insert(-2, 'learning_mastery_score')
        
        df_export = df[final_columns]
        df_export.to_csv(output_path, index=False)
        print(f"\n✅ Dataset saved to: {output_path}")
    
    label_source = "teacher-assigned (KNN propagated)" if use_teacher_labels else "formulaic LMS"
    print("\n" + "=" * 60)
    print("DATASET GENERATION COMPLETE")
    print(f"Methodology: Cholesky decomposition on {len(real_df)} real student records")
    print(f"Target variable: {label_source}")
    print("=" * 60)
    
    return df


if __name__ == "__main__":
    # Generate dataset with 2000 students using teacher labels
    df = generate_dataset(
        n_students=2000,
        output_path="xscaffold_student_dataset.csv",
        use_teacher_labels=True
    )
