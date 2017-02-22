<?php

/*
Plugin Name:  Musikcorps Protokolle
Plugin URI:   https://github.com/musikcorps/webseite
Description:  Fügt Protokolle als neuen Beitragstyp hinzu.
Version:      1.0.0
Author:       Johannes Lauinger <johannes@lauinger-it.de>
Author URI:   https://johannes-lauinger.de
License:      MIT License
*/


namespace Musikcorps;


class MusikcorpsProtocolsPlugin {

    public function __construct() {
        add_action('init', array($this, 'create_post_type'));
        add_action('add_meta_boxes', array($this, 'add_protocol_email_box'));
        add_action('save_post', array($this, 'check_email_on_save'));
        //add_action('admin_notices', array($this, 'no_emails_notice'));
        add_action('admin_menu', array($this, 'register_send_page'));
        add_action('admin_menu', array($this, 'register_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    private function render($template) {
        include plugin_dir_path(__FILE__)."/templates/$template.html.php";
    }

    public function create_post_type() {
        register_post_type('musikcorps_protocol', array(
            'labels' => array(
                'name' => __( 'Protokolle' ),
                'singular_name' => __( 'Protokoll' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'protokolle'),
            'menu_position' => 20
        ));
        flush_rewrite_rules();
    }

    public function add_protocol_email_box() {
        add_meta_box('musikcorps_protocol_email',  'E-Mail Versand', array($this, 'render_protocol_email_box'),
            'musikcorps_protocol', 'side', 'default');
    }

    public function render_protocol_email_box() {
        wp_nonce_field(plugin_basename( __FILE__ ), 'musikcorps_protocol_nonce');
        $this->render('email_box');
    }

    function check_email_on_save() {
        if (isset($_POST["send_email"])) {
            if(count($_POST["emails"]) > 0) {
                $query = http_build_query(array(
                    "page" => "musikcorps_protocols_send_page",
                    "post" => get_the_ID(),
                    "emails" => $_POST["emails"]
                ));
                wp_redirect("admin.php?$query");
                exit();
            } else {
            }
        }
    }

    public function no_emails_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>
                Du musst mindestens eine E-Mail-Adresse als Empfänger auswählen.
            </p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <?php
    }

    public function register_send_page() {
        add_submenu_page(
            '_nonexistent',
            __('Protokoll per E-Mail versenden', 'musikcorps'),
            '',
            'edit_posts',
            'musikcorps_protocols_send_page',
            array($this, 'render_send_page')
        );
    }

    public function render_send_page() {
        $this->render('send_email_page');
    }

    public function register_settings_page() {
        add_menu_page(
            'Protokolle',
            __('Protokolle', 'musikcorps'),
            'manage_options',
            'musikcorps_protocols_settings',
            array($this, 'render_settings_page')
        );
    }

    public function render_settings_page() {
        $this->render('settings_page');
    }

    public function register_settings() {
        register_setting('musikcorps-protocols', 'recipients');
    }
}


new MusikcorpsProtocolsPlugin();