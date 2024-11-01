<?php
/*
Plugin Name: WooCommerce ceština
Version: 1.0
Description: Preloží WooCommerce a vybrané dodatkové pluginy do ceštiny.
*/

if (!function_exists('woocommerce_cestina_load_textdomain_hook')) {
    function woocommerce_cestina_load_textdomain_hook( $domain = '', $mofile = '' ){
        $basedir = trailingslashit(WP_LANG_DIR);
        $baselen = strlen($basedir);

        // only run this if file being loaded is under WP_LANG_DIR
        if ( $basedir === substr($mofile, 0, $baselen) ){
            // Correct custom directory path within the plugin's translate directory
            $plugin_dir = dirname(dirname(plugin_dir_path(__FILE__))); // two levels up to reach the plugin root
            $custom_mofile = $plugin_dir . '/translates/' . substr($mofile, $baselen);

            if ( file_exists($custom_mofile) ){
                load_textdomain($domain, $custom_mofile);
            }
        }
    }
}

// Check if the translation service is enabled
$is_enabled = get_option('woocommerce_cestina_translation_enabled', false);

if ($is_enabled) {
    add_action('load_textdomain', 'woocommerce_cestina_load_textdomain_hook', 10, 2);
}
?>
