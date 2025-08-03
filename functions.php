<?php
/**
 * Enqueue theme assets
 */
function notanothertheme_enqueue_assets() {
    $theme_uri = get_template_directory_uri();

    wp_enqueue_style(
        'notanothertheme-style',
        $theme_uri . '/dist/theme.css',
        array(),
        filemtime(get_template_directory() . '/dist/theme.css')
    );

    wp_enqueue_script(
        'notanothertheme-scripts',
        $theme_uri . '/dist/theme.js',
        array(),
        filemtime(get_template_directory() . '/dist/theme.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'notanothertheme_enqueue_assets');
