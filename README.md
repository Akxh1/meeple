<div align="center">

# X-Scaffold

### A Predict-Explain-Act Framework for Intelligent Learning Management

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Python](https://img.shields.io/badge/Python-3.9+-3776AB?style=for-the-badge&logo=python&logoColor=white)](https://python.org)
[![XGBoost](https://img.shields.io/badge/XGBoost-ML-017CEE?style=for-the-badge&logo=xgboost&logoColor=white)](https://xgboost.readthedocs.io)
[![Gemini](https://img.shields.io/badge/Gemini-API-8E75B2?style=for-the-badge&logo=google&logoColor=white)](https://ai.google.dev)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)

<br/>

![Flask](https://img.shields.io/badge/Flask-3.0-000000?style=flat-square&logo=flask&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-Database-003B57?style=flat-square&logo=sqlite&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-6.0-646CFF?style=flat-square&logo=vite&logoColor=white)
![Node.js](https://img.shields.io/badge/Node.js-20+-339933?style=flat-square&logo=nodedotjs&logoColor=white)
![SHAP](https://img.shields.io/badge/SHAP-XAI-FF6F00?style=flat-square)
![scikit-learn](https://img.shields.io/badge/scikit--learn-ML-F7931E?style=flat-square&logo=scikitlearn&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpinedotjs&logoColor=white)

<br/>

*A final-year research project exploring how Machine Learning, Explainable AI, and Large Language Models can be synthesised to close the gap between identifying struggling students and actively supporting them.*

</div>

<br/>

## Table of Contents

- [Overview](#overview)
- [Architecture](#architecture)
- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [ML Pipeline](#ml-pipeline)
- [Environment Variables](#environment-variables)
- [Default Accounts](#default-accounts)
- [API Endpoints](#api-endpoints)
- [Acknowledgements](#acknowledgements)

---

## Overview

Modern Learning Management Systems generate large volumes of student interaction data, yet most platforms stop at surface-level reporting. They can tell an instructor *that* a student is underperforming, but rarely explain *why*, and almost never take proactive steps to help.

X-Scaffold addresses this gap through a unified **Predict-Explain-Act** pipeline:

| Stage | Component | What It Does |
|:-----:|-----------|-------------|
| 🔮 **Predict** | XGBoost Classifier | Analyses 11 behavioural features captured during exams to classify each student into one of four mastery levels |
| 🔍 **Explain** | SHAP (XAI Layer) | Generates human-readable factor breakdowns so instructors understand the reasoning behind each prediction |
| 🎯 **Act** | Gemini LLM | Delivers personalised, level-appropriate hints to students and produces strategic insights for instructors |

---

## Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                    FRONTEND  (Blade + Tailwind)                   │
│   Student Dashboard  ·  Teacher Dashboard  ·  Exam Interface     │
└────────────────────────────┬─────────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────────┐
│                  LARAVEL 12 BACKEND  (PHP 8.2+)                  │
│   Controllers  ·  Services  ·  Middleware  ·  Eloquent ORM       │
├──────────────┬───────────────────────┬───────────────────────────┤
│              │                       │                           │
│  ┌───────────▼──────────┐  ┌────────▼────────┐  ┌──────────────┐│
│  │ MLPredictionService  │  │ GeminiInsights  │  │HintController ││
│  │  (Flask API client)  │  │ Service (LLM)   │  │ (LLM hints)  ││
│  └───────────┬──────────┘  └────────┬────────┘  └──────┬───────┘│
└──────────────┼──────────────────────┼──────────────────┼────────┘
               │                      │                  │
┌──────────────▼──────────┐  ┌────────▼──────────────────▼────────┐
│  FLASK ML API  (Python) │  │      GEMINI API  (Google AI)       │
│  XGBoost + SHAP engine  │  │   gemini-2.5-flash-lite model      │
└─────────────────────────┘  └────────────────────────────────────┘
```

---

## Key Features

### 🎓 Student Experience

| Feature | Description |
|---------|-------------|
| **Level Indicator Exam** | A diagnostic assessment that captures behavioural data and feeds it into the ML model, producing a personalised mastery classification |
| **Mock Exams** | Unlimited practice exams with AI-powered adaptive hints that respond to each student's predicted level |
| **4-Tier Adaptive Hints** | Hint depth and detail scale dynamically based on the student's mastery level (see table below) |
| **Student Dashboard** | Provides a module-by-module overview of performance metrics, mastery scores, and instructor notifications |
| **Fallback Hints** | When the LLM is unavailable, the system falls back to rule-based, topic-specific hints to ensure uninterrupted learning |

**Adaptive Hint Tiers:**

| Tier | Mastery Level | Scaffolding Strategy |
|:----:|:-------------:|---------------------|
| L1 | Advanced | A single Socratic question designed to push deeper thinking |
| L2 | Proficient | Two guiding bullet points nudging towards the correct approach |
| L3 | Developing | Three numbered steps: recall the concept, avoid a common mistake, take the first action |
| L4 | At Risk | Full concept explanation with a worked example and the correct answer |

### 👨‍🏫 Instructor Experience

| Feature | Description |
|---------|-------------|
| **Instructor Dashboard** | A class-wide overview displaying risk distribution charts, performance analytics, and per-student drill-down capability |
| **AI-Generated Insights** | On-demand Gemini-powered analysis that produces executive summaries, identifies performance patterns, and recommends strategic interventions |
| **Warning System** | Enables instructors to send targeted notifications directly to students identified as struggling |
| **Question Management** | Full CRUD operations with support for Excel/CSV bulk import of exam questions |
| **Data Export** | Export all pipeline data as CSV for offline analysis or ML model retraining |
| **XAI Explanations** | SHAP-based factor breakdowns that show instructors exactly which behavioural indicators contributed to a student's classification |

### ⚙️ ML and Data Pipeline

| Capability | Detail |
|------------|--------|
| **11 Behavioural Features** | Captured from live exam interactions including score, time, confidence, hint usage, tab switches, and more |
| **Learning Mastery Score** | A research-backed composite metric combining six core features into a single mastery indicator |
| **XGBoost Classifier** | Performs 4-class classification: Advanced, Proficient, Developing, and At Risk |
| **SHAP Integration** | Computes per-student feature importance values to power the Explain stage |
| **Synthetic Data Generation** | Uses Cholesky decomposition to generate statistically faithful training data that preserves inter-feature correlations |

---

## Tech Stack

<table>
<tr>
<td align="center" width="33%">

### Backend

![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)
![Python](https://img.shields.io/badge/Python_3.9+-3776AB?style=flat-square&logo=python&logoColor=white)
![Flask](https://img.shields.io/badge/Flask_3.0-000000?style=flat-square&logo=flask&logoColor=white)

</td>
<td align="center" width="33%">

### Frontend

![Blade](https://img.shields.io/badge/Blade-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=flat-square&logo=alpinedotjs&logoColor=white)
![Vite](https://img.shields.io/badge/Vite_6-646CFF?style=flat-square&logo=vite&logoColor=white)

</td>
<td align="center" width="33%">

### ML and AI

![XGBoost](https://img.shields.io/badge/XGBoost-017CEE?style=flat-square)
![SHAP](https://img.shields.io/badge/SHAP-FF6F00?style=flat-square)
![scikit-learn](https://img.shields.io/badge/scikit--learn-F7931E?style=flat-square&logo=scikitlearn&logoColor=white)
![Gemini](https://img.shields.io/badge/Gemini_API-8E75B2?style=flat-square&logo=google&logoColor=white)

</td>
</tr>
</table>

---

## Prerequisites

Before setting up the project, ensure the following are installed:

| Requirement | Version | Notes |
|-------------|---------|-------|
| ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) | 8.2+ | SQLite extension must be enabled |
| ![Composer](https://img.shields.io/badge/Composer-885630?style=flat-square&logo=composer&logoColor=white) | 2.x | PHP dependency manager |
| ![Node.js](https://img.shields.io/badge/Node.js-339933?style=flat-square&logo=nodedotjs&logoColor=white) | 20+ | Includes npm |
| ![Python](https://img.shields.io/badge/Python-3776AB?style=flat-square&logo=python&logoColor=white) | 3.9+ | With pip |
| **Laravel Herd** | Latest | Recommended local environment (or any alternative) |
| **Gemini API Key** | Free tier | Obtain from [Google AI Studio](https://aistudio.google.com/apikey) |

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Akxh1/X-Scaffold.git
cd X-Scaffold
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Install Python ML Dependencies

```bash
cd ml_model
pip install -r requirements.txt
cd ..
```

### 5. Configure the Environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and configure the following values:

```env
APP_NAME=X-Scaffold
APP_URL=http://meeple.test       # adjust to your local setup

GEMINI_INSIGHTS_API=your_gemini_api_key_here
```

### 6. Set Up the Database

```bash
php artisan migrate
php artisan db:seed --class=ModulesTableSeeder
php artisan db:seed --class=QuestionsSeeder
php artisan db:seed --class=TeacherUserSeeder
```

---

## Running the Application

### Option A: Concurrent Start (Recommended)

```bash
npm run dev:full
```

This launches both the **Vite** frontend dev server and the **Flask ML API** simultaneously.

In a separate terminal, start the Laravel backend:

```bash
php artisan serve
```

> If you are using **Laravel Herd**, the application is served automatically at your configured `.test` domain.

### Option B: Start Each Service Individually

```bash
# Terminal 1: Laravel server (skip if using Herd)
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Terminal 3: Flask ML API
cd ml_model
python api.py
```

The ML API will be available at `http://127.0.0.1:5000`.

---

## Project Structure

```
X-Scaffold/
│
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php          # Teacher and student dashboards
│   │   ├── HintController.php               # Adaptive LLM hint generation
│   │   ├── LevelIndicatorExamController.php # Diagnostic exam logic
│   │   ├── MockExamController.php           # Practice exam logic
│   │   ├── NotificationController.php       # Warning and notification system
│   │   └── Teacher/                         # Question management endpoints
│   ├── Models/
│   │   ├── Student.php
│   │   ├── StudentModulePerformance.php     # ML predictions and XAI data
│   │   ├── LevelIndicatorAttempt.php        # Exam attempt with behavioural data
│   │   └── MockExamAttempt.php
│   └── Services/
│       ├── MLPredictionService.php           # Flask API client and LMS engine
│       └── GeminiInsightsService.php         # Gemini AI insights for instructors
│
├── ml_model/
│   ├── api.py                               # Flask REST API (predict + SHAP)
│   ├── train_model.py                       # XGBoost training pipeline
│   ├── predict.py                           # Standalone prediction script
│   ├── generate_dataset.py                  # Cholesky synthetic data generation
│   ├── setup_ml.py                          # One-click ML environment setup
│   ├── xscaffold_xgboost_model.pkl          # Pre-trained model artifact
│   ├── xscaffold_scaler.pkl                 # Feature scaler artifact
│   └── requirements.txt                     # Python dependencies
│
├── resources/views/
│   ├── dashboard.blade.php                  # Teacher dashboard view
│   ├── dashboard/student.blade.php          # Student dashboard view
│   ├── level-indicator/                     # Level Indicator exam views
│   ├── mock-exam/                           # Mock exam views
│   └── welcome.blade.php                    # Landing page
│
├── database/
│   ├── migrations/                          # Schema definitions
│   └── seeders/                             # Sample data seeders
│
└── routes/web.php                           # Application route definitions
```

---

## ML Pipeline

### Learning Mastery Score (LMS)

The Learning Mastery Score is a composite metric designed to capture a more holistic view of student understanding than raw exam scores alone. It is computed as follows:

```
LMS = 0.50 × Score  +  0.15 × HardQAccuracy  +  10 × ConfidenceCalibration
    + 10 × AnswerStability  +  10 × AttentionFocus  -  15 × HintDependency^1.5
```

The formula rewards independent performance, well-calibrated confidence, and sustained attention, while applying a non-linear penalty for excessive hint dependency.

### Classification Levels

| Level | LMS Range | Hint Tier | Interpretation |
|:-----:|:---------:|:---------:|----------------|
| 🟢 Advanced | 76 to 100 | L1 (Socratic) | Demonstrates independent mastery with minimal scaffolding needed |
| 🔵 Proficient | 56 to 75 | L2 (Guiding) | Solid foundational understanding with occasional support |
| 🟠 Developing | 36 to 55 | L3 (Structured) | Requires step-by-step guidance to build confidence |
| 🔴 At Risk | 0 to 35 | L4 (Full Support) | Needs comprehensive explanation and direct answers |

### 11 Behavioural Features

These features are captured automatically from student interactions during exams:

| Feature | Description |
|---------|-------------|
| `score_percentage` | Overall exam score (0 to 100%) |
| `hard_question_accuracy` | Accuracy on questions marked as difficult |
| `hint_usage_percentage` | Proportion of questions where hints were requested |
| `avg_confidence` | Self-reported confidence rating (1 to 5 scale) |
| `answer_changes_rate` | Number of answer changes per question |
| `tab_switches_rate` | Number of tab switches per question |
| `avg_time_per_question` | Average time spent per question in seconds |
| `review_percentage` | Proportion of questions marked for review |
| `avg_first_action_latency` | Time in seconds before the first interaction on each question |
| `clicks_per_question` | Total mouse clicks per question |
| `performance_trend` | Score difference between the first and second halves of the exam |

### Training the Model

To retrain the model from scratch:

```bash
cd ml_model
python train_model.py
```

This executes the full training pipeline, outputs evaluation metrics, and saves the model artifacts (`xscaffold_xgboost_model.pkl` and `xscaffold_scaler.pkl`).

---

## Environment Variables

| Variable | Required | Description |
|----------|:--------:|-------------|
| `APP_NAME` | ✅ | Application display name (default: `X-Scaffold`) |
| `APP_URL` | ✅ | Base URL for the application |
| `DB_CONNECTION` | ✅ | Database driver (default: `sqlite`) |
| `GEMINI_INSIGHTS_API` | ✅ | Google Gemini API key used for hint generation and instructor insights |
| `IPINFO_TOKEN` | ❌ | Optional IPInfo token for geolocation features |

---

## Default Accounts

The following accounts are created when the database seeders are run:

| Role | Email | Password |
|:----:|-------|----------|
| 👨‍🏫 Teacher | `teacher@example.com` | `password123` |
| 👨‍🏫 Teacher | `teacher@test.com` | Same as student account |
| 🎓 Student | `student@test.com` | Set during seeding |

---

## API Endpoints

### Student Routes

*Requires authentication with the `student` role.*

| Method | Route | Description |
|:------:|-------|-------------|
| `GET` | `/dashboard/student` | Student dashboard |
| `GET` | `/module/{module}` | Module detail page |
| `GET` | `/module/{module}/level-indicator/start` | Begin a Level Indicator exam |
| `POST` | `/module/{module}/level-indicator/submit` | Submit a Level Indicator exam |
| `GET` | `/module/{module}/mock-exam/start` | Begin a Mock exam |
| `POST` | `/module/{module}/mock-exam/submit` | Submit a Mock exam |

### Instructor Routes

*Requires authentication with the `teacher` role.*

| Method | Route | Description |
|:------:|-------|-------------|
| `GET` | `/dashboard` | Instructor dashboard |
| `GET` | `/dashboard/student/{student}` | Student detail view with XAI breakdown |
| `POST` | `/dashboard/student/{student}/generate-insights` | Generate AI-powered performance insights |
| `POST` | `/dashboard/student/{student}/warn` | Send a warning notification to a student |
| `GET` | `/dashboard/export-data` | Export pipeline data as CSV |

### Public Routes

| Method | Route | Description |
|:------:|-------|-------------|
| `POST` | `/generate-hint` | Generate an adaptive hint via the LLM |
| `GET` | `/Test-Exam` | Access the standalone test exam interface |

---

## Acknowledgements

This project was developed as part of a final-year dissertation at the University of Westminster. It explores the intersection of educational technology, machine learning, and explainable AI with the aim of creating more responsive and supportive digital learning environments.

**Research Title:**  
*"A Predict-Explain-Act Framework for the LMS: Synthesising Machine Learning Prediction, XAI Diagnostics, and LLM-Driven Adaptive Intervention"*

---

<div align="center">

*Built with*
![Laravel](https://img.shields.io/badge/-Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Python](https://img.shields.io/badge/-Python-3776AB?style=flat-square&logo=python&logoColor=white)
![XGBoost](https://img.shields.io/badge/-XGBoost-017CEE?style=flat-square)
![Gemini](https://img.shields.io/badge/-Gemini-8E75B2?style=flat-square&logo=google&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/-Tailwind-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)

</div>
