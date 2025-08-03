<?php

// Theme setup
add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('wp-block-styles');
  add_theme_support('editor-styles');
  add_editor_style('dist/theme.css');
  add_theme_support('custom-line-height');
  add_theme_support('custom-units');
  add_theme_support('responsive-embeds');
  add_theme_support('html5', ['script', 'style']);
});

// Enqueue CSS and JS
add_action('wp_enqueue_scripts', function () {
  $theme_version = wp_get_theme()->get('Version');

  // Enqueue main stylesheet
  wp_enqueue_style(
    'notanothertheme-style',
    get_template_directory_uri() . '/dist/theme.css',
    [],
    filemtime(get_template_directory() . '/dist/theme.css')
  );

  // Enqueue Shoelace preload theme (actual file lives in dist/)
  wp_enqueue_style(
    'shoelace-theme-light',
    get_template_directory_uri() . '/dist/vendor/shoelace/themes/light.css',
    [],
    null
  );

  // Enqueue main script
  wp_enqueue_script(
    'notanothertheme-script',
    get_template_directory_uri() . '/dist/theme.js',
    [],
    filemtime(get_template_directory() . '/dist/theme.js'),
    true
  );
});

// Inject Shoelace preload early
add_action('wp_head', function () {
  echo '<link rel="preload" href="' . get_template_directory_uri() . '/dist/vendor/shoelace/themes/light.css" as="style">';
}, 0);

// Inject branded spinner early to prevent FOUC
add_action('wp_body_open', function () {
  echo <<<HTML
<sl-spinner id="boot-spinner" style="
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 48px;
  height: 48px;
  color: var(--accent, #00f0ff);
  z-index: 9999;
"></sl-spinner>
HTML;
});

// Remove spinner once Shoelace finishes loading
add_action('wp_footer', function () {
  echo <<<HTML
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const spinner = document.getElementById('boot-spinner');
    if (spinner) {
      spinner.remove();
    }
  });
</script>
HTML;
});
