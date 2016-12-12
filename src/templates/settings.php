<div class="wrap">
	<h1>MailMojo Settings</h1>
	<form method="post" action="options.php">
	<?php
		settings_fields('mailmojo-widget-settings');
		do_settings_sections('mailmojo-settings-admin');
		submit_button();
	?>
	</form>
</div>
