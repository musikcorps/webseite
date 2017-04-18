<div class="wrap">
    <h2 class="wp-heading-inline"><?= __('Neues Mitglied') ?></h2>

    <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>

    <form method="post" action="admin-post.php">
        <p></p>
        <table class='wp-list-table fixed'>
            <tr>
                <th class="ss-th-width"><label for="firstname"><?= __('Vorname') ?></label></th>
                <td><input type="text" name="firstname" id="firstname" value="<?= $item->firstname ?>" class="ss-field-width" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="lastname"><?= __('Nachname') ?></label></th>
                <td><input type="text" name="lastname" id="lastname" value="<?= $item->lastname ?>" class="ss-field-width" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="instrument"><?= __('Instrument') ?></label></th>
                <td><input type="text" name="instrument" id="instrument" value="<?= $item->instrument ?>" class="ss-field-width" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="register"><?= __('Register', 'musikcorps') ?></label></th>
                <td><input type="text" name="register" id="register" value="<?= $item->register ?>" class="ss-field-width" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="birthday"><?= __('Geburtstag') ?></label></th>
                <td><input type="text" name="birthday" id="birthday" value="<?= $item->birthday ?>" class="ss-field-width" placeholder="YYYY-MM-DD" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="active_since"><?= __('Aktiv seit') ?></label></th>
                <td><input type="text" name="active_since" id="active_since" value="<?= $item->active_since ?>" class="ss-field-width" placeholder="YYYY-MM-DD" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="abzeichen"><?= __('Abzeichen') ?></label></th>
                <td><input type="text" name="abzeichen" id="abzeichen" value="<?= $item->abzeichen ?>" class="ss-field-width" /></td>
            </tr>
        </table>
        <p></p>
        <input type="hidden" name="action" value="musikcorps_save_member" />
        <input type="submit" name="musikcorps_save_member" value="<?= __('Speichern') ?>" class="button button-primary button-large" />
    </form>
</div>


<style>

</style>