<?php
/**
 * All gutwas gutwa
 *
 * @package ElggGutwa
 */

elgg_push_breadcrumb(elgg_echo('gutwa'));

elgg_register_title_button();

$limit = get_input("limit", 10);

$title = elgg_echo('gutwa:all');

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'gutwa',
	'limit' => $limit,
	'full_view' => FALSE
));
if (!$content) {
	$content = elgg_echo('gutwa:none');
}

$sidebar = gutwa_get_type_cloud();
$sidebar = elgg_view('gutwa/sidebar');

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);