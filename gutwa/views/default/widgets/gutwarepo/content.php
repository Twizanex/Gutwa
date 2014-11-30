<?php
/**
 * Elgg gutwa widget view 
 *
 * @package ElggGutwa
 */


$num = $vars['entity']->num_display;

$options = array(
	'type' => 'object',
	'subtype' => 'gutwa',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$url = "gutwa/owner/" . elgg_get_page_owner_entity()->username;
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('gutwa:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('gutwa:none');
}
