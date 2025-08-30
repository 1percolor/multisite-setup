<?php
namespace Acme\MsPlugin\Tests\Integration;

use Acme\MsPlugin\REST\ExampleController;
use PHPUnit\Framework\TestCase;

class ExampleIntegrationTest extends TestCase {
    public function test_rest_controller_returns_expected_data(): void {
        $request = new \WP_REST_Request( 'GET', '/acme/v1/example' );
        
        $response = ExampleController::get_example( $request );
        
        $this->assertInstanceOf( \WP_REST_Response::class, $response );
        $data = $response->get_data();
        $this->assertArrayHasKey( 'message', $data );
        $this->assertArrayHasKey( 'blog_id', $data );
        $this->assertArrayHasKey( 'is_multisite', $data );
    }
}
