<?php
/**
 * WordPress custom install script.
 */

/**
 * Installs the site.
 *
 * Runs the required functions to set up and populate the database,
 * including primary admin user and initial options.
 *
 * @since 2.1.0
 *
 * @param string $blog_title    Blog title.
 * @param string $user_name     User's username.
 * @param string $user_email    User's email.
 * @param bool   $public        Whether blog is public.
 * @param string $deprecated    Optional. Not used.
 * @param string $user_password Optional. User's chosen password. Default empty (random password).
 * @param string $language      Optional. Language chosen. Default empty.
 * @return array Array keys 'url', 'user_id', 'password', and 'password_message'.
 */
function wp_install( $blog_title, $user_name, $user_email, $public, $deprecated = '', $user_password = '', $language = '' ) {
    if ( !empty( $deprecated ) )
        _deprecated_argument( __FUNCTION__, '2.6' );

    wp_check_mysql_version();
    wp_cache_flush();
    make_db_current_silent();
    populate_options();
    populate_roles();


    /*
     * Fix problem with installation of lazyest-gallery, which fails if this option is not yet set
     */
    update_option('lazyest-gallery', []);


    /* General settings */

    update_option('blogname', $blog_title);
    update_option('admin_email', $user_email);
    update_option('blog_public', $public);

    $guessurl = wp_guess_url();

    update_option('siteurl', $guessurl);

    // If not a public blog, don't ping.
    if ( ! $public ) update_option('default_pingback_flag', 0);

    if ( $language ) {
        update_option( 'WPLANG', $language );
    } else {
        update_option( 'WPLANG', 'de_DE' );
    }


    /*
    * Create default user. If the user already exists, the user tables are
    * being shared among blogs. Just set the role in that case.
    */
    $user_id = username_exists($user_name);
    $user_password = trim($user_password);
    $email_password = false;
    if ( !$user_id && empty($user_password) ) {
        $user_password = wp_generate_password( 12, false );
        $message = __('<strong><em>Note that password</em></strong> carefully! It is a <em>random</em> password that was generated just for you.');
        $user_id = wp_create_user($user_name, $user_password, $user_email);
        update_user_option($user_id, 'default_password_nag', true, true);
        $email_password = true;
    } elseif ( ! $user_id ) {
        // Password has been provided
        $message = '<em>'.__('Your chosen password.').'</em>';
        $user_id = wp_create_user($user_name, $user_password, $user_email);
    } else {
        $message = __('User already exists. Password inherited.');
    }

    $user = new WP_User($user_id);
    $user->set_role('administrator');

    wp_install_defaults($user_id);

    wp_install_maybe_enable_pretty_permalinks();

    flush_rewrite_rules();

    wp_new_blog_notification($blog_title, $guessurl, $user_id, ($email_password ? $user_password : __('The password you chose during the install.') ) );

    wp_cache_flush();

    /**
    * Fires after a site is fully installed.
    *
    * @since 3.9.0
    *
    * @param WP_User $user The site owner.
    */
    do_action( 'wp_install', $user );

    return array('url' => $guessurl, 'user_id' => $user_id, 'password' => $user_password, 'password_message' => $message);
}

/**
 * Creates the initial content for a newly-installed site.
 *
 * Adds the default "Uncategorized" category, the first post (with comment),
 * first page, and default widgets for default theme for the current version.
 *
 * @since 2.1.0
 *
 * @param int $user_id User ID.
 */
function wp_install_defaults( $user_id ) {
  global $wpdb, $wp_rewrite, $current_site, $table_prefix;

  update_option( 'template', 'musikcorps-2017' );
  update_option( 'stylesheet', 'musikcorps-2017' );

  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', '1' );

  /** @see wp-admin/options-general.php */

  update_option( 'timezone_string', 'Europe/Berlin' );
  update_option( 'date_format', 'j. F Y' );
  update_option( 'time_format', 'G:i' );

  /** @see wp-admin/options-discussion.php */

  /** Before a comment appears a comment must be manually approved: true */
  update_option( 'comment_moderation', 1 );

  /** Before a comment appears the comment author must have a previously approved comment: false */
  update_option( 'comment_whitelist', 0 );

  /** Allow people to post comments on new articles (this setting may be overridden for individual articles): false */
  update_option( 'default_comment_status', 0 );

  /** Allow link notifications from other blogs: false */
  update_option( 'default_ping_status', 0 );

  /** Attempt to notify any blogs linked to from the article: false */
  update_option( 'default_pingback_flag', 0 );

  /** @see wp-admin/options-media.php */

  /** @see wp-admin/options-permalink.php */

  /** Permalink custom structure: /%postname% */
  update_option( 'permalink_structure', '/%postname%/' );


  // Default category
  /*$cat_name = __('Unkategorisiert');
  $cat_slug = sanitize_title(_x('Unkategorisiert', 'Standardkategorie'));

  if ( global_terms_enabled() ) {
    $cat_id = $wpdb->get_var( $wpdb->prepare( "SELECT cat_ID FROM {$wpdb->sitecategories} WHERE category_nicename = %s", $cat_slug ) );
    if ( $cat_id == null ) {
      $wpdb->insert( $wpdb->sitecategories, array('cat_ID' => 0, 'cat_name' => $cat_name, 'category_nicename' => $cat_slug, 'last_updated' => current_time('mysql', true)) );
      $cat_id = $wpdb->insert_id;
    }
    update_option('default_category', $cat_id);
  } else {
    $cat_id = 1;
  }

  $wpdb->insert( $wpdb->terms, array('term_id' => $cat_id, 'name' => $cat_name, 'slug' => $cat_slug, 'term_group' => 0) );
  $wpdb->insert( $wpdb->term_taxonomy, array('term_id' => $cat_id, 'taxonomy' => 'category', 'description' => '', 'parent' => 0, 'count' => 1));
  $cat_tt_id = $wpdb->insert_id;

  // First post
  */$now = current_time( 'mysql' );
  $now_gmt = current_time( 'mysql', 1 );/*
  $first_post_guid = get_option( 'home' ) . '/?p=1';

  $first_post = get_site_option( 'first_post' );

  if ( ! $first_post ) {
    $first_post = __( 'Dies ist der erste Blogbeitrag. Du kannst ihn bearbeiten oder löschen.' );
  }

  $wpdb->insert( $wpdb->posts, array(
    'post_author' => $user_id,
    'post_date' => $now,
    'post_date_gmt' => $now_gmt,
    'post_content' => $first_post,
    'post_excerpt' => '',
    'post_title' => __('Hallo Welt!'),
    'post_name' => sanitize_title( _x('hallo-welt', 'Hallo Welt Slug') ),
    'post_modified' => $now,
    'post_modified_gmt' => $now_gmt,
    'guid' => $first_post_guid,
    'comment_count' => 1,
    'to_ping' => '',
    'pinged' => '',
    'post_content_filtered' => ''
  ));
  $wpdb->insert( $wpdb->term_relationships, array('term_taxonomy_id' => $cat_tt_id, 'object_id' => 1) );*/

  // First Page
  $first_page = sprintf( __( "Dies ist eine Beispielseite."), admin_url() );
  $first_post_guid = get_option('home') . '/?page_id=2';
  $wpdb->insert( $wpdb->posts, array(
    'post_author' => $user_id,
    'post_date' => $now,
    'post_date_gmt' => $now_gmt,
    'post_content' => $first_page,
    'post_excerpt' => '',
    'comment_status' => 'closed',
    'post_title' => __( 'Beispielseite' ),
    'post_name' => __( 'beispielseite' ),
    'post_modified' => $now,
    'post_modified_gmt' => $now_gmt,
    'guid' => $first_post_guid,
    'post_type' => 'page',
    'to_ping' => '',
    'pinged' => '',
    'post_content_filtered' => ''
  ));
  $wpdb->insert( $wpdb->postmeta, array( 'post_id' => 2, 'meta_key' => '_wp_page_template', 'meta_value' => 'default' ) );

  // Set up default widgets for default theme.
  update_option( 'widget_search', array ( 2 => array ( 'title' => '' ), '_multiwidget' => 1 ) );
  update_option( 'widget_meta', array ( 2 => array ( 'title' => '' ), '_multiwidget' => 1 ) );
  update_option( 'sidebars_widgets', array ( 'wp_inactive_widgets' => array (), 'sidebar-1' => array ( 0 => 'search-2', 5 => 'meta-2', ), 'array_version' => 3 ) );

  if ( ! is_multisite() )
    update_user_meta( $user_id, 'show_welcome_panel', 1 );
  elseif ( ! is_super_admin( $user_id ) && ! metadata_exists( 'user', $user_id, 'show_welcome_panel' ) )
    update_user_meta( $user_id, 'show_welcome_panel', 2 );

  if ( is_multisite() ) {
    // Flush rules to pick up the new page.
    $wp_rewrite->init();
    $wp_rewrite->flush_rules();

    $user = new WP_User($user_id);
    $wpdb->update( $wpdb->options, array('option_value' => $user->user_email), array('option_name' => 'admin_email') );

    // Remove all perms except for the login user.
    $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id != %d AND meta_key = %s", $user_id, $table_prefix.'user_level') );
    $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id != %d AND meta_key = %s", $user_id, $table_prefix.'capabilities') );

    if ( !is_super_admin( $user_id ) && $user_id != 1 )
      $wpdb->delete( $wpdb->usermeta, array( 'user_id' => $user_id , 'meta_key' => $wpdb->base_prefix.'1_capabilities' ) );
  }

  /** @see wp-admin/includes/screen.php */

  /** Show welcome panel: false */
  update_user_meta( $user_id, 'show_welcome_panel', 0 );

  /** @see wp-includes/user.php */

  /** Activate some plugins automatically if they exists */
  wp_install_activate_plugins();
}

/*
 * Helper which activates some useful plugins
 */
function wp_install_activate_plugins() {

  // Get the list of all installed plugins
  $all_plugins = get_plugins();

  // Auto activate these plugins on install
  // You can override default ones using WP_AUTO_ACTIVATE_PLUGINS in your wp-config.php
  if (defined('WP_AUTO_ACTIVATE_PLUGINS')) {
    $plugins = explode(',',WP_AUTO_ACTIVATE_PLUGINS);
  } else {
    $plugins = array();
  }

  // Activate plugins if they can be found from installed plugins
  foreach ($all_plugins as $plugin_path => $data) {
    $plugin_name = explode('/',$plugin_path)[0]; // get the folder name from plugin
    if (in_array($plugin_name,$plugins)) { // If plugin is installed activate it
      // Do the activation
      include_once(WP_PLUGIN_DIR . '/' . $plugin_path);
      do_action('activate_plugin', $plugin_path);
      do_action('activate_' . $plugin_path);
      $current[] = $plugin_path;
      sort($current);
      update_option('active_plugins', $current);
      do_action('activated_plugin', $plugin_path);
    }
  }
}

/**
 * Notifies the site admin that the setup is complete.
 * We don't want to send any emails about this
 *
 *
 * @since 2.1.0
 *
 * @param string $blog_title Blog title.
 * @param string $blog_url   Blog url.
 * @param int    $user_id    User ID.
 * @param string $password   User's Password.
 */
function wp_new_blog_notification($blog_title, $blog_url, $user_id, $password) {
  // Do nothing please, we don't need this spam
}
