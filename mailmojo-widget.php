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
class MailMojoWidget extends WP_Widget {
	/*
	 * MailMojoPlugin
	 */
	private $mmPlugin;

	/**
	 * Inits the widget, script, styles and subscribe action.
	 */
	public function __construct() {
		$options = array(
			'description' => __('Easily integrate a mailing list signup form.', 'mailmojo')
		);
		parent::__construct('mailmojo', __('MailMojo Signup Form', 'mailmojo'), $options);

		$this->mmPlugin = MailMojoPlugin::getInstance();

		// Add custom javascript and css file
		add_action('init', function () {
			wp_enqueue_script(
				'mailmojo',
				plugins_url('js/mailmojo.js', __FILE__),
				array('jquery'), false
			);

			// Add localized strings to our script
			wp_localize_script('mailmojo', ' MailMojoWidget', array(
				'linkText' => __('Click to add more', 'mailmojo')
			));

			wp_enqueue_style(
				'mailmojo',
				plugins_url('css/mailmojo.css', __FILE__)
			);
		});

		// Custom parse request for subscriptions
		add_action('parse_request', array($this, 'subscribe'));
	}

	/**
	 * Outputs the widget form on widgets admin page.
	 *
	 * @param array $instance
	 */
	public function form ($instance) {
		$mmApi = $this->mmPlugin->getApi();
		if ($mmApi === null) {
			global $blog_id;
			$adminUrl = get_admin_url($blog_id);
			$output = "<p>%s <a href=\"{$adminUrl}options-general.php?page={$this->mmPlugin->getBasename()}\">%s</a>.</p>";
			echo sprintf($output,
				__('You need to enter your MailMojo account information on the', 'mailmojo'),
				__('MailMojo settings page', 'mailmojo')
			);
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
			'successmsg' => __('Ta-da! You\'ve successfully signed up. Thank you!', 'mailmojo')
		);
		$vars = wp_parse_args($instance, $defaults);
		extract($vars);

		$incname = checked($incname, true, false);
		$output = <<<HTML
<h3>%s</h3>
<p>
	<label for="{$this->get_field_id('listid')}">%s:</label>
	<input class="widefat" type="text" id="{$this->get_field_id('listid')}"
			name="{$this->get_field_name('listid')}" value="{$listid}">
	<br/>
	<small>%s</small>
</p>
<p>
	<label for="{$this->get_field_id('title')}">%s:</label>
	<input class="widefat" type="text" id="{$this->get_field_id('title')}"
			name="{$this->get_field_name('title')}" value="{$title}">
</p>
<p>
	<label for="{$this->get_field_id('desc')}">%s:</label>
	<textarea class="widefat" id="{$this->get_field_id('desc')}"
			name="{$this->get_field_name('desc')}">{$desc}</textarea>
</p>
<p>
	<label for="{$this->get_field_id('incname')}">
		<input type="checkbox" id="{$this->get_field_id('incname')}"
			name="{$this->get_field_name('incname')}" {$incname}>
		%s
	</label>
</p>
<p>
	<label for="{$this->get_field_id('buttontext')}">%s:</label>
	<input class="widefat" type="text" id="{$this->get_field_id('buttontext')}"
			name="{$this->get_field_name('buttontext')}" value="{$buttontext}">
</p>
<h3>%s</h3>
<p>
	<label for="{$this->get_field_id('tagdesc')}">%s:</label>
	<input class="widefat" type="text" id="{$this->get_field_id('tagdesc')}"
			name="{$this->get_field_name('tagdesc')}" value="{$tagdesc}">
</p>
<p>
	<label for="{$this->get_field_id('tags')}">%s:</label>
	<textarea class="widefat" id="{$this->get_field_id('tags')}"
			name="{$this->get_field_name('tags')}">{$tags}</textarea>
</p>
<h3>%s</h3>
<p>
	<label for="{$this->get_field_id('successmsg')}">%s:</label>
	<textarea class="widefat" id="{$this->get_field_id('successmsg')}"
			name="{$this->get_field_name('successmsg')}">{$successmsg}</textarea>
</p>
HTML;
		echo sprintf($output,
			__('General', 'mailmojo'),
			__('MailMojo List ID', 'mailmojo'),
			__('To find the List ID: go to the list of your choice, in MailMojo, and look at the last part of the URL. That is the List ID. E.g: "mailmojo.no/lists/123", where 123 is the List ID.', 'mailmojo'),
			__('Title', 'mailmojo'),
			__('Description Below Title', 'mailmojo'),
			__('Include name field', 'mailmojo'),
			__('Signup Button Text', 'mailmojo'),
			__('Optional Tags', 'mailmojo'),
			__('Tag Selection Label', 'mailmojo'),
			__('Tags (comma separated)', 'mailmojo'),
			__('Notifications', 'mailmojo'),
			__('Success Message', 'mailmojo')
		);
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
		$incname = $tags = $desc = '';
		extract($args);
		$mmApi = $this->mmPlugin->getApi();
		if ($mmApi === null || empty($instance['listid'])) {
			return '';
		}

		// Include name input field
		if ($instance['incname']) {
			$incname = <<<HTML
<div class="field">
	<label for="mailmojo_name">%s:</label>
	<input class="text" type="text" id="mailmojo_name" name="mailmojo_name">
</div>
HTML;
			$incname = sprintf($incname, __('Name', 'mailmojo'));
		}

		// Checkboxes for tags
		if (!empty($instance['tags'])) {
			$tags = $this->getHtmlForTags($instance);
		}

		// Description
		if (!empty($instance['desc'])) {
			$desc = "<h2>{$instance['desc']}</h2>";
		}

		// The main output of the widget
		$output = <<<HTML
{$before_widget}
	{$before_title}{$instance['title']}{$after_title}
	$desc
	<form method="post" id="mailmojo_form_{$this->number}" class="mailmojo_form">
		<div class="field">
			<label for="mailmojo_email">%s:</label>
			<input class="text" type="text" id="mailmojo_email" name="mailmojo_email">
		</div>
		$incname
		$tags
		<div class="submit">
			<input type="hidden" name="mailmojo_listid" value="{$instance['listid']}">
			<input class="submit" type="submit" name="mailmojo_subscribe" value="{$instance['buttontext']}">
			<img class="loader" src="%s" alt="loading..." height="16" width="16">
		</div>
	</form>
{$after_widget}
HTML;
		echo sprintf($output,
			__('E-mail', 'mailmojo'),
			plugins_url('img/loader.gif', __FILE__)
		);
	}

	/**
	 * Returns the HTML for checkboxes with tags.
	 *
	 * @param $instance
	 * @return string
	 */
	private function getHtmlForTags ($instance) {
		$output = '';
		if (!empty($instance['tags'])) {
			if (!empty($instance['tagdesc'])) {
				$output .= "<h3>{$instance['tagdesc']}:</h3>\n";
			}
			$tags = explode(',', $instance['tags']);
			$output .= "<ul class=\"field\">\n";
			foreach ($tags as $tag) {
				$t = ucfirst(mb_strtolower($tag));
				$output .= <<<HTML
<li>
	<label>
		<input type="checkbox" name="mailmojo_tags[]" value="{$tag}" />
		{$t}
	</label>
</li>
HTML;
			}
			$output .= "</ul>\n";
		}
		return $output;
	}

	/**
	 * Processes the ajax request from the MailMojo widget. Subcribes
	 * the contact and returns correct response message and status code.
	 *
	 * TODO: What about none javascript users?
	 */
	public function subscribe () {
		if (!empty($_POST['mailmojo_listid'])) {
			$listid = $_POST['mailmojo_listid'];
			$email = !empty($_POST['mailmojo_email']) ? $_POST['mailmojo_email'] : '';
			$name = !empty($_POST['mailmojo_name']) ? $_POST['mailmojo_name'] : '';
			$tags = !empty($_POST['mailmojo_tags']) ? implode(',', $_POST['mailmojo_tags']) : '';

			$result = array('msg' => '', 'success' => false);

			if (empty($email)) {
				$result['msg'] = __('You must provide an e-mail address.', 'mailmojo');
			}
			else if (!is_email($email)) {
				$result['msg'] = __('Invalid e-mail address.', 'mailmojo');
			}
			else {
				try {
					$mmApi = $this->mmPlugin->getApi();
					if ($mmApi->subscribe($listid, $email, $name, $tags)) {
						$result['success'] = true;
						$result['msg'] = $this->getSuccessMsg();
					}
					else {
						$result['msg'] = __('An unknown error occured.', 'mailmojo');
					}
				}
				catch (Exception $e) {
					$result['msg'] = __('PHP curl extension not installed.', 'mailmojo');
				}
			}
			header('Content-Type: application/json');
			exit(json_encode($result));
		}
	}

	/**
	 * Return the widget option for successmsg
	 *
	 * @return string
	 */
	public function getSuccessMsg () {
		$options = get_option($this->option_name);
		$number = $this->number;
		return $options[$number]['successmsg'];
	}
}
