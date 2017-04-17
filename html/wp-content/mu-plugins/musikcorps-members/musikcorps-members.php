<?php

/*
Plugin Name:  Musikcorps Mitglieder
Plugin URI:   https://github.com/musikcorps/webseite
Description:  Mitgliederliste, nach Instrument und Register gruppiert
Version:      1.0.0
Author:       Johannes Lauinger <johannes@lauinger-it.de>
Author URI:   https://johannes-lauinger.de
License:      MIT License
*/


namespace Musikcorps;


class MusikcorpsMembersPlugin {

    private $table_name;

    public function __construct() {
        $this->check_and_init_db();
        add_action('admin_menu', array($this, 'register_admin_view'));
    }

    private function render($template) {
        include plugin_dir_path(__FILE__)."/templates/$template.html.php";
    }

    private function check_and_init_db() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'mc_members';
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->table_name'") != $this->table_name) {
            $this->create_db();
        }
    }

    public function create_db() {
        global $wpdb;
      	$charset_collate = $wpdb->get_charset_collate();
      	$sql = "CREATE TABLE $this->table_name (
        		id mediumint(9) NOT NULL AUTO_INCREMENT,
        		firstname varchar(256) NOT NULL,
            lastname varchar(256) NOT NULL,
            instrument varchar(256) NULL,
            register varchar(256) NULL,
            birthday date NULL,
            active_since date NULL,
            abzeichen varchar(4096) NULL,
        		PRIMARY KEY id (id)
      	) $charset_collate;";
      	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      	dbDelta($sql);
        update_option('musikcorps_members_version', '1.0');
    }

    public function register_admin_view() {
      add_menu_page(
          'musikcorps_members_view',
          __('Mitglieder', 'musikcorps'),
          'manage_options',
          'musikcorps_members',
          array($this, 'render_admin_view'),
          'dashicons-heart',
          35
      );
  }

  public function render_admin_view() {
      $this->render('admin_view');
  }
}


new MusikcorpsMembersPlugin();
