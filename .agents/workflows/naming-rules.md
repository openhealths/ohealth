---
description: Naming rules for branches, issues, and pull requests
---
# Naming Rules

When creating issues, branches, and pull requests on this project, strictly follow these conventions:

1. **Issues**: Create the GitHub issue first using `gh issue create` to get the Issue ID.
2. **Branches**: 
   - Branches MUST be named using the format `i{ISSUE_ID}_{short_snake_case_description}`.
   - Example: If the created issue is `#31` and the task is fixing the user ID migration, the branch name must be `i31_fix_user_id_migration`.
3. **Pull Requests**:
   - All Pull Requests MUST be created as a **draft** by default.
   - Use the `gh pr create --draft` flag when creating the PR.
   - The PR title and body should reference the Issue ID, e.g., "Resolves #31".
