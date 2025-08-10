<?php
namespace NotAnotherTheme\Shortcodes;

add_action('init', function () {

  // [nat_year]
  add_shortcode('nat_year', fn() => esc_html( wp_date('Y') ));

  // [nat_date format="Y-m-d"]
  add_shortcode('nat_date', function ($atts = []) {
    $a = shortcode_atts(['format' => 'Y-m-d'], $atts, 'nat_date');
    return esc_html( wp_date($a['format']) );
  });

  // [nat_site_title] / [nat_site_tagline]
  add_shortcode('nat_site_title', fn() => esc_html( get_bloginfo('name') ));
  add_shortcode('nat_site_tagline', fn() => esc_html( get_bloginfo('description') ));

  // URLs
  add_shortcode('nat_home_url',  fn() => esc_url( home_url('/') ));
  add_shortcode('nat_site_url',  fn() => esc_url( site_url() ));
  add_shortcode('nat_admin_url', function ($atts = []) {
    $a = shortcode_atts(['path' => ''], $atts, 'nat_admin_url');
    return esc_url( admin_url( ltrim((string)$a['path'],'/') ) );
  });
  add_shortcode('nat_login_url', function ($atts = []) {
    $a = shortcode_atts(['redirect' => ''], $atts, 'nat_login_url');
    $r = $a['redirect'] ? wp_sanitize_redirect($a['redirect']) : '';
    return esc_url( wp_login_url($r) );
  });
  add_shortcode('nat_logout_url', function ($atts = []) {
    $a = shortcode_atts(['redirect' => ''], $atts, 'nat_logout_url');
    $r = $a['redirect'] ? wp_sanitize_redirect($a['redirect']) : '';
    return esc_url( wp_logout_url($r) );
  });

  // Theme version
  add_shortcode('nat_theme_version', function () {
    return esc_html( (string) wp_get_theme()->get('Version') );
  });

  // Site link
  add_shortcode('nat_site_link', function ($atts = []) {
    $a = shortcode_atts(['text' => ''], $atts, 'nat_site_link');
    $text = $a['text'] !== '' ? $a['text'] : get_bloginfo('name');
    return sprintf('<a href="%s">%s</a>', esc_url(home_url('/')), esc_html($text));
  });

  // Copyright
  add_shortcode('nat_copyright', function ($atts = []) {
    $a = shortcode_atts(['prefix' => '©', 'sep' => ' ', 'suffix' => ''], $atts, 'nat_copyright');
    $out = trim($a['prefix']) . $a['sep'] . wp_date('Y') . $a['sep'] . get_bloginfo('name');
    if ($a['suffix']) $out .= $a['sep'] . $a['suffix'];
    return esc_html($out);
  });

  // Current user
  add_shortcode('nat_user', function ($atts = []) {
    $a = shortcode_atts(['field' => 'display_name'], $atts, 'nat_user');
    $u = wp_get_current_user();
    if (!$u || 0 === $u->ID) return '';
    $field = (string) $a['field'];
    $val = isset($u->$field) ? $u->$field : '';
    return esc_html((string)$val);
  });

  // Conditionals
  add_shortcode('nat_if_logged_in', function ($atts = [], $content = '') {
    return is_user_logged_in() ? do_shortcode($content) : '';
  });
  add_shortcode('nat_if_guest', function ($atts = [], $content = '') {
    return ! is_user_logged_in() ? do_shortcode($content) : '';
  });

});
