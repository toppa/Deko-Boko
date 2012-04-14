<?php
/*
Plugin Name: Deko Boko
Plugin URI: http://www.toppa.com/deko-boko-wordpress-plugin/
Description: An easily extensible contact form, using re-captcha
Author: Michael Toppa
Version: 1.3.5
Author URI: http://www.toppa.com
*/

$dekoBokoToppaLibsDir = dirname(__FILE__) . '/../toppa-plugin-libraries-for-wordpress';
register_activation_hook(__FILE__, 'dekoBokoActivate');
load_plugin_textdomain('dekoboko', false, basename(dirname(__FILE__)) . '/languages/');

if (file_exists($dekoBokoToppaLibsDir)) {
    require_once($dekoBokoToppaLibsDir . '/ToppaAutoLoaderWp.php');
    $dekoBokoToppaAutoLoader = new ToppaAutoLoaderWp('/toppa-plugin-libraries-for-wordpress');
    $dekoBokoAutoLoader = new ToppaAutoLoaderWp('/deko-boko-a-recaptcha-contact-form-plugin');
    $dekoBokoFunctionsFacade = new ToppaFunctionsFacadeWp();
    $dekoBoko = new DekoBoko($dekoBokoFunctionsFacade);
    $dekoBoko->run();
}

function dekoBokoActivate() {
    $autoLoaderPath = dirname(__FILE__) . '/../toppa-plugin-libraries-for-wordpress/ToppaAutoLoaderWp.php';

    if (!file_exists($autoLoaderPath)) {
        $message = __('To activate Deko Boko you need to first install', 'dekoboko')
            . ' <a href="http://wordpress.org/extend/plugins/toppa-plugin-libraries-for-wordpress/">Toppa Plugins Libraries for WordPress</a>';
        dekoBokoCancelActivation($message);
    }

    elseif (!function_exists('spl_autoload_register')) {
        dekoBokoCancelActivation(__('You must have at least PHP 5.1.2 to use Deko Boko', 'dekoboko'));
    }

    elseif (version_compare(get_bloginfo('version'), '3.0', '<')) {
        dekoBokoCancelActivation(__('You must have at least WordPress 3.0 to use Deko Boko', 'dekoboko'));
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

function dekoBokoCancelActivation($message) {
    deactivate_plugins(basename(__FILE__));
    wp_die($message);
}

