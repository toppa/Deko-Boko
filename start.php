<?php
/*
Plugin Name: Deko Boko
Plugin URI: http://www.toppa.com/deko-boko-wordpress-plugin/
Description: An easily extensible contact form, using re-captcha
Author: Michael Toppa
Version: 1.3.2
Author URI: http://www.toppa.com
*/


$autoLoaderPath = dirname(__FILE__) . '/../toppa-plugin-libraries-for-wordpress/ToppaAutoLoaderWp.php';

if (file_exists($autoLoaderPath) && function_exists('spl_autoload_register')) {
    require_once($autoLoaderPath);
    $toppaAutoLoader = new ToppaAutoLoaderWp('/toppa-plugin-libraries-for-wordpress');
    $dekoBokoAutoLoader = new ToppaAutoLoaderWp('/deko-boko-a-recaptcha-contact-form-plugin');
    $functionsFacade = new ToppaFunctionsFacadeWp();
    $dekoBoko = new DekoBoko($functionsFacade);
    $dekoBoko->run();
}

else {
    // do nothing if dependencies are not met
}

register_activation_hook(__FILE__, 'dekoBokoActivate');

function dekoBokoActivate() {
    $autoLoaderPath = dirname(__FILE__) . '/../toppa-plugin-libraries-for-wordpress/ToppaAutoLoaderWp.php';

    if (!function_exists('spl_autoload_register')) {
        trigger_error('You must have at least PHP 5.1.2 to use Deko Boko (this is not actually a PHP error)', E_USER_ERROR);
    }

    else if (version_compare(get_bloginfo('version'), '3.0', '<')) {
        trigger_error('You must have at least WordPress 3.0 to use Deko Boko (this is not actually a PHP error)', E_USER_ERROR);
    }

    else if (!file_exists($autoLoaderPath)) {
        trigger_error('You must install the plugin "Toppa Plugin Libraries for WordPress" to use Deko Boko (this is not actually a PHP error)', E_USER_ERROR);
    }

    else {
        require_once($autoLoaderPath);
        $toppaAutoLoader = new ToppaAutoLoaderWp('/toppa-plugin-libraries-for-wordpress');
        $dekoBokoAutoLoader = new ToppaAutoLoaderWp('/deko-boko-a-recaptcha-contact-form-plugin');
        $functionsFacade = new ToppaFunctionsFacadeWp();
        $dekoBoko = new DekoBoko($functionsFacade);
        $dekoBoko->install();
    }
}

