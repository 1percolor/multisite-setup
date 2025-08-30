<?php
/**
 * PHPUnit bootstrap file
 */

// Load Brain Monkey for unit tests
require_once __DIR__ . '/../vendor/autoload.php';

// Set up Brain Monkey
Brain\Monkey\setUp();

// Load WordPress test environment if available
if ( file_exists( __DIR__ . '/../vendor/wordpress/wordpress/tests/phpunit/includes/bootstrap.php' ) ) {
    require_once __DIR__ . '/../vendor/wordpress/wordpress/tests/phpunit/includes/bootstrap.php';
}
