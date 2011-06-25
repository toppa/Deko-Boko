<?php

class DekoBoko {
    private $version = '1.3.1';
    private $settings;
    private $functionsFacade;
    private $defaultTemplate = 'contact-form.php';
    private $headersInForm = array('name', 'email', 'subject');
    private $submissionErrors = array();
    private $dir;

    public function __construct(ToppaFunctionsFacade &$functionsFacade) {
        $this->functionsFacade = $functionsFacade;
        $this->dir = dirname(__FILE__);

    }

    function run() {
        $this->settings = $this->functionsFacade->getSetting('dekoboko_options');
        $pathToTranslationFiles = $this->functionsFacade->getPluginDirectoryName(__FILE__) . '/languages/';
        load_plugin_textdomain('dekoBoko', false, $pathToTranslationFiles);
        add_action('admin_menu', array($this, 'initAdminMenus'));
        add_shortcode('dekoboko', array($this, 'handleContactPage'));
        add_action('template_redirect', array($this, 'getHeadTags'));
    }

    public function install() {
        $settingsDefaults = array(
            'recipient' => null,
            'success' => '<p>Thank you for your message. If you had a question, I will try to write back as soon as I can. You can <a href="' . get_bloginfo('wpurl') . '">return to my home page</a>.</p>',
            'subject' => '[' . get_option('blogname') . '] Contact Form Email',
            'cc_header' => "Thank you for writing to BLOGNAME. Below is a copy of the message you submitted via our contact form on DATETIME.\n\n--------------------------------------------",
            'cc_footer' => "\n\n--------------------------------------------\n\nThanks again for writing. If you had a question, someone will write back to you as soon as possible.",
            'recaptcha_lang' => 'en',
            'recaptcha_theme' => 'red',
            'pages' => null,
            'version' => null,
            'public_key' => null,
            'private_key' => null

        );

        $oldSettings = $this->functionsFacade->getSetting('dekoboko_options');

        // prior to v1.3, settings were serialized
        if (is_string($oldSettings)) {
            $oldSettings = unserialize($oldSettings);
        }

        if (is_array($oldSettings) && !empty($oldSettings)) {
            $this->settings = $oldSettings;
        }

        else {
            $this->settings = $settingsDefaults;
        }

        // if we don't have recaptcha keys, see if the site has them set from the recaptcha
        // for comments plugin
        if (!$this->settings['public_key']) {
            $recaptchaSettings = $this->functionsFacade->getSetting('recaptcha');

            if ($recaptchaSettings['pubkey']) {
                $this->settings['public_key'] = $recaptchaSettings['pubkey'];
                $this->settings['private_key'] = $recaptchaSettings['privkey'];
            }
        }

        $this->settings['version'] = $this->version;

        if (!$this->settings['recipient']) {
            $settings['recipient'] = $this->functionsFacade->getSetting('admin_email');
        }

        $this->functionsFacade->setSetting('dekoboko_options', $this->settings);
        return true;
    }

    public function initAdminMenus() {
        add_options_page('Deko Boko', 'Deko Boko', 'manage_options', __FILE__, array($this, 'handleSettingsMenu'));
    }

    public function handleSettingsMenu() {
        // check for valid nonce on form submission (WP displays its own message on failure)
        if ($_REQUEST['dekoboko_action']) {
            $this->functionsFacade->checkAdminNonceFields('dekoboko_nonce', 'dekoboko_nonce');
        }

        if ($_REQUEST['dekoboko_action'] == 'update_options') {
            array_walk_recursive($_REQUEST['dekoboko_options'], array('ToppaFunctions', 'trimCallback'));
            array_walk_recursive($_REQUEST['dekoboko_options'], array('ToppaFunctions', 'stripslashesCallback'));

            // process any page slugs - save as an array
            if ($_REQUEST['dekoboko_options']['pages']) {
                $_REQUEST['dekoboko_options']['pages'] = preg_split("/[\s,]+/", $_REQUEST['dekoboko_options']['pages']);
            }

            $this->settings = array_merge($this->settings, $_REQUEST['dekoboko_options']);
            $this->functionsFacade->setSetting('dekoboko_options', $this->settings);
            $message = __("Deko Boko settings saved.", 'dekoboko');
        }

        // for linking to the recaptcha site for key signup, in the settings form
        $siteUrl = parse_url($this->functionsFacade->getSetting('site_url'));

        ob_start();
        require_once($this->dir . '/display/settings.php');
        $settingsForm = ob_get_contents();
        ob_end_clean();
        echo $settingsForm;
        return true;
    }

    public function handleContactPage($userShortcode) {
        $template = null; // will be set in extract call
        extract($this->functionsFacade->setShortcodeAttributes(
                    array('template' => $this->defaultTemplate),
                    $userShortcode));

        if ($_POST['dekoboko_submit']) {
            $formDataIsSafe = $this->checkFormDataIsSafe();

            if ($formDataIsSafe === true) {
                $sentEmailSuccessfully = $this->sendEmail();
            }

            if ($sentEmailSuccessfully === true) {
                return '<div class="dekoboko_success">' . $this->settings['success'] . '</div>';
            }
        }

        // sanitize for re-display in the form if there was an error
        // keep underscore variable names for backward compatibility with form templates
        $dekoboko_required = $_POST['dekoboko_required'];
        $dekoboko_optional = $_POST['dekoboko_optional'];

        if (!empty($dekoboko_required)) {
            array_walk($dekoboko_required, array($this, 'cleanInput'), true);
        }

        if (!empty($dekoboko_optional)) {
            array_walk($dekoboko_optional, array($this, 'cleanInput'), true);
        }

        ob_start();

        if (!empty($this->submissionErrors)) {
            echo "\n"
                 . '<div class="dekoboko_errors"><p>'
                 . __("Sorry, there were errors in your submission. Please try again.", 'dekoboko')
                 . "</p>\n<ul>\n";

            foreach($this->submissionErrors as $error) {
                echo "<li>$error</li>\n";
            }

            echo "</ul></div>\n";
        }

        $jsSettings = "
            <script type='text/javascript'>
            var RecaptchaOptions = { theme : '"
            . $this->settings['recaptcha_theme'] . "', lang : '"
            . $this->settings['recaptcha_lang'] . "' };
            </script>
        ";

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
            $useSsl = true;
        }

        else {
            $useSsl = false;
        }

        $recaptcha_html = $jsSettings . RecaptchaForDekoBoko::recaptcha_get_html($this->settings['public_key'], null, $useSsl);
        require $this->dir . "/display/$template";
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function checkFormDataIsSafe() {
        if (!$this->functionsFacade->checkPublicNonceField($_POST['dekoboko_nonce'], 'dekoboko_nonce')) {
            $this->submissionErrors[] = "<strong>" . __("Invalid Nonce", 'dekoboko') . "</strong>";
        }

        $recaptchaResponse = RecaptchaForDekoBoko::recaptcha_check_answer($this->settings['private_key'], $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

        if (!$recaptchaResponse->is_valid) {
            $this->submissionErrors[] = "<strong>" . __("ReCAPTCHA error", 'dekoboko') . ":</strong> "
                . __("your captcha response was incorrect - please try again", 'dekoboko');
        }

        foreach ($this->headersInForm as $header) {
            if ($this->checkHeaderIsSafe($_POST['dekoboko_required'][$header]) === false) {
                $this->submissionErrors[] = "<strong>$header</strong> " . __("header contains malicious data", 'dekoboko');
            }

            if ($this->checkHeaderIsSafe($_POST['dekoboko_optional'][$header]) === false) {
                $this->submissionErrors[] = "<strong>$header</strong> " . __("header contains malicious data", 'dekoboko');
            }
        }

        foreach ($_POST['dekoboko_required'] as $k=>$v) {
            if (!strlen($v)) {
                $this->submissionErrors[] = __("Required field", 'dekoboko')
                    . " <strong>$k</strong> " . __("is blank", 'dekoboko');
            }

            if ($k == 'email' && strlen($v)) {
                if (!$this->functionsFacade->checkEmailHasValidFormat($v)) {
                    $safeToDisplayEmail = $this->functionsFacade->sanitizeString($v);
                    $this->submissionErrors[] = "<strong>$safeToDisplayEmail</strong> "
                        . __("is not a valid email address", 'dekoboko');
                }
            }
        }

        if (!empty($this->submissionErrors)) {
            return false;
        }

        return true;
    }

    public function checkHeaderIsSafe($header) {
        $badStrings = array("content-type:","mime-version:","multipart/mixed",
            "content-transfer-encoding:","bcc:","cc:","to:","%0A","%0D","\n",
            "\r");

        foreach($badStrings as $bad) {
            if(strpos(strtolower($header), $bad) !== false) {
                return false;
            }
        }

        return true;
    }

    public function sendEmail() {
        $formFieldsData = array_merge((array)$_POST['dekoboko_required'], (array)$_POST['dekoboko_optional']);
        array_walk($formFieldsData, array($this, 'cleanInput'));
        $subject = null;

        if ($formFieldsData['subject']) {
            $subject = $this->settings['subject'] ? ($this->settings['subject'] . ': ') : '';
            $subject .= $formFieldsData['subject'];
        }

        else {
             $subject = $this->settings['subject'];
        }

        $messageBody = '';

        foreach ($formFieldsData as $k=>$v) {
            if ($formFieldsData['name'] && !$formFieldsData['email']) {
                $messageBody .= __("Message from ", 'dekoboko') . $formFieldsData['name'] . "\n\n";
            }

            if ($k == 'message') {
                $messageBody .= "$v\n\n";
            }

            else if (!in_array($k, $this->headersInForm)) {
                $messageBody .= "$k: $v\n";
            }
        }

        $from = null;
        $messageHeaders = null;

        if ($formFieldsData['name'] && $formFieldsData['email']) {
            $from = $formFieldsData['name'] . " <" . $formFieldsData['email'] . ">";
        }

        else if ($formFieldsData['email']) {
            $from = $formFieldsData['email'];
        }

        if ($from) {
            $messageHeaders = "From: " . $from . "\r\n";
        }

        $sentSuccessfully = $this->functionsFacade->sendEmail(
            $this->settings['recipient'],
            $subject,
            $messageBody,
            $messageHeaders);

        if (($formFieldsData['email']) && $sentSuccessfully && $formFieldsData['cc_me']) {
            $messageBodyHeader = null;

            if ($this->settings['cc_header']) {
                $messageBodyHeader = $this->settings['cc_header'] . "\n\n";
            }

            $messageBodyFooter = $this->settings['cc_footer'];

            if ($messageBodyHeader) {
                $messageBodyHeader = str_replace('BLOGNAME', $this->functionsFacade->getSetting('blogname'), $messageBodyHeader);
                $messageBodyHeader = str_replace('DATETIME', date('F jS, Y \a\t h:i A e'), $messageBodyHeader);
                $messageBody = $messageBodyHeader . $messageBody;
            }

            if ($messageBodyFooter) {
                $messageBodyFooter = str_replace('BLOGNAME', $this->functionsFacade->getSetting('blogname'), $messageBodyFooter);
                $messageBodyFooter = str_replace('DATETIME', date('F jS, Y \a\t h:i A e'), $messageBodyFooter);
                $messageBody .= $messageBodyFooter;
            }

            $sentSuccessfully = $this->functionsFacade->sendEmail(
                $formFieldsData['email'],
                $subject,
                $messageBody,
                $messageHeaders);
        }

        return $sentSuccessfully;
    }

    public function getHeadTags() {
        $cssUrl = $this->functionsFacade->getUrlForCustomizableFile('dekoboko.css', __FILE__, 'display/');

        // limit inclusion of the stylesheet if we know which pages...
        if (is_array($this->settings['pages'])) {
            foreach ($this->settings['pages'] as $page) {
                if ($this->functionsFacade->isPage($page)) {
                    $this->functionsFacade->enqueueStylesheet('dekoboko_css', $cssUrl, false, $this->version);
                    break;
                }
            }
        }

        // ...otherwise always load it
        else {
            $this->functionsFacade->enqueueStylesheet('dekoboko_css', $cssUrl, false, $this->version);
        }
    }

    public function cleanInput(&$value, $key, $html = null) {
        if ($html) {
            $value = htmlspecialchars(stripslashes(trim($value)), ENT_COMPAT, 'UTF-8');
        }

        else {
            $value = stripslashes(trim($value));
        }
    }
}
