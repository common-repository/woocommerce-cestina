<?php
// Definice cest
$mo_file_url = 'https://url.wpress.tech/mo-soubory/pluginy/woocommerce-cs_CZ.mo';
$mo_file_path = plugin_dir_path(__FILE__) . '../../translates/plugins/woocommerce-cs_CZ.mo';

// Funkce pro stažení a uložení .mo souboru
function download_mo_file($mo_file_url, $mo_file_path) {
    $mo_file_content = file_get_contents($mo_file_url);
    if ($mo_file_content !== false) {
        file_put_contents($mo_file_path, $mo_file_content);
    }
}

// Zpracování formuláře pro změnu stavu služby překladu
$updated = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['woocommerce_cestina_translation_enabled'])) {
        // Aktivace služby - stažení souboru
        $updated = update_option('woocommerce_cestina_translation_enabled', true);
        
        if ($updated) {
            download_mo_file($mo_file_url, $mo_file_path);
        }
    } else {
        // Deaktivace služby - smazání souboru
        $updated = update_option('woocommerce_cestina_translation_enabled', false);
        
        if ($updated && file_exists($mo_file_path)) {
            unlink($mo_file_path);
        }
    }
}

// Kontrola aktualizace souboru při aktualizaci pluginu
add_action('upgrader_process_complete', function() use ($mo_file_url, $mo_file_path) {
    download_mo_file($mo_file_url, $mo_file_path);
}, 10, 2);

// Získání aktuálního stavu služby překladu
$is_enabled = get_option('woocommerce_cestina_translation_enabled', false);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>WooCommerce čeština</title>
    <link rel="stylesheet" href="<?php echo WC_CZ_PLUGIN_URL . 'admin/css/style.css'; ?>">
</head>
<body>
    <div class="wpt_style-wrap">
        <div class="wpt_style-dashboard">
            <div class="wpt_style-dashboard-header">
                <div class="wpt_style-header-greeting">
                    <?php
                    // Načtení aktuálního přihlášeného uživatele
                    $current_user = wp_get_current_user();
                    ?>
                    <h2>Ahoj <?php echo esc_html($current_user->display_name); ?></h2>
                </div>
            </div>
            <div class="wpt_style-dashboard-content">
                <div class="wpt_style-content-left">
                    <div class="wpt_style-card">
                        <h3>Aktivovat/Deaktivovat překlady</h3>
                        <div class="wpt_style-plugin-info">
                            <?php if ($updated): ?>
                                <div class="notice notice-success is-dismissible">
                                    <p>Nastavení bylo úspěšně uloženo.</p>
                                </div>
                            <?php endif; ?>
                            <form method="post">
                                <p>Woocommerce překlad:
                                    <label class="wpt_style-switch">
                                        <input type="checkbox" name="woocommerce_cestina_translation_enabled" <?php echo $is_enabled ? 'checked' : ''; ?>>
                                        <span class="wpt_style-slider"></span>
                                    </label>
                                </p>
                                <input type="submit" value="Uložit změny" class="wpt_style-button">
                            </form>
                        </div>
                    </div>
                    <div class="wpt_style-card">
                        <?php
                        // Získání informací o nainstalovaném pluginu
                        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/woocommerce-cestina/woocommerce-cestina.php');
                        $installed_version = $plugin_data['Version'];

                        // Získání nejnovější verze z hlavního repozitáře WordPress
                        $response = wp_remote_get('https://api.wordpress.org/plugins/info/1.0/woocommerce-cestina.json');
                        if (is_array($response) && !is_wp_error($response)) {
                            $body = wp_remote_retrieve_body($response);
                            $plugin_info = json_decode($body, true);
                            $latest_version = isset($plugin_info['version']) ? $plugin_info['version'] : 'unknown';
                        } else {
                            $latest_version = 'unknown';
                        }
                        ?>
                        <h3>Verze pluginu: <?php echo esc_html($installed_version); ?></h3>
                        <div class="wpt_style-plugin-info">
                            <div>Nejnovější verze je: <?php echo esc_html($latest_version); ?></div>
                        </div>
                    </div>
                    <div class="wpt_style-card">
                        <h3>Informace o pluginu</h3>
                        <div class="wpt_style-plugin-info">
                            <?php
                            // Získání informací o pluginu z API
                            $info_response = wp_remote_get('https://wpress.tech/wp-json/wp/v2/wpt-translate-plugin?slug=woocommerce-translate');
                            if (is_array($info_response) && !is_wp_error($info_response)) {
                                $info_body = wp_remote_retrieve_body($info_response);
                                $plugin_info = json_decode($info_body, true);

                                $plugin_description = isset($plugin_info[0]['acf']['popis_pluginu']) ? $plugin_info[0]['acf']['popis_pluginu'] : 'Informace nejsou dostupné.';
                                $text_domain_prekladu = isset($plugin_info[0]['acf']['text_domain_prekladu']) ? $plugin_info[0]['acf']['text_domain_prekladu'] : 'Informace nejsou dostupné.';
                            } else {
                                $plugin_description = 'Připravujeme...';
                                $text_domain_prekladu = 'Připravujeme...';
                            }
                            ?>
                            <p><strong>Popis:</strong> <?php echo esc_html($plugin_description); ?></p>
                            <p><strong>Text domain překladu:</strong> <?php echo esc_html($text_domain_prekladu); ?></p>
                        </div>
                    </div>
                    <div class="wpt_style-card">
                        <h3>Info a podpora</h3>
                        <div class="wpt_style-plugin-info">
                            <a href="https://wordpress.org/support/plugin/woocommerce-cestina/" target="_blank" class="wpt_style-button">Podpora</a>
                            <a href="https://wpress.tech/podpora/?wpsc-section=ticket-list" target="_blank" class="wpt_style-button">Podpora na webu</a>
                            <a href="https://www.paypal.com/donate/?hosted_button_id=WSYSW77FTMHQ2" target="_blank" class="wpt_style-button">Přispěj na vývoj</a>
                        </div>
                    </div>
                </div>
                <div class="wpt_style-content-right">
                    <div class="wpt_style-card">
                        <h3>Datum a čas</h3>
                        <div class="wpt_style-calendar">
                            <span class="wpt_style-span"><?php echo date_i18n(get_option('date_format')); ?></span>
                            <span class="wpt_style-span"><?php echo date_i18n(get_option('time_format')); ?></span>
                        </div>
                    </div>
                    <div class="wpt_style-card">
                        <h3>Info o překladu</h3>
                        <div class="wpt_style-overview-chart">
                            <?php
                            // Získání informací o překladu z API
                            $translate_response = wp_remote_get('https://wpress.tech/wp-json/wp/v2/plugins-support?slug=woocommerce');
                            if (is_array($translate_response) && !is_wp_error($translate_response)) {
                                $translate_body = wp_remote_retrieve_body($translate_response);
                                $translate_info = json_decode($translate_body, true);

                                $text_domain = isset($translate_info[0]['acf']['text_domain']) ? $translate_info[0]['acf']['text_domain'] : 'Informace nejsou dostupné.';
                                $podporovany_jazyk = isset($translate_info[0]['acf']['podporovany_jazyk']) ? $translate_info[0]['acf']['podporovany_jazyk'] : 'Informace nejsou dostupné.';
                                $testovano_na_verzi = isset($translate_info[0]['acf']['testovano_na_verzi']) ? $translate_info[0]['acf']['testovano_na_verzi'] : 'Informace nejsou dostupné.';
                                $posledni_aktualizace = isset($translate_info[0]['acf']['posledni_aktualizace']) ? $translate_info[0]['acf']['posledni_aktualizace'] : 'Informace nejsou dostupné.';
                                $autor_prekladu = isset($translate_info[0]['acf']['autor_prekladu']) ? $translate_info[0]['acf']['autor_prekladu'] : 'Informace nejsou dostupné.';
                                $stav_prekladu_cz = isset($translate_info[0]['acf']['stav_prekladu_cz']) ? $translate_info[0]['acf']['stav_prekladu_cz'] : 'Informace nejsou dostupné.';
                                $prelozeno_cz_retezcu = isset($translate_info[0]['acf']['prelozeno_cz_retezcu']) ? $translate_info[0]['acf']['prelozeno_cz_retezcu'] : 'Informace nejsou dostupné.';
                            } else {
                                $text_domain = 'Připravujeme...';
                                $podporovany_jazyk = 'Připravujeme...';
                                $testovano_na_verzi = 'Připravujeme...';
                                $posledni_aktualizace = 'Připravujeme...';
                                $autor_prekladu = 'Připravujeme...';
                                $stav_prekladu_cz = 'Připravujeme...';
                                $prelozeno_cz_retezcu = 'Připravujeme...';
                            }

                            // Kontrola, zda je podporovaný jazyk pole, a jeho převod na řetězec
                            if (is_array($podporovany_jazyk)) {
                                $podporovany_jazyk = implode(', ', $podporovany_jazyk);
                            }
                            ?>
                            <p><strong>Text domain:</strong> <?php echo esc_html($text_domain); ?></p>
                            <p><strong>Podporovaný jazyk:</strong> <?php echo esc_html($podporovany_jazyk); ?></p>
                            <p><strong>Testováno na verzi:</strong> <?php echo esc_html($testovano_na_verzi); ?></p>
                            <p><strong>Poslední aktualizace:</strong> <?php echo esc_html($posledni_aktualizace); ?></p>
                            <p><strong>Autor překladu:</strong> <?php echo esc_html($autor_prekladu); ?></p>
                            <p><strong>Stav překladu (CZ):</strong> <?php echo esc_html($stav_prekladu_cz); ?></p>
                            <p><strong>Přeloženo CZ řetězců:</strong> <?php echo esc_html($prelozeno_cz_retezcu); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
