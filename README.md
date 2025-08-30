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
