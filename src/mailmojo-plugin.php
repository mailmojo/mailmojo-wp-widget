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
	 * Return true if plugin is fully activated.
	 *
	 * @return bool
	 */
	public function isActive () {
		$options = get_option('mailmojo_options');
		return !empty($options['username']);
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
		return "{$adminUrl}options-general.php?page={MailMojoSettings::MENU_SLUG}";
	}

	/**
	 * Returns username stored as an option if it exist.
	 *
	 * @return string
	 */
	public function getUsername () {
		$options = get_option('mailmojo_options');
		return !empty($options['username']) ? $options['username'] : '';
	}
}
