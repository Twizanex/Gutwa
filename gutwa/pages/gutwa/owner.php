<?php
/**
 * Individual's or group's gutwa file
 *
 * @package ElggGutwa
 */

// access check for closed groups
group_gatekeeper();

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('gutwa/all');
}

elgg_push_breadcrumb(elgg_echo('gutwa'), "gutwa/all");
elgg_push_breadcrumb($owner->name);

elgg_register_title_button();

$params = array();

if ($owner->guid == elgg_get_logged_in_user_guid()) {
	// user looking at own gutwas
	$params['filter_context'] = 'mine';
} else if (elgg_instanceof($owner, 'user')) {
	// someone else's gutwas
	// do not show select a tab when viewing someone else's posts
	$params['filter_context'] = 'none';
} else {
	// group gutwas
	$params['filter'] = '';
}

$title = elgg_echo("gutwa:user", array($owner->name));

// List gutwas
$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'gutwa',
	'container_guid' => $owner->guid,
	'limit' => 10,
	'full_view' => FALSE,
));
if (!$content) {
	$content = elgg_echo("gutwa:none");
}

$sidebar = gutwa_get_type_cloud(elgg_get_page_owner_guid());
$sidebar = elgg_view('gutwa/sidebar');

$params['content'] = $content;
$params['title'] = $title;
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

