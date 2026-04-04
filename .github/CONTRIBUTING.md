# Contributing to X-Scaffold

Thank you for your interest in X-Scaffold. This project is a final-year dissertation and is maintained solely by me **Akssayan Kalaharan**. Contributions are accepted only by prior arrangement with the project owner.

## Important Notice

This repository is provided for **viewing purposes only** under an All Rights Reserved licence. Unauthorised copying, forking, or redistribution is not permitted. If you wish to contribute, you must first contact the project owner for written approval.

## Branching Strategy

All work must follow this branching model:

| Branch | Purpose |
|--------|---------|
| `main` | Production-ready code. Only the project owner merges into this branch. |
| `develop` | Integration branch for ongoing work. PRs are merged here first. |
| `feature/<name>` | New features (e.g. `feature/hint-difficulty-scaling`). |
| `fix/<name>` | Bug fixes (e.g. `fix/shap-value-overflow`). |
| `docs/<name>` | Documentation changes (e.g. `docs/update-readme`). |

## Pull Request Process

1. **Create a branch** from `develop` using the naming convention above.
2. **Make your changes** in small, focused commits with clear messages.
3. **Open a Pull Request** to `develop` (never directly to `main`).
4. **Fill out the PR template** completely — explain what the change does, why it is needed, and how it was tested.
5. **Wait for review** — only the project owner (@Akxh1) can approve and merge PRs.
6. **Do not merge your own PRs** — all merges are performed by the project owner.

## Commit Message Format

Use clear, descriptive commit messages:

```
feat: add hint difficulty scaling based on mastery level
fix: resolve SHAP value overflow for edge-case predictions
docs: update README with ML pipeline section
refactor: extract LMS calculation into dedicated service
test: add unit tests for feature engineering pipeline
```

## What We Accept

- Bug fixes with clear reproduction steps
- Documentation improvements
- Test coverage improvements
- Performance optimisations with benchmarks

## What We Do Not Accept

- Unsolicited feature additions without prior discussion
- Changes to the ML model or training pipeline without approval
- Breaking changes to the API contract between Laravel and Flask
- Code style changes outside of the project's Pint/ESLint configuration
