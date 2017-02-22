<?php

$post = get_post($_GET["post"]);

$content = $post->post_content;
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

$text_content = strip_tags(preg_replace('/\<br(\s*)?\/?\>|\<\/p\>/i', "\n", $content));

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
                                <?php foreach($_GET["emails"] as $email): ?>
                                    <li><?= esc_attr($email) ?></li>
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
                    <h2 class="hndle" style="cursor: default;">Absenden</h2>
                    <div class="inside">
                        <p>Ich habe überprüft: Die E-Mail sieht gut aus und die Empfänger sind korrekt.</p>
                        <div class="clear"></div>
                        <input type="submit" name="do_send_email" class="button button-primary button-large" value="Jetzt versenden!" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>