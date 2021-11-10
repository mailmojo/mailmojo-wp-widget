<?php
/*
Plugin Name: MailMojo Widget
Plugin URI: http://github.com/eliksir/MailMojo-WP-Widget
Description: Adds a signup widget for a MailMojo mailing list to your WordPress site.
Author: Eliksir AS
Author URI: http://e5r.no
Version: 1.0.5
*/

/*
 * Copyright Eliksir AS  (email : post@e5r.no)
 * License: GPLv2 <http://www.gnu.org/licenses/gpl-2.0.html>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (version_compare(PHP_VERSION, '5.4.0') < 0) {
	/**
	 * Automatically deactivate plugin on init if PHP version requirement
	 * is not met.
	 */
	function mailmojo_deactivate_plugin () {
		if (current_user_can('activate_plugins') &&
				is_plugin_active(plugin_basename(__FILE__))) {
			deactivate_plugins(plugin_basename(__FILE__));

			// Hide the default "Plugin activated" notice
			if (isset($_GET['activate'])) {
				unset($_GET['activate']);
			}
		}
	}

	/**
	 * Show a PHP version warning in admin if requirement not met.
	 *
	 * This will only be displayed once when plugin is attempted activated,
	 * since we automatically deactivate it in `mailmojo_deactivate_plugin`.
	 */
	function mailmojo_show_version_notice () {
		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			__('The MailMojo Widget plugin requires PHP 5.4 or newer.', 'mailmojo')
		);
	}

	add_action('admin_init', 'mailmojo_deactivate_plugin');
	add_action('admin_notices', 'mailmojo_show_version_notice');
}
else {
	include_once('vendor/autoload.php');
	include_once('mailmojo-plugin.php');
	include_once('mailmojo-settings.php');
	include_once('mailmojo-widget.php');

	MailMojoPlugin::getInstance();


	register_uninstall_hook(__FILE__, array('MailMojoSettings', 'removeSettings'));
}
