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
                    <textarea name="signature" id="signature" style="width: 100%;" rows="7"
                    ><?php echo esc_attr(get_option('signature')); ?></textarea>
                    <p class="description">
                        Signatur, die in E-Mails angefügt wird, wenn das [presse-signatur] Tag eingebaut wurde. HTML wird unterstützt.<br />
                        Beispiel: &lt;p&gt;&lt;small&gt;&copy; Musikcorps FFW Niedernberg e.V.&lt;/small&gt;&lt;/p&gt;
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="template_presse">Vorlage für Presse-Infos</label>
                </th>
                <td>
                    <textarea name="template_presse" id="template_presse" style="width: 100%;" rows="7"
                    ><?php echo esc_attr(get_option('template_presse')); ?></textarea>
                    <p class="description">
                        Dieser Text steht standardmäßig in einer neuen Presse-Info, sobald diese erstellt wird. HTML wird unterstützt.<br />
                        Beispiel: [presse-geburtstage] [presse-signatur]
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="template_protocol">Vorlage für Protokolle</label>
                </th>
                <td>
                    <textarea name="template_protocol" id="template_protocol" style="width: 100%;" rows="7"
                    ><?php echo esc_attr(get_option('template_protocol')); ?></textarea>
                    <p class="description">
                        Dieser Text steht standardmäßig in einer neuen Presse-Info, sobald diese erstellt wird. HTML wird unterstützt.<br />
                        Beispiel: Anwesende: ... Entschuldigt: ...
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>