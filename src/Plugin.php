<?php
namespace Acme\MsPlugin;

use Acme\MsPlugin\Admin\SettingsPage;
use Acme\MsPlugin\REST\ExampleController;
use Acme\MsPlugin\Cron\ExampleEvent;

// Load assets enqueue function
require_once __DIR__ . '/Assets/enqueue.php';

final class Plugin {
    public function boot(): void {
        // Admin settings
        add_action( 'admin_menu', [ SettingsPage::class, 'register' ] );

        // REST routes
        add_action( 'rest_api_init', [ ExampleController::class, 'register_routes' ] );

        // Cron
        add_action( ExampleEvent::HOOK, [ ExampleEvent::class, 'handle' ] );
        if ( ! wp_next_scheduled( ExampleEvent::HOOK ) ) {
            wp_schedule_event( time() + 60, 'hourly', ExampleEvent::HOOK );
        }

        // Assets
        add_action( 'admin_enqueue_scripts', '\\Acme\\MsPlugin\\Assets\\enqueue' );
    }
}
