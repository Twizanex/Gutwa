<?php
/**
 * Upload a new gutwa 
 *
 * @package ElggGutwa
 */

elgg_load_library('elgg:gutwa');

$owner = elgg_get_page_owner_entity();

gatekeeper();
group_gatekeeper();

/*if(isset($_SESSION['paid_status'])) {
register_error(elgg_echo("your payment has been received,Kindly upload your banner"));
unset($_SESSION['paid_status']);
}*/

$title = elgg_echo('Upload a Banner');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('gutwa'), "gutwa/all");
if (elgg_instanceof($owner, 'user')) {
	elgg_push_breadcrumb($owner->name, "gutwa/owner/$owner->username");
} else {
	elgg_push_breadcrumb($owner->name, "gutwa/group/$owner->guid/all");
}
elgg_push_breadcrumb($title);

// create form
$form_vars = array('enctype' => 'multipart/form-data','action' =>'gutwa/process');
$body_vars = gutwa_prepare_form_vars();
$content = elgg_view_form('gutwa/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
