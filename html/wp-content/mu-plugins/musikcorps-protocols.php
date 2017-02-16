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

if ( !session_id() ) {
    session_start();
}


add_action( 'init', 'create_post_type' );

function create_post_type() {
    register_post_type( 'musikcorps_protocol',
        array(
            'labels' => array(
                'name' => __( 'Protokolle' ),
                'singular_name' => __( 'Protokoll' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'protokolle'),
        )
    );
    flush_rewrite_rules();
}


add_action('add_meta_boxes', 'add_protocol_email_box');

function add_protocol_email_box() {
    add_meta_box('musikcorps-protocol-email', 'E-Mail Versand', 'render_protocol_email_box', 'musikcorps_protocol', 'side', 'default');
}

function render_protocol_email_box() {
    wp_nonce_field(plugin_basename( __FILE__ ), 'musikcorps_protocol_nonce');
    ?>
    <div style="padding: .3rem 0 .5rem 0;">
        <label style="display: block;"><input type="checkbox" name="email_ausschuss" value="1" />Verwaltungsausschuss</label>
        <label style="display: block;"><input type="checkbox" name="email_mitglieder" value="1" />Aktive Mitglieder</label>
        <label style="display: block;"><input type="checkbox" name="email_sacher" value="1" />Sacher Druck</label>
    </div>
    <input type="submit" class="button button-primary button-large" value="E-Mail versenden (Vorschau)" />
    <div class="clear"></div>
    <?php
}


add_action('save_post', 'check_email_on_save');

function check_email_on_save() {
    $_SESSION["emails"] = array();
    if ($_POST["email_ausschuss"] == 1) $_SESSION["emails"][] = "Ausschuss";
    if ($_POST["email_mitglieder"] == 1) $_SESSION["emails"][] = "Mitglieder";
    if ($_POST["email_sacher"] == 1) $_SESSION["emails"][] = "Sacher";

    if(count($_SESSION["emails"]) > 0) {
        wp_redirect("admin.php?page=musikcorps_send_page");
        exit();
    }
}


//add_action('admin_notices', 'email_sent_notice');

function email_sent_notice() {
    if (count($_SESSION["emails"]) == 0) return;
    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            E-Mail wurde gesendet:
            <?php foreach($_SESSION["emails"] as $email) { echo $email."  "; } ?>
        </p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
    <?php
    $_SESSION["emails"] = array();
}


add_action('admin_menu', 'register_send_page');

function register_send_page() {
    add_submenu_page(
        '_nonexistent',
        __('Protokoll per E-Mail versenden', 'musikcorps'),
        '',
        'manage_options',
        'musikcorps_send_page',
        'render_send_page'
    );
}

function render_send_page() {
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">E-Mail versenden</h1>
        <ul>
            <?php foreach($_SESSION["emails"] as $email) { echo "<li>".$email."</li>"; } ?>
        </ul>
    </div>
    <?php
}