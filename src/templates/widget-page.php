<?php echo $args['before_widget'] ?>

<?php echo $args['before_title'] ?>
	<?php echo $instance['title'] ?>
<?php echo $args['after_title'] ?>

<?php if (!empty($instance['desc'])) : ?>
	<p><?php echo $instance['desc'] ?></p>
<?php endif; ?>

<form method="post"
	action="<?php echo $instance['subscribeurl'] ?>"
	id="mailmojo_<?php echo $this->number ?>_form"
	class="mailmojo_form">

	<p class="field">
		<label for="mailmojo_<?php echo $this->number ?>_email">
			<?php echo __('E-mail', 'mailmojo') ?>:
		</label>
		<input class="text" type="text"
			id="mailmojo_<?php echo $this->number ?>_email"
			name="email">
	</p>

	<?php if (!empty($instance['incname'])) : ?>
		<p class="field">
			<label for="mailmojo_<?php echo $this->number ?>_name">
				<?php echo __('Name', 'mailmojo') ?>:
			</label>
			<input class="text" type="text"
				id="mailmojo_<?php echo $this->number ?>_name"
				name="name">
		</p>
	<?php endif; ?>

	<?php if (!empty($instance['tags'])) : ?>
		<?php if (!empty($instance['tagdesc'])) : ?>
			<p><?php echo $instance['tagdesc'] ?></p>
		<?php endif; ?>
		<ul class="field">
			<?php foreach ($instance['tags'] as $tag) : ?>
				<?php $t = ucfirst(mb_strtolower(trim($tag))); ?>
				<li>
					<label>
						<input type="<?php echo $instance['tagtype'] === 'multiple' ? 'checkbox' : 'radio' ?>"
							name="tags[]" value="<?php echo $tag ?>">
						<?php echo $t ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<p class="submit">
		<?php if (!empty($instance['fixedtags'])) : ?>
			<input type="hidden" name="tagsadditional" value="<?php echo $instance['fixedtags'] ?>">
		<?php endif; ?>
		<input class="submit" type="submit" value="<?php echo $instance['buttontext'] ?>">
	</p>
</form>

<?php echo $args['after_widget'] ?>
