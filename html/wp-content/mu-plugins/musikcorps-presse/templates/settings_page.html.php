<div class="wrap">
    <h1>Musikcorps Protokolle - Einstellungen</h1>
    <form method="post" action="options.php">
        <?php settings_fields('musikcorps-presse'); ?>
        <?php do_settings_sections('musikcorps-presse'); ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="recipients">Mögliche Empfänger</label>
                </th>
                <td>
                    <textarea name="recipients" id="recipients" style="width: 100%;" rows="4"
                              placeholder="Mitglieder Musikcorps <alle@musikcorps-niedernberg.de>"
                    ><?php echo esc_attr(get_option('recipients')); ?></textarea>
                    <p class="description">
                        Trage einen möglichen Empfänger pro Zeile ein. Zunächst kommt der Name, anschließend die
                        Adresse in spitzen Klammern.<br />
                        Beispiel: Mitglieder Musikcorps &lt&lt;alle@musikcorps-niedernberg.de&gt&gt;
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="from_address">Absender-Adresse</label>
                </th>
                <td>
                    <input name="from_address" id="from_address" style="width: 100%;"
                           placeholder="Musikcorps Niedernberg <no-reply@musikcorps-niedernberg.de>"
                           value="<?php echo esc_attr(get_option('from_address')); ?>" />
                    <p class="description">
                        Von dieser E-Mail-Adresse werden Presse-Infos und Protokolle verschickt.<br />
                        Beispiel: Musikcorps Niedernberg &lt;no-reply@musikcorps-niedernberg.de&gt;
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>