# X-Scaffold ML Model

Machine Learning pipeline for student mastery level prediction using XGBoost Classifier with SHAP explanations.

## 🚀 Quick Start

```bash
# 1. Install dependencies
pip install -r requirements.txt

# 2. Generate dataset, train model, and verify
python setup_ml.py

# 3. Start the API server
python api.py
```

## 📁 Files

| File | Description |
|------|-------------|
| `setup_ml.py` | One-command setup script (recommended) |
| `generate_dataset.py` | Synthetic dataset generator (2000 students) |
| `train_model.py` | XGBoost Classifier training with feature importance |
| `predict.py` | Command-line prediction tool |
| `api.py` | Flask API server for Laravel integration |
| `requirements.txt` | Python dependencies |
| `ML_DOCUMENTATION.md` | Comprehensive documentation |

## 📊 Model Details

- **Algorithm**: XGBoost Classifier (Gradient Boosting)
- **Features**: 11 behavioral features from exam interactions
- **Target**: 4-class mastery level (at_risk, developing, proficient, advanced)
- **Expected Accuracy**: 85-92%

## 🔌 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/health` | Health check |
| POST | `/predict` | Single prediction with SHAP |
| POST | `/batch_predict` | Batch predictions |

## 📖 Documentation

See [ML_DOCUMENTATION.md](ML_DOCUMENTATION.md) for:
- Dataset specification
- Feature descriptions
- Training process
- API reference
- Laravel integration guide
