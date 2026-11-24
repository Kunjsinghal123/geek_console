🚀 Project Feature & Change Classification (by Commit Types)
✨ 1. Features (feat)

Represents new functionality added to the project

Includes:

New modules (e.g., uninstall system, authentication, dashboard)
API endpoints
UI components/pages
Integrations (Shopify, Google, etc.)
Business logic implementations

Example:

Uninstall process implementation
API file creation and configuration
Setup wizard system


🐛 2. Bug Fixes (fix)

Represents issues resolved in the project

Includes:

Logic corrections
API bug fixes
UI fixes
Edge case handling
Performance bug fixes

Example:

Fix webhook HMAC validation issue
Resolve token storage bug


🧹 3. Chores (chore)

Represents non-functional updates (maintenance tasks)

Includes:

Dependency updates
File structure cleanup
Config updates
Build/tooling improvements

Example:

Update Laravel version
Clean unused files
Modify environment configs


📝 4. Documentation (docs)

Represents documentation-related changes

Includes:

README updates
API documentation
Setup instructions
Code comments improvements

Example:

Add API usage guide
Document uninstall flow


🎨 5. Style (style)

Represents code formatting and styling (no logic change)

Includes:

Indentation fixes
Code formatting
Naming consistency
CSS/UI styling tweaks

Example:

Format controller code
Update Bootstrap UI spacing


🔨 6. Refactor (refactor)

Represents code restructuring without changing functionality

Includes:

Optimizing logic
Splitting large functions
Improving readability
Code reusability improvements

Example:

Move webhook logic to service class
Simplify controller methods


⚡ 7. Performance (perf)

Represents performance improvements

Includes:

Query optimization
Caching
Reducing API calls
Faster processing

Example:

Optimize database queries
Add caching for API responses


🧪 8. Tests (test)

Represents testing-related updates

Includes:

Unit tests
Feature tests
Test case fixes

Example:

Add uninstall webhook test
Fix failing test cases


🔧 9. Build (build)

Represents build system or dependency changes

Includes:

Composer/NPM changes
Build scripts
CI/CD setup

Example:

Update composer dependencies
Configure build pipeline


🚀 10. CI/CD (ci)

Represents continuous integration / deployment changes

Includes:

GitHub Actions
Deployment scripts
Automation workflows

Example:

Add CI pipeline
Setup auto deployment


⏪ 11. Revert (revert)

Represents reverting previous commits

Example:

Revert uninstall feature due to bug


Example

type(scope): short description

feat(uninstall): implement uninstallation

Added uninstall process for the app.
Added api file and configured it.

