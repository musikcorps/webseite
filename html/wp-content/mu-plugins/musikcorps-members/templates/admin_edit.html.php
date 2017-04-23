<div class="wrap">
    <h2 class="wp-heading-inline"><?= isset($_GET["id"]) ? __('Mitglied bearbeiten') : __('Neues Mitglied') ?></h2>

    <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>

    <form method="post" action="admin-post.php">
        <p></p>
        <table class='wp-list-table fixed'>
            <tr>
                <th class="ss-th-width"><label for="firstname"><?= __('Vorname') ?></label></th>
                <td><input type="text" name="firstname" id="firstname" value="<?= $this->item->firstname ?>" class="ss-field-width" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="lastname"><?= __('Nachname') ?></label></th>
                <td><input type="text" name="lastname" id="lastname" value="<?= $this->item->lastname ?>" class="ss-field-width" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="instrument"><?= __('Instrument') ?></label></th>
                <td><input type="text" name="instrument" id="instrument" value="<?= $this->item->instrument ?>" class="ss-field-width" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="register"><?= __('Register', 'musikcorps') ?></label></th>
                <td><input type="text" name="register" id="register" value="<?= $this->item->register ?>" class="ss-field-width" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="birthday"><?= __('Geburtstag') ?></label></th>
                <td><input type="text" name="birthday" id="birthday" value="<?= $this->item->birthday ?>" class="ss-field-width" placeholder="YYYY-MM-DD" /></td>
            </tr>
            <tr>
                <th class="ss-th-width"><label for="email"><?= __('E-Mail') ?></label></th>
                <td><input type="text" name="email" id="email" value="<?= $this->item->email ?>" class="ss-field-width" /></td>
            </tr>
        </table>
        <p></p>
        <?php if(isset($_GET["id"])) { echo '<input type="hidden" name="id" value="'.$_GET["id"].'" />'; } ?>
        <input type="hidden" name="action" value="musikcorps_save_member" />
        <input type="submit" name="musikcorps_save_member" value="<?= __('Speichern') ?>" class="button button-primary button-large" />
        <?php if(isset($_GET["id"])) { echo '<input type="submit" name="musikcorps_delete_member" value="Löschen" class="button button-large" />'; } ?>
    </form>
</div>


<style>

</style>