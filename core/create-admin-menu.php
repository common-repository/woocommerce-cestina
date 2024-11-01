<?php
/**
 * Create Admin Menu
 *
 * @package WPTECH
 */

// Přidání hlavní stránky a podstránek do administrace WordPressu
if (!function_exists('novy_plugin_add_admin_menu')) {
    function novy_plugin_add_admin_menu() {
        // Hlavní stránka
        add_menu_page(
            'Nový plugin', // Název stránky
            'Nový plugin', // Název v menu
            'manage_options',   // Schopnosti uživatele, které jsou nutné pro přístup
            'novy-plugin', // Slug stránky
            'novy_plugin_main_page_content', // Callback funkce
            plugin_dir_url(__FILE__) . '../assets/icons/wpt-translate.png', // Icon URL
            1 // Pozice v menu
        );

        // Statistiky pluginu
        add_submenu_page(
            'novy-plugin', // Slug hlavní stránky
            'Statistiky pluginu',  // Název stránky
            'Statistiky pluginu',  // Název v menu
            'manage_options',      // Schopnosti uživatele, které jsou nutné pro přístup
            'plugin-statistics',  // Slug stránky
            'novy_plugin_plugin_statistics_page', // Callback funkce
            2 // Pozice v menu (hned po hlavní stránce)
        );

        // Přeložené pluginy
        add_submenu_page(
            'novy-plugin', // Slug hlavní stránky
            'Přeložené pluginy', // Název stránky
            'Přeložené pluginy', // Název v menu
            'manage_options',    // Schopnosti uživatele, které jsou nutné pro přístup
            'translated-plugins', // Slug stránky
            'novy_plugin_translated_plugins_page', // Callback funkce
            3 // Pozice v menu
        );

        // Přeložené šablony
        add_submenu_page(
            'novy-plugin', // Slug hlavní stránky
            'Přeložené šablony', // Název stránky
            'Přeložené šablony', // Název v menu
            'manage_options',    // Schopnosti uživatele, které jsou nutné pro přístup
            'translated-themes', // Slug stránky
            'novy_plugin_translated_themes_page', // Callback funkce
            4 // Pozice v menu
        );

        // Kontakt
        add_submenu_page(
            'novy-plugin',  // Slug hlavní stránky
            'Kontakt',           // Název stránky
            'Kontakt',           // Název v menu
            'manage_options',    // Schopnosti uživatele, které jsou nutné pro přístup
            'novy-plugin-contact',    // Slug stránky
            'novy_plugin_display_contact', // Callback funkce
            99 // Pozice v menu
        );

        // Stáhnout/Požádat o plugin
        add_submenu_page(
            'novy-plugin',  // Slug hlavní stránky
            'Stáhnout/Požádat o plugin',  // Název stránky
            'Stáhnout/Požádat o plugin',  // Název v menu
            'manage_options',    // Schopnosti uživatele, které jsou nutné pro přístup
            'download-request-plugin',  // Slug stránky
            'novy_plugin_download_request_page', // Callback funkce
            5 // Pozice v menu
        );
    }

    add_action('admin_menu', 'novy_plugin_add_admin_menu');
}

/**
 * Enqueue the CSS styles for the admin menu
 */
if (!function_exists('novy_plugin_enqueue_admin_styles')) {
    function novy_plugin_enqueue_admin_styles() {
        // Načtení externího CSS souboru pro stylování menu
        wp_enqueue_style('novy-plugin-admin-menu-styles', plugin_dir_url(__FILE__) . '../admin/css/style-wpt-menu.css', array(), '1.0', 'all');

        // Přidání vlastního CSS pro skrytí submenu položek a červený text pro hlavní menu
        echo '
        <style>
            #toplevel_page_novy-plugin .wp-submenu li {
                display: none; /* Skryje všechny položky submenu */
            }

            #toplevel_page_novy-plugin .wp-first-item {
                display: block; /* Zajistí, že první položka (hlavní menu) zůstane viditelná */
            }

            #toplevel_page_novy-plugin .wp-menu-name {
                color: red !important; /* Nastaví červený text pro hlavní menu */
            }
        </style>
        ';
    }

    add_action('admin_enqueue_scripts', 'novy_plugin_enqueue_admin_styles');
}

// Callback funkce pro obsah hlavní stránky
if (!function_exists('novy_plugin_main_page_content')) {
    function novy_plugin_main_page_content() {
        include(plugin_dir_path(__FILE__) . '../admin/pages/main-page.php');
    }
}

// Callback funkce pro obsah stránky Statistiky pluginu
if (!function_exists('novy_plugin_plugin_statistics_page')) {
    function novy_plugin_plugin_statistics_page() {
        include(plugin_dir_path(__FILE__) . '../admin/pages/plugin-statistics.php');
    }
}

// Callback funkce pro obsah stránky Kontakt
if (!function_exists('novy_plugin_display_contact')) {
    function novy_plugin_display_contact() {
        include(plugin_dir_path(__FILE__) . '../admin/pages/contact.php');
    }
}

// Callback funkce pro obsah stránky Přeložené pluginy
if (!function_exists('novy_plugin_translated_plugins_page')) {
    function novy_plugin_translated_plugins_page() {
        include(plugin_dir_path(__FILE__) . '../admin/pages/translated-plugins.php');
    }
}

// Callback funkce pro obsah stránky Přeložené šablony
if (!function_exists('novy_plugin_translated_themes_page')) {
    function novy_plugin_translated_themes_page() {
        include(plugin_dir_path(__FILE__) . '../admin/pages/translated-themes.php');
    }
}

// Callback funkce pro obsah stránky Stáhnout/Požádat o plugin
if (!function_exists('novy_plugin_download_request_page')) {
    function novy_plugin_download_request_page() {
        include(plugin_dir_path(__FILE__) . '../admin/pages/download-request-plugin.php');
    }
}

?>
