<p>
	<label for="<?php echo $this->get_field_id('subscribeurl') ?>">
		<?php echo __('Subscribe to Email List', 'mailmojo') ?>:
	</label>
	<select class="widefat" name="<?php echo $this->get_field_name('subscribeurl') ?>"
			id="<?php echo $this->get_field_id('subscribeurl') ?>">
		<?php foreach (self::$lists as $list) : ?>
			<option value="<?php echo $list->getSubscribeUrl() ?>"
				<?php echo $instance['subscribeurl'] == $list->getSubscribeUrl() ? 'selected' : '' ?>>
					<?php echo $list->getName() ?>
			</option>
		<?php endforeach; ?>
	</select>
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
	<label for="<?php echo $this->get_field_id('buttontext') ?>">
		<?php echo __('Signup Button Text', 'mailmojo') ?>:
	</label>
	<input class="widefat" type="text"
			id="<?php echo $this->get_field_id('buttontext') ?>"
			name="<?php echo $this->get_field_name('buttontext') ?>"
			value="<?php echo $instance['buttontext'] ?>">
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

<h4><?php echo __('Optional Tags', 'mailmojo') ?></h4>
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
