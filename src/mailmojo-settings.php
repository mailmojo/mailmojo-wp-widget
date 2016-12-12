<?php
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
class MailMojoSettings {
	const GROUP_NAME = 'mailmojo-widget-settings';
	const MENU_SLUG = 'mailmojo-widget';
	const MENU_TITLE = 'MailMojo';
	const PAGE_TITLE = 'MailMojo';

	/*
	 * The singleton instance
	 */
	private static $instance;

	/**
	 * Initiates the settings.
	 */
	public function __construct () {
		add_action('admin_menu', array($this, 'addPluginPage'));
		add_action('admin_init', array($this, 'pageInit'));
		$this->options = get_option('mailmojo_options');
	}

	/**
	 * Return the one and only singleton instance of this class.
	 */
	public static function getInstance () {
		if (empty(self::$instance)) {
			self::$instance = new MailMojoSettings();
		}

		return self::$instance;
	}

	/**
	 * Add plugins page.
	 */
	public function addPluginPage () {
		add_options_page(
			self::PAGE_TITLE,
			self::MENU_TITLE,
			'manage_options',
			self::MENU_SLUG,
			array($this, 'create_admin_page')
		);
	}

	/**
	 * Initiate the settings page.
	 */
	public function pageInit () {
		register_setting(
			self::GROUP_NAME,
			'mailmojo_options',
			array($this, 'sanitize')
		);

		add_settings_section(
			'mailmojo_settings_id',
			'Settings',
			array($this, 'settingsSection'),
			'mailmojo-settings-admin'
		);

		add_settings_field(
			'username',
			'Username',
			array($this, 'usernameField'),
			'mailmojo-settings-admin',
			'mailmojo_settings_id'
		);
	}

	/**
	 * Remove settings from database.
	 */
	public function removeSettings () {
		unregister_setting(self::GROUP_NAME, 'mailmojo_options');
	}

	/**
	 * Hook for printing the admin page for the settings.
	 */
	public function create_admin_page () {
		include('templates/settings.php');
	}

	/**
	 * Output section for settings.
	 */
	public function settingsSection () {
		print('Enter the username of your MailMojo Account.');
	}

	/**
	 * Output settings field for username.
	 */
	public function usernameField () {
		printf(
			'<input type="text" id="username" name="mailmojo_options[username]" ' .
			'value="%s">',
			isset($this->options['username']) ?
				esc_attr($this->options['username']) : ''
		);
	}

	/**
	 * Sanitize the settings field before saving it.
	 */
	public function sanitize ($input) {
		$new_input = array();

		foreach ($input as $key => $value) {
			$new_input[$key] = sanitize_text_field($value);
		}

		return $new_input;
	}
}
