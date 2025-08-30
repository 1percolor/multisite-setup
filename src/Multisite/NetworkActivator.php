<?php
namespace Acme\MsPlugin\Multisite;

final class NetworkActivator {
    public static function activate( callable $per_blog ): void {
        if ( ! is_multisite() ) { $per_blog( get_current_blog_id() ); return; }
        $sites = get_sites([ 'number' => 0 ]);
        foreach ( $sites as $site ) {
            switch_to_blog( (int) $site->blog_id );
            $per_blog( (int) $site->blog_id );
            restore_current_blog();
        }
    }
}
