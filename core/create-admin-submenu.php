<?php
// Funkce pro vytvoření submenu v admin panelu
function woocommerce_cestina_add_admin_submenu() {
    add_submenu_page(
        'options-general.php',            // Parent slug - pod záložkou Nastavení
        'WooCommerce Translate',          // Page title
        'WooCommerce Translate',          // Menu title
        'manage_options',                 // Capability
        'woocommerce-cestina-translate',  // Menu slug
        'woocommerce_cestina_submenu_page' // Callback function
    );
}
add_action('admin_menu', 'woocommerce_cestina_add_admin_submenu');

// Funkce pro zobrazení obsahu stránky
function woocommerce_cestina_submenu_page() {
    // Zahrnout soubor s obsahem stránky
    include plugin_dir_path(__FILE__) . '../admin/pages/plugin-info-setup.php';
}
