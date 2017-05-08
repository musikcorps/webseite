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

require_once('members-list-table.php');


class MusikcorpsMembersPlugin {

    private $table_name;

    public function __construct() {
        $this->check_and_init_db();
        add_action('admin_menu', array($this, 'register_admin_view'));
        add_action('admin_post_musikcorps_save_member', array($this, 'save_member'));
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
                email varchar(512) NULL,
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
            'edit_posts',
            'musikcorps_members',
            array($this, 'render_admin_view'),
            'dashicons-heart',
            25
        );
        add_submenu_page(
            'musikcorps_members',
            'musikcorps_members_add',
            __('Erstellen', 'musikcorps'),
            'edit_posts',
            'musikcorps_members_add',
            array($this, 'render_admin_add')
        );
        add_submenu_page(
            'musikcorps_members',
            'musikcorps_members_import',
            __('Importieren', 'musikcorps'),
            'edit_posts',
            'musikcorps_members_import',
            array($this, 'render_admin_import')
        );
    }

    public function render_admin_view() {
        global $wpdb;
        if (isset($_GET["msg"])) {
            switch (intval($_GET["msg"])) {
                case 1: $this->message = __("Mitglied hinzugefügt."); break;
                case 2: $this->message = __("Mitglied aktualisiert."); break;
                case 3: $this->message = __("Mitglied gelöscht."); break;
                case 4: $this->message = __("Unbekannte Aktion."); break;
            }
        }
        if (isset($_GET["id"])) {
            $this->item = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->table_name WHERE id=%d", $_GET["id"]))[0];
            $this->render('admin_edit');
        } else {
            $this->render('admin_view');
        }
    }

    public function render_admin_add() {
        $this->render('admin_edit');
    }

    public function save_member() {
        global $wpdb;
        if (isset($_POST["musikcorps_save_member"]) && isset($_POST["id"])) {
            $wpdb->update(
                $this->table_name,
                array(
                    'firstname' => $_POST["firstname"],
                    'lastname' => $_POST["lastname"],
                    'instrument' => $_POST["instrument"],
                    'register' => $_POST["register"],
                    'birthday' => $_POST["birthday"],
                    'email' => $_POST["email"],
                ),
                array('id' => $_POST["id"]),
                array('%s', '%s', '%s', '%s', '%s', '%s'),
                array('%d')
            );
            wp_redirect("admin.php?page=musikcorps_members&msg=2");
            exit();
        } else if (isset($_POST["musikcorps_save_member"]) && !isset($_POST["id"])) {
            $wpdb->insert(
                $this->table_name,
                array(
                    'firstname' => $_POST["firstname"],
                    'lastname' => $_POST["lastname"],
                    'instrument' => $_POST["instrument"],
                    'register' => $_POST["register"],
                    'birthday' => $_POST["birthday"],
                    'email' => $_POST["email"],
                ),
                array('%s', '%s', '%s', '%s', '%s', '%s')
            );
            wp_redirect("admin.php?page=musikcorps_members&msg=1");
            exit();
        } else if (isset($_POST["musikcorps_delete_member"]) && isset($_POST["id"])) {
            $wpdb->delete(
                $this->table_name,
                array('id' => $_POST["id"]),
                array('%d')
            );
            wp_redirect("admin.php?page=musikcorps_members&msg=3");
            exit();
        } else {
            wp_redirect("admin.php?page=musikcorps_members&msg=4");
            exit();
        }
    }

    public function render_admin_import() {
        $this->render('admin_import');
    }
}


new MusikcorpsMembersPlugin();
