# Mock Exam App - Project Goals & Research Context

## 🎯 Purpose

This Mock Exam Application serves as a **data collection instrument** for a research project focused on building and validating a Machine Learning model for **Student Performance Prediction**. The app simulates an authentic exam environment to capture realistic behavioral and performance data from students.

---

## 📚 Research Project Overview

### Project Title
> **"A 'Predict-Explain-Act' Framework for the LMS: Synthesizing Machine Learning Prediction, XAI Diagnostics, and LLM-Driven Adaptive Intervention."**

### Executive Summary

This research project addresses the **critical fragmentation in current educational technology**. While modern Learning Management Systems (LMS) collect vast amounts of data, they fail to close the loop between:
- **Identifying** a struggling student
- **Helping** them effectively

This project introduces a **unified framework** that synthesizes:

| Component | Role |
|-----------|------|
| **Machine Learning (ML)** | Predicts student risk and performance |
| **Explainable AI (XAI)** | Explains predictions to teachers in human-understandable terms |
| **Large Language Models (LLM)** | Automatically acts on insights to support students |

The goal is to create a system that not only **predicts** student risk but **explains** it to educators and **automatically acts** on it to support students.

---

## 🔬 Role of This App in the Research

### Primary Objective
Gather authentic exam interaction data to:
1. **Perform Exploratory Data Analysis (EDA)** to identify key features
2. **Build and validate an ML model** for student performance prediction
3. **Generate synthetic data** based on real patterns for model training and testing

### Data Collection Goals

The app captures the following categories of behavioral and performance data:

#### 📊 Performance Metrics
- Correct/incorrect answers per question
- Overall score and accuracy
- Performance breakdown by topic/module

#### ⏱️ Temporal Patterns
- Time spent on each question
- Total exam duration
- Time distribution across difficulty levels
- Pacing patterns (rushing vs. deliberating)

#### 🔄 Behavioral Signals
- Navigation patterns (forward/backward movement)
- Questions marked for review
- Answer changes and revision patterns
- Hesitation indicators

#### 🧠 Engagement Indicators
- Question skip patterns
- Hint usage (if applicable)
- Confidence indicators through review marking

---

## 🏗️ The Predict-Explain-Act Framework

```
┌─────────────────────────────────────────────────────────────────┐
│                         LMS DATA LAYER                          │
│         (Mock Exam App collects authentic exam data)            │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      1. PREDICT (ML Model)                      │
│  • Identifies at-risk students via Learning Mastery Score       │
│  • Predicts performance outcomes using 11 behavioral features   │
│  • Flags early warning signals based on 4-level classification  │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      2. EXPLAIN (XAI Layer)                     │
│  • Translates predictions into understandable insights         │
│  • Shows teachers WHY a student is struggling                   │
│  • Highlights specific areas of concern                         │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      3. ACT (LLM Intervention)                  │
│  • Generates personalized learning recommendations              │
│  • Provides adaptive scaffolding to students                    │
│  • Creates targeted intervention strategies                     │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🎯 Implemented Features (11 ML Features)

### Tier 1: Core 6 Features (Used for LMS Calculation)

These features have **strong research citations** and create the target variable:

| Feature | Description | LMS Role |
|---------|-------------|----------|
| `score_percentage` | Overall exam score (0-100%) | Base Performance (50%) |
| `hard_question_accuracy` | Accuracy on difficult questions | Deep Understanding (15%) |
| `hint_usage_percentage` | % of questions where hints used | Independence Penalty (-15) |
| `avg_confidence` | Self-reported confidence (1-5) | Calibration Bonus (+10) |
| `answer_changes_rate` | Answer changes per question | Stability Bonus (+10) |
| `tab_switches_rate` | Tab switches per question | Attention Bonus (+10) |

### Tier 2: Additional 5 Features (ML Predictors Only)

| Feature | Description | Purpose |
|---------|-------------|---------|
| `avg_time_per_question` | Average seconds per question | Processing speed |
| `review_percentage` | % of questions marked for review | Uncertainty indicator |
| `avg_first_action_latency` | Seconds before first click | Cognitive load |
| `clicks_per_question` | Total clicks per question | Engagement intensity |
| `performance_trend` | Score change 1st→2nd half | Fatigue/improvement |

---

## 📐 Learning Mastery Score (LMS)

### Formula
```
LMS = 0.50×S + 0.15×Hd + 10×Ccal + 10×Ks + 10×Af − 15×Hu^1.5
```

### Why LMS ≠ Raw Score
| Scenario | Raw Score | LMS | Interpretation |
|----------|-----------|-----|----------------|
| High hints, low confidence | 80% | ~55 | Scaffolded performance |
| No hints, high confidence | 70% | ~78 | Independent mastery |
| Inconsistent answers | 75% | ~60 | Unstable knowledge |

### Mastery Level Classification
| Level | LMS Range | Color Code |
|-------|-----------|------------|
| At-Risk | 0-35 | 🔴 Red |
| Developing | 36-55 | 🟠 Orange |
| Proficient | 56-75 | 🔵 Blue |
| Advanced | 76-100 | 🟢 Green |

> Full algorithm details: See `/lms-explained` page in the app or `Performance_Prediction_Validation.md`

---

## 🔄 Synthetic Data Generation

After EDA, the collected data patterns will be used to:
1. **Understand feature distributions** and correlations
2. **Generate synthetic datasets** using Cholesky decomposition
3. **Train ML models** on diverse, representative data
4. **Validate model performance** on held-out real data

---

## ✅ Implementation Status

| Component | Status | Notes |
|-----------|--------|-------|
| **Data Collection** | ✅ Complete | 11 features collected from exam interactions |
| **Admin Panel** | ✅ Complete | View students, export CSV, toggle ML features |
| **LMS Calculation** | ✅ Complete | Research-backed 6-component formula |
| **LMS Display** | ✅ Complete | Student table + profile with color-coded levels |
| **Algorithm Explanation** | ✅ Complete | `/lms-explained` page with full breakdown |
| **EDA Script** | ✅ Complete | `student_eda_synthetic_data.py` |
| **Research Validation** | ✅ Complete | `Performance_Prediction_Validation.md` |
| **ML Model** | ✅ Complete | Bagging Classifier (50 estimators, R²≈0.90) in `ml_model/` |
| **XAI Integration** | ✅ Complete | SHAP integration in Flask API + Laravel service |
| **LLM Intervention** | ✅ Complete | Adaptive hints via Gemini API with SHAP context |


---

## 📄 Project Documentation

| File | Purpose |
|------|---------|
| `PROJECT_GOALS.md` | This file - project overview and progress |
| `Performance_Prediction_Validation.md` | LMS formula justification with 30 research citations |
| `student_eda_synthetic_data.py` | Colab script for EDA and synthetic data generation |
| `citations_StudentPerformance.csv` | Research paper citations (personal use - gitignored) |

---

## 📈 Expected Outcomes

| Phase | Deliverable | Status |
|-------|-------------|--------|
| Data Collection | Rich exam interaction dataset | ✅ |
| EDA | Feature importance ranking, correlation analysis | ✅ |
| Model Development | Performance prediction ML model | 🔲 |
| XAI Integration | Interpretable explanations for predictions | 🔲 |
| LLM Integration | Automated intervention recommendations | 🔲 |

---

## 🔗 Connection to LMS Integration

This Mock Exam App serves as a **proof-of-concept** data source. The insights and models developed here will be:
- Adaptable to any LMS environment
- Scalable to larger student populations
- Generalizable across different subject domains

---

*Last Updated: January 2026*  
*Research Project: Predict-Explain-Act Framework for Intelligent LMS*

