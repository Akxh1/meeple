import pandas as pd
import numpy as np

np.random.seed(42)
num_samples = 10000

# 🎯 Broader range of student abilities
student_ability = np.clip(np.random.normal(loc=0.5, scale=0.45, size=num_samples), 0, 1)

# Helper to generate features with noise, scaled to [min_val, max_val]
def generate_feature(base, min_val, max_val, noise_std):
    raw = base + np.random.normal(0, noise_std, size=num_samples)
    clipped = np.clip(raw, 0, 1)
    return np.round(clipped * (max_val - min_val) + min_val, 2)

# Generate feature columns
data = {
    'exam_1': generate_feature(student_ability, 40, 100, 0.12),
    'exam_2': generate_feature(student_ability, 30, 95, 0.12),
    'exam_3': generate_feature(student_ability, 50, 100, 0.12),
    'attendance_rate': generate_feature(student_ability, 60, 100, 0.15),
    'engagement_score': generate_feature(student_ability, 2, 10, 0.18),
    'group_work_score': generate_feature(student_ability, 0, 100, 0.20),
    'revision_hours': generate_feature(student_ability, 0, 20, 0.18),
    'quiz_score': np.round(generate_feature(student_ability, 0, 100, 0.15)),
}

df = pd.DataFrame(data)

# Normalize for weighted scoring
df['exam_1_norm'] = df['exam_1'] / 100
df['exam_2_norm'] = df['exam_2'] / 100
df['exam_3_norm'] = df['exam_3'] / 100
df['attendance_norm'] = df['attendance_rate'] / 100
df['engagement_norm'] = df['engagement_score'] / 10
df['group_work_norm'] = df['group_work_score'] / 100
df['revision_norm'] = df['revision_hours'] / 20
df['quiz_norm'] = df['quiz_score'] / 100

# Weight configuration
weights = {
    'exam_1_norm': 0.17,
    'exam_2_norm': 0.17,
    'exam_3_norm': 0.17,
    'attendance_norm': 0.10,
    'engagement_norm': 0.09,
    'group_work_norm': 0.05,
    'revision_norm': 0.10,
    'quiz_norm': 0.15,
}

# Normalize weights (precautionary)
weight_sum = sum(weights.values())
normalized_weights = {k: v / weight_sum for k, v in weights.items()}

# Compute final pass score
def compute_final_score(row):
    base_score = sum(row[f] * normalized_weights[f] for f in normalized_weights)
    noisy_score = base_score + np.random.normal(0, 0.05)  # more noise for realism
    final_score = round(noisy_score * 100, 2)
    return np.clip(final_score, 0.1, 99.9)  # avoid hard 0 or 100

df['final_pass_score'] = df.apply(compute_final_score, axis=1)

# Drop normalized columns before export
df.drop(columns=[col for col in df.columns if '_norm' in col], inplace=True)

# Save
df.to_csv("V3_student_dataset_balanced.csv", index=False)
print(f"✅ Saved as 'V3_student_dataset_balanced.csv' with {num_samples} diverse samples.")
