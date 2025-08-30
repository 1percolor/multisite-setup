<?php
/**
 * Plugin Name: ACME Multisite Plugin
 * Description: Starter plugin scaffold with Multisite helpers, REST, Cron, and Vite-built assets.
 * Version: 0.1.0
 * Network: true
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require __DIR__ . '/vendor/autoload.php';

// Activation/Deactivation hooks
register_activation_hook( __FILE__, [ \Acme\MsPlugin\Installer::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ \Acme\MsPlugin\Installer::class, 'deactivate' ] );

add_action( 'plugins_loaded', static function () {
    ( new \Acme\MsPlugin\Plugin() )->boot();
});
