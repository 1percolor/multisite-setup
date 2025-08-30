<?php
namespace Acme\MsPlugin\Admin;

final class SettingsPage {
    public static function register(): void {
        add_options_page(
            'ACME Settings',
            'ACME Settings',
            'manage_options',
            'acme-settings',
            [ self::class, 'render' ]
        );
    }

    public static function render(): void {
        ?>
        <div class="wrap">
            <h1>ACME Plugin Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'acme_settings' );
                do_settings_sections( 'acme_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
