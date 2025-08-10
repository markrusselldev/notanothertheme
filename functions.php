<?php
// Bootstrap: constants + includes
define('NAT_THEME_DIR', get_template_directory());
define('NAT_THEME_URI', get_template_directory_uri());

require_once NAT_THEME_DIR . '/inc/setup.php';
require_once NAT_THEME_DIR . '/inc/assets.php';
require_once NAT_THEME_DIR . '/inc/shortcodes.php';
require_once NAT_THEME_DIR . '/inc/admin.php';

// Dev helpers (only in development)
if ( defined('WP_ENV') && WP_ENV === 'development' ) {
  require_once NAT_THEME_DIR . '/inc/dev.php';
}
