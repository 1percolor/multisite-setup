export default {
  '*.{js,ts}': ['eslint --fix', 'prettier --write'],
  '*.{css,scss}': ['stylelint --fix', 'prettier --write'],
  '*.php': ['vendor/bin/phpcs --fix'],
  '*.{json,md,yml,yaml}': ['prettier --write'],
};
