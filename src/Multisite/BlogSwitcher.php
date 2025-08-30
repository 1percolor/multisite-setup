<?php
namespace Acme\MsPlugin\Multisite;

final class BlogSwitcher {
    public static function at( int $blog_id, callable $cb ) {
        if ( ! is_multisite() ) {
            return $cb();
        }
        $current = get_current_blog_id();
        switch_to_blog( $blog_id );
        try { return $cb(); }
        finally { restore_current_blog(); }
    }
}
