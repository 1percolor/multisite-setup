#!/usr/bin/env bash
set -e

# Install composer & WP-CLI inside the wordpress container image
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp

# Node global tools as needed (pnpm recommended)
npm i -g pnpm
pnpm -v
