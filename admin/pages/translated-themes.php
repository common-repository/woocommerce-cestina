<?php
// Funkce pro získání dat z REST API
function wptech_get_translated_themes($search_letter = '') {
    $url = 'https://wpress.tech/wp-json/wp/v2/themes-support?per_page=100&order=desc&orderby=date';

    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

    // Filtrace podle počátečního písmene, pokud je zadáno
    if (!empty($search_letter)) {
        $filtered_data = array_filter($data, function($theme) use ($search_letter) {
            return stripos($theme->title->rendered, $search_letter) === 0;
        });
        return $filtered_data;
    }

    return $data;
}

// Získání počátečního písmena, pokud existuje
$search_letter = isset($_GET['letter']) ? sanitize_text_field($_GET['letter']) : '';

// Získání dat
$translated_themes = wptech_get_translated_themes($search_letter);

// Získání dostupných písmen
$available_letters = array_unique(array_map(function($theme) {
    return strtoupper($theme->title->rendered[0]);
}, wptech_get_translated_themes()));
sort($available_letters);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přeložené Šablony - WPressTech translate</title>
    <link rel="stylesheet" href="<?php echo WC_CZ_PLUGIN_URL . 'admin/css/header-footer.css'; ?>">
	<link rel="stylesheet" href="<?php echo WC_CZ_PLUGIN_URL . 'admin/css/site-content.css'; ?>">
</head>
<body>
    <?php include 'plugin-translate-header.php'; ?>
    <div class="wptech_translate-wrap">
        <h1>Přeložené Šablony</h1>
        
        <!-- Abecední navigace -->
        <div class="alphabet-nav">
            <strong>Filtrovat:</strong> 
            <a href="admin.php?page=translated-themes" class="<?php echo empty($search_letter) ? 'active' : ''; ?>">Zobrazit vše</a>
            <?php foreach ($available_letters as $letter): ?>
                <a href="admin.php?page=translated-themes&letter=<?php echo esc_attr($letter); ?>" class="<?php echo $search_letter === $letter ? 'active' : ''; ?>"><?php echo esc_html($letter); ?></a>
            <?php endforeach; ?>
        </div>
        
        <ul class="theme-list">
            <?php if (!empty($translated_themes)): ?>
                <?php foreach ($translated_themes as $theme): ?>
                    <li>
                        <h2><?php echo esc_html($theme->title->rendered); ?></h2>
                        <?php 
                            // Zkontrolujeme, zda je `obrazek` objekt nebo pole a obsahuje `url`
                            if (!empty($theme->acf->obrazek)) {
                                if (is_object($theme->acf->obrazek) && !empty($theme->acf->obrazek->url)) {
                                    $image_url = $theme->acf->obrazek->url;
                                } elseif (is_array($theme->acf->obrazek) && !empty($theme->acf->obrazek['url'])) {
                                    $image_url = $theme->acf->obrazek['url'];
                                } else {
                                    $image_url = '';
                                }
                            } else {
                                $image_url = '';
                            }

                            if (!empty($image_url)): ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($theme->title->rendered); ?>">
                            <?php endif; ?>
                        <p><?php echo wp_kses_post($theme->excerpt->rendered); ?></p>
                        
                        <!-- Zobrazení jednotlivých polí podle pořadí -->
                        <?php if (!empty($theme->acf->text_domain)): ?>
                            <?php $text_domain = esc_html($theme->acf->text_domain); // Skryté načtení text_domain ?>
                        <?php endif; ?>
                        <?php if (!empty($theme->acf->podporovany_jazyk)): ?>
                            <p><strong>Podporovaný jazyk:</strong> 
                            <?php 
                                if (is_array($theme->acf->podporovany_jazyk)) {
                                    echo esc_html(implode(', ', $theme->acf->podporovany_jazyk));
                                } else {
                                    echo esc_html($theme->acf->podporovany_jazyk);
                                }
                            ?></p>
                        <?php endif; ?>
                        <?php if (!empty($theme->acf->testovano_na_verzi)): ?>
                            <p><strong>Testováno na verzi:</strong> <?php echo esc_html($theme->acf->testovano_na_verzi); ?></p>
                        <?php endif; ?>
                        <?php if (isset($theme->acf->zahrnuto_v_pluginu) && is_array($theme->acf->zahrnuto_v_pluginu)): ?>
                            <p><strong>Zahrnuto v pluginu:</strong> 
                            <?php 
                                echo esc_html(implode(', ', $theme->acf->zahrnuto_v_pluginu));
                            ?></p>
                        <?php endif; ?>
                        <?php if (!empty($theme->acf->od_verze)): ?>
                            <p><strong>Od verze:</strong> <?php echo esc_html($theme->acf->od_verze); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($theme->acf->typ_licence)): ?>
                            <p><strong>Typ licence:</strong> 
                            <?php 
                                if (is_array($theme->acf->typ_licence)) {
                                    echo esc_html(implode(', ', $theme->acf->typ_licence));
                                } else {
                                    echo esc_html($theme->acf->typ_licence);
                                }
                            ?></p>
                        <?php endif; ?>

                        <!-- Nové pole "Přidáno" -->
                        <?php if (!empty($theme->acf->pridano)): ?>
                            <p><strong>Přidáno:</strong> <?php echo esc_html($theme->acf->pridano); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($theme->acf->posledni_aktualizace)): ?>
                            <p><strong>Poslední aktualizace:</strong> <?php echo esc_html($theme->acf->posledni_aktualizace); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($theme->acf->autor_prekladu)): ?>
                            <p><strong>Autor překladu:</strong> <?php echo esc_html($theme->acf->autor_prekladu); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($theme->acf->stav_prekladu_cz)): ?>
                            <p><strong>Stav překladu CZ:</strong> <?php echo esc_html($theme->acf->stav_prekladu_cz); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($theme->acf->stav_prekladu_sk)): ?>
                            <p><strong>Stav překladu SK:</strong> <?php echo esc_html($theme->acf->stav_prekladu_sk); ?></p>
                        <?php endif; ?>

                        <!-- Nové pole "Přeloženo cz řetězců" -->
                        <?php if (!empty($theme->acf->prelozeno_cz_retezcu)): ?>
                            <p><strong>Přeloženo cz řetězců:</strong> <?php echo number_format_i18n($theme->acf->prelozeno_cz_retezcu, 0); ?></p>
                        <?php endif; ?>

                        <!-- Zobrazit další data z ACF, pokud existují -->
                        <?php if (!empty((array)$theme->acf)): ?>
                            <?php foreach ($theme->acf as $key => $value): ?>
                                <?php if (!in_array($key, ['obrazek', 'podporovany_jazyk', 'testovano_na_verzi', 'zahrnuto_v_pluginu', 'od_verze', 'typ_licence', 'posledni_aktualizace', 'autor_prekladu', 'stav_prekladu_cz', 'stav_prekladu_sk', 'text_domain', 'pridano', 'prelozeno_cz_retezcu'])): ?>
                                    <p><strong><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?>:</strong> 
                                    <?php 
                                        if (is_array($value)) {
                                            echo esc_html(implode(', ', $value));
                                        } else {
                                            echo esc_html($value);
                                        }
                                    ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Žádné přeložené šablony nebyly nalezeny.</li>
            <?php endif; ?>
        </ul>
    </div>
    <?php include 'plugin-translate-footer.php'; ?>
</body>
</html>
