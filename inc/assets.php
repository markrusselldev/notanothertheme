<?php
namespace NotAnotherTheme\Assets;

add_action('wp_enqueue_scripts', function () {
  $css = NAT_THEME_DIR . '/dist/theme.css';
  $js  = NAT_THEME_DIR . '/dist/theme.js';

  wp_enqueue_style(
    'notanothertheme-style',
    NAT_THEME_URI . '/dist/theme.css',
    [],
    file_exists($css) ? filemtime($css) : null
  );

  wp_enqueue_style(
    'shoelace-light',
    NAT_THEME_URI . '/dist/vendor/shoelace/themes/light.css',
    [],
    null
  );

  wp_enqueue_script(
    'notanothertheme-script',
    NAT_THEME_URI . '/dist/theme.js',
    [],
    file_exists($js) ? filemtime($js) : null,
    true
  );
});

// Optional: preload Shoelace theme early
add_action('wp_head', function () {
  echo '<link rel="preload" href="' . esc_url(NAT_THEME_URI . '/dist/vendor/shoelace/themes/light.css') . '" as="style">';
}, 0);
