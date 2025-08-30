#!/usr/bin/env bash
set -e
until docker compose exec -T db mariadb -u root -p${DB_ROOT_PASSWORD:-root} -e "SELECT 1;" > /dev/null 2>&1; do
  echo "Waiting for database..."
  sleep 2
done
