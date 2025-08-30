<?php
namespace Acme\MsPlugin\Cron;

final class ExampleEvent {
    public const HOOK = 'acme_example_cron';

    public static function handle(): void {
        // Example cron job that logs to error log
        error_log( 'ACME Plugin: Example cron job executed at ' . current_time( 'mysql' ) );
    }
}
