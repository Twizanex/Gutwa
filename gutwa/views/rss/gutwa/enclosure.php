<?php
/**
 * Link to download the gutwa
 *
 * @uses $vars['entity']
 */

if (elgg_instanceof($vars['entity'], 'object', 'gutwa')) {
	$download_url = elgg_get_site_url() . 'gutwa/download/' . $vars['entity']->getGUID();
	$size = $vars['entity']->size();
	$mime_type = $vars['entity']->getMimeType();
	echo <<<END

	<enclosure url="$download_url" length="$size" type="$mime_type" />";
END;
}
