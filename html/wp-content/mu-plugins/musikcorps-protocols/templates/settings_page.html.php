<div class="wrap">
    <h1>Musikcorps Protokolle - Einstellungen</h1>
    <form method="post" action="options.php">
        <?php settings_fields('musikcorps-protocols'); ?>
        <?php do_settings_sections('musikcorps-protocols'); ?>
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
                        Beispiel: Mitglieder Musikcorps &lt;alle@musikcorps-niedernberg.de&gt;
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>