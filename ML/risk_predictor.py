import pandas as pd
import sys
import json

print("Reading file from:", sys.argv[1], file=sys.stderr)

# Get the file path from Laravel
file_path = sys.argv[1]

# Read the Excel file
df = pd.read_excel(file_path)

# Basic rule-based prediction (can be replaced with ML later)
score = (
    0.4 * df['exam_1'] +
    0.4 * df['exam_2'] +
    0.1 * df['attendance_rate'] * 100 +
    0.1 * df['engagement_score'] * 100
).clip(0, 100)

# Format predictions as { student_id: score }
predictions = dict(zip(df['student_id'], score.round(2).tolist()))

# Print JSON so Laravel can read it
print(json.dumps(predictions))
