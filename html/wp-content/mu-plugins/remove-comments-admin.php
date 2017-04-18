<?php
/*
Plugin Name:  Remove Comments from Admin Area
Plugin URI:   https://github.com/musikcorps/webseite
Description:  Removes comments and its settings from the admin area as they are not used
Version:      1.0.0
Author:       Johannes Lauinger
Author URI:   https://lauinger-it.de/
License:      MIT License
*/


namespace Musikcorps;


class RemoveCommentsAdminPlugin {

    public function __construct() {
        add_action('admin_menu', array($this, 'remove_menus'));
        add_action('init', array($this, 'remove_comment_support'));
        add_action('wp_before_admin_bar_render', array($this, 'remove_admin_bar'));
    }

    public function remove_menus() {
        remove_menu_page('edit-comments.php');
    }

    public function remove_comment_support() {
        remove_post_type_support('post', 'comments');
        remove_post_type_support('page', 'comments');
    }

    public function remove_admin_bar() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    }
}


new RemoveCommentsAdminPlugin();
