<h3><?php echo __('General', 'mailmojo') ?></h3>
<p>
	<label for="<?php echo $this->get_field_id('listid') ?>">
		<?php echo __('MailMojo List ID', 'mailmojo') ?>:
	</label>
	<input class="widefat" type="text"
		id="<?php echo $this->get_field_id('listid') ?>"
		name="<?php echo $this->get_field_name('listid') ?>"
		value="<?php echo $instance['listid'] ?>">
	<br/>
	<small>
		<?php echo __('To find the list ID: Go to the email list of your choice in MailMojo,
		and look at the last part of the URL. That is the list ID. E.g. given
		"mailmojo.no/lists/123", 123 is the list ID.', 'mailmojo') ?>
	</small>
</p>

<p>
	<label for="<?php echo $this->get_field_id('title') ?>">
		<?php echo __('Title', 'mailmojo') ?>:
	</label>
	<input class="widefat" type="text"
			id="<?php echo $this->get_field_id('title') ?>"
			name="<?php echo $this->get_field_name('title') ?>"
			value="<?php echo $instance['title'] ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id('desc') ?>">
		<?php echo __('Description Below Title', 'mailmojo') ?>:
	</label>
	<textarea class="widefat"
			id="<?php echo $this->get_field_id('desc') ?>"
			name="<?php echo $this->get_field_name('desc') ?>"><?php echo $instance['desc'] ?></textarea>
</p>

<p>
	<label for="<?php echo $this->get_field_id('incname') ?>">
		<input type="checkbox"
			id="<?php echo $this->get_field_id('incname') ?>"
			name="<?php echo $this->get_field_name('incname') ?>"
			<?php echo $instance['incname'] ?>>
		<?php echo __('Include name field', 'mailmojo') ?>
	</label>
</p>

<p>
	<label for="<?php echo $this->get_field_id('buttontext') ?>">
		<?php echo __('Signup Button Text', 'mailmojo') ?>:
	</label>
	<input class="widefat" type="text"
			id="<?php echo $this->get_field_id('buttontext') ?>"
			name="<?php echo $this->get_field_name('buttontext') ?>"
			value="<?php echo $instance['buttontext'] ?>">
</p>

<h3><?php echo __('Optional Tags', 'mailmojo') ?></h3>
<p>
	<label for="<?php echo $this->get_field_id('tagdesc') ?>">
		<?php echo __('Tag Selection Label', 'mailmojo') ?>:
	</label>
	<input class="widefat" type="text"
		id="<?php echo $this->get_field_id('tagdesc') ?>"
		name="<?php echo $this->get_field_name('tagdesc') ?>"
		value="<?php echo $instance['tagdesc'] ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id('tags') ?>">
		<?php echo __('Tags (comma separated)', 'mailmojo') ?>:
	</label>
	<textarea class="widefat"
			id="<?php echo $this->get_field_id('tags') ?>"
			name="<?php echo $this->get_field_name('tags') ?>"><?php echo $instance['tags'] ?></textarea>
</p>
