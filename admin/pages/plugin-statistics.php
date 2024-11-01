<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>WPressTech translate</title>
    <link rel="stylesheet" href="<?php echo WC_CZ_PLUGIN_URL . 'admin/css/header-footer.css'; ?>">
	<link rel="stylesheet" href="<?php echo WC_CZ_PLUGIN_URL . 'admin/css/site-content.css'; ?>">
</head>
<body>
    <?php include 'plugin-translate-header.php'; ?>
    <div class="wptech_translate-wrap">
        <div class="wptech_translate-dashboard">
            <div class="wptech_translate-dashboard-header">
                <div class="wptech_translate-header-greeting">
                    <?php
                    // Načtení aktuálního přihlášeného uživatele
                    $current_user = wp_get_current_user();
                    ?>
                    <h2>Ahoj <?php echo esc_html($current_user->display_name); ?></h2>
                </div>
            </div>
            <div class="wptech_translate-dashboard-content">
                <div class="wptech_translate-content-left">
                    <div class="wptech_translate-card">
                        <?php
                        // Získání informací o nainstalovaném pluginu
                        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/wptech-translate/wptech-translate.php');
                        $installed_version = $plugin_data['Version'];

                        // Získání nejnovější verze z URL
                        $response = wp_remote_get('https://aktualizace.wpress.tech/?action=get_metadata&slug=wptech-translate');
                        if (is_array($response) && !is_wp_error($response)) {
                            $body = $response['body'];
                            $metadata = json_decode($body, true);
                            $latest_version = isset($metadata['version']) ? $metadata['version'] : 'unknown';
                        } else {
                            $latest_version = 'unknown';
                        }
                        ?>
                        <h3>Verze pluginu</h3>
                        <div class="wptech_translate-plugin-info">
                            <div>Aktuální verze pluginu je: <?php echo esc_html($latest_version); ?> ( po zveřejnění pluginu bude nastaven plugin na verzi 1.0)</div>
                        </div>
                    </div>
                    <div class="wptech_translate-card">
                        <h3>Posledních pět nových překladů pluginů</h3>
                        <div class="wptech_translate-overview-chart">
                            <?php
                            // Funkce pro načítání dat z API pro pluginy
                            function get_latest_plugins() {
                                $url = 'https://wpress.tech/wp-json/wp/v2/plugins-support?per_page=100&order=desc&orderby=date';
                                $response = wp_remote_get($url);
                                if (is_array($response) && !is_wp_error($response)) {
                                    $body = wp_remote_retrieve_body($response);
                                    $plugins = json_decode($body, true);
                                    // Filtrování položek, které mají "Zahrnuto v pluginu" nastavené na "Připravuje se", "Ne" nebo "Zrušeno"
                                    $filtered_plugins = array_filter($plugins, function($plugin) {
                                        return !isset($plugin['acf']['zahrnuto_v_pluginu']) || 
                                        !in_array('Připravuje se', $plugin['acf']['zahrnuto_v_pluginu']) &&
                                        !in_array('Ne', $plugin['acf']['zahrnuto_v_pluginu']) &&
                                        !in_array('Zrušeno', $plugin['acf']['zahrnuto_v_pluginu']);
                                    });
                                    return array_slice($filtered_plugins, 0, 5); // Vrátit pouze pět položek
                                }
                                return [];
                            }

                            // Načítání dat z API
                            $latest_plugins = get_latest_plugins();
                            foreach ($latest_plugins as $plugin) {
                                echo '<span>' . esc_html($plugin['title']['rendered']) . '</span><br>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="wptech_translate-card">
                        <h3>Posledních pět nových překladů šablon</h3>
                        <div class="wptech_translate-activity-chart">
                            <?php
                            // Funkce pro načítání dat z API pro šablony
                            function get_latest_themes() {
                                $url = 'https://wpress.tech/wp-json/wp/v2/themes-support?per_page=100&order=desc&orderby=date';
                                $response = wp_remote_get($url);
                                if (is_array($response) && !is_wp_error($response)) {
                                    $body = wp_remote_retrieve_body($response);
                                    $themes = json_decode($body, true);
                                    // Filtrování položek, které mají "Zahrnuto v pluginu" nastavené na "Připravuje se", "Ne" nebo "Zrušeno"
                                    $filtered_themes = array_filter($themes, function($theme) {
                                        return !isset($theme['acf']['zahrnuto_v_pluginu']) || 
                                        !in_array('Připravuje se', $theme['acf']['zahrnuto_v_pluginu']) &&
                                        !in_array('Ne', $theme['acf']['zahrnuto_v_pluginu']) &&
                                        !in_array('Zrušeno', $theme['acf']['zahrnuto_v_pluginu']);
                                    });
                                    return array_slice($filtered_themes, 0, 5); // Vrátit pouze pět položek
                                }
                                return [];
                            }

                            // Načítání dat z API
                            $latest_themes = get_latest_themes();
                            foreach ($latest_themes as $theme) {
                                echo '<span>' . esc_html($theme['title']['rendered']) . '</span><br>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="wptech_translate-content-right">
                    <div class="wptech_translate-card">
                        <h3>Datum</h3>
                        <div class="wptech_translate-calendar">
                            <span><?php echo date_i18n(get_option('date_format')); ?></span>
                        </div>
                    </div>
                    <div class="wptech_translate-card">
                        <h3>Budoucí podpora pluginu</h3>
                        <div class="wptech_translate-overview-chart">
                            <ul>
                                <?php
                                // Funkce pro načítání dat z API pro budoucí podporu pluginů
                                function get_upcoming_plugins() {
                                    $url = 'https://wpress.tech/wp-json/wp/v2/plugins-support?per_page=100';
                                    $response = wp_remote_get($url);
                                    if (is_array($response) && !is_wp_error($response)) {
                                        $body = wp_remote_retrieve_body($response);
                                        $data = json_decode($body, true);
                                        return array_filter($data, function($plugin) {
                                            return isset($plugin['acf']['zahrnuto_v_pluginu']) && in_array('Připravuje se', $plugin['acf']['zahrnuto_v_pluginu']);
                                        });
                                    }

                                    return [];
                                }

                                // Načítání dat pro budoucí podporu pluginů
                                $upcoming_plugins = get_upcoming_plugins();
                                foreach ($upcoming_plugins as $plugin) {
                                    echo '<li>' . esc_html($plugin['title']['rendered']) . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="wptech_translate-card">
                        <h3>Budoucí podpora šablon</h3>
                        <div class="wptech_translate-overview-chart">
                            <ul>
                                <?php
                                // Funkce pro načítání dat z API pro budoucí podporu šablon
                                function get_upcoming_themes() {
                                    $url = 'https://wpress.tech/wp-json/wp/v2/themes-support?per_page=100';
                                    $response = wp_remote_get($url);
                                    if (is_array($response) && !is_wp_error($response)) {
                                        $body = wp_remote_retrieve_body($response);
                                        $data = json_decode($body, true);
                                        return array_filter($data, function($theme) {
                                            return isset($theme['acf']['zahrnuto_v_pluginu']) && in_array('Připravuje se', $theme['acf']['zahrnuto_v_pluginu']);
                                        });
                                    }
                                    return [];
                                }

                                // Načítání dat pro budoucí podporu šablon
                                $upcoming_themes = get_upcoming_themes();
                                foreach ($upcoming_themes as $theme) {
                                    echo '<li>' . esc_html($theme['title']['rendered']) . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="wptech_translate-card">
                        <h3>Celkový počet přeložených řetězců</h3>
                        <div class="wptech_translate-overview-chart">
                            <?php
                            // Funkce pro načítání a počítání přeložených řetězců
                            function get_total_translated_strings() {
                                $total_strings = 0;

                                // Získání dat z pluginů
                                $plugin_url = 'https://wpress.tech/wp-json/wp/v2/plugins-support?per_page=100';
                                $plugin_response = wp_remote_get($plugin_url);
                                if (is_array($plugin_response) && !is_wp_error($plugin_response)) {
                                    $plugin_body = wp_remote_retrieve_body($plugin_response);
                                    $plugins = json_decode($plugin_body, true);
                                    foreach ($plugins as $plugin) {
                                        if (isset($plugin['acf']['prelozeno_cz_retezcu'])) {
                                            $total_strings += (int)$plugin['acf']['prelozeno_cz_retezcu'];
                                        }
                                    }
                                }

                                // Získání dat z šablon
                                $theme_url = 'https://wpress.tech/wp-json/wp/v2/themes-support?per_page=100';
                                $theme_response = wp_remote_get($theme_url);
                                if (is_array($theme_response) && !is_wp_error($theme_response)) {
                                    $theme_body = wp_remote_retrieve_body($theme_response);
                                    $themes = json_decode($theme_body, true);
                                    foreach ($themes as $theme) {
                                        if (isset($theme['acf']['prelozeno_cz_retezcu'])) {
                                            $total_strings += (int)$theme['acf']['prelozeno_cz_retezcu'];
                                        }
                                    }
                                }

                                return number_format($total_strings, 0, '', ' ');
                            }

                            $total_translated_strings = get_total_translated_strings();
                            echo '<span>' . esc_html($total_translated_strings) . '</span>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'plugin-translate-footer.php'; ?>
</body>
</html>
