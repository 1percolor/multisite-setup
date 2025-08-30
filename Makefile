SHELL := /bin/bash

up:
	docker compose up -d
	./bin/wait-for-db.sh
	@echo "Creating wp directory if missing..."
	mkdir -p wp
	# Core download
	docker compose exec -T wordpress bash -lc 'if [ ! -f /var/www/html/wp-settings.php ]; then wp core download --force; fi'
	# Create wp-config with salts
	docker compose exec -T wordpress bash -lc 'if [ ! -f /var/www/html/wp-config.php ]; then wp config create --dbname=$${WORDPRESS_DB_NAME} --dbuser=$${WORDPRESS_DB_USER} --dbpass=$${WORDPRESS_DB_PASSWORD} --dbhost=$${WORDPRESS_DB_HOST}; fi'
	# Install single site first
	docker compose exec -T wordpress bash -lc 'wp core install --url=http://localhost:8080 --title="WP MS Dev" --admin_user=admin --admin_password=admin --admin_email=admin@example.com || true'
	# Convert to multisite (subdir)
	docker compose exec -T wordpress bash -lc 'wp core multisite-convert --title="WP Network" --skip-config || true'
	# Network constants append if not present
	docker compose exec -T wordpress bash -lc 'grep -q "MULTISITE" wp-config.php || printf "\n/* Multisite */\ndefine(\\\"MULTISITE\\\", true);\ndefine(\\\"SUBDOMAIN_INSTALL\\\", false);\n\$base = \"/\";\ndefine(\\\"DOMAIN_CURRENT_SITE\\\", \\\"localhost\\\");\ndefine(\\\"PATH_CURRENT_SITE\\\", \"/\");\ndefine(\\\"SITE_ID_CURRENT_SITE\\\", 1);\ndefine(\\\"BLOG_ID_CURRENT_SITE\\\", 1);\n" >> wp-config.php'
	@echo "Multisite ready at http://localhost:8080 (admin/admin)"

down:
	docker compose down -v

rebuild:
	docker compose down -v
	docker compose build --no-cache
	$(MAKE) up

ssh:
	docker compose exec wordpress bash

install-deps:
	composer install
	pnpm install

build:
	pnpm run build

dev:
	pnpm run dev

lint:
	pnpm run lint && vendor/bin/phpcs

test:
	vendor/bin/phpunit

e2e:
	pnpm run e2e

fmt:
	pnpm run format

ci: install-deps lint test build
