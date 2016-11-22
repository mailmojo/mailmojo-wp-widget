<?php
/*  Copyright Eliksir AS  (email : post@e5r.no)

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
		add_action('init', array($this, 'initFiles'));
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
		if (empty($this->mmPlugin->username)) {
			global $blog_id;
			$adminUrl = get_admin_url($blog_id);
			$url = "{$adminUrl}options-general.php?page={$this->mmPlugin->getBasename()}";
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
HTML;
		echo sprintf($output,
			__('General', 'mailmojo'),
			__('MailMojo List ID', 'mailmojo'),
			__('To find the list ID: Go to the email list of your choice in MailMojo, and look at the last part of the URL. That is the list ID. E.g. given "mailmojo.no/lists/123", 123 is the list ID.', 'mailmojo'),
			__('Title', 'mailmojo'),
			__('Description Below Title', 'mailmojo'),
			__('Include name field', 'mailmojo'),
			__('Signup Button Text', 'mailmojo'),
			__('Optional Tags', 'mailmojo'),
			__('Tag Selection Label', 'mailmojo'),
			__('Tags (comma separated)', 'mailmojo')
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

		if (empty($this->mmPlugin->username) || empty($instance['listid'])) {
			return '';
		}

		// Include name input field
		if ($instance['incname']) {
			$incname = <<<HTML
<p class="field">
	<label for="mailmojo_{$this->number}_name">%s:</label>
	<input class="text" type="text" id="mailmojo_{$this->number}_name" name="name">
</p>
HTML;
			$incname = sprintf($incname, __('Name', 'mailmojo'));
		}

		$tags = '';
		if (!empty($instance['tags'])) {
			$tags = $this->getHtmlForTags($instance);
		}

		$desc = '';
		if (!empty($instance['desc'])) {
			$desc = "<p>{$instance['desc']}</p>";
		}

		// The main output of the widget
		$output = <<<HTML
{$before_widget}
	{$before_title}{$instance['title']}{$after_title}
	$desc
	<form method="post" action="{$this->getSubscribeUrl($instance['listid'])}"
			id="mailmojo_{$this->number}_form"
			class="mailmojo_form">
		<p class="field">
			<label for="mailmojo_{$this->number}_email">%s:</label>
			<input class="text" type="text" id="mailmojo_{$this->number}_email" name="email">
		</p>
		$incname
		$tags
		<p class="submit">
			<input class="submit" type="submit" value="{$instance['buttontext']}">
		</p>
	</form>
{$after_widget}
HTML;
		echo sprintf($output, __('E-mail', 'mailmojo'));
	}

	/**
	 * Returns URL to MailMojo subscription endpoint for the given list.
	 *
	 * @param $listid
	 * @return string
	 */
	private function getSubscribeUrl ($listid) {
		return "https://{$this->mmPlugin->username}.mailmojo.no/{$listid}/s";
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
				$output .= "<p>{$instance['tagdesc']}:</p>\n";
			}
			$tags = explode(',', $instance['tags']);
			$output .= "<ul class=\"field\">\n";
			foreach ($tags as $tag) {
				$t = ucfirst(mb_strtolower(trim($tag)));
				$output .= <<<HTML
<li>
	<label>
		<input type="checkbox" name="tags[]" value="{$tag}" />
		{$t}
	</label>
</li>
HTML;
			}
			$output .= "</ul>\n";
		}
		return $output;
	}
}
