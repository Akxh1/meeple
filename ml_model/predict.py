import sys
import pandas as pd
import joblib
import json
import os

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Missing Excel file path"}))
        return

    file_path = sys.argv[1]

    try:
        # Load the Excel file
        df = pd.read_excel(file_path)

        # Expecting a column 'student_id' along with input features
        if 'student_id' not in df.columns:
            print(json.dumps({"error": "Missing 'student_id' column in Excel"}))
            return

        # Get the absolute path to the model file
        model_path = os.path.abspath(os.path.join(os.path.dirname(__file__), 'risk_predictor_model.pkl'))
        model = joblib.load(model_path)

        # Define the feature columns used during training
        feature_cols = [
        'exam_1',
        'exam_2',
        'exam_3',
        'attendance_rate',
        'engagement_score',
        'group_work_score',
        'revision_hours',
        'quiz_score'
    ]

        # Ensure the required columns are present
        for col in feature_cols:
            if col not in df.columns:
                print(json.dumps({"error": f"Missing required column: {col}"}))
                return

        # Make predictions
        predictions = model.predict(df[feature_cols])

        # Format output as a dictionary { student_id: prediction_score }
        result = {
            str(row['student_id']): round(float(pred), 2)
            for row, pred in zip(df.to_dict(orient='records'), predictions)
        }

        # Return JSON output
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == '__main__':
    main()
