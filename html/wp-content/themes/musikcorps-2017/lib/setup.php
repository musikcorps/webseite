<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
    // Enable plugins to manage the document title
    // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
    add_theme_support('title-tag');

    // Register wp_nav_menu() menus
    // http://codex.wordpress.org/Function_Reference/register_nav_menus
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage')
    ]);

    // Enable HTML5 markup support
    // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
    add_theme_support('html5', ['caption', 'gallery', 'search-form']);

    // Use main stylesheet for visual editor
    // To add custom styles edit /assets/styles/layouts/_tinymce.scss
    add_editor_style(Assets\asset_path('styles/main.css'));

    // Allow for uploading a custom header background image
    add_theme_support('custom-header', [
        'uploads' => true,
        'default-image' => Assets\asset_path('images/front-page-background.jpg'),
        'header-text' => false,
        'width'       => 1920,
        'height'      => 950,
        'flex-height' => true,
    ]);
    $header_images = array(
        'musikcorps' => array(
            'url'           => Assets\asset_path('images/front-page-background.jpg'),
            'thumbnail_url' => Assets\asset_path('images/front-page-background.jpg'),
            'description'   => 'Musikcorps Gruppenfoto',
        ),
    );
    register_default_headers($header_images);

    // Allow for uploading a custom logo image
    add_theme_support('custom-logo', [
        'default-image' => Assets\asset_path('images/logo.png'),
        'width'       => 400,
        'height'      => 500,
    ]);
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
    register_sidebar([
        'name'          => __('Startseite', 'musikcorps'),
        'id'            => 'sidebar-primary',
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');


/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
      is_404(),
      !is_front_page(),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
    wp_enqueue_script('foundation/js', Assets\asset_path('scripts/foundation.js'), ['jquery'], null, true);
    wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);
