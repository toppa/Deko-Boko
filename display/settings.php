<div class="wrap">
    <div style="float: right; font-weight: bold; margin-top: 20px;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="5378623">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
        <?php _e("Support Deko Boko", 'dekoboko'); ?> &raquo; <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="<?php _e("Support Deko Boko", 'dekoboko'); ?>" title="<?php _e("Support Deko Boko", 'dekoboko'); ?>" style="vertical-align: middle;" />
        <a href="http://www.toppa.com/deko-boko-wordpress-plugin/" target="_blank"><?php _e("Deko Boko Help", 'dekoboko'); ?></a>
        </form>
    </div>

    <h2><?php echo "Deko Boko" . __("Settings", 'dekoboko'); ?></h2>

    <?php if ($message) {
        echo '<div id="message" class="updated fade"><p>' . $message .'</p></div>';
        unset($message);
    } ?>

    <form method="post">
    <?php $this->functionsFacade->createNonceFields('dekoboko_nonce', 'dekoboko_nonce'); ?>
    <input type="hidden" name="dekoboko_action" value="update_options">
    <table border="0" cellspacing="3" cellpadding="3" class="form-table">
    <tr valign="top">
    <td nowrap="nowrap"><?php _e("reCAPTCHA public key:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><input type="text" name="dekoboko_options[public_key]" value="<?php echo $this->settings['public_key']; ?>" size="40" /></td>
    <td rowspan="2"><strong>&laquo;</strong> <?php _e("If you are already using the WP-reCAPTCHA plugin for comments, Deko Boko will copy the API key you've already set. If you are not using the WP-reCAPTCHA plugin for comments, then you need to get a", 'dekoboko'); ?>
    <a href="<?php echo RecaptchaForDekoBoko::recaptcha_get_signup_url($_SERVER['HTTP_HOST'], 'wordpress');?>" target="_blank"><?php _e('free reCAPTCHA API key for your site', 'dekoboko'); ?></a> <?php _e('and enter the public and private keys here.', 'dekoboko'); ?></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e("reCAPTCHA private key:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><input type="text" name="dekoboko_options[private_key]" value="<?php echo $this->settings['private_key']; ?>" size="40" /></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e("Recipient email address:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><input type="text" name="dekoboko_options[recipient]" value="<?php echo $this->settings['recipient']; ?>" size="40" /></td>
    <td><strong>&laquo;</strong> <?php _e('Where to email submissions from the the contact form. Separate multiple addresses with commas.', 'dekoboko'); ?></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e("Email subject line:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><input type="text" name="dekoboko_options[subject]" value="<?php echo $this->settings['subject']; ?>" size="40" /></td>
    <td><strong>&laquo;</strong> <?php _e('An optional subject line that will appear on emails sent to you through the contact form. If you also include a subject line in the contact form, then the subject provided by users will be appended to this subject line.', 'dekoboko'); ?></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e("Welcome message:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><textarea name="dekoboko_options[welcome]" cols="40" rows="5"><?php echo $this->settings['welcome']; ?></textarea></td>
    <td><strong>&laquo;</strong> <?php _e('An optional message to display at the top of the contact form. You can include HTML.', 'dekoboko'); ?></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e("Success message:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><textarea name="dekoboko_options[success]" cols="40" rows="5"><?php echo $this->settings['success']; ?></textarea></td>
    <td><strong>&laquo;</strong> <?php _e('The message to show users after they successfully submit the contact form. You can include HTML.', 'dekoboko'); ?></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e('"CC Me" header:', 'dekoboko'); ?></td>
    <td nowrap="nowrap"><textarea name="dekoboko_options[cc_header]" cols="40" rows="5"><?php echo $this->settings['cc_header']; ?></textarea></td>
    <td><strong>&laquo;</strong> <?php _e("If a user checks the 'CC Me' box, this text will appear at the top of the message that's sent to them. If you use the special word BLOGNAME in all capital letters, Deko Boko will substitute the name of your blog. If you use DATETIME it will substitute the date and time the email was sent. HTML is not supported.", 'dekoboko'); ?></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e('"CC Me" footer:', 'dekoboko'); ?></td>
    <td nowrap="nowrap"><textarea name="dekoboko_options[cc_footer]" cols="40" rows="5"><?php echo $this->settings['cc_footer']; ?></textarea></td>
    <td><strong>&laquo;</strong> <?php _e("If a user checks the 'CC Me' box, this text will appear at the bottom of the message that's sent to them. If you use the special word BLOGNAME in all capital letters, Deko Boko will substitute the name of your blog. If you use DATETIME it will substitute the date and time the email was sent. HTML is not supported.", 'dekoboko'); ?></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e("Page Slugs/IDs:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><textarea name="dekoboko_options[pages]" cols="40" rows="5"><?php if (is_array($this->settings['pages'])) echo implode("\n", $this->settings['pages']); ?></textarea></td>
    <td><strong>&laquo;</strong> <?php _e("Optional: enter the slugs or IDs for the pages/posts where you use Deko Boko, separated by line breaks. This lets Deko Boko know on which pages to load its stylesheet (otherwise the stylesheet is loaded on every page). You can find the slug for a page by clicking 'Quick Edit' on your Edit Posts or Edit Pages screen.", 'dekoboko'); ?></td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e("reCAPTCHA theme:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><select name="dekoboko_options[recaptcha_theme]">
        <option value="red"<?php if ($this->settings['recaptcha_theme'] == 'red') echo ' selected="selected"'; ?>><?php _e('red', 'dekoboko'); ?></option>
        <option value="white"<?php if ($this->settings['recaptcha_theme'] == 'white') echo ' selected="selected"'; ?>><?php _e('white', 'dekoboko'); ?></option>
        <option value="blackglass"<?php if ($this->settings['recaptcha_theme'] == 'blackglass') echo ' selected="selected"'; ?>><?php _e('blackglass', 'dekoboko'); ?></option>
        <option value="clean"<?php if ($this->settings['recaptcha_theme'] == 'clean') echo ' selected="selected"'; ?>><?php _e('clean', 'dekoboko'); ?></option>
        <option value="custom"<?php if ($this->settings['recaptcha_theme'] == 'custom') echo ' selected="selected"'; ?>><?php _e('custom', 'dekoboko'); ?></option>
    </select></td>
    <td><strong>&laquo;</strong> <?php _e("'Red' is the default theme. Activating the 'clean' theme allows you to adjust the reCAPTCHA widget's colors - see dekoboko.css for the classes.", 'dekoboko'); ?> <a href="http://wiki.recaptcha.net/index.php/Theme" target="_blank"><?php _e("You can preview the themes here", 'dekoboko'); ?></a>. <?php _e("'Custom' is for advanced users only, who want to change the layout of the reCAPTCHA widget - see the", 'dekoboko'); ?> <a href="http://recaptcha.net/apidocs/captcha/client.html" target="_blank"><?php _e("reCAPTCHA Client API Documentation", 'dekoboko'); ?></a>.</td>
    </tr>

    <tr valign="top">
    <td nowrap="nowrap"><?php _e("reCAPTCHA language:", 'dekoboko'); ?></td>
    <td nowrap="nowrap"><select name="dekoboko_options[recaptcha_lang]">
        <option value="en" <?php if ($this->settings['recaptcha_lang'] == 'en') echo 'selected="selected"'; ?>><?php _e('English', 'dekoboko'); ?></option>
        <option value="nl" <?php if ($this->settings['recaptcha_lang'] == 'nl') echo 'selected="selected"'; ?>><?php _e('Dutch', 'dekoboko'); ?></option>
        <option value="fr" <?php if ($this->settings['recaptcha_lang'] == 'fr') echo 'selected="selected"'; ?>><?php _e('French', 'dekoboko'); ?></option>
        <option value="de" <?php if ($this->settings['recaptcha_lang'] == 'de') echo 'selected="selected"'; ?>><?php _e('German', 'dekoboko'); ?></option>
        <option value="pt" <?php if ($this->settings['recaptcha_lang'] == 'pt') echo 'selected="selected"'; ?>><?php _e('Portuguese', 'dekoboko'); ?></option>
        <option value="ru" <?php if ($this->settings['recaptcha_lang'] == 'ru') echo 'selected="selected"'; ?>><?php _e('Russian', 'dekoboko'); ?></option>
        <option value="es" <?php if ($this->settings['recaptcha_lang'] == 'es') echo 'selected="selected"'; ?>><?php _e('Spanish', 'dekoboko'); ?></option>
        <option value="tr" <?php if ($this->settings['recaptcha_lang'] == 'tr') echo 'selected="selected"'; ?>><?php _e('Turkish', 'dekoboko'); ?></option>
    </select></td>
    <td>&nbsp;</td>
    </tr>
    </table>

    <p><input type="submit" class="button-primary" name="save" value="<?php _e('Save Options', 'dekoboko'); ?>" /></p>
    </form>
</div>
