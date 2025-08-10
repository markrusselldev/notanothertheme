<?php
namespace NotAnotherTheme\Admin;

add_action('admin_menu', function () {
  remove_submenu_page('themes.php', 'nav-menus.php'); // classic menus
  remove_submenu_page('themes.php', 'widgets.php');
  remove_submenu_page('themes.php', 'customize.php');
  remove_submenu_page('themes.php', 'theme-editor.php');
}, 999);

add_action('load-nav-menus.php', function () {
  wp_safe_redirect( admin_url('site-editor.php?path=/navigation'), 301 );
  exit;
});
