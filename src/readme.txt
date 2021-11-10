=== MailMojo Widget ===
Contributors: stianpr, asteinlein, fdanielsen
Tags: mailmojo, newsletter, newsletters, mailing list, signup, subscribe, widget, email, email marketing, email
Requires at least: 4.0
Tested up to: 5.8.1
Stable tag: 1.0.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a signup widget for a MailMojo mailing list to your WordPress site.

== Description ==

> <strong>This plugin requires a MailMojo account. MailMojo is a Norwegian email marketing webapp.</strong><br />Get in on the mojo: <a href="http://mailmojo.no">Sign up for a free account here</a>.

This plugin provides a super easy way for your visitors to sign up to
your MailMojo mailing list. You can add multiple instances of the widget
to accept signups to different lists, and tags are supported for
segmentation.

You are welcome to contribute to this plugin on
<a href="https://github.com/eliksir/mailmojo-wp-widget">Github</a>.

== Installation ==

This plugin requires the PHP curl extension.

1. Upload the widget directory to /wp-content/plugins/
1. Activate the plugin through the "Plugins" menu in the WordPress admin
1. Enter settings for the plugin at the "Settings > MailMojo" page
1. Drag the widget to your desired location at the "Appearance > Widgets" page
1. Specify the mailing list in your account, and customize any settings you want
1. Receive signups, wheee!

== Screenshots ==

1. The signup form in your sidebar.
2. Customize the widget with your choices.

== Changelog ==

= 1.0.5 =
* Verify compatibility with WordPress 5.8.1.

= 1.0.4 =
* Fix compatibility with PHP 7.2.
* Fix link to MailMojo integrations page with token.
* Verify compatibility with WordPress 5.

= 1.0.3 =
* Fix syntax errors in PHP 5.4.

= 1.0.2 =
* Sort email lists in widget dropdown
* Validate access token when updating settings

= 1.0.1 =
* Fix syntax errors in PHP 5.4.

= 1.0.0 =
* Important: Requires PHP 5.4 or newer.
* Use MailMojo API to support retrieval of email lists.
* Support single vs multiple tag selection.
* Support fixed tags.

= 0.7 =
* Prevent spam registrations by leveraging MailMojo's subscription endpoint
  with reCAPTCHA.

= 0.6 =
* Support WordPress not being hosted on root of domain
* Use paragraph HTML element for description in widget output

= 0.5 =
* Improve error handling

= 0.4 =
* Tested and verified for WordPress 3.6.1
* Fixed a bug with capitalizing first char in tags

= 0.3 =
* Widget can now be used on PHP 5.2 or greater.

= 0.2.1 =
* Fixed a bug where deactivation removed settings.

= 0.2 =
* Norwegian (Bokmål) translation.

= 0.1 =
* Initial release.

== Internationalization (i18n) ==

In addition to the default English language, translations in the
following languages are included:

* nb_NO - Norwegian (Bokmål)

If you're interested in doing a translation, please make a pull request
on <a href="https://github.com/eliksir/mailmojo-wp-widget">Github</a>.
