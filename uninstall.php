<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

require __DIR__ . '/vendor/autoload.php';

\Acme\MsPlugin\Installer::uninstall();
