<?php
/*  Copyright 2011  Eliksir AS  (email : post@e5r.no)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class MailMojoPlugin {
	/*
	 * The singleton instance
	 */
	private static $instance;

	/*
	 * MailMojo username
	 */
	public $username;

	/**
	 * Initiates the plugin.
	 */
	private function __construct () {
		add_action('admin_init', array($this, 'registerSettings'));
		add_action('admin_menu', array($this, 'initSettingsPage'));

		$this->initWidget();

		$options = $this->getOptions();
		$this->username = !empty($options['username']) ? $options['username'] : '';

		// Init localization for the plugin
		$this->loadTextDomain();
	}

	/**
	 * Return the one and only singleton instance of this class.
	 */
	public function getInstance () {
		if (empty(self::$instance)) {
			self::$instance = new MailMojoPlugin();
		}
		return self::$instance;
	}

	/**
	 * Adds mailmojo_options to the database when activating this plugin. The widget
	 * options will automatically be created when used the first time.
	 */
	public static function setUpOptions () {
		add_option('mailmojo_options', '', '', 'no');
	}

	/**
	 * Removes all database options for MailMojo widget.
	 */
	public static function removeOptions () {
		delete_option('mailmojo_options');
		// Since we know our widget is named mailmojo we can safely remove it.
		delete_option('widget_mailmojo');
	}

	/**
	 * Register mailmojo_options so that POST action in admin
	 * will save our options.
	 */
	public function registerSettings () {
		register_setting('mailmojo_options', 'mailmojo_options');
	}

	/**
	 * Adds the MailMojo settings page as sub menu to settings.
	 */
	public function initSettingsPage () {
		add_submenu_page(
			'options-general.php', 'MailMojo', 'MailMojo',
			'activate_plugins', __FILE__, array($this, 'adminPage')
		);
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
	 * Output the content for the MailMojo settings page.
	 */
	public function adminPage () {
		$output = <<<HTML
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>%s</h2>
HTML;
		echo sprintf($output, __('MailMojo Settings', 'mailmojo'));
		if (function_exists('curl_init')) {
			echo '<p>' . __('Enter the username of the MailMojo account where the mailing list you want signups to are located. After saving the changes, go to the Widgets menu in the Appearance section to configure your widget.', 'mailmojo') . '</p>';
			echo '<form action="options.php" method="post">';
			settings_fields('mailmojo_options');
			$output = <<<HTML
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="mailmojo-username">%s</label>
					</th>
					<td>
						<input name="mailmojo_options[username]" type="text"
							id="mailmojo-username" value="{$this->username}"
							class="regular-text code" />
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="%s" />
			</p>
		</form>
	</div>
HTML;
			echo sprintf($output,
					__('Username', 'mailmojo'),
					__('Save Changes', 'mailmojo')
			);
		}
		else {
			echo '<p>' . __('You need to have the PHP Client URL Library (cURL) to be able to use this widget. You can read up on how to install the extension <a href="http://php.net/manual/en/book.curl.php">here</a>', 'mailmojo') . '</p>';
		}
	}

	/**
	 * Returns the basename of the plugin. Relative to the wp-content/plugins/ directory.
	 */
	public function getBasename () {
		return plugin_basename(__FILE__);
	}

	/**
	 * Returns the options for MailMojo. Since we store our option serialized,
	 * this is returned as an array with out options as keys.
	 *
	 * @return array
	 */
	public function getOptions () {
		return get_option('mailmojo_options');
	}

	/**
	 * Returns reference to MailMojoApi. Used in the widget.
	 *
	 * @return MailMojoApi
	 */
	public function getApi () {
		if (!empty($this->username)) {
			return new MailMojoApi($this->username);
		}
		return null;
	}
}
