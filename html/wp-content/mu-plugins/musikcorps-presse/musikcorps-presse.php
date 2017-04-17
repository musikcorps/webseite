<?php

/*
Plugin Name:  Musikcorps Presse
Plugin URI:   https://github.com/musikcorps/webseite
Description:  Fügt Protokolle und Presse-Infos als neuen Beitragstyp hinzu, erlaubt E-Mail-Versand.
Version:      1.0.0
Author:       Johannes Lauinger <johannes@lauinger-it.de>
Author URI:   https://johannes-lauinger.de
License:      MIT License
*/


namespace Musikcorps;


class MusikcorpsPressePlugin {

    // MIME multipart boundary
    private $boundary = "564db94f777e5d1308e32a6773ff812f4178481c326a49b2180fdf5fb415";

    public function __construct() {
        add_action('init', array($this, 'create_post_types'));
        add_action('post_submitbox_misc_actions', array($this, 'protocol_change_visibility_metabox'));
        add_action('admin_menu', array($this, 'register_settings_page'));
        add_action('add_meta_boxes', array($this, 'add_email_box'));
        add_action('save_post', array($this, 'check_email_on_save'));
        add_action('admin_menu', array($this, 'register_send_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_post_musikcorps_do_send_email', array($this, 'do_send_email'));
        add_filter('post_updated_messages', array($this, 'updated_messages'));
        add_shortcode('presse-geburtstage', array($this, 'shortcode_birthdays'));
        add_shortcode('presse-signatur', array($this, 'shortcode_signature'));
    }

    private function render($template) {
        include plugin_dir_path(__FILE__)."/templates/$template.html.php";
    }

    private function ob_render($template) {
        ob_start();
        include plugin_dir_path(__FILE__)."/templates/$template.html.php";
        return ob_get_clean();
    }

    public function create_post_types() {
        register_post_type('musikcorps_protocol', array(
            'labels' => array(
                'name' => __('Protokolle'),
                'singular_name' => __('Protokoll'),
                'edit_item' => __('Protokoll bearbeiten'),
                'add_new_item' => __('Protokoll erstellen')
            ),
            'public' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'rewrite' => array('slug' => 'protokolle'),
            'menu_position' => 31,
            'menu_icon' => 'dashicons-clipboard',
        ));
        register_post_type('musikcorps_presse', array(
            'labels' => array(
                'name' => __('Presse-Infos'),
                'singular_name' => __('Presse-Info'),
                'edit_item' => __('Presse-Info bearbeiten'),
                'add_new_item' => __('Presse-Info erstellen')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'presse'),
            'menu_position' => 30,
            'menu_icon' => 'dashicons-rss',
        ));
        flush_rewrite_rules();
    }

    function protocol_change_visibility_metabox() {
        global $post;
        if ($post->post_type != 'musikcorps_protocol') return;
        $post->post_password = '';
        $visibility = 'private';
        $visibility_trans = __('Privat');
        ?>
        <script type="text/javascript">
            (function($){
                try {
                    $('#post-visibility-display').text('<?php echo $visibility_trans; ?>');
                    $('#hidden-post-visibility').val('<?php echo $visibility; ?>');
                    $('#visibility-radio-<?php echo $visibility; ?>').attr('checked', true);
                } catch(err){}
            }) (jQuery);
        </script>
        <?php
    }

    public function updated_messages($messages) {
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
            102 => __('<span class="dashicons dashicons-warning" style="color:#d9534f;"></span> Beim Senden der E-Mail ist ein Fehler aufgetreten, bitte versuche es erneut!', 'musikcorps'),
            103 => __('<span class="dashicons dashicons-warning" style="color:#d9534f;"></span> Du musst mindestens einen Empfänger auswählen.', 'musikcorps'),
        );
        $messages['musikcorps_presse'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __('Presse-Info aktualisiert. <a href="%s">Anschauen</a>', 'musikcorps'), esc_url(get_permalink($post_ID))),
            2 => '',
            3 => '',
            4 => __('Presse-Info aktualisiert.', 'musikcorps'),
            5 => isset($_GET['revision'] ) ? sprintf( __( 'Presse-Info als Revision %s gespeichert.', 'musikcorps'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            6 => sprintf(__('Presse-Info veröffentlicht. <a href="%s">Anschauen</a>', 'musikcorps'), esc_url(get_permalink($post_ID))),
            7 => __('Presse-Info gespeichert.', 'musikcorps'),
            8 => sprintf(__('Presse-Info eingereicht. <a target="_blank" href="%s">Vorschau</a>', 'musikcorps'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
            9 => sprintf(__('Presse-Info geplant für: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Vorschau</a>', 'musikcorps'), date_i18n(__('M j, Y @ G:i', 'recipe-hero'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
            10 => sprintf(__('Presse-Info-Entwurf aktualisiert. <a target="_blank" href="%s">Vorschau</a>', 'musikcorps'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
            101 => __('Die Presse-Info wurde erfolgreich per E-Mail verschickt.', 'musikcorps'),
            102 => __('<span class="dashicons dashicons-warning" style="color:#d9534f;"></span> Beim Senden der E-Mail ist ein Fehler aufgetreten, bitte versuche es erneut!', 'musikcorps'),
            103 => __('<span class="dashicons dashicons-warning" style="color:#d9534f;"></span> Du musst mindestens einen Empfänger auswählen.', 'musikcorps'),
        );
        return $messages;
    }

    public function add_email_box() {
        add_meta_box('musikcorps_presse_email',  'E-Mail Versand', array($this, 'render_email_box'),
            'musikcorps_protocol', 'side', 'default');
        add_meta_box('musikcorps_presse_email',  'E-Mail Versand', array($this, 'render_email_box'),
            'musikcorps_presse', 'side', 'default');
    }

    public function render_email_box() {
        wp_nonce_field(plugin_basename( __FILE__ ), 'musikcorps_presse_nonce');
        $this->render('email_box');
    }

    function check_email_on_save() {
        if (isset($_POST["send_email"])) {
            $postId = get_the_ID();
            if(count($_POST["emails"]) > 0) {
                $query = http_build_query(array(
                    "page" => "musikcorps_presse_send_page",
                    "post" => $postId,
                    "emails" => $_POST["emails"]
                ));
                wp_redirect("admin.php?$query");
                exit();
            } else {
                wp_redirect("post.php?action=edit&post=$postId&message=103");
                exit();
            }
        }
    }

    public function register_send_page() {
        add_submenu_page(
            '_nonexistent',
            __('Per E-Mail versenden', 'musikcorps'),
            '',
            'edit_posts',
            'musikcorps_presse_send_page',
            array($this, 'render_send_page')
        );
    }

    public function render_send_page() {
        $this->render('send_email_page');
    }

    public function register_settings_page() {
        add_options_page(
            'Presse-Infos und Protokolle',
            __('Presse-Infos und Protokolle', 'musikcorps'),
            'manage_options',
            'musikcorps_presse_settings',
            array($this, 'render_settings_page')
        );
    }

    public function render_settings_page() {
        $this->render('settings_page');
    }

    public function register_settings() {
        register_setting('musikcorps-presse', 'recipients');
        register_setting('musikcorps-presse', 'from_address');
        register_setting('musikcorps-presse', 'signature');
    }

    public function do_send_email() {
        $postId = $_POST["post"];
        $raw_recipients = get_option('recipients');
        $from = get_option('from_address');

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
        }, $_POST["emails"]);
        $post = get_post($postId);

        $headers = array(
            "From: $from",
            "MIME-Version: 1.0",
        );

        $content = "<h1>$post->post_title</h1><br>\n\n" . $post->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        $text_content = strip_tags(preg_replace('/\<br(\s*)?\/?\>|\<\/p\>/i', "\n", $content));
        $text_content = implode("\n", array_map('trim', explode("\n", $text_content)));

        $body = "--$this->boundary\nContent-Type: text/plain\n\n" .
                $text_content .
                "\n\n--$this->boundary\nContent-Type: text/html; charset=utf-8\n\n" .
                $content .
                "\n\n--$this->boundary--";

        add_filter('wp_mail_content_type', array($this, 'mail_content_type'));
        $result = wp_mail($addresses, $post->post_title, $body, $headers);
        remove_filter('wp_mail_content_type', array($this, 'mail_content_type'));

        if ($result) {
            wp_redirect("post.php?action=edit&post=$postId&message=101");
        } else {
            wp_redirect("post.php?action=edit&post=$postId&message=102");
        }
        exit();
    }

    function mail_content_type() {
        return "multipart/alternative; boundary=$this->boundary";
    }

    function shortcode_birthdays() {
        return $this->ob_render('shortcode_birthdays');
    }

    function shortcode_signature() {
        return get_option('signature');
    }
}


new MusikcorpsPressePlugin();
