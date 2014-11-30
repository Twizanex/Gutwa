<?php
/**
 * View  gutwa  
 *
 * @package ElggGutwa
 */

$gutwa = get_entity(get_input('guid'));
if (!$gutwa) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('gutwa'), 'gutwa/all');

$crumbs_title = $owner->name;
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "gutwa/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($crumbs_title, "gutwa/owner/$owner->username");
}

$title = $gutwa->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($gutwa, array('full_view' => true));
$content .= elgg_view_comments($gutwa);

elgg_register_menu_item('title', array(
	'name' => 'download',
	'text' => elgg_echo('gutwa:download'),
	'href' => "gutwa/download/$gutwa->guid",
	'link_class' => 'elgg-button elgg-button-action',
));

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
