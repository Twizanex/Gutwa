<?php
/**
* Elgg gutwa delete
* 
* @package Elgggutwa 
*/

$guid = (int) get_input('guid');

$gutwa = new GutwaPluginFile($guid);
if (!$gutwa->guid) {
	register_error(elgg_echo("gutwa:deletefailed"));
	forward('gutwa/all');
}

if (!$gutwa->canEdit()) {
	register_error(elgg_echo("gutwa:deletefailed"));
	forward($gutwa->getURL());
}

$container = $gutwa->getContainerEntity();

if (!$gutwa->delete()) {
	register_error(elgg_echo("gutwa:deletefailed"));
} else {
	system_message(elgg_echo("gutwa:deleted"));
}

if (elgg_instanceof($container, 'group')) {
	forward("gutwa/group/$container->guid/all");
} else {
	forward("gutwa/owner/$container->username");
}
