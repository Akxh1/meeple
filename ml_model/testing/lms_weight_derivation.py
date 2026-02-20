"""
==============================================================================
LMS Weight Derivation from Real Data (Google Colab Script)
==============================================================================
Problem: The current LMS formula uses literature-backed weights, but we need
         stronger empirical justification from our ACTUAL dataset.

Challenge: We do NOT have a ground-truth LMS score to regress against.

Solution: Use UNSUPERVISED methods to derive feature importance/weights
         directly from the correlation structure and variance of the data.

Methods Used:
  1. PCA (Principal Component Analysis) - weights from 1st principal component
  2. Entropy-based Weighting - features with more discriminating power get higher weight
  3. Factor Analysis - latent factor loadings
  4. Correlation-based Weighting - average inter-feature correlation strength

Final Output: A composite weight (average of all methods) that is data-driven
              and can be cited as empirically derived.

Usage: Upload student_research_data.csv to Colab and run all cells.
==============================================================================
"""

# =============================================================================
# CELL 1: Setup and Imports
# =============================================================================
# Run: pip install if needed (Colab has most of these pre-installed)
# !pip install pandas numpy matplotlib seaborn scikit-learn scipy

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.preprocessing import MinMaxScaler, StandardScaler
from sklearn.decomposition import PCA, FactorAnalysis
from scipy import stats
import warnings
warnings.filterwarnings('ignore')

# Set plotting style
plt.style.use('seaborn-v0_8-whitegrid')
sns.set_palette("husl")
plt.rcParams['figure.figsize'] = (12, 8)
plt.rcParams['font.size'] = 12

print("✅ All imports loaded successfully")


# =============================================================================
# CELL 2: Load and Prepare Data
# =============================================================================

# --- OPTION A: Upload file in Colab (DEFAULT) ---
from google.colab import files
uploaded = files.upload()  # Upload student_research_data.csv
filename = list(uploaded.keys())[0]
df = pd.read_csv(filename)

# --- OPTION B: Direct path (if already mounted via Drive) ---
# df = pd.read_csv('/content/drive/MyDrive/student_research_data.csv')

# --- OPTION C: Local path (for testing outside Colab) ---
# df = pd.read_csv('student_research_data.csv')

print(f"📊 Loaded {len(df)} real student records")
print(f"📋 Columns: {list(df.columns)}")
print(f"\n{df.describe().round(2)}")


# =============================================================================
# CELL 3: Define Core Features for LMS
# =============================================================================

# The 6 core features used in the LMS formula
CORE_FEATURES = [
    'score_percentage',        # S  - Overall exam performance
    'hard_question_accuracy',  # Hd - Deep understanding indicator
    'hint_usage_percentage',   # Hu - Scaffolding dependency (NEGATIVE)
    'avg_confidence',          # Ccal - Metacognitive calibration
    'answer_changes_rate',     # Ks - Knowledge stability (NEGATIVE)
    'tab_switches_rate'        # Af - Attention/focus factor (NEGATIVE)
]

# Direction: +1 = higher is better, -1 = higher is worse
FEATURE_DIRECTION = {
    'score_percentage': +1,
    'hard_question_accuracy': +1,
    'hint_usage_percentage': -1,
    'avg_confidence': +1,
    'answer_changes_rate': -1,
    'tab_switches_rate': -1
}

# Extract core features
core_df = df[CORE_FEATURES].copy()
print(f"\n✅ Core features extracted: {CORE_FEATURES}")
print(f"\n📈 Core Feature Statistics:")
print(core_df.describe().round(2))


# =============================================================================
# CELL 4: Correlation Matrix Visualization
# =============================================================================

# Compute Pearson correlation matrix
corr_matrix = core_df.corr()

fig, axes = plt.subplots(1, 2, figsize=(20, 8))

# 4a. Heatmap with values
sns.heatmap(corr_matrix, annot=True, fmt='.3f', cmap='RdBu_r', center=0,
            vmin=-1, vmax=1, square=True, linewidths=0.5,
            xticklabels=[f.replace('_', '\n') for f in CORE_FEATURES],
            yticklabels=[f.replace('_', '\n') for f in CORE_FEATURES],
            ax=axes[0])
axes[0].set_title('Pearson Correlation Matrix\n(6 Core LMS Features)', fontsize=14, fontweight='bold')

# 4b. Spearman correlation (for non-linear relationships)
spearman_corr = core_df.corr(method='spearman')
sns.heatmap(spearman_corr, annot=True, fmt='.3f', cmap='RdBu_r', center=0,
            vmin=-1, vmax=1, square=True, linewidths=0.5,
            xticklabels=[f.replace('_', '\n') for f in CORE_FEATURES],
            yticklabels=[f.replace('_', '\n') for f in CORE_FEATURES],
            ax=axes[1])
axes[1].set_title('Spearman Correlation Matrix\n(6 Core LMS Features)', fontsize=14, fontweight='bold')

plt.tight_layout()
plt.savefig('correlation_matrices.png', dpi=150, bbox_inches='tight')
plt.show()

print("\n📊 Correlation Matrix (Pearson):")
print(corr_matrix.round(3))
print("\n📊 Correlation Matrix (Spearman):")
print(spearman_corr.round(3))


# =============================================================================
# CELL 5: Normalize Data for Weight Derivation
# =============================================================================

# Min-Max normalize all features to [0, 1] for fair comparison
scaler = MinMaxScaler()
core_normalized = pd.DataFrame(
    scaler.fit_transform(core_df),
    columns=CORE_FEATURES
)

# Flip negative features so that HIGHER = BETTER for all
for feat, direction in FEATURE_DIRECTION.items():
    if direction == -1:
        core_normalized[feat] = 1 - core_normalized[feat]

print("✅ Features normalized to [0,1] with negative features flipped")
print("\nNormalized Statistics:")
print(core_normalized.describe().round(3))


# =============================================================================
# CELL 6: METHOD 1 — PCA-Based Weights
# =============================================================================
"""
PCA identifies the direction of maximum variance in the data.
The loadings of the 1st principal component indicate which features
contribute most to distinguishing students from each other.

Citation Justification:
- Jolliffe (2002) - Principal Component Analysis
- Abdi & Williams (2010) - PCA for feature weighting in composite indices
"""

# Standardize for PCA
std_scaler = StandardScaler()
core_standardized = std_scaler.fit_transform(core_normalized)

pca = PCA(n_components=len(CORE_FEATURES))
pca.fit(core_standardized)

# Extract loadings from 1st principal component
pc1_loadings = np.abs(pca.components_[0])
pca_weights = pc1_loadings / pc1_loadings.sum()  # Normalize to sum=1

print("=" * 60)
print("METHOD 1: PCA-Based Weights")
print("=" * 60)
print(f"\nExplained variance ratios: {pca.explained_variance_ratio_.round(3)}")
print(f"PC1 explains {pca.explained_variance_ratio_[0]*100:.1f}% of total variance")
print(f"\nPC1 Raw Loadings: {pca.components_[0].round(3)}")
print(f"\nPCA-Derived Weights (normalized):")
for feat, w in zip(CORE_FEATURES, pca_weights):
    print(f"  {feat:30s}: {w:.4f} ({w*100:.1f}%)")


# =============================================================================
# CELL 7: METHOD 2 — Entropy-Based Weights
# =============================================================================
"""
Entropy weighting assigns higher weights to features with more
variation/information content. Features where all students score
similarly (low entropy) contribute less to differentiation.

Citation Justification:
- Shannon (1948) - Information entropy
- Zou et al. (2006) - Entropy weighting for multi-criteria decision making
- Wang & Lee (2009) - Entropy method for objective weight determination
"""

def entropy_weights(data):
    """
    Calculate entropy-based weights.
    Features with more variation (lower entropy) get higher weight.
    """
    # Ensure non-negative values
    data_shifted = data - data.min() + 1e-10
    
    # Normalize each column to proportion
    p = data_shifted / data_shifted.sum()
    
    # Calculate entropy for each feature
    k = 1 / np.log(len(data))  # Normalization constant
    entropy = -k * (p * np.log(p + 1e-10)).sum()
    
    # Divergence (1 - entropy) — higher divergence = more useful
    divergence = 1 - entropy
    
    # Normalize to get weights
    weights = divergence / divergence.sum()
    return weights, entropy, divergence

entropy_w, entropies, divergences = entropy_weights(core_normalized)

print("=" * 60)
print("METHOD 2: Entropy-Based Weights")
print("=" * 60)
print(f"\nEntropy Values (lower = more discriminating):")
for feat, e, d in zip(CORE_FEATURES, entropies, divergences):
    print(f"  {feat:30s}: entropy={e:.4f}, divergence={d:.4f}")
print(f"\nEntropy-Derived Weights (normalized):")
for feat, w in zip(CORE_FEATURES, entropy_w):
    print(f"  {feat:30s}: {w:.4f} ({w*100:.1f}%)")


# =============================================================================
# CELL 8: METHOD 3 — Factor Analysis Weights
# =============================================================================
"""
Factor Analysis extracts latent factors from observed variables.
The loadings indicate how strongly each observed feature relates
to the underlying latent construct (mastery).

Citation Justification:
- Thompson (2004) - Exploratory and Confirmatory Factor Analysis
- Hair et al. (2019) - Multivariate Data Analysis (8th edition)
"""

# Factor Analysis with 1 factor (assuming single latent "mastery" construct)
fa = FactorAnalysis(n_components=1, random_state=42)
fa.fit(core_standardized)

# Extract loadings
fa_loadings = np.abs(fa.components_[0])
fa_weights = fa_loadings / fa_loadings.sum()

print("=" * 60)
print("METHOD 3: Factor Analysis Weights")
print("=" * 60)
print(f"\nFactor Loadings (raw):")
for feat, loading in zip(CORE_FEATURES, fa.components_[0]):
    print(f"  {feat:30s}: {loading:.4f}")
print(f"\nFA-Derived Weights (normalized):")
for feat, w in zip(CORE_FEATURES, fa_weights):
    print(f"  {feat:30s}: {w:.4f} ({w*100:.1f}%)")


# =============================================================================
# CELL 9: METHOD 4 — Correlation-Based Weights (CRITIC Method)
# =============================================================================
"""
CRITIC (Criteria Importance Through Intercriteria Correlation)
combines standard deviation (information content) with
inter-criteria correlation (contrast between features).

Features that have HIGH variation AND LOW correlation with others
get the highest weights (they provide unique information).

Citation Justification:
- Diakoulaki et al. (1995) - CRITIC method for objective weight determination
- Zeleny (1982) - Multiple Criteria Decision Making
"""

def critic_weights(data):
    """
    CRITIC method: combines standard deviation with correlation contrast.
    Weight_j = σ_j × Σ(1 - r_jk)
    """
    # Standard deviation of each feature
    std_devs = data.std()
    
    # Correlation matrix
    corr = data.corr()
    
    # Contrast measure: sum of (1 - correlation) for each feature
    contrast = (1 - corr).sum()
    
    # Information content = std × contrast
    info_content = std_devs * contrast
    
    # Normalize to weights
    weights = info_content / info_content.sum()
    return weights, std_devs, contrast, info_content

critic_w, stds, contrasts, info_contents = critic_weights(core_normalized)

print("=" * 60)
print("METHOD 4: CRITIC-Based Weights")
print("=" * 60)
print(f"\nFeature Analysis:")
print(f"{'Feature':30s} {'Std Dev':>10s} {'Contrast':>10s} {'Info':>10s} {'Weight':>10s}")
print("-" * 72)
for feat, s, c, i, w in zip(CORE_FEATURES, stds, contrasts, info_contents, critic_w):
    print(f"  {feat:28s} {s:10.4f} {c:10.4f} {i:10.4f} {w:10.4f}")


# =============================================================================
# CELL 10: Composite Weights — Combining All Methods
# =============================================================================

# Create comparison DataFrame
weight_comparison = pd.DataFrame({
    'Feature': CORE_FEATURES,
    'PCA': pca_weights,
    'Entropy': entropy_w.values if hasattr(entropy_w, 'values') else entropy_w,
    'Factor Analysis': fa_weights,
    'CRITIC': critic_w.values if hasattr(critic_w, 'values') else critic_w,
})

# Calculate composite (equal-weighted average of all methods)
weight_comparison['Composite'] = weight_comparison[['PCA', 'Entropy', 'Factor Analysis', 'CRITIC']].mean(axis=1)

# Re-normalize composite to sum to 1
weight_comparison['Composite'] = weight_comparison['Composite'] / weight_comparison['Composite'].sum()

# Add direction
weight_comparison['Direction'] = weight_comparison['Feature'].map(FEATURE_DIRECTION)

print("=" * 60)
print("COMPOSITE WEIGHTS — All Methods Combined")
print("=" * 60)
print(f"\n{weight_comparison.to_string(index=False, float_format='{:.4f}'.format)}")

print(f"\n{'='*60}")
print("DATA-DRIVEN LMS FORMULA")
print(f"{'='*60}")
print("\nLMS = ", end="")
terms = []
for _, row in weight_comparison.iterrows():
    feat = row['Feature']
    w = row['Composite']
    sign = '+' if row['Direction'] == 1 else '-'
    if len(terms) == 0 and sign == '+':
        terms.append(f"{w:.3f} × {feat}")
    else:
        terms.append(f"{sign} {w:.3f} × {feat}")
print("\n      ".join(terms))


# =============================================================================
# CELL 11: Visualization — Weight Comparison Across Methods
# =============================================================================

fig, axes = plt.subplots(2, 2, figsize=(18, 14))

methods = ['PCA', 'Entropy', 'Factor Analysis', 'CRITIC']
colors = ['#2196F3', '#4CAF50', '#FF9800', '#9C27B0']
short_labels = [f.replace('_', '\n') for f in CORE_FEATURES]

for idx, (method, color) in enumerate(zip(methods, colors)):
    ax = axes[idx // 2, idx % 2]
    bars = ax.bar(short_labels, weight_comparison[method], color=color, alpha=0.8, edgecolor='black')
    ax.set_title(f'Method {idx+1}: {method} Weights', fontsize=14, fontweight='bold')
    ax.set_ylabel('Weight (normalized)')
    ax.set_ylim(0, max(weight_comparison[method]) * 1.3)
    
    # Add value labels
    for bar, val in zip(bars, weight_comparison[method]):
        ax.text(bar.get_x() + bar.get_width()/2, bar.get_height() + 0.005,
                f'{val:.3f}', ha='center', va='bottom', fontsize=10, fontweight='bold')

plt.suptitle('Weight Derivation — 4 Unsupervised Methods', fontsize=16, fontweight='bold', y=1.02)
plt.tight_layout()
plt.savefig('weight_methods_comparison.png', dpi=150, bbox_inches='tight')
plt.show()


# =============================================================================
# CELL 12: Final Composite Weights Visualization
# =============================================================================

fig, axes = plt.subplots(1, 2, figsize=(18, 7))

# 12a. Bar chart — Composite vs Original
original_weights_approx = {
    'score_percentage': 0.50,
    'hard_question_accuracy': 0.15,
    'hint_usage_percentage': 0.15,  # ~15 out of 100 range
    'avg_confidence': 0.10,
    'answer_changes_rate': 0.10,
    'tab_switches_rate': 0.10
}
original_w = np.array([original_weights_approx[f] for f in CORE_FEATURES])
original_w_norm = original_w / original_w.sum()

x = np.arange(len(CORE_FEATURES))
width = 0.35

bars1 = axes[0].bar(x - width/2, original_w_norm, width, label='Original (Literature)',
                     color='#FF6B6B', alpha=0.8, edgecolor='black')
bars2 = axes[0].bar(x + width/2, weight_comparison['Composite'], width, label='Data-Driven (This Study)',
                     color='#4ECDC4', alpha=0.8, edgecolor='black')

axes[0].set_xticks(x)
axes[0].set_xticklabels(short_labels, fontsize=9)
axes[0].set_ylabel('Normalized Weight')
axes[0].set_title('Original vs Data-Driven Weights', fontsize=14, fontweight='bold')
axes[0].legend(fontsize=11)

for bar, val in zip(bars1, original_w_norm):
    axes[0].text(bar.get_x() + bar.get_width()/2, bar.get_height() + 0.005,
                 f'{val:.3f}', ha='center', va='bottom', fontsize=8)
for bar, val in zip(bars2, weight_comparison['Composite']):
    axes[0].text(bar.get_x() + bar.get_width()/2, bar.get_height() + 0.005,
                 f'{val:.3f}', ha='center', va='bottom', fontsize=8)

# 12b. Radar / Spider chart for composite weights
categories = [f.replace('_percentage', '%').replace('_rate', ' (rate)')
              .replace('avg_', '').replace('_', ' ').title()
              for f in CORE_FEATURES]
N = len(categories)
angles = np.linspace(0, 2 * np.pi, N, endpoint=False).tolist()
angles += angles[:1]

values_orig = original_w_norm.tolist() + [original_w_norm[0]]
values_data = weight_comparison['Composite'].tolist() + [weight_comparison['Composite'].iloc[0]]

ax2 = fig.add_subplot(122, polar=True)
axes[1].set_visible(False)  # Hide the rectangular axes

ax2.plot(angles, values_orig, 'o-', linewidth=2, label='Original', color='#FF6B6B')
ax2.fill(angles, values_orig, alpha=0.15, color='#FF6B6B')
ax2.plot(angles, values_data, 'o-', linewidth=2, label='Data-Driven', color='#4ECDC4')
ax2.fill(angles, values_data, alpha=0.15, color='#4ECDC4')

ax2.set_xticks(angles[:-1])
ax2.set_xticklabels(categories, fontsize=9)
ax2.set_title('Weight Distribution\n(Radar Comparison)', fontsize=14, fontweight='bold', pad=20)
ax2.legend(loc='lower right', fontsize=10)

plt.tight_layout()
plt.savefig('weight_comparison_final.png', dpi=150, bbox_inches='tight')
plt.show()


# =============================================================================
# CELL 13: Statistical Validation — Bootstrap Confidence Intervals
# =============================================================================
"""
Bootstrap the PCA weights to provide confidence intervals,
demonstrating the stability of the derived weights.
"""

n_bootstrap = 1000
bootstrap_weights = np.zeros((n_bootstrap, len(CORE_FEATURES)))

for i in range(n_bootstrap):
    # Resample with replacement
    sample = core_normalized.sample(n=len(core_normalized), replace=True)
    sample_std = StandardScaler().fit_transform(sample)
    
    # Fit PCA
    pca_boot = PCA(n_components=len(CORE_FEATURES))
    pca_boot.fit(sample_std)
    
    # Extract weights from PC1
    loadings = np.abs(pca_boot.components_[0])
    bootstrap_weights[i] = loadings / loadings.sum()

# Calculate confidence intervals
ci_lower = np.percentile(bootstrap_weights, 2.5, axis=0)
ci_upper = np.percentile(bootstrap_weights, 97.5, axis=0)
ci_mean = bootstrap_weights.mean(axis=0)

print("=" * 60)
print("BOOTSTRAP VALIDATION (1000 iterations)")
print("=" * 60)
print(f"\n{'Feature':30s} {'Mean Weight':>12s} {'95% CI Lower':>14s} {'95% CI Upper':>14s}")
print("-" * 72)
for feat, m, lo, hi in zip(CORE_FEATURES, ci_mean, ci_lower, ci_upper):
    print(f"  {feat:28s} {m:12.4f} {lo:14.4f} {hi:14.4f}")

# Visualize bootstrap distributions
fig, axes = plt.subplots(2, 3, figsize=(18, 10))
for idx, (feat, ax) in enumerate(zip(CORE_FEATURES, axes.flat)):
    ax.hist(bootstrap_weights[:, idx], bins=30, color=colors[idx % len(colors)],
            alpha=0.7, edgecolor='black')
    ax.axvline(ci_mean[idx], color='red', linestyle='--', linewidth=2, label=f'Mean: {ci_mean[idx]:.3f}')
    ax.axvline(ci_lower[idx], color='orange', linestyle=':', linewidth=1.5, label=f'95% CI: [{ci_lower[idx]:.3f}, {ci_upper[idx]:.3f}]')
    ax.axvline(ci_upper[idx], color='orange', linestyle=':', linewidth=1.5)
    ax.set_title(feat.replace('_', ' ').title(), fontsize=11, fontweight='bold')
    ax.legend(fontsize=8)

plt.suptitle('Bootstrap Weight Distributions (n=1000)', fontsize=14, fontweight='bold')
plt.tight_layout()
plt.savefig('bootstrap_weights.png', dpi=150, bbox_inches='tight')
plt.show()


# =============================================================================
# CELL 14: Generate Final LMS Formula
# =============================================================================

print("\n" + "=" * 70)
print("  FINAL DATA-DRIVEN LMS FORMULA")
print("=" * 70)

composite = weight_comparison[['Feature', 'Composite', 'Direction']].copy()
composite = composite.sort_values('Composite', ascending=False)

# Scale weights to make the formula practical (multiply by 100)
# This makes the final LMS on a 0-100 scale
print("\n--- Option 1: Weighted Sum with Normalized Features (0-1 each) ---")
print("LMS = 100 × (")
for i, (_, row) in enumerate(composite.iterrows()):
    sign = '+' if row['Direction'] == 1 else '-'
    prefix = '  ' if i == 0 and sign == '+' else f'  {sign} '
    print(f"  {prefix}{row['Composite']:.4f} × norm({row['Feature']})")
print(")")

print("\n--- Option 2: Percentage Weights (for readable formula) ---")
print("LMS = ", end="")
for i, (_, row) in enumerate(composite.iterrows()):
    pct = row['Composite'] * 100
    sign = '+' if row['Direction'] == 1 else '-'
    if i == 0 and sign == '+':
        print(f"{pct:.1f}% × {row['Feature']}", end="")
    else:
        print(f" {sign} {pct:.1f}% × {row['Feature']}", end="")
print()

print("\n--- Weight Summary ---")
print(f"\n{'Feature':30s} {'Weight':>10s} {'Direction':>10s}")
print("-" * 52)
for _, row in composite.iterrows():
    dir_str = "POSITIVE" if row['Direction'] == 1 else "NEGATIVE"
    print(f"  {row['Feature']:28s} {row['Composite']*100:8.1f}% {dir_str:>10s}")

print(f"\n✅ Total weight sum: {composite['Composite'].sum():.4f} (should be 1.0000)")


# =============================================================================
# CELL 15: Comparison Report & Academic Justification
# =============================================================================

print("\n" + "=" * 70)
print("  METHODOLOGY REPORT FOR ACADEMIC CITATION")
print("=" * 70)

report = """
METHODOLOGY: Data-Driven Weight Derivation for Learning Mastery Score (LMS)

1. PROBLEM STATEMENT
   The LMS formula requires feature weights that determine each core
   behavioural indicator's contribution to the composite mastery score.
   Without a pre-existing ground-truth mastery score, supervised methods
   (e.g., regression) cannot be applied.

2. APPROACH: UNSUPERVISED MULTI-METHOD WEIGHT DERIVATION
   Four established unsupervised methods were applied to the real student
   dataset (N=51) to derive objective feature weights:

   Method 1 — Principal Component Analysis (PCA)
   • Extracts the direction of maximum variance (PC1)
   • PC1 loadings indicate each feature's discriminating power
   • References: Jolliffe (2002); Abdi & Williams (2010)

   Method 2 — Entropy-Based Weighting
   • Features with higher information entropy receive lower weights
   • Measures each feature's discriminating ability across students
   • References: Shannon (1948); Zou et al. (2006)

   Method 3 — Factor Analysis (FA)
   • Single-factor model assumes one latent 'mastery' construct
   • Factor loadings represent each feature's relationship to mastery
   • References: Thompson (2004); Hair et al. (2019)

   Method 4 — CRITIC Method
   • Combines standard deviation with inter-criteria correlation
   • Features with high variation AND low redundancy get higher weight
   • References: Diakoulaki et al. (1995)

3. COMPOSITE WEIGHT DERIVATION
   Final weights are the arithmetic mean of all four methods,
   re-normalized to sum to 1.0. This multi-method ensemble reduces
   bias from any single technique.

4. VALIDATION
   Bootstrap resampling (n=1000) provides 95% confidence intervals
   for the PCA-derived weights, demonstrating stability despite the
   moderate sample size.

5. KEY REFERENCES
   - Abdi, H. & Williams, L.J. (2010). Principal Component Analysis.
     WIREs Computational Statistics, 2(4), pp.433–459.
   - Diakoulaki, D., Mavrotas, G. & Papayannakis, L. (1995). Determining
     objective weights in multiple criteria problems: The CRITIC method.
     Computers & Operations Research, 22(7), pp.763–770.
   - Hair, J.F. et al. (2019). Multivariate Data Analysis (8th ed.).
     Cengage Learning.
   - Jolliffe, I.T. (2002). Principal Component Analysis (2nd ed.).
     Springer.
   - Shannon, C.E. (1948). A Mathematical Theory of Communication.
     Bell System Technical Journal, 27(3), pp.379–423.
   - Thompson, B. (2004). Exploratory and Confirmatory Factor Analysis.
     APA.
   - Zou, Z.H. et al. (2006). Entropy method for determination of
     weight of evaluating indicators. Journal of Software, 17(8).
"""
print(report)


# =============================================================================
# CELL 16: Export Results
# =============================================================================

# Save weight comparison to CSV
weight_comparison.to_csv('lms_weight_derivation_results.csv', index=False)
print("✅ Results saved to: lms_weight_derivation_results.csv")

# Save correlation matrices
corr_matrix.to_csv('pearson_correlation_matrix.csv')
spearman_corr.to_csv('spearman_correlation_matrix.csv')
print("✅ Correlation matrices saved to CSV")

# Save bootstrap results
bootstrap_df = pd.DataFrame({
    'Feature': CORE_FEATURES,
    'Bootstrap_Mean': ci_mean,
    'CI_Lower_2.5%': ci_lower,
    'CI_Upper_97.5%': ci_upper,
    'Composite_Weight': weight_comparison['Composite'].values
})
bootstrap_df.to_csv('bootstrap_validation.csv', index=False)
print("✅ Bootstrap validation saved to: bootstrap_validation.csv")

print("\n🎯 DONE! You now have data-driven weights derived from your real dataset.")
print("   Use these in your LMS formula with proper academic citations.")
