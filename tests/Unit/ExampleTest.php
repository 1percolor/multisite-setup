<?php
namespace Acme\MsPlugin\Tests\Unit;

use Acme\MsPlugin\Multisite\BlogSwitcher;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase {
    protected function setUp(): void {
        parent::setUp();
        Brain\Monkey\setUp();
    }

    protected function tearDown(): void {
        Brain\Monkey\tearDown();
        parent::tearDown();
    }

    public function test_blog_switcher_calls_callback(): void {
        $callback_called = false;
        $callback = function() use (&$callback_called) {
            $callback_called = true;
            return 'test_result';
        };

        Functions\when( 'is_multisite' )->justReturn( false );

        $result = BlogSwitcher::at( 1, $callback );

        $this->assertTrue( $callback_called );
        $this->assertEquals( 'test_result', $result );
    }
}
