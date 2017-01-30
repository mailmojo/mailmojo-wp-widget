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
	 * Email lists retrieved from MailMojo.
	 */
	private static $lists;

	/**
	 * Inits the widget, script, styles and subscribe action.
	 */
	public function __construct() {
		$options = array(
			'description' => __('Easily integrate a mailing list signup form.', 'mailmojo')
		);
		parent::__construct('mailmojo', __('MailMojo Signup Form', 'mailmojo'), $options);

		add_action('init', array($this, 'initFiles'));

		$this->plugin = MailMojoPlugin::getInstance();
	}

	/**
	 * Adds our custom CSS file.
	 *
	 * XXX: Consider removing the CSS file since it only contains a one-liner.
	 */
	public function initFiles () {
		wp_enqueue_style(
			'mailmojo',
			plugins_url('css/mailmojo.css', __FILE__)
		);
	}

	/**
	 * Return true if the widget is enabled.
	 *
	 * Minimum widget data needs to be present: subscribeurl or the deprecated listid.
	 *
	 * @return bool
	 */
	public function isEnabled () {
		$options = get_option('mailmojo_options');
		// TODO: Remove options check on listid when username is removed
		return !empty($options['subscribeurl']) || !empty($options['listid']);
	}

	/**
	 * Outputs the widget form on widgets admin page.
	 *
	 * @param array $instance
	 */
	public function form ($instance) {
		$settingsPageUrl = $this->plugin->getSettingsPageUrl();

		if (!$this->plugin->isActive()) {
			echo '<p>' . sprintf(
				__('You need to enter your MailMojo account information on the <a href="%s">MailMojo settings page</a> first.', 'mailmojo'),
					 $settingsPageUrl) . '</p>';
			return;
		}

		if (self::$lists === null) {
			try {
				$api = new MailMojo\Api\ListsApi();

				self::$lists = $api->getLists();
				usort(self::$lists, function ($listA, $listB) {
					return strcmp($listA->getName(), $listB->getName());
				});
			}
			catch (MailMojo\ApiException $e) {
				echo '<p>' . sprintf(
					__('Your email lists could not be retrieved. Please go to <a href="%s">MailMojo settings page</a> and make sure the access token is correct.', 'mailmojo'),
						 $settingsPageUrl) . '</p>';
				return;
			}

			if (count(self::$lists) === 0) {
				echo '<p>' . __("You don't have any email lists in MailMojo. Please go to MailMojo and add one. Refresh this page when done.", 'mailmojo') . '</p>';
				return;
			}
		}

		$defaults = array(
			'listid' => '',  // TODO: Remove when username is not needed anymore.
			'subscribeurl' => '',
			'title' => __('Newsletter Signup', 'mailmojo'),
			'desc' => '',
			'incname' => false,
			'tagdesc' => __('Interests:', 'mailmojo'),
			'tagtype' => 'multiple',
			'tags' => '',
			'fixedtags' => '',
			'buttontext' => __('Sign me up!', 'mailmojo'),
		);

		$instance = wp_parse_args($instance, $defaults);
		$instance['incname'] = checked($instance['incname'], true, false);

		// TODO: Remove when username is not present anymore.
		if (empty($instance['subscribeurl']) && !empty($instance['listid'])) {
			$instance['subscribeurl'] = $this->getSubscribeUrl($instance['listid']);
		}

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
	 * Outputs the content of the widget, though only if all settings are present.
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget ($args, $instance) {
		// TODO: Change to only check subscribe url when listid is removed
		if (empty($instance['subscribeurl']) && empty($instance['listid'])) {
			return '';
		}

		if (!empty($instance['tags'])) {
			$instance['tags'] = explode(',', $instance['tags']);
		}

		// TODO: Remove when username is not present anymore.
		if (empty($instance['subscribeurl'])) {
			$instance['subscribeurl'] = $this->getSubscribeUrl($instance['listid']);
		}

		include('templates/widget-page.php');
	}

	/**
	 * Returns URL to MailMojo subscription endpoint for the given list.
	 *
	 * TODO: Remove when username is not needed anymore.
	 *
	 * @param $listid
	 * @return string
	 */
	private function getSubscribeUrl ($listid) {
		$username = $this->plugin->settings->getUsername();
		return "https://{$username}.mailmojo.no/{$listid}/s";
	}
}
