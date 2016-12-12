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
class MailMojoWidget extends WP_Widget {
	/*
	 * MailMojoPlugin
	 */
	private $plugin;

	/**
	 * Inits the widget, script, styles and subscribe action.
	 */
	public function __construct() {
		$options = array(
			'description' => __('Easily integrate a mailing list signup form.', 'mailmojo')
		);
		parent::__construct('mailmojo', __('MailMojo Signup Form', 'mailmojo'), $options);

		add_action('init', array($this, 'initFiles'));
		add_action('parse_request', array($this, 'subscribe'));

		$this->plugin = MailMojoPlugin::getInstance();
	}

	/**
	 * Inits the javascript, css and localization files.
	 */
	public function initFiles () {
		wp_enqueue_style(
			'mailmojo',
			plugins_url('css/mailmojo.css', __FILE__)
		);
	}

	/**
	 * Outputs the widget form on widgets admin page.
	 *
	 * @param array $instance
	 */
	public function form ($instance) {
		if (!$this->plugin->isActive()) {
			$url = $this->plugin->getSettingsPageUrl();

			echo '<p>' . sprintf(
				__('You need to enter your MailMojo account information on the <a href="%s">MailMojo settings page</a>', 'mailmojo'),
				$url) . '</p>';
			return;
		}

		$defaults = array(
			'listid' => '',
			'title' => __('Newsletter Signup', 'mailmojo'),
			'desc' => '',
			'incname' => false,
			'tagdesc' => __('Interests', 'mailmojo'),
			'tags' => '',
			'buttontext' => __('Sign me up!', 'mailmojo'),
		);

		$instance = wp_parse_args($instance, $defaults);
		$instance['incname'] = checked($instance['incname'], true, false);

		include('templates/widget-admin.php');
	}

	/**
	 * Processes widget options to be saved.
	 *
	 * @param array $newInstance New options
	 * @param array $oldInstance Old options
	 * @return array Merged options
	 */
	public function update ($newInstance, $oldInstance) {
		$newInstance = array_map('esc_attr', $newInstance);
		$instance = wp_parse_args($newInstance, $oldInstance);
		// Custom mapping for bool values
		$instance['incname'] = !empty($newInstance['incname']);
		return $instance;
	}

	/**
	 * Outputs the content of the widget, though only if all settings is present.
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget ($args, $instance) {
		if (!$this->plugin->isActive() || empty($instance['listid'])) {
			return '';
		}

		if (!empty($instance['tags'])) {
			$instance['tags'] = explode(',', $instance['tags']);
		}

		include('templates/widget-page.php');
	}

	/**
	 * Returns URL to MailMojo subscription endpoint for the given list.
	 *
	 * @param $listid
	 * @return string
	 */
	private function getSubscribeUrl ($listid) {
		$username = $this->plugin->getUsername();
		return "https://{$username}.mailmojo.no/{$listid}/s";
	}
}
