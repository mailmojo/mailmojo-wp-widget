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
	const MM_INTEGRATIONS_URL = 'https://v3.mailmojo.no/integrations/wordpress/';

	private $options;
	private $plugin;

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
		$obj = self::$instance;

		if (empty($obj)) {
			self::$instance = new MailMojoSettings();
		}

		return self::$instance;
	}

	/**
	 * Return access token stored as an option if it exist.
	 *
	 * @param bool $obfuscate Obfuscate the access token.
	 * @return string
	 */
	public function getAccessToken ($obfuscate = false) {
		$token = !empty($this->options['access_token']) ? $this->options['access_token'] : '';

		if ($obfuscate && !empty($token)) {
			return '*********' . substr($token, 9);
		}

		return $token;
	}

	/**
	 * Check if an access token is valid for fetching email lists.
	 *
	 * Temporarily overrides API access token in API configuration, then
	 * attempts to fetch lists from the API. If the attempt fails, we assume
	 * the access token is invalid.
	 *
	 * @param string $token
	 * @return bool
	 */
	public function validateAccessToken () {
		$token = $this->getAccessToken();

		if (empty($token)) {
			return true;
		}

		$apiConfig = MailMojo\Configuration::getDefaultConfiguration();
		$existingToken = $apiConfig->getAccessToken();
		$isValid = true;

		$apiConfig->setAccessToken($token);
		$api = new MailMojo\Api\ListsApi();

		try {
			$lists = $api->getLists();
		}
		catch (MailMojo\ApiException $e) {
			$isValid = false;
		}

		$apiConfig->setAccessToken($existingToken);

		return $isValid;
	}

	/**
	 * Returns username stored as an option if it exists.
	 *
	 * TODO: Remove when username is not needed anymore.
	 *
	 * @return string
	 */
	public function getUsername () {
		return !empty($this->options['username']) ? $this->options['username'] : '';
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
			array($this, 'createAdminPage')
		);
	}

	/**
	 * Initiate the settings page.
	 */
	public function pageInit () {
		$token = $this->getAccessToken();

		register_setting(
			self::GROUP_NAME,
			'mailmojo_options',
			array($this, 'sanitize')
		);

		add_settings_section(
			'mailmojo_settings_id',
			'API Settings',
			array($this, 'settingsSection'),
			'mailmojo-settings-admin'
		);

		add_settings_field(
			'access_token',
			'Access Token',
			array($this, 'accessTokenField'),
			'mailmojo-settings-admin',
			'mailmojo_settings_id'
		);

		// TODO: Remove when username is not needed anymore.
		if (empty($token)) {
			add_settings_field(
				'username',
				'Username',
				array($this, 'usernameField'),
				'mailmojo-settings-admin',
				'mailmojo_settings_id'
			);
		}
	}

	/**
	 * Remove settings from database.
	 */
	public function removeSettings () {
		unregister_setting(self::GROUP_NAME, 'mailmojo_options');
	}

	/**
	 * Hook for printing the admin page for the settings.
	 *
	 * Also performs validation of any configured token, giving an error message
	 * if the token is invalid.
	 */
	public function createAdminPage () {
		$notice = null;

		if (!$this->validateAccessToken()) {
			$notice = sprintf(
				'<div class="notice notice-error"><p>' .
					__('Access token is not valid. You\'ll need to <a href="%s">retrieve a new access token</a> from the WordPress client in MailMojo.', 'mailmojo') .
				'</p></div>',
				MailMojoSettings::MM_INTEGRATIONS_URL
			);
		}

		include('templates/settings.php');
	}

	/**
	 * Output section for settings.
	 */
	public function settingsSection () {
		echo '<p>' . sprintf(__('To connect with your MailMojo account, you need to <a href="%s">retrieve an access token</a> from a Wordpress client in MailMojo. This will enable the widget to retrieve the necessary data to enable signups.', 'mailmojo'),
			MailMojoSettings::MM_INTEGRATIONS_URL) . '</p>';
	}

	/**
	 * Output settings field for access token.
	 */
	public function accessTokenField () {
		$integrationUrl = self::MM_INTEGRATIONS_URL;
		printf(
			'<input type="text" id="access_token" name="mailmojo_options[access_token]" ' .
			'value="%s" placeholder="%s" class="regular-text code"><p><a href="%s">%s</a></p>',
				esc_attr($this->getAccessToken(true)),
			__('Paste the access token here', 'mailmojo'),
			$integrationUrl,
			__('Get your access token here', 'mailmojo')
		);
	}

	/**
	 * Output settings field for username.
	 *
	 * TODO: Remove when username is not needed anymore.
	 */
	public function usernameField () {
		printf(
			'<input type="text" id="username" name="mailmojo_options[username]" ' .
			'value="%s" readonly><p><small><em>%s</em></small></p>',
			esc_attr($this->getUsername()),
			__('Deprecated in favor of using the MailMojo API.')
		);
	}

	/**
	 * Sanitize the settings field before saving it.
	 *
	 * Fields that have been obfuscated (contains nine stars) will not be saved
	 * to the option field due to being incomplete.
	 */
	public function sanitize ($input) {
		$new_input = array();

		foreach ($input as $key => $value) {
			if (stripos($value, '*********') === False) {
				$new_input[$key] = sanitize_text_field($value);
			}
			else {
				$new_input[$key] = $this->options[$key];
			}
		}

		return $new_input;
	}
}
