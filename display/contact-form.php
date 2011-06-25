<form action="<?php echo get_permalink(); ?>" method="post" id="dekoboko_form">
<?php wp_nonce_field('dekoboko_nonce', 'dekoboko_nonce'); ?>

<?php if ($this->settings['welcome']) {
        echo "<p>" . $this->settings['welcome'] . "</p>";
} ?>

<p><?php _e("Fields marked with ", 'dekoboko'); ?><span class="dekoboko_required">*</span> <?php _e("are required", 'dekoboko'); ?>.</p>

<fieldset>
<ol>
<li>
    <label for="dekoboko_name"><?php _e("Name", 'dekoboko'); ?><span class="dekoboko_required">*</span></label>
    <input type="text" name="dekoboko_required[name]" id="dekoboko_name" value="<?php echo $dekoboko_required['name']; ?>" size="30" />
</li>

<li>
    <label for="dekoboko_email"><?php _e("Email", 'dekoboko'); ?><span class="dekoboko_required">*</span></label>
    <input type="text" name="dekoboko_required[email]" id="dekoboko_email" value="<?php echo $dekoboko_required['email']; ?>" size="30" />
</li>

<li>
    <label for="dekoboko_subject"><?php _e("Subject", 'dekoboko'); ?></label>
    <input type="text" name="dekoboko_optional[subject]" id="dekoboko_subject" value="<?php echo $dekoboko_optional['subject']; ?>" size="30" />
</li>

<li>
    <label for="dekoboko_message"><?php _e("Message", 'dekoboko'); ?><span class="dekoboko_required">*</span></label>
    <textarea name="dekoboko_required[message]" cols="30" rows="5" id="dekoboko_message"><?php echo $dekoboko_required['message']; ?></textarea>
</li>

<li>
    <label for="dekoboko_cc_me"><?php _e("CC Me", 'dekoboko'); ?></label>
    <input type="checkbox" name="dekoboko_optional[cc_me]" id="dekoboko_cc_me" value="Y"<?php if ($dekoboko_optional['cc_me'] == 'Y') echo ' checked="checked"'; ?> />
    <span style="font-size: x-small;"><?php _e("Check this box to send a copy of your message to yourself.", 'dekoboko'); ?></span>
</li>

<li><label for="recaptcha_response_field"><?php _e("Are You Human?", 'dekoboko'); ?><span class="dekoboko_required">*</span><br />
    <span style="font-size: x-small;"><a href="http://www.google.com/recaptcha/help" onclick="window.open('http://www.google.com/recaptcha/help','name','height=600,width=500'); return false;" title="Help"><?php _e("What's this?", 'dekoboko'); ?></a></span></label>
    <?php echo $recaptcha_html ?>
</li>
</ol>
</fieldset>

<fieldset id="dekoboko_end">
    <input type="submit" name="dekoboko_submit" id="dekoboko_submit" value="<?php _e("Send Message", 'dekoboko'); ?>" />
</fieldset>
</form>

