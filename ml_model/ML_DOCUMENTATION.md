# X-Scaffold ML Pipeline Documentation

## 📋 Overview

This document provides comprehensive documentation for the X-Scaffold Machine Learning pipeline, including dataset generation, model training, and API deployment.

---

## 🎯 Model Architecture

### Algorithm: XGBoost Classifier

**Why XGBoost?**
Based on comprehensive model comparison testing (Feb 2026), XGBoost (Extreme Gradient Boosting) was selected as the production model because:

1. **Superior Accuracy**: Ranked #1 across all metrics (Accuracy, F1, AUC)
2. **Critical Class Performance**: Achieved 95% F1 on at-risk students (vs 78% for Bagging)
3. **Robust Regularization**: Built-in L1/L2 regularization prevents overfitting
4. **SHAP Compatibility**: Native support for TreeExplainer provides fast, exact explanations

**Configuration**:
- Algorithm: Gradient Boosting (Trees)
- Number of Estimators: 50
- Max Depth: 8
- Learning Rate: 0.1
- Eval Metric: mlogloss
- Objective: multi:softprob

---

## 📊 Dataset Specification

### Features (11 Total)

#### Tier 1: Core Features (Used in LMS Calculation)

| Feature | Type | Range | Description | Research Justification |
|---------|------|-------|-------------|------------------------|
| `score_percentage` | Float | 0-100 | Overall exam score | Primary performance indicator (Pardos & Baker, 2014) |
| `hard_question_accuracy` | Float | 0-100 | Accuracy on difficult questions | Deep understanding proxy (Chi et al., 2018) |
| `hint_usage_percentage` | Float | 0-100 | % of questions with hints used | Independence indicator (Aleven et al., 2016) |
| `avg_confidence` | Float | 1-5 | Self-reported confidence | Metacognitive awareness (Tobias & Everson, 2002) |
| `answer_changes_rate` | Float | 0-2 | Answer changes per question | Knowledge stability (Shute, 2008) |
| `tab_switches_rate` | Float | 0-5 | Tab switches per question | Focus indicator (Baker et al., 2004) |

#### Tier 2: Additional ML Predictors

| Feature | Type | Range | Description | Research Justification |
|---------|------|-------|-------------|------------------------|
| `avg_time_per_question` | Float | 5-300s | Average seconds per question | Processing speed (D'Mello & Graesser, 2012) |
| `review_percentage` | Float | 0-100 | % of questions marked for review | Metacognition (Pintrich, 2002) |
| `avg_first_action_latency` | Float | 0.5-30s | Seconds before first click | Cognitive load (Sweller, 2011) |
| `clicks_per_question` | Float | 1-20 | Total clicks per question | Engagement intensity (Cocea & Weibelzahl, 2009) |
| `performance_trend` | Float | -50 to +50 | Score change 1st→2nd half | Fatigue/improvement (Roscoe et al., 2014) |

### Target Variable

| Variable | Type | Values | Description |
|----------|------|--------|-------------|
| `mastery_level` | Integer | 0, 1, 2, 3 | Classification target |
| `mastery_level_name` | String | at_risk, developing, proficient, advanced | Human-readable label |
| `learning_mastery_score` | Float | 0-100 | LMS score (for reference) |

### Mastery Level Classification

| Level | LMS Range | Description | Intervention Priority |
|-------|-----------|-------------|----------------------|
| 0 - At Risk | 0-35 | Significant struggle, needs immediate support | 🔴 HIGH |
| 1 - Developing | 36-55 | Partial understanding, moderate support needed | 🟠 MEDIUM |
| 2 - Proficient | 56-75 | Good understanding, occasional guidance | 🔵 LOW |
| 3 - Advanced | 76-100 | Excellent mastery, minimal intervention | 🟢 NONE |

---

## 🔄 Data Generation Process

### Student Archetypes

The synthetic dataset is generated using 4 student archetypes based on educational research:

#### Archetype 1: At-Risk (15% of dataset)
- Low scores (μ=35, σ=12)
- High hint usage (μ=65%, σ=15%)
- Low confidence (μ=2.0, σ=0.5)
- Erratic behavior (high answer changes, tab switches)
- Negative performance trend

#### Archetype 2: Developing (35% of dataset)
- Moderate scores (μ=55, σ=10)
- Moderate hint usage (μ=40%, σ=15%)
- Average confidence (μ=2.8, σ=0.5)
- Some inconsistency in behavior
- Slight negative trend

#### Archetype 3: Proficient (35% of dataset)
- Good scores (μ=72, σ=8)
- Low hint usage (μ=20%, σ=12%)
- Good confidence (μ=3.6, σ=0.5)
- Stable behavior
- Slight positive trend

#### Archetype 4: Advanced (15% of dataset)
- Excellent scores (μ=88, σ=7)
- Minimal hint usage (μ=8%, σ=8%)
- High confidence (μ=4.3, σ=0.4)
- Efficient behavior
- Strong positive trend

### LMS Calculation Formula

The LMS formula was refined through a three-stage process: initial literature-based weights, data-driven correlation analysis on N=56 real students, and a final hybrid formula.

#### Stage 1: Literature-Based Formula (Initial)

```
LMS = 0.50×S + 0.15×Hd + 10×Ccal + 10×Ks + 10×Af − 15×Hu^1.5
```

#### Stage 2: Data-Driven Analysis (N=56 Real Students)

Four unsupervised methods (PCA, Entropy Weighting, Factor Analysis, CRITIC) were applied to the 6 core features of 56 real student records to derive empirical weights without a ground-truth target variable. The composite data-driven weights were: Hd (26.6%), S (25.6%), Confidence (16.5%), Tab Switches (15.2%), Hint Usage (11.0%), Answer Changes (5.1%).

#### Stage 3: Refined Hybrid Formula (Production)

```
LMS = 0.30×S + 0.25×(Hd×100) + 15×Ccal + 15×Af − 10×Hu − 5×Ac
```

Where:
- **S** = score_percentage (0-100) — reduced from 50% to 30% per data analysis
- **Hd** = hard_question_accuracy / 100 — increased from 15% to 25% (data: #1 differentiator)
- **Ccal** = Calibration bonus (1 if confidence matches performance, 0 otherwise) — increased to 15
- **Af** = Attention factor (1 if low tab switches, 0 if high) — increased to 15
- **Hu** = hint_usage_percentage / 100 — penalty reduced from 15 to 10
- **Ac** = answer_changes_rate penalty — reduced from 10 to 5 (low variance in data)

---

## 🧪 Model Training Process

### Pipeline Steps

```
1. Load Dataset (xscaffold_student_dataset.csv)
       ↓
2. Train/Test Split (80/20, stratified)
       ↓
3. Feature Scaling (StandardScaler)
       ↓
4. Train XGBoost Classifier (50 estimators)
       ↓
5. Evaluate (Accuracy, F1, Confusion Matrix)
       ↓
6. Cross-Validation (5-fold stratified)
       ↓
7. Feature Importance Analysis (Gini + Permutation)
       ↓
8. Save Model Artifacts
```

### Expected Performance Metrics

Based on the synthetic dataset design, expected metrics are:

| Metric | Expected Value |
|--------|----------------|
| Test Accuracy | 85-92% |
| F1 Macro | 0.83-0.90 |
| Cross-Val Mean | 0.84-0.91 |
| Overfitting Gap | < 5% |

### Output Artifacts

| File | Description |
|------|-------------|
| `xscaffold_xgboost_model.pkl` | Trained XGBoost Classifier |
| `xscaffold_scaler.pkl` | StandardScaler for feature normalization |
| `feature_names.json` | Feature order and class names |
| `model_metrics.json` | Performance metrics |
| `feature_importance.json` | Feature importance rankings |
| `model_config.json` | Hyperparameters and config |

---

## 🔮 SHAP Integration (XAI)

### What is SHAP?

SHAP (SHapley Additive exPlanations) provides:
- **Local explanations**: Why THIS student got THIS prediction
- **Global explanations**: Which features matter most overall
- **Additive contributions**: Each feature's exact contribution to the prediction

### SHAP Output Format

For each prediction, the API returns:

```json
{
  "explanation": {
    "contributions": {
      "score_percentage": {"value": 0.234, "direction": "positive"},
      "hint_usage_percentage": {"value": -0.156, "direction": "negative"},
      ...
    },
    "top_positive": ["score_percentage", "avg_confidence", "hard_question_accuracy"],
    "top_negative": ["hint_usage_percentage", "tab_switches_rate"],
    "natural_language": "Positive factors: score_percentage (+0.234), avg_confidence (+0.089). Areas for improvement: hint_usage_percentage (-0.156)."
  }
}
```

### Natural Language Generation

The `natural_language` field is designed to be passed directly to the LLM for hint generation:

```php
// HintController.php
$xai_analysis = $prediction['explanation']['natural_language'];
// "Positive factors: score_percentage (+0.234)..."
```

---

## 🚀 API Reference

### Endpoints

#### `GET /health`
Health check endpoint.

**Response:**
```json
{
  "status": "healthy",
  "model": "xscaffold_xgboost_model",
  "features_count": 11,
  "classes": ["at_risk", "developing", "proficient", "advanced"],
  "shap_available": true
}
```

#### `POST /predict`
Single student prediction with SHAP explanation.

**Request:**
```json
{
  "score_percentage": 72.5,
  "hard_question_accuracy": 65.0,
  "hint_usage_percentage": 18.0,
  "avg_confidence": 3.8,
  "answer_changes_rate": 0.35,
  "tab_switches_rate": 0.9,
  "avg_time_per_question": 82.0,
  "review_percentage": 38.0,
  "avg_first_action_latency": 4.8,
  "clicks_per_question": 4.5,
  "performance_trend": 7.5
}
```

**Response:**
```json
{
  "prediction": {
    "mastery_level": 2,
    "mastery_level_name": "proficient",
    "confidence": 0.8734,
    "probabilities": {
      "at_risk": 0.0234,
      "developing": 0.0832,
      "proficient": 0.8734,
      "advanced": 0.0200
    }
  },
  "explanation": {
    "contributions": {...},
    "top_positive": ["score_percentage", "avg_confidence"],
    "top_negative": ["hint_usage_percentage"],
    "natural_language": "Positive factors: score_percentage (+0.234)..."
  }
}
```

#### `POST /batch_predict`
Batch predictions for multiple students.

**Request:**
```json
{
  "students": [
    {"score_percentage": 72.5, ...},
    {"score_percentage": 45.0, ...}
  ]
}
```

---

## 📦 Setup Instructions

### 1. Install Python Dependencies

```bash
cd ml_model
pip install -r requirements.txt
```

### 2. Generate Dataset

```bash
python generate_dataset.py
```

This creates `xscaffold_student_dataset.csv` with 2000 student records.

### 3. Train Model

```bash
python train_model.py
```

This trains the XGBoost Classifier and saves all artifacts.

### 4. Start API Server

```bash
python api.py
```

The API will be available at `http://localhost:5000`.

### 5. Test Prediction

```bash
python predict.py
```

Or with custom features:
```bash
python predict.py --features '{"score_percentage": 72.5, ...}'
```

---

## 🔗 Laravel Integration

### Calling the ML API from Laravel

```php
// In StudentModulePerformance controller or HintController

use Illuminate\Support\Facades\Http;

$features = [
    'score_percentage' => $performance->score_percentage,
    'hard_question_accuracy' => $performance->hard_question_accuracy,
    // ... all 11 features
];

$response = Http::post('http://localhost:5000/predict', $features);

if ($response->successful()) {
    $prediction = $response->json();
    
    // Store prediction
    $performance->predicted_level = $prediction['prediction']['mastery_level'];
    $performance->shap_values = json_encode($prediction['explanation']['contributions']);
    $performance->save();
    
    // Pass XAI to LLM for hint generation
    $xai_analysis = $prediction['explanation']['natural_language'];
}
```

---

## 📚 Research Citations

1. Pardos, Z. A., & Baker, R. S. (2014). Affective states and state tests. *Journal of Learning Analytics, 1*(1), 107-128.
2. Chi, M., et al. (2018). Learning from worked examples: A deep comprehension approach. *Educational Psychology Review, 30*(3), 839-866.
3. Aleven, V., et al. (2016). Help seeking and help design in interactive learning environments. *Review of Educational Research, 86*(1), 227-268.
4. Baker, R. S., et al. (2004). Off-task behavior in the cognitive tutor classroom. *CHI 2004*, 383-390.
5. D'Mello, S., & Graesser, A. (2012). Dynamics of affective states during complex learning. *Learning and Instruction*.
6. Jolliffe, I. T. (2002). *Principal Component Analysis* (2nd ed.). Springer.
7. Shannon, C. E. (1948). A Mathematical Theory of Communication. *Bell System Technical Journal, 27*(3), 379-423.
8. Diakoulaki, D., Mavrotas, G., & Papayannakis, L. (1995). Determining objective weights in multiple criteria problems: The CRITIC method. *Computers & Operations Research, 22*(7), 763-770.
9. Hair, J. F., et al. (2019). *Multivariate Data Analysis* (8th ed.). Cengage Learning.
10. Tobias, S., & Everson, H. T. (2002). Knowing what you know and what you don't. *College Board Research Report No. 2002-3*.

---

## 📝 Changelog

### v2.1.0 (February 2026)
- **LMS Formula Refinement**: Hybrid formula derived from literature + data-driven analysis
- Applied PCA, Entropy, Factor Analysis, CRITIC to N=56 real student records
- Score percentage weight reduced (50%→30%), hard question accuracy increased (15%→25%)
- Added `lms_weight_derivation.py` testing script

### v2.0.0 (February 2026)
- **Major Update**: Switched champion model from Bagging to XGBoost
- Improved at-risk student detection (78% -> 95% F1)
- Updated API to use SHAP TreeExplainer (faster/exact)
- Added testing documentation (`ML_TESTING.html`)

### v1.0.0 (January 2026)
- Initial release
- Bagging Classifier with 50 estimators
- 11-feature dataset based on project requirements
- SHAP integration for XAI
- Flask API for serving predictions
- Comprehensive documentation

---

*X-Scaffold Research Project*  
*Final Year Project • Computing Mathematics Education*
