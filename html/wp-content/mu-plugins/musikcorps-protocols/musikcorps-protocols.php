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
        add_action('admin_post_musikcorps_do_send_email', array($this, 'do_send_email'));
        add_filter('post_updated_messages', array($this, 'protocol_updated_messages'));
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
            'menu_position' => 20,
        ));
        flush_rewrite_rules();
    }

    public function protocol_updated_messages($messages) {
        global $post, $post_ID;
        $messages['musikcorps_protocol'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __('Protokoll aktualisiert. <a href="%s">Anschauen</a>', 'musikcorps'), esc_url(get_permalink($post_ID))),
            2 => '',
            3 => '',
            4 => __('Protokoll aktualisiert.', 'musikcorps'),
            5 => isset($_GET['revision'] ) ? sprintf( __( 'Protokoll als Revision %s gespeichert.', 'musikcorps'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            6 => sprintf(__('Protokoll veröffentlicht. <a href="%s">Anschauen</a>', 'musikcorps'), esc_url(get_permalink($post_ID))),
            7 => __('Protokoll gespeichert.', 'musikcorps'),
            8 => sprintf(__('Protokoll eingereicht. <a target="_blank" href="%s">Vorschau</a>', 'musikcorps'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
            9 => sprintf(__('Protokoll geplant für: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Vorschau</a>', 'musikcorps'), date_i18n(__('M j, Y @ G:i', 'recipe-hero'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
            10 => sprintf(__('Protokoll-Entwurf aktualisiert. <a target="_blank" href="%s">Vorschau</a>', 'musikcorps'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),

            101 => __('Das Protokoll wurde erfolgreich per E-Mail verschickt.', 'musikcorps'),
            102 => __('Du musst mindestens einen Empfänger auswählen.', 'musikcorps'),
        );
        return $messages;
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
            $postId = get_the_ID();
            if(count($_POST["emails"]) > 0) {
                $query = http_build_query(array(
                    "page" => "musikcorps_protocols_send_page",
                    "post" => $postId,
                    "emails" => $_POST["emails"]
                ));
                wp_redirect("admin.php?$query");
                exit();
            } else {
                wp_redirect("post.php?action=edit&post=$postId&message=102");
                exit();
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

    public function do_send_email() {
        $postId = $_POST["post"];

        $raw_recipients = get_option('recipients');
        preg_match_all('!(.*?)\s+<\s*(.*?)\s*>!', $raw_recipients, $matches);
        $recipients = array();
        for ($i=0; $i<count($matches[0]); $i++) {
            $recipients[] = array(
                'name' => $matches[1][$i],
                'email' => $matches[2][$i],
            );
        }

        $addresses = array_map(function ($x) use($recipients) {
            return $recipients[$x]["name"]." <".$recipients[$x]["email"].">";
        }, $_GET["emails"]);
        $post = get_post($postId);

        wp_mail($addresses, $post->post_title, $post->post_content);

        wp_redirect("post.php?action=edit&post=$postId&message=101");
        exit();
    }
}


new MusikcorpsProtocolsPlugin();