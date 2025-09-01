# WordPress Multisite Plugin Starter

A modern, production-ready WordPress Multisite plugin development environment with Docker, comprehensive testing, and CI/CD.

## Features

- 🐳 **Docker Environment**: NGINX + PHP 8.3-FPM + MariaDB + MailHog + phpMyAdmin
- 🏗️ **Plugin Scaffold**: PSR-4 autoloading, DI-friendly architecture
- 🌐 **Multisite Ready**: Network activation, blog switching helpers
- ⚡ **Vite Build System**: TypeScript + PostCSS for modern asset building
- 🧪 **Comprehensive Testing**: PHPUnit, PHPCS, PHPStan, Playwright E2E
- 🔄 **CI/CD**: GitHub Actions with PHP/JS matrix testing
- 🛠️ **Developer Experience**: VS Code devcontainer, Xdebug, pre-commit hooks

## Prerequisites

- Docker & Docker Compose
- VS Code (optional, for devcontainer)
- Make (for convenient commands)

## Quick Start

1. **Clone and setup environment**:
   ```bash
   cp env.example .env
   make up
   ```

2. **Install dependencies**:
   ```bash
   make install-deps
   ```

3. **Build assets**:
   ```bash
   make build
   ```

4. **Access WordPress**:
   - Main site: http://localhost:8080 (admin/admin)
   - Network Admin: http://localhost:8080/wp-admin/network/
   - phpMyAdmin: http://localhost:8081
   - MailHog: http://localhost:8025

## Development Commands

### Docker & Environment
```bash
make up          # Start all services and setup WordPress Multisite
make down        # Stop and remove containers/volumes
make rebuild     # Rebuild containers and restart
make ssh         # SSH into WordPress container
```

### Dependencies & Building
```bash
make install-deps  # Install PHP (Composer) and JS (pnpm) dependencies
make build         # Build production assets with Vite
make dev           # Start Vite dev server with HMR
```

### Testing & Quality
```bash
make test    # Run PHPUnit tests
make e2e     # Run Playwright E2E tests
make lint    # Run PHPCS + ESLint + Stylelint
make fmt     # Format code with Prettier
make ci      # Run full CI pipeline locally
```

## Project Structure

```
wp-ms-plugin-starter/
├── .devcontainer/          # VS Code devcontainer config
├── .github/workflows/      # GitHub Actions CI
├── .vscode/               # VS Code settings
├── docker/                # Docker configuration files
├── src/                   # Plugin source code (PSR-4)
│   ├── Admin/            # Admin interface
│   ├── Assets/           # Asset enqueuing
│   ├── Cron/             # Scheduled tasks
│   ├── Multisite/        # Multisite helpers
│   └── REST/             # REST API endpoints
├── tests/                 # PHPUnit tests
├── e2e/                   # Playwright E2E tests
├── resources/             # Source assets (JS/CSS)
└── wp/                    # WordPress installation (auto-created)
```

## Plugin Development

### Multisite Helpers

The scaffold includes utilities for multisite development:

```php
use Acme\MsPlugin\Multisite\BlogSwitcher;
use Acme\MsPlugin\Multisite\NetworkActivator;

// Execute code in specific blog context
BlogSwitcher::at(2, function() {
    // This code runs in blog ID 2
    $option = get_option('my_option');
});

// Network activation across all sites
NetworkActivator::activate(function($blog_id) {
    // This runs for each blog during network activation
    add_option('acme_activated', true);
});
```

### Adding New Features

1. **REST Endpoints**: Add to `src/REST/`
2. **Admin Pages**: Add to `src/Admin/`
3. **Cron Jobs**: Add to `src/Cron/`
4. **Assets**: Add to `resources/js/` and `resources/css/`

### Asset Building

Assets are built with Vite and support:
- TypeScript compilation
- PostCSS processing
- Hot Module Replacement (HMR) in development
- Production optimization

```bash
pnpm dev     # Development with HMR
pnpm build   # Production build
```

## Testing

### PHP Testing
- **Unit Tests**: Use Brain Monkey for mocking WordPress functions
- **Integration Tests**: Test against real WordPress environment
- **Code Quality**: PHPCS (WordPress Coding Standards) + PHPStan

### E2E Testing
- **Playwright**: Cross-browser testing
- **Multisite Flows**: Network admin, site creation, plugin activation
- **REST API**: Endpoint testing

### Running Tests
```bash
make test    # PHPUnit
make e2e     # Playwright
make lint    # Code quality checks
```

## CI/CD

GitHub Actions automatically:
- Tests PHP 8.1, 8.2, 8.3
- Runs code quality checks (PHPCS, PHPStan)
- Builds and tests JavaScript assets
- Runs on every push and PR

## Multisite Management

### Creating New Sites
```bash
# Via WP-CLI
./bin/wp site create --slug=site2 --title="Site 2" --email=admin@example.com

# Via WordPress Admin
# Go to Network Admin > Sites > Add New
```

### Network vs Site Activation
- **Network Activation**: Plugin available on all sites
- **Site Activation**: Plugin activated per individual site
- Use `NetworkActivator::activate()` for network-wide setup

## Configuration

### Environment Variables
Copy `env.example` to `.env` and customize:
- Ports for services
- Database credentials
- WordPress settings

### WordPress Configuration
- Multisite enabled by default (subdirectory mode)
- Debug mode enabled in development
- Xdebug configured for step debugging

## Troubleshooting

### Common Issues

1. **Port conflicts**: Change ports in `.env`
2. **Database connection**: Run `make down && make up` to reset
3. **Plugin not loading**: Check `wp/wp-content/plugins/` directory
4. **Assets not building**: Run `make install-deps && make build`

### Debugging
- **Xdebug**: Configured for VS Code debugging
- **Error Logs**: Check Docker logs with `docker compose logs wordpress`
- **Database**: Access via phpMyAdmin at http://localhost:8081

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make changes with tests
4. Run `make ci` to verify
5. Submit a pull request

## License

MIT License - see LICENSE file for details.

## Husky Instructions
You are an expert DevOps engineer working inside an existing WordPress-plugin style repo (PHP + JS/TS). Implement a Husky-based git cycle with opinionated defaults that fit WP + JS projects.

## Objectives
1) Enforce a clean git cycle:
   - Branching: feature/*, fix/*, chore/*, docs/*, release/*, hotfix/*; prevent direct commits to main.
   - Commits: Conventional Commits only (feat, fix, chore, docs, refactor, perf, test, build, ci, style, revert).
   - Pre-commit quality gate: fast local checks (format, lint, type checks, PHP sniffs) on staged files.
   - Pre-push gate: run unit tests and lightweight build.
   - Releases: standard-version (Conventional Commits → CHANGELOG.md + tag + version bump). Support `npm run release[:patch|:minor|:major]`.
   - Optional signing: support git commit -S if user has GPG set up (don’t force).

2) Tools to (auto)configure:
   - Husky + lint-staged
   - Commitlint + Commitizen (cz-git)
   - ESLint + Prettier for JS/TS
   - PHP_CodeSniffer (WordPress rules) + PHPStan (level configurable) via Composer scripts
   - Optional: stylelint if CSS present (skip if no CSS files)
   - Node 18+ assumed; Composer available.

3) Non-goals:
   - Don’t change application logic.
   - Be fast: pre-commit should never run full test suites over the entire repo; only staged files where possible.

## Deliverables
- Update/add the following files with safe, idempotent changes. If a file exists, extend it without breaking current content.
- Print a short “HOW TO USE” section at the end with common commands.

## Implementation Steps

1) Add dependencies (use caret ranges):
+++json
{
  "devDependencies": {
    "@commitlint/cli": "^19.3.0",
    "@commitlint/config-conventional": "^19.2.2",
    "commitizen": "^4.3.0",
    "cz-git": "^1.9.0",
    "eslint": "^9.9.0",
    "eslint-config-prettier": "^9.1.0",
    "eslint-plugin-import": "^2.29.1",
    "husky": "^9.1.0",
    "lint-staged": "^15.2.9",
    "prettier": "^3.3.3",
    "standard-version": "^9.5.0"
  }
}
+++

- If repo has CSS/SCSS files, also add:
+++json
{
  "devDependencies": {
    "stylelint": "^16.8.0",
    "stylelint-config-standard": "^36.0.0"
  }
}
+++

2) package.json edits:
   - Merge/append these fields & scripts (preserve existing):
+++json
{
  "private": false,
  "type": "module",
  "scripts": {
    "prepare": "husky",
    "format": "prettier --write .",
    "format:check": "prettier --check .",
    "lint": "eslint .",
    "lint:fix": "eslint . --fix",
    "php:cs": "phpcs -q",
    "php:cbf": "phpcbf -q",
    "php:stan": "phpstan analyse --no-interaction",
    "test": "echo \"(add your test runner here)\" && exit 0",
    "build": "echo \"(add your build step here)\" && exit 0",
    "commit": "cz",
    "release": "standard-version",
    "release:patch": "standard-version --release-as patch",
    "release:minor": "standard-version --release-as minor",
    "release:major": "standard-version --release-as major"
  },
  "lint-staged": {
    "*.{js,jsx,ts,tsx}": [
      "prettier --write",
      "eslint --fix"
    ],
    "*.{css,scss}": [
      "prettier --write",
      "stylelint --fix"
    ],
    "*.{json,md,yml,yaml}": [
      "prettier --write"
    ],
    "*.{php}": [
      "php -l",
      "phpcbf -q",
      "phpcs -q"
    ]
  },
  "config": {
    "commitizen": {
      "path": "cz-git"
    }
  },
  "standard-version": {
    "skip": {
      "changelog": false
    },
    "types": [
      { "type": "feat", "section": "Features" },
      { "type": "fix", "section": "Bug Fixes" },
      { "type": "perf", "section": "Performance" },
      { "type": "refactor", "section": "Refactoring" },
      { "type": "build", "section": "Build System" },
      { "type": "ci", "section": "CI" },
      { "type": "docs", "section": "Documentation" },
      { "type": "style", "section": "Styling" },
      { "type": "test", "section": "Tests" },
      { "type": "chore", "section": "Chores" }
    ]
  }
}
+++

3) ESLint & Prettier base configs (create if missing):
- .eslintrc.cjs
+++js
module.exports = {
  root: true,
  env: { browser: true, es2022: true, node: true },
  parserOptions: { ecmaVersion: "latest", sourceType: "module" },
  extends: ["eslint:recommended", "plugin:import/recommended", "prettier"],
  rules: {
    "no-unused-vars": ["error", { argsIgnorePattern: "^_" }],
    "import/order": ["warn", { "newlines-between": "always" }]
  },
  ignorePatterns: ["dist/", "build/", "vendor/", "**/*.min.js"]
};
+++

- .prettierrc
+++json
{
  "printWidth": 100,
  "singleQuote": true,
  "trailingComma": "es5"
}
+++

- .prettierignore
+++text
dist/
build/
vendor/
node_modules/
*.min.js
+++

4) Commitlint & Commitizen config:
- commitlint.config.cjs
+++js
module.exports = { extends: ["@commitlint/config-conventional"] };
+++

- .czrc (cz-git minimal prompt)
+++json
{
  "$schema": "https://cdn.jsdelivr.net/gh/Zhengqbbb/cz-git@1.9.3/schema/cz-git.json",
  "useEmoji": false,
  "scopes": ["plugin", "build", "docs", "core", "api", "ui", "deps", "tests", "ci"],
  "messages": {
    "type": "Select the type of change that you're committing:",
    "scope": "Denote the SCOPE of this change (optional):",
    "subject": "Write a SHORT, IMPERATIVE description:\n",
    "body": "Provide a LONGER description (optional). Use '|' for new line:\n",
    "footer": "List any ISSUES CLOSED by this change (e.g. #123, optional):\n"
  }
}
+++

5) Husky hooks:
- Initialize Husky:
+++bash
npx husky init
+++

- Overwrite/create `.husky/commit-msg`:
+++bash
#!/usr/bin/env sh
. "$(dirname -- "$0")/_/husky.sh"

# Enforce Conventional Commits
npx --no commitlint --edit "$1"
+++

- Overwrite/create `.husky/pre-commit`:
+++bash
#!/usr/bin/env sh
. "$(dirname -- "$0")/_/husky.sh"

# Run quick checks only on staged files
npx --no lint-staged
+++

- Overwrite/create `.husky/pre-push`:
+++bash
#!/usr/bin/env sh
. "$(dirname -- "$0")/_/husky.sh"

# Stop pushes that break tests or basic build
npm run build && npm test
+++

6) Branch naming guard (client-side):
- Create `.husky/pre-commit-branch` and source it from `pre-commit` **above** lint-staged.
   Update `.husky/pre-commit` to include:
+++bash
#!/usr/bin/env sh
. "$(dirname -- "$0")/_/husky.sh"

# Branch name policy: allow main, and patterns below
branch="$(git rev-parse --abbrev-ref HEAD)"
case "$branch" in
  main|master|release/*|hotfix/*|feature/*|fix/*|chore/*|docs/*) ;;
  *)
    echo "❌ Branch '$branch' violates naming policy. Use feature/*, fix/*, etc."
    exit 1
    ;;
esac

npx --no lint-staged
+++

7) Composer scripts for PHP tooling (append if composer.json exists):
- composer.json (add or merge “scripts” + “require-dev”):
+++json
{
  "require-dev": {
    "squizlabs/php_codesniffer": "^3.10",
    "phpstan/phpstan": "^1.11",
    "wp-coding-standards/wpcs": "^3.0"
  },
  "scripts": {
    "phpcs-install": [
      "Dealerdirect\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\Plugin::run"
    ]
  },
  "extra": {
    "phpcodesniffer-standard": "WordPress"
  }
}
+++

- After Composer install, ensure phpcs knows WPCS path (if needed):
+++bash
vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
vendor/bin/phpcs --config-set default_standard WordPress
+++

8) Git protection (local safety net):
- Add a client-side protection to prevent commits directly to main (commit-msg hook is too late, use pre-commit check already added).
- Also create a tiny pre-push main guard:
+++bash
#!/usr/bin/env sh
. "$(dirname -- "$0")/_/husky.sh"

branch="$(git rev-parse --abbrev-ref HEAD)"
if [ "$branch" = "main" ] || [ "$branch" = "master" ]; then
  echo "❌ Direct pushes to $branch are blocked. Open a PR and squash-merge."
  exit 1
fi

npm run build && npm test
+++

9) Root dotfiles (create if missing):
- .editorconfig
+++ini
root = true

[*]
charset = utf-8
end_of_line = lf
insert_final_newline = true
indent_style = space
indent_size = 2
trim_trailing_whitespace = true
+++

- .gitattributes
+++text
* text=auto eol=lf
*.php linguist-language=PHP
*.css linguist-language=CSS
*.js linguist-language=JavaScript
+++

10) CI note (do not implement CI here, just add TODO in README): require PRs, squash merge, and tags on release. (GitHub branch protection to be configured manually.)

## README additions
Append a “Git Cycle” section with:
- Branch naming rules
- Commit format examples
- Release commands
- How to bypass hooks in emergencies: `HUSKY=0 git commit ...` (not recommended)

## Post-Task
- Run installation steps:
  1) npm i
  2) composer install (if composer.json exists)
  3) npx husky init (already called by prepare, but ensure hooks are present)
  4) vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs (if needed)
- Print a short usage guide.

## Usage Guide (print at end)
- Start a feature: `git checkout -b feature/<slug>`
- Commit (guided): `npm run commit`
- Push: `git push --set-upstream origin feature/<slug>`
- Open PR → squash-merge
- Release: `npm run release[:patch|:minor|:major]` → push tags: `git push --follow-tags origin main`

Make all changes now.

## Husky specific wordpress instructions
## Add-Ons Pack: WordPress + Pressable + Cross-Platform polish

### A) WordPress PHPCS config file
Create `.phpcs.xml.dist` at repo root (idempotent; merge if exists):
+++xml
<?xml version="1.0"?>
<ruleset name="WP Plugin Rules">
  <description>WordPress Coding Standards for this plugin.</description>
  <file>./</file>
  <exclude-pattern>vendor/*</exclude-pattern>
  <exclude-pattern>node_modules/*</exclude-pattern>
  <exclude-pattern>dist/*</exclude-pattern>
  <rule ref="WordPress"/>
  <config name="report_width" value="120"/>
  <arg value="sp"/> <!-- show sniff and progress -->
  <arg name="extensions" value="php,inc"/>
  <rule ref="Generic.Files.LineLength">
    <properties>
      <property name="lineLimit" value="120"/>
      <property name="absoluteLineLimit" value="140"/>
      <property name="ignoreComments" value="true"/>
    </properties>
  </rule>
</ruleset>
+++

### B) Cross-platform Husky hooks (Windows/macOS/Linux)
Ensure all Husky files are executable and POSIX-sh:
+++bash
git config core.autocrlf input
chmod +x .husky/* 2>/dev/null || true
+++
Update hook shebangs to use `/usr/bin/env sh` (already in base prompt). Do **not** use bashisms.

### C) Node & PHP versions (pin locally so team runs same toolchain)
- Add `.nvmrc`:
+++text
18
+++
- Add `.tool-versions` (asdf users; safe to add):
+++text
nodejs 18.20.3
php 8.2.20
+++
- Add `engines` to `package.json` (merge):
+++json
{
  "engines": {
    "node": ">=18 <21"
  }
}
+++

### D) Lint staged smarter for PHP (only changed files)
Replace the PHP entry in `lint-staged` with this (merge/overwrite):
+++json
{
  "*.{php,inc}": [
    "php -l",
    "vendor/bin/phpcbf -q || true",
    "vendor/bin/phpcs -q"
  ]
}
+++
> Note: using vendor binaries ensures the right versions (post-`composer install`).

### E) Optional: CSS/SCSS stylelint config (only if CSS present)
Create `.stylelintrc.cjs`:
+++js
module.exports = {
  extends: ["stylelint-config-standard"],
  rules: {
    "color-hex-length": "short"
  },
  ignoreFiles: ["**/dist/**", "**/build/**", "**/*.min.css"]
};
+++

### F) Prevent accidental large commits
Add a size guard to `.husky/pre-commit` **before** `lint-staged`:
+++bash
# Block huge commits (over ~400 files). Adjust as needed.
count="$(git diff --cached --name-only | wc -l | tr -d ' ')"
max=400
if [ "$count" -gt "$max" ]; then
  echo "❌ This commit has $count files (> $max). Split it into smaller commits."
  exit 1
fi
+++

### G) Commit message examples in README
Append to README:
+++md
## Git Commit Examples
- `feat(plugin): add settings page for API keys`
- `fix(api): handle rate limit backoff for MLS Grid`
- `chore(deps): bump phpcs to ^3.10`
- `docs(readme): add usage guide`
+++

### H) Local branch protection reminder
Append a “Branch Protection” checklist to README (for GitHub UI):
+++md
## Branch Protection (enable in GitHub → Settings → Branches)
- Protect `main`:
  - Require PRs
  - Require status checks (tests/lint) to pass
  - Require linear history or squash merges
  - Dismiss stale approvals on new commits
  - Restrict who can push (optional)
+++

### I) Optional: Minimal CI (GitHub Actions)
> Keep it lean; runs on PRs and pushes to main. If you prefer to add later, skip this.
Create `.github/workflows/ci.yml`:
+++yaml
name: ci
on:
  pull_request:
  push:
    branches: [main]
jobs:
  build-and-test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 18
          cache: npm
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          tools: composer:v2
      - run: npm ci
      - run: composer install --no-interaction --prefer-dist
      - run: npx commitlint --from=HEAD~1 --to=HEAD || true
      - run: npm run build
      - run: npm test
      - run: vendor/bin/phpcs -q
+++

### J) Optional: Secret scanning (local)
Add `gitleaks` to catch secrets before push (skip if you don’t want it).
- Add to README “Tools” section; CI can be added later.

### K) Quickstart Recap (print at end)
- `npm i`  
- `composer install` (if using PHP tools)  
- `npx husky init` (hooks are created by `prepare`, but ensure they exist)  
- `git checkout -b feature/your-change`  
- `npm run commit` → follow prompts  
- `git push -u origin feature/your-change` → open PR → squash-merge  
- Release: `npm run release:patch|minor|major` then `git push --follow-tags`

