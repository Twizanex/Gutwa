<?php
/**
 * Edit a gutwa
 *
 * @package ElggGutwa
 */

elgg_load_library('elgg:gutwa');

gatekeeper();

$gutwa_guid = (int) get_input('guid');
$gutwa = new GutwaPluginFile($gutwa_guid);
if (!$gutwa) {
	forward();
}
if (!$gutwa->canEdit()) {
	forward();
}

$title = elgg_echo('gutwa:edit');

elgg_push_breadcrumb(elgg_echo('gutwa'), "gutwa/all");
elgg_push_breadcrumb($gutwa->title, $gutwa->getURL());
elgg_push_breadcrumb($title);

elgg_set_page_owner_guid($gutwa->getContainerGUID());

$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = gutwa_prepare_form_vars($gutwa);

$content = elgg_view_form('gutwa/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);