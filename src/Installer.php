<?php
namespace Acme\MsPlugin;

use Acme\MsPlugin\Multisite\NetworkActivator;

final class Installer {
    public static function activate(): void {
        NetworkActivator::activate( function( int $blog_id ) {
            // Set default options for each blog
            add_option( 'acme_plugin_version', '0.1.0' );
            add_option( 'acme_plugin_activated', current_time( 'mysql' ) );
        } );
    }

    public static function deactivate(): void {
        // Clean up scheduled events
        wp_clear_scheduled_hook( \Acme\MsPlugin\Cron\ExampleEvent::HOOK );
    }

    public static function uninstall(): void {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        // Network-wide cleanup
        if ( is_multisite() ) {
            $sites = get_sites( [ 'number' => 0 ] );
            foreach ( $sites as $site ) {
                switch_to_blog( (int) $site->blog_id );
                self::cleanup_blog();
                restore_current_blog();
            }
        } else {
            self::cleanup_blog();
        }
    }

    private static function cleanup_blog(): void {
        // Remove options
        delete_option( 'acme_plugin_version' );
        delete_option( 'acme_plugin_activated' );
        
        // Remove any custom tables if they exist
        // global $wpdb;
        // $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}acme_data" );
    }
}
