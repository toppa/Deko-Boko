=== Deko Boko ===
Contributors: toppa
Donate link: http://www.toppa.com/deko-boko-wordpress-plugin
Tags: email, contact, spam, captcha
Requires at least: 3.0
Tested up to: 3.1.3
Stable tag: 1.3.1

Deko Boko is a simple but highly extensible contact form, integrating reCAPTCHA for handling spam.

== Description ==

**Version 1.3**

It's been almost two years since the late Deko Boko update. This version updates the reCAPTCHA library code, as reCAPTCHA is now run through Google. It includes several minor enhancements and bug fixes (see the change log).

**Installation of [Toppa Plugin Libraries for WordPress](http://wordpress.org/extend/plugins/toppa-plugin-libraries-for-wordpress/) is required with this release. Please download and activate it before upgrading Deko Boko.**

**Why another contact form plugin?**

Why write yet another email contact form for WordPress? There are two things that make Deko Boko unique:

1. It uses [reCAPTCHA](http://recaptcha.net/) for handling spam. reCAPTCHA is a great project that uses data from its captcha forms to help digitize books.

2. The Deko Boko contact form can be extended any way you want, but without the need for complicated admin menus. If you're comfortable editing HTML, then you can add any number and any type of input fields to the contact form. You can control which fields are optional or required. When the form is submitted, any fields that you added will have their data included in the body of the email.

**Features**

* The form layout is controlled by a CSS styled list, which provides a great deal of flexibility. With CSS edits you can change the position of the field labels to top-aligned, left-justified, or right-justified. Deko Boko uses the techniques outlined in [Cameron Adam's excellent article on form layout](http://www.sitepoint.com/fancy-form-design-css/).

* Plays nicely with [WP-reCAPTCHA](http://wordpress.org/extend/plugins/wp-recaptcha/), the WordPress plugin for using reCAPTCHA to protect against comment spam. If you already have a API key set up with WP-reCAPTCHA, Deko Boko will automatically copy it to the Deko Boko settings.

* Includes selectors for using different themes and languages with the reCAPTCHA widget, as well as support for custom CSS for the reCAPTCHA widget.

* Support for multiple, custom contact forms.

* "CC Me" option for users to receive a copy of the message they submit to you. You can specify header text and footer text to "wrap" this message. Deko Boko can automatically include the name of your blog and a timestamp in the header or footer text.

* Security in addition to reCAPTCHA is included. Deko Boko protects against email header injections and XSS attacks.

* You can have Deko Boko load its stylesheet only on pages where you use the Deko Boko contact form, so it won't be loaded unnecessarily on other pages.

* Localization support: a dekoboko.pot file is included to enable translations to other languages (French and Spanish translations are included, but have not yet been updated for version 1.3).

* A sample form is included, to help you make your own custom contact form.

* You can put a custom copy of dekoboko.css in your active theme folder, so you won't lose your stylesheet customizations when upgrading Deko Boko.

== Installation ==

**Installation**

1. Install [Toppa Plugin Libraries for WordPress](http://wordpress.org/extend/plugins/toppa-plugin-libraries-for-wordpress/) in your plugin folder
1. Install Deko Boko in your plugin folder and activate.
1. Look for Deko under your "Settings" tab, and configure it before using.

== Frequently Asked Questions ==

* Requires PHP 5.1.2 or higher.
* Please go to [the Deko Boko page on my site](http://www.toppa.com/deko-boko-wordpress-plugin) for a Usage Guide and other information.
* For troubleshooting help, please [post a comment in my latest post on WordPress plugins](http://www.toppa.com/category/technical/wordpress-plugins/).

== Screenshots ==

1. Example contact form

== Changelog ==

= 1.3.1 =
* Bug fix: correctly call recaptchalib functions from local version of RecaptchaForDekoBoko class
* Remove extraneous recaptchalib files
* Include timezone when rendering time in "cc me" emails
* Use Toppa Lib's function facade for getting blog name
* Added !important to css for dekoboko errors div
* Updated label tag for "Are You Human" to reflect new recaptcha input name
* Revised .pot language translation file

= 1.3 =
* First round of a major refactoring, goal is to use Agile coding practices
* Uses Toppa Plugin Libraries for WordPress, for WP functions facade and autoloading
* Bug fix: PHP warning on the contact form (due to an array_walk issue)
* Bug fix: corrected possible security hole in analysis of headers
* Added uninstall.php; removed uninstall option from form on settings page (now uninstalls from main plugins menu)
* Reads recaptcha keys from recaptcha when activated, but now copies them to Deko Boko settings and uses them from there
* Updated recaptchalib to latest version (1.11)
* Modified recaptchalib to wrap it in its own class, to avoid conflicts with other recaptcha plugins
* 'manage_options' capability required for managing settings (was using old user levels number system)
* On activation, defaults to address in WordPress' admin_email setting for the recipient
* Updated link for recaptcha help pop-up window to new location at google.com
* Added exactly one unit test (it's a start!)

= 1.2.2 =
* Added  Spanish and French translations
* Bug fix: Settings menu would show English as the selected language even if you had picked a different one

= 1.2.1 =
* bug fix: now correctly saves the reCAPTCHA API keys if they hadn't been set previously (was failing to save if WP-reCAPTCHA hadn’t been installed previously); bug fix: now correctly cleans up old-style Deko Boko options from the database.

= 1.2 =
* Added option to load the Deko Boko stylesheet only on pages using the contact form, so it won’t be loaded unnecessarily on other pages.
* Add localization support: a dekoboko.pot file is included to enable translations to other languages.
* In addition to the standard contact form, a sample form is now also included, as a guide for users to make their own custom contact forms (to avoid confusion for non-programmers, the sample form does not include localization code).
* Supports a custom copy of dekoboko.css in the active theme folder (so customizations are not lost when Deko Boko is upgraded).
* Added an uninstall option.

= 1.1 =
* Complete rewrite of the XHTML and CSS for the contact form. Both are now easier to work with, the XHTML is semantically correct, and no CSS filters are needed for different browsers. The rewrite was prompted by IE7′s incompatibility with the widely used “clearfix” code in the previous versions of Deko Boko.
* The latest version of WP-reCAPTCHA changed the name of the WordPress option variable where it stores its API key, so Deko Boko now looks for it under the new name and the old name.
* Added a language selector to the Deko Boko settings menu, for choosing a language other than English for the reCAPTCHA widget.

= 1.0.1 =
* bug fix: the Settings form now correctly lists the selected reCAPTCHA widget theme.

= 1.0 =
* Added Settings selector for choosing a reCAPTCHA theme, and CSS color options for use with the “clean” theme (thanks Matthew E. for the suggestion).
* Added protection from cross site scripting attacks when displaying an invalid email address back to the user (thanks Chris S.).
* Added support for multiple templates
* Added “CC Me” option (thanks Cheryl M.)
* Bug Fix: now behaves correctly if you have a form with no optional fields (thanks Jeff for reporting this)

= 0.9 =
* Beta version. First public release.
