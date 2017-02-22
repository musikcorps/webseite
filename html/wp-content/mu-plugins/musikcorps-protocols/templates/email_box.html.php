<?php

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

<div style="padding: .3rem 0 .5rem 0;">
    <?php foreach($recipients as $recipient): ?>
        <label style="display: block;">
            <input type="checkbox" name="emails[]" value="<?= esc_attr($recipient["name"])." <".esc_attr($recipient["email"]).">" ?>" />
            <?= esc_attr($recipient["name"]) ?>
        </label>
    <?php endforeach ?>
</div>
<input type="submit" name="send_email" class="button button-primary button-large" value="E-Mail versenden (Vorschau)" />
<div class="clear"></div>