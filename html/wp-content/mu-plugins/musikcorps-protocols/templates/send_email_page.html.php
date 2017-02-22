<?php

$post = get_post($_GET["post"]);

$content = $post->post_content;
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

$text_content = strip_tags(preg_replace('/\<br(\s*)?\/?\>|\<\/p\>/i', "\n", $content));

$emails = $_GET["emails"];

$raw_recipients = get_option('recipients');
preg_match_all('!(.*?)\s+<\s*(.*?)\s*>!', $raw_recipients, $matches);
$recipients = array();
for ($i=0; $i<count($matches[0]); $i++) {
    $recipients[] = array(
        'name' => $matches[1][$i],
        'email' => $matches[2][$i],
    );
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Protokoll per E-Mail versenden</h1>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">

            <div id="post-body-content">
                <div class="postbox">
                    <div class="inside">
                        <p><b>Betreff:</b></p>
                        <p><?= $post->post_title ?></p>

                        <p><b>Empfänger:</b></p>
                        <p>
                            <ul>
                                <?php foreach($emails as $email): ?>
                                    <li>
                                        <?= esc_attr($recipients[$email]['name']) ?>
                                        &lt;<?= esc_attr($recipients[$email]['email']) ?>&gt;
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </p>
                    </div>
                </div>

                <p><b>HTML-Version</b></p>
                <div class="postbox">
                    <div class="inside">
                        <style>
                            .post-container { width: 100%; overflow-x: auto; }
                            .post-container img { max-width: 100%; height: auto; }
                        </style>
                        <div class="post-container">
                            <?= $content ?>
                        </div>
                    </div>
                </div>

                <p><b>Nur-Text-Version</b></p>
                <div class="postbox">
                    <div class="inside">
                        <div class="post-container">
                            <pre><?= $text_content ?></pre>
                        </div>
                    </div>
                </div>
            </div>

            <div id="postbox-container-1" class="postbox-container">
                <div class="postbox">
                    <h2 class="hndle" style="cursor: default;">Frühere E-Mails</h2>
                    <div class="inside">
                        <p>
                            <span class="dashicons dashicons-yes"></span>
                            Dieses Protokoll wurde bisher nicht per E-Mail gesendet.
                        </p>
                    </div>
                </div>

                <div class="postbox">
                    <h2 class="hndle" style="cursor: default;">Absenden</h2>
                    <div class="inside">
                        <p>Ich habe überprüft: Die E-Mail sieht gut aus und die Empfänger sind korrekt.</p>
                        <div class="clear"></div>
                        <form action="admin-post.php" method="post">
                            <input type="hidden" name="post" value="<?= $post->ID ?>" />
                            <?php foreach($emails as $email): ?>
                                <input type="hidden" name="email[]" value="<?= $email ?>" />
                            <?php endforeach ?>
                            <input type="hidden" name="action" value="musikcorps_do_send_email" />
                            <input type="submit" name="musikcorps_do_send_email" class="button button-primary button-large" value="Jetzt versenden!" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>