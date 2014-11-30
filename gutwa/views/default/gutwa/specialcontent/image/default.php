<?php
/**
 * Display an image
 *
 * @uses $vars['entity']
 */

$gutwa = $vars['entity'];

$image_url = $gutwa->getIconURL('large');
$image_url = elgg_format_url($image_url);
$download_url = elgg_get_site_url() . "gutwa/download/{$gutwa->getGUID()}";

if ($vars['full_view']) {
	echo <<<HTML
		<div class="gutwa-photo">
			<a href="$download_url"><img class="elgg-photo" src="$image_url" /></a>
		</div>
HTML;
}