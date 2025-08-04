import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error, r2_score
import joblib

# Load your dataset
df = pd.read_csv("V3_student_dataset_balanced.csv")

# Features and Target
X = df.drop(columns=["final_pass_score"])
y = df["final_pass_score"]

# Train/test split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train a Random Forest model
model = RandomForestRegressor(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# Evaluate
predictions = model.predict(X_test)
print("✅ Model trained.")
print("MAE:", mean_absolute_error(y_test, predictions))
print("R²:", r2_score(y_test, predictions))

# Save model
joblib.dump(model, "risk_predictor_model.pkl")
print("✅ Model saved as risk_predictor_model.pkl")

# ✅ Model trained. MAE: 4.1320695 R²: 0.9071207118482925