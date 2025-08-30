<?php
namespace Acme\MsPlugin\Assets;

function enqueue() {
    $plugin_dir = dirname( __DIR__, 2 );
    $build_dir = $plugin_dir . '/build/assets';
    $url = plugins_url( 'build/assets', $plugin_dir . '/acme-ms-plugin.php' );
    
    // Find the hashed admin JS file
    $js_files = glob( $build_dir . '/admin-*.js' );
    if ( ! empty( $js_files ) ) {
        $js_file = basename( $js_files[0] );
        wp_enqueue_script( 'acme-admin', $url . '/' . $js_file, [], null, true );
    }
    
    // Find the hashed admin CSS file (if it exists)
    $css_files = glob( $build_dir . '/admin-*.css' );
    if ( ! empty( $css_files ) ) {
        $css_file = basename( $css_files[0] );
        wp_enqueue_style( 'acme-admin', $url . '/' . $css_file, [], null );
    }
}
