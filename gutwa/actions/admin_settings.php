<?php

global $CONFIG;

admin_gatekeeper();
action_gatekeeper();

// Params array (text boxes and drop downs)
$params = get_input('params');
$result = false;
foreach ($params as $k => $v) {
	if (!set_plugin_setting($k, $v, 'gutwa')) {
		register_error(sprintf(elgg_echo('plugins:settings:save:fail'), 'gutwa'));
		forward($_SERVER['HTTP_REFERER']);
	}
}

system_message(elgg_echo('gutwa:settings:save:ok'));	
forward($_SERVER['HTTP_REFERER']);
