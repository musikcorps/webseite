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
                        Beispiel: Mitglieder Musikcorps &ltalle@musikcorps-niedernberg.de&gt
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
            <tr>
                <th scope="row">
                    <label for="signature">E-Mail-Signatur</label>
                </th>
                <td>
                    <textarea name="signature" id="signature" style="width: 100%;" rows="10"
                    ><?php echo esc_attr(get_option('signature')); ?></textarea>
                    <p class="description">
                        Signatur, die in E-Mails angefügt wird, wenn das [presse-signatur] Tag eingebaut wurde. HTML wird unterstützt.<br />
                        Beispiel: &lt;p&gt;&lt;small&gt;&copy; Musikcorps FFW Niedernberg e.V.&lt;/small&gt;&lt;/p&gt;
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>