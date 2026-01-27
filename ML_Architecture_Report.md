# Machine Learning Pipeline and Model Architecture Report
**Project:** X-Scaffold (Intelligent Scaffolding System)
**Date:** January 2026

## 1. Executive Summary
This report outlines the technical architecture of the Machine Learning (ML) component within the X-Scaffold system. The system employs a supervised classification pipeline designed to predict student mastery levels in real-time and provide explainable insights (XAI) to power adaptive scaffolding interventions. The architecture prioritizes robustness, interpretability, and educational validity.

## 2. Model Architecture

### 2.1 Algorithm Selection
The core predictive model is a **Bagging Classifier (Bootstrap Aggregating)**.

*   **Base Estimator:** Decision Tree Classifier
*   **Ensemble Strategy:** 50 independent estimators trained on bootstrap samples.
*   **Key Design Rationale:**
    *   **Variance Reduction:** Educational data is inherently noisy; bagging significantly reduces the high variance associated with single decision trees.
    *   **Robustness:** The ensemble method mitigates the risk of overfitting to specific student outliers.
    *   **Non-Linearity:** Capable of capturing complex, non-linear relationships between behavioral features and learning mastery.

### 2.2 Hyperparameters
The model is configured with the following hyperparameters optimized for educational datasets:

| Parameter | Value | Justification |
| :--- | :--- | :--- |
| **n_estimators** | 50 | Sufficient ensemble size to stabilize predictions without excessive latency. |
| **max_samples** | 0.8 (80%) | Ensures diversity among base estimators. |
| **max_depth** | 8 | Limits tree complexity to prevent overfitting to noise. |
| **min_samples_split** | 10 | Enforces generalizing splits rather than memorizing small groups. |

## 3. Data Pipeline & Feature Engineering

### 3.1 Input Features
The model utilizes **11 quantitative features** derived from real-time student interaction logs. These features are categorized into three variance groups:

#### A. Performance Indicators
*   `score_percentage`: Overall raw score on the module.
*   `hard_question_accuracy`: Accuracy specifically on questions tagged with high difficulty.

#### B. Behavioral Metrics
*   `hint_usage_percentage`: Frequency of hint requests (negative correlation with mastery).
*   `answer_changes_rate`: Measure of indecision or lack of confidence.
*   `tab_switches_rate`: Proxy for off-task behavior or external resource seeking.
*   `clicks_per_question`: Engagement intensity and efficiency.
*   `avg_first_action_latency`: cognitive processing time before initial action.

#### C. Metacognitive & Temporal Metrics
*   `avg_confidence`: Self-reported confidence levels (Likert scale 1-5).
*   `avg_time_per_question`: Processing speed.
*   `review_percentage`: Usage of the "Mark for Review" mechanism.
*   `performance_trend`: Temporal score trajectory (first half vs. second half).

### 3.2 Target Variable
The target variable is a discrete **Mastery Level** classification:
0.  **At Risk** (0-35 LMS) – Needs immediate intervention.
1.  **Developing** (36-55 LMS) – Needs guided practice.
2.  **Proficient** (56-75 LMS) – Standard progression.
3.  **Advanced** (76-100 LMS) – Needs enrichment/acceleration.

## 4. Explainable AI (XAI) Integration

To ensure the "Black Box" problem does not hinder educational adoption, the system integrates **SHAP (SHapley Additive exPlanations)**.

*   **Methodology:** Kernel SHAP (Model-agnostic).
*   **Functionality:**
    1.  Computes the marginal contribution of *each feature* to the final prediction.
    2.  Generates a **Natural Language Summary** (e.g., *"Positive factors: score_percentage (+0.23). Areas for improvement: hint_usage_percentage (-0.15)."*).
    3.  This summary is passed to the LLM Scaffolding Engine to generate personalized, context-aware hints.

## 5. Deployment Architecture

### 5.1 API Microservice
The ML core is encapsulated as a standalone **Flask API** microservice to decouple it from the main application logic.

*   **Endpoints:**
    *   `POST /predict`: Real-time single-student inference. Returns prediction, confidence score, and SHAP explanation.
    *   `POST /batch_predict`: Bulk inference for admin analytics.
    *   `GET /health`: System status and model metadata.

### 5.2 Integration Flow
1.  **Data Collection:** Laravel backend aggregates student logs during the exam.
2.  **Request:** On exam completion (or checkpoint), Laravel sends the feature vector to the Flask API.
3.  **Inference:** The Bagging Model predicts the mastery level.
4.  **Explanation:** SHAP calculates feature attributions.
5.  **Action:** The prediction and XAI summary are returned to Laravel to trigger the appropriate scaffolding workflow.
