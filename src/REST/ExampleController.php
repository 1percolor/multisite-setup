<?php
namespace Acme\MsPlugin\REST;

final class ExampleController {
    public static function register_routes(): void {
        register_rest_route( 'acme/v1', '/example', [
            'methods' => 'GET',
            'callback' => [ self::class, 'get_example' ],
            'permission_callback' => '__return_true',
        ] );
    }

    public static function get_example( \WP_REST_Request $request ): \WP_REST_Response {
        return new \WP_REST_Response( [
            'message' => 'Hello from ACME plugin!',
            'blog_id' => get_current_blog_id(),
            'is_multisite' => is_multisite(),
        ] );
    }
}
