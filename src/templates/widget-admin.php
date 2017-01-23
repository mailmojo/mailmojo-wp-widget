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

<h4>
	<?php echo __('Selectable tags', 'mailmojo') ?>
	<small>(<?php echo __('optional', 'mailmojo') ?>)</small>
</h4>
<p>
	<label for="<?php echo $this->get_field_id('tagdesc') ?>">
		<?php echo __('Tag selection label', 'mailmojo') ?>:
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
<p>
	<label><?php echo __('Selection options', 'mailmojo') ?>:</label><br/>
	<label>
		<input class="widefat" type="radio"
			name="<?php echo $this->get_field_name('tagtype') ?>"
			value="single"
			<?php echo $instance['tagtype'] == 'single' ? 'checked': '' ?>>
		<?php echo __('Single choice', 'mailmojo') ?>
	</label><br/>
	<label>
		<input class="widefat" type="radio"
			name="<?php echo $this->get_field_name('tagtype') ?>"
			value="multiple"
			<?php echo $instance['tagtype'] == 'multiple' ? 'checked': '' ?>>
		<?php echo __('Multiple choice', 'mailmojo') ?>
	</label>
</p>


<h4>
	<?php echo __('Fixed tag(s)', 'mailmojo') ?>
	<small>(<?php echo __('optional', 'mailmojo') ?>)</small>
</h4>
<p><em><?php echo __('These tags will automatically be added to the subscribed contact.', 'mailmojo') ?></em></p>
<p>
	<label for="<?php echo $this->get_field_id('fixedtags') ?>">
		<?php echo __('Tags (comma separated)', 'mailmojo') ?>:
	</label>
	<textarea class="widefat"
			id="<?php echo $this->get_field_id('fixedtags') ?>"
			name="<?php echo $this->get_field_name('fixedtags') ?>"><?php echo $instance['fixedtags'] ?></textarea>
</p>
