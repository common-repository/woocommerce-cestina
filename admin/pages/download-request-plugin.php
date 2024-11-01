<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>WPressTech translate</title>
    <link rel="stylesheet" href="<?php echo WC_CZ_PLUGIN_URL . 'admin/css/header-footer.css'; ?>">
</head>
<body>
    <?php include 'plugin-translate-header.php'; ?>
    <div class="wptech_translate-wrap" style="text-align: center; padding: 50px;">
        <?php
        // Načtení URL aktuálního webu a odstranění www. a koncových lomítek pro konzistentní porovnání
        $current_site_url = rtrim(preg_replace('/^www\./', '', get_site_url()), '/');

        // Funkce pro kontrolu VIP statusu
        function is_vip_site($site_url) {
            $response = wp_remote_get('https://wpress.tech/wp-json/wp/v2/vip-sites?per_page=100&order=desc&orderby=date');

            if (is_wp_error($response)) {
                return false; // Pokud dojde k chybě při volání API, považujeme web za ne VIP
            }

            $body = wp_remote_retrieve_body($response);
            $vip_sites = json_decode($body);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return false; // Pokud je chyba při dekódování JSON, považujeme web za ne VIP
            }

            // Kontrola, zda se URL aktuálního webu shoduje s URL v API
            foreach ($vip_sites as $site) {
                if (isset($site->acf->url_adresa)) {
                    // Odstranění www. a koncových lomítek z URL v API pro konzistentní porovnání
                    $api_site_url = rtrim(preg_replace('/^www\./', '', $site->acf->url_adresa), '/');
                    if ($api_site_url === $site_url) {
                        return true; // Pokud je nalezena shoda, web je VIP
                    }
                }
            }

            return false; // Pokud není nalezena shoda, web není VIP
        }

        // Kontrola, zda je web ve VIP seznamu
        if (is_vip_site($current_site_url)) {
            // Obsah pro VIP weby
            echo '<h2>Stáhnout WPTrans Plugin</h2>';
            echo '<p>Pro váš web je dostupný WPTrans Plugin ke stažení.</p>';
            echo '<a href="#" style="padding: 15px 30px; background-color: #005a9c; color: white; text-decoration: none; font-size: 18px; border-radius: 5px; display: inline-block; margin-top: 20px;">Stáhnout Plugin</a>';
        } else {
            // Původní obsah pro ne VIP weby
            echo '<h2>Žádost o stažení WPTrans Plugin</h2>';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Načtení aktuálního přihlášeného uživatele
                $current_user = wp_get_current_user();
                $user_name = $current_user->display_name;
                $user_email = $current_user->user_email;

                // Nastavení e-mailu
                $to = 'info@wpress.tech';
                $subject = 'Žádost o WPTrans Plugin';
                $message = "Uživatel \"$user_name\" žádá o stažení WPTrans Plugin pro webové stránky.\n\n";
                $message .= "Webová stránka: $current_site_url\n";
                $message .= "Email: $user_email";
                $headers = "From: $user_name <$user_email>";

                // Odeslání e-mailu
                if (wp_mail($to, $subject, $message, $headers)) {
                    echo '<p style="color: green;">Žádost byla úspěšně odeslána.</p>';
                } else {
                    echo '<p style="color: red;">Nastala chyba při odesílání e-mailu. Zkontrolujte prosím nastavení serveru a zkuste to znovu.</p>';
                }
            }

            echo '<form method="post" action="">';
            echo '<button type="submit" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Odeslat žádost</button>';
            echo '</form>';
        }
        ?>
    </div>
    <?php include 'plugin-translate-footer.php'; ?>
</body>
</html>
