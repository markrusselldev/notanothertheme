<?php
namespace NotAnotherTheme\Setup;

add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('wp-block-styles');
  add_theme_support('editor-styles');
  add_editor_style('dist/theme.css');
  add_theme_support('custom-line-height');
  add_theme_support('custom-units');
  add_theme_support('responsive-embeds');
  add_theme_support('html5', ['script','style']);

  register_nav_menus([
    'primary' => __('Primary Menu', 'notanothertheme'),
  ]);
});

// Lock template & template‑part editing (keep file-driven header/footer)
add_filter('block_editor_settings_all', function ($s) {
  $s['canEditTemplate'] = false;
  $s['canEditTemplatePart'] = false;
  return $s;
});
