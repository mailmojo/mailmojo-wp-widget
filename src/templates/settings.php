<div class="wrap">
	<h1>MailMojo</h1>

	<?php echo $notice ?>

	<form method="post" action="options.php">
	<?php
		settings_fields('mailmojo-widget-settings');
		do_settings_sections('mailmojo-settings-admin');
		submit_button();
	?>
	</form>
</div>
