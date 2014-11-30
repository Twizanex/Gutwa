<?php
/**
 * Friends gutwa 
 *
 * @package ElggGutwa
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('gutwa/all');
}

elgg_push_breadcrumb(elgg_echo('gutwa'), "gutwa/all");
elgg_push_breadcrumb($owner->name, "gutwa/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

$title = elgg_echo("gutwa:friends");

// offset is grabbed in list_user_friends_objects
$content = list_user_friends_objects($owner->guid, 'gutwa', 10, false);
if (!$content) {
	$content = elgg_echo("gutwa:none");
}

$sidebar = gutwa_get_type_cloud($owner->guid, true);

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);