<?php
namespace NotAnotherTheme\Dev;

if ( ! defined('ABSPATH') ) { exit; }

function nat_dev_active(): bool {
  return defined('WP_ENV') && WP_ENV === 'development';
}

/** ---------- Visual proofs it's loaded ---------- **/
add_action('wp_footer', function () {
  if ( ! nat_dev_active() ) return;
  echo "\n<!-- NAT DEV active: dev.php loaded -->\n";
  echo '<div style="
    position: fixed; right: 10px; bottom: 10px; z-index: 999999;
    background:#111; color:#0f0; font:12px/1.2 system-ui, sans-serif;
    padding:6px 8px; border-radius:6px; opacity:.9;
    box-shadow:0 2px 8px rgba(0,0,0,.35)
  ">NAT DEV</div>';
}, 999);

add_action('admin_notices', function () {
  if ( ! nat_dev_active() ) return;
  echo '<div class="notice notice-info" style="border-left-color:#0f0"><p><strong>NAT DEV:</strong> development mode is active.</p></div>';
});

/** ---------- Force toolbar ON in dev (just in case) ---------- **/
add_filter('show_admin_bar', function ($show) {
  return nat_dev_active() ? true : $show;
}, 99);

/** ---------- Admin bar menu (best-effort; page below is the real UI) ---------- **/
add_action('admin_bar_menu', function ($bar) {
  if ( ! nat_dev_active() ) return;
  if ( ! is_admin_bar_showing() ) return;

  $bar->add_node([
    'id'    => 'nat-dev',
    'title' => 'NAT Dev',
    'href'  => admin_url('themes.php?page=nat-dev-tools'),
  ]);

  $theme  = wp_get_theme();
  $parent = $theme->parent() ? ' ← child of ' . $theme->parent()->get('Name') : '';
  $bar->add_node([
    'id'     => 'nat-dev-theme',
    'parent' => 'nat-dev',
    'title'  => sprintf('Theme: %s v%s%s',
      esc_html($theme->get('Name')),
      esc_html($theme->get('Version')),
      esc_html($parent)
    ),
    'href'   => admin_url('themes.php')
  ]);

  $req     = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/';
  $current = home_url( add_query_arg([], $req) );

  $bar->add_node([
    'id'     => 'nat-dev-flush',
    'parent' => 'nat-dev',
    'title'  => 'Flush Object Cache',
    'href'   => add_query_arg('nat_flush_cache', '1', $current)
  ]);

  $bar->add_node([
    'id'     => 'nat-dev-reset-parts',
    'parent' => 'nat-dev',
    'title'  => 'Reset Header/Footer Overrides',
    'href'   => add_query_arg('nat_reset_parts', '1', $current)
  ]);
}, PHP_INT_MAX);

/** ---------- Query-string actions (work even without any UI) ---------- **/
add_action('init', function () {
  if ( ! nat_dev_active() ) return;
  if ( ! is_user_logged_in() ) return;

  if ( isset($_GET['nat_flush_cache']) ) {
    wp_cache_flush();
    if ( ! headers_sent() ) {
      wp_safe_redirect( remove_query_arg('nat_flush_cache') );
      exit;
    }
  }

  if ( isset($_GET['nat_reset_parts']) ) {
    foreach ( ['header', 'footer'] as $slug ) {
      $post = get_page_by_path($slug, OBJECT, 'wp_template_part');
      if ( $post instanceof \WP_Post ) {
        wp_delete_post($post->ID, true);
      }
    }
    if ( ! headers_sent() ) {
      wp_safe_redirect( remove_query_arg('nat_reset_parts') );
      exit;
    }
  }
});

/** ---------- Appearance → Theme Dev Tools page (rock-solid UI) ---------- **/
add_action('admin_menu', function () {
  if ( ! nat_dev_active() ) return;
  add_theme_page(
    __('Theme Dev Tools','notanothertheme'),
    __('Theme Dev Tools','notanothertheme'),
    'manage_options',
    'nat-dev-tools',
    __NAMESPACE__ . '\\render_dev_tools_page'
  );
});

function render_dev_tools_page() {
  if ( ! current_user_can('manage_options') ) wp_die('Nope.');
  ?>
  <div class="wrap">
    <h1>Theme Dev Tools</h1>
    <p>Environment: <code><?php echo esc_html( defined('WP_ENV') ? WP_ENV : 'not set' ); ?></code></p>

    <form method="post" style="margin-top:1rem;">
      <?php wp_nonce_field('nat_dev_tools'); ?>
      <button class="button button-primary" name="nat_action" value="flush">Flush Object Cache</button>
      <button class="button" name="nat_action" value="reset_parts">Reset Header/Footer Overrides</button>
    </form>

    <?php if ( isset($_GET['nat_msg']) ): ?>
      <div class="notice notice-success is-dismissible" style="margin-top:1rem;">
        <p><?php echo esc_html( wp_unslash($_GET['nat_msg']) ); ?></p>
      </div>
    <?php endif; ?>
  </div>
  <?php
}

add_action('admin_init', function () {
  if ( ! nat_dev_active() ) return;
  if ( ! is_admin() ) return;
  if ( ! isset($_POST['nat_action']) ) return;
  if ( ! current_user_can('manage_options') ) wp_die('Nope.');
  check_admin_referer('nat_dev_tools');

  $msg = '';
  if ( $_POST['nat_action'] === 'flush' ) {
    wp_cache_flush();
    $msg = 'Object cache flushed.';
  } elseif ( $_POST['nat_action'] === 'reset_parts' ) {
    foreach ( ['header', 'footer'] as $slug ) {
      $post = get_page_by_path($slug, OBJECT, 'wp_template_part');
      if ( $post instanceof \WP_Post ) wp_delete_post($post->ID, true);
    }
    $msg = 'Header/Footer overrides reset.';
  }

  wp_safe_redirect( add_query_arg('nat_msg', rawurlencode($msg), admin_url('themes.php?page=nat-dev-tools')) );
  exit;
});
