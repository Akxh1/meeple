"""
X-Scaffold Dataset Generator
=============================
Generates realistic synthetic student performance data for the X-Scaffold
Predict-Explain-Act framework based on educational research.

Features (11 total):
- Tier 1 (6 features used in LMS calculation):
  1. score_percentage: Overall exam score (0-100%)
  2. hard_question_accuracy: Accuracy on difficult questions (0-100%)
  3. hint_usage_percentage: % of questions where hints used (0-100%)
  4. avg_confidence: Self-reported confidence (1-5 scale)
  5. answer_changes_rate: Answer changes per question (0-2)
  6. tab_switches_rate: Tab switches per question (0-5)

- Tier 2 (5 additional ML predictors):
  7. avg_time_per_question: Average seconds per question (5-300s)
  8. review_percentage: % of questions marked for review (0-100%)
  9. avg_first_action_latency: Seconds before first click (0.5-30s)
  10. clicks_per_question: Total clicks per question (1-20)
  11. performance_trend: Score change 1st→2nd half (-50 to +50)

Target Variable:
- mastery_level: Classification target (0=at_risk, 1=developing, 2=proficient, 3=advanced)
- learning_mastery_score: Regression target (0-100)

Author: X-Scaffold Research Team
Date: January 2026
"""

import numpy as np
import pandas as pd
from scipy import stats
import warnings
warnings.filterwarnings('ignore')

# Set random seed for reproducibility
np.random.seed(42)

def generate_student_profiles(n_students: int = 2000) -> pd.DataFrame:
    """
    Generate realistic student performance profiles using correlated feature distributions.
    
    The generation process:
    1. Create 4 student archetypes (at_risk, developing, proficient, advanced)
    2. Generate base features with appropriate correlations
    3. Add realistic noise and constraints
    4. Calculate LMS and mastery levels
    """
    
    # Distribution of students across mastery levels (realistic class distribution)
    # Slightly skewed toward developing/proficient (bell curve)
    level_distribution = {
        'at_risk': 0.15,      # 15% at-risk
        'developing': 0.35,   # 35% developing
        'proficient': 0.35,   # 35% proficient
        'advanced': 0.15      # 15% advanced
    }
    
    # Calculate number of students per level
    n_at_risk = int(n_students * level_distribution['at_risk'])
    n_developing = int(n_students * level_distribution['developing'])
    n_proficient = int(n_students * level_distribution['proficient'])
    n_advanced = n_students - n_at_risk - n_developing - n_proficient
    
    students = []
    
    # ============================================================
    # ARCHETYPE 1: AT-RISK STUDENTS (LMS: 0-35)
    # Characteristics: Low scores, high hint usage, low confidence,
    # erratic behavior (many answer changes, tab switches)
    # ============================================================
    for _ in range(n_at_risk):
        score = np.clip(np.random.normal(35, 12), 10, 55)
        hard_acc = np.clip(np.random.normal(20, 10), 0, 40)
        hint_usage = np.clip(np.random.normal(65, 15), 30, 100)
        confidence = np.clip(np.random.normal(2.0, 0.5), 1, 3.5)
        answer_changes = np.clip(np.random.normal(1.2, 0.4), 0.3, 2)
        tab_switches = np.clip(np.random.normal(3.5, 1.2), 1, 5)
        avg_time = np.clip(np.random.normal(45, 20), 10, 120)
        review_pct = np.clip(np.random.normal(15, 10), 0, 40)
        first_action = np.clip(np.random.normal(12, 5), 3, 30)
        clicks = np.clip(np.random.normal(12, 4), 4, 20)
        perf_trend = np.clip(np.random.normal(-15, 10), -50, 10)
        
        students.append({
            'score_percentage': round(score, 1),
            'hard_question_accuracy': round(hard_acc, 1),
            'hint_usage_percentage': round(hint_usage, 1),
            'avg_confidence': round(confidence, 2),
            'answer_changes_rate': round(answer_changes, 3),
            'tab_switches_rate': round(tab_switches, 2),
            'avg_time_per_question': round(avg_time, 1),
            'review_percentage': round(review_pct, 1),
            'avg_first_action_latency': round(first_action, 2),
            'clicks_per_question': round(clicks, 1),
            'performance_trend': round(perf_trend, 1),
            'archetype': 'at_risk'
        })
    
    # ============================================================
    # ARCHETYPE 2: DEVELOPING STUDENTS (LMS: 36-55)
    # Characteristics: Moderate scores, moderate hint usage,
    # average confidence, some inconsistency
    # ============================================================
    for _ in range(n_developing):
        score = np.clip(np.random.normal(55, 10), 35, 70)
        hard_acc = np.clip(np.random.normal(40, 12), 15, 60)
        hint_usage = np.clip(np.random.normal(40, 15), 15, 70)
        confidence = np.clip(np.random.normal(2.8, 0.5), 1.5, 4)
        answer_changes = np.clip(np.random.normal(0.8, 0.3), 0.2, 1.5)
        tab_switches = np.clip(np.random.normal(2.0, 0.8), 0.5, 4)
        avg_time = np.clip(np.random.normal(60, 25), 20, 150)
        review_pct = np.clip(np.random.normal(25, 12), 5, 50)
        first_action = np.clip(np.random.normal(8, 4), 2, 20)
        clicks = np.clip(np.random.normal(8, 3), 3, 15)
        perf_trend = np.clip(np.random.normal(-5, 12), -30, 20)
        
        students.append({
            'score_percentage': round(score, 1),
            'hard_question_accuracy': round(hard_acc, 1),
            'hint_usage_percentage': round(hint_usage, 1),
            'avg_confidence': round(confidence, 2),
            'answer_changes_rate': round(answer_changes, 3),
            'tab_switches_rate': round(tab_switches, 2),
            'avg_time_per_question': round(avg_time, 1),
            'review_percentage': round(review_pct, 1),
            'avg_first_action_latency': round(first_action, 2),
            'clicks_per_question': round(clicks, 1),
            'performance_trend': round(perf_trend, 1),
            'archetype': 'developing'
        })
    
    # ============================================================
    # ARCHETYPE 3: PROFICIENT STUDENTS (LMS: 56-75)
    # Characteristics: Good scores, low hint usage, good confidence,
    # stable behavior, positive trends
    # ============================================================
    for _ in range(n_proficient):
        score = np.clip(np.random.normal(72, 8), 55, 85)
        hard_acc = np.clip(np.random.normal(60, 12), 35, 80)
        hint_usage = np.clip(np.random.normal(20, 12), 0, 45)
        confidence = np.clip(np.random.normal(3.6, 0.5), 2.5, 4.5)
        answer_changes = np.clip(np.random.normal(0.4, 0.2), 0.1, 1)
        tab_switches = np.clip(np.random.normal(1.0, 0.5), 0, 2.5)
        avg_time = np.clip(np.random.normal(75, 30), 30, 180)
        review_pct = np.clip(np.random.normal(35, 15), 10, 70)
        first_action = np.clip(np.random.normal(5, 2), 1, 12)
        clicks = np.clip(np.random.normal(5, 2), 2, 10)
        perf_trend = np.clip(np.random.normal(5, 10), -15, 30)
        
        students.append({
            'score_percentage': round(score, 1),
            'hard_question_accuracy': round(hard_acc, 1),
            'hint_usage_percentage': round(hint_usage, 1),
            'avg_confidence': round(confidence, 2),
            'answer_changes_rate': round(answer_changes, 3),
            'tab_switches_rate': round(tab_switches, 2),
            'avg_time_per_question': round(avg_time, 1),
            'review_percentage': round(review_pct, 1),
            'avg_first_action_latency': round(first_action, 2),
            'clicks_per_question': round(clicks, 1),
            'performance_trend': round(perf_trend, 1),
            'archetype': 'proficient'
        })
    
    # ============================================================
    # ARCHETYPE 4: ADVANCED STUDENTS (LMS: 76-100)
    # Characteristics: Excellent scores, minimal hint usage,
    # high confidence, efficient behavior, strong positive trends
    # ============================================================
    for _ in range(n_advanced):
        score = np.clip(np.random.normal(88, 7), 75, 100)
        hard_acc = np.clip(np.random.normal(82, 10), 60, 100)
        hint_usage = np.clip(np.random.normal(8, 8), 0, 25)
        confidence = np.clip(np.random.normal(4.3, 0.4), 3.5, 5)
        answer_changes = np.clip(np.random.normal(0.2, 0.15), 0, 0.6)
        tab_switches = np.clip(np.random.normal(0.4, 0.3), 0, 1.5)
        avg_time = np.clip(np.random.normal(90, 35), 40, 200)
        review_pct = np.clip(np.random.normal(50, 20), 15, 90)
        first_action = np.clip(np.random.normal(3, 1.5), 0.5, 8)
        clicks = np.clip(np.random.normal(3, 1.5), 1, 7)
        perf_trend = np.clip(np.random.normal(12, 8), -5, 40)
        
        students.append({
            'score_percentage': round(score, 1),
            'hard_question_accuracy': round(hard_acc, 1),
            'hint_usage_percentage': round(hint_usage, 1),
            'avg_confidence': round(confidence, 2),
            'answer_changes_rate': round(answer_changes, 3),
            'tab_switches_rate': round(tab_switches, 2),
            'avg_time_per_question': round(avg_time, 1),
            'review_percentage': round(review_pct, 1),
            'avg_first_action_latency': round(first_action, 2),
            'clicks_per_question': round(clicks, 1),
            'performance_trend': round(perf_trend, 1),
            'archetype': 'advanced'
        })
    
    df = pd.DataFrame(students)
    
    # Shuffle the dataframe
    df = df.sample(frac=1, random_state=42).reset_index(drop=True)
    
    return df


def calculate_lms(df: pd.DataFrame) -> pd.DataFrame:
    """
    Calculate Learning Mastery Score (LMS) using the research-backed formula.
    
    Formula:
    LMS = 0.50×S + 0.15×Hd + 10×Ccal + 10×Ks + 10×Af − 15×Hu^1.5
    
    Where:
    - S = score_percentage (0-100)
    - Hd = hard_question_accuracy / 100 (0-1)
    - Ccal = Calibration bonus based on avg_confidence alignment
    - Ks = Knowledge stability bonus based on answer_changes_rate
    - Af = Attention factor bonus based on tab_switches_rate
    - Hu = hint_usage_percentage / 100 (0-1)
    """
    
    df = df.copy()
    
    # S - Score percentage (50% weight)
    S = df['score_percentage']
    
    # Hd - Hard question accuracy (normalized, 15% weight)
    Hd = df['hard_question_accuracy'] / 100
    
    # Ccal - Calibration bonus (confidence alignment with performance)
    # +1 if confidence matches performance, 0 otherwise
    expected_confidence = 1 + (df['score_percentage'] / 25)  # 1-5 scale based on score
    confidence_diff = np.abs(df['avg_confidence'] - expected_confidence)
    Ccal = np.where(confidence_diff <= 1, 1, 0)  # Binary: 1 if calibrated, 0 if not
    
    # Ks - Knowledge stability (low answer changes = stable = bonus)
    # 1 if answer_changes_rate <= 0.5, 0 if >= 1.5, linear in between
    Ks = np.clip(1 - (df['answer_changes_rate'] - 0.5) / 1.0, 0, 1)
    
    # Af - Attention factor (low tab switches = focused = bonus)
    # 1 if tab_switches_rate <= 1, 0 if >= 3, linear in between
    Af = np.clip(1 - (df['tab_switches_rate'] - 1) / 2.0, 0, 1)
    
    # Hu - Hint usage penalty
    Hu = df['hint_usage_percentage'] / 100
    
    # Calculate LMS
    df['learning_mastery_score'] = (
        0.50 * S +
        0.15 * (Hd * 100) +  # Scale Hd back to 0-15 range
        10 * Ccal +
        10 * Ks +
        10 * Af -
        15 * np.power(Hu, 1.5)
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
    
    Args:
        n_students: Number of students to generate
        output_path: Path to save CSV (optional)
    
    Returns:
        Complete DataFrame with all features and targets
    """
    
    print("=" * 60)
    print("X-SCAFFOLD DATASET GENERATOR")
    print("=" * 60)
    
    # Step 1: Generate base profiles
    print(f"\n[1/4] Generating {n_students} student profiles...")
    df = generate_student_profiles(n_students)
    
    # Step 2: Calculate LMS
    print("[2/4] Calculating Learning Mastery Scores...")
    df = calculate_lms(df)
    
    # Step 3: Add student IDs
    print("[3/4] Adding student identifiers...")
    df = add_student_ids(df)
    
    # Step 4: Verify class distribution
    print("[4/4] Verifying dataset quality...")
    
    print("\n" + "-" * 40)
    print("CLASS DISTRIBUTION:")
    print("-" * 40)
    distribution = df['mastery_level_name'].value_counts()
    for level, count in distribution.items():
        pct = count / len(df) * 100
        print(f"  {level:12s}: {count:4d} ({pct:5.1f}%)")
    
    print("\n" + "-" * 40)
    print("FEATURE STATISTICS:")
    print("-" * 40)
    feature_cols = [
        'score_percentage', 'hard_question_accuracy', 'hint_usage_percentage',
        'avg_confidence', 'answer_changes_rate', 'tab_switches_rate',
        'avg_time_per_question', 'review_percentage', 'avg_first_action_latency',
        'clicks_per_question', 'performance_trend'
    ]
    print(df[feature_cols].describe().round(2).to_string())
    
    print("\n" + "-" * 40)
    print("LMS STATISTICS:")
    print("-" * 40)
    print(f"  Mean LMS:   {df['learning_mastery_score'].mean():.1f}")
    print(f"  Std LMS:    {df['learning_mastery_score'].std():.1f}")
    print(f"  Min LMS:    {df['learning_mastery_score'].min():.1f}")
    print(f"  Max LMS:    {df['learning_mastery_score'].max():.1f}")
    
    # Save if path provided
    if output_path:
        # Reorder columns for final output
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
    print("=" * 60)
    
    return df


if __name__ == "__main__":
    # Generate dataset with 2000 students
    df = generate_dataset(
        n_students=2000,
        output_path="xscaffold_student_dataset.csv"
    )
