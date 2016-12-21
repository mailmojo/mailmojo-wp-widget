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
class MailMojoPlugin {
	private $settings;

	/*
	 * The singleton instance
	 */
	private static $instance;

	/**
	 * Initiates the plugin.
	 */
	private function __construct () {
		$this->initWidget();
		$this->loadTextDomain();
		$this->settings = MailMojoSettings::getInstance();

		$accessToken = $this->settings->getAccessToken();
		if ($accessToken) {
			MailMojo\Configuration::getDefaultConfiguration()->setAccessToken($accessToken);
		}
		else {
			add_action('admin_notices', array($this, 'displayNoticeInfo'));
		}
	}

	/**
	 * Return the one and only singleton instance of this class.
	 */
	public static function getInstance () {
		if (empty(self::$instance)) {
			self::$instance = new MailMojoPlugin();
		}

		return self::$instance;
	}

	/**
	 * Display a site-wide notice on missing settings for the widget.
	 *
	 * For users that have upgraded from v0.x to v1 the signup form will still
	 * work but we want them to add an access token instead.
	 */
	public function displayNoticeInfo () {
		echo '<br><div class="update-nag">' . sprintf(
			__('Settings for the MailMojo widget is outdated. <a href="%s">Please update the settings now</a>.', 'mailmojo'),
			$this->getSettingsPageUrl()
		) . '</div>';
	}

	/**
	 * Return true if access token is present.
	 *
	 * @return bool
	 */
	public function isActive () {
		return !empty($this->settings->getAccessToken());
	}

	/**
	 * Inits the MailMojo widget.
	 */
	public function initWidget () {
		add_action('widgets_init', create_function('',
			'return register_widget("MailMojoWidget");')
		);
	}

	/**
	 * Loads the i18n file.
	 */
	public function loadTextDomain () {
		$path = basename(dirname( __FILE__ )) . '/languages';
		load_plugin_textdomain('mailmojo', false, $path);
	}

	/**
	 * Return URL for the settings page.
	 *
	 * @return string
	 */
	public function getSettingsPageUrl () {
		global $blog_id;
		$adminUrl = get_admin_url($blog_id);
		$slug = MailMojoSettings::MENU_SLUG;
		return "{$adminUrl}options-general.php?page={$slug}";
	}

}
