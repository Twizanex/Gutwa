<?php
/**
 * gutwa helper functions
 *
 * @package ElggGutwa
 */

/**
 * Prepare the upload/edit form variables
 *
 * @param GutwaPluginFile 
 * @return array
 */
function gutwa_prepare_form_vars($gutwa = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $gutwa,
	);

	if ($gutwa) {
		foreach (array_keys($values) as $field) {
			if (isset($gutwa->$field)) {
				$values[$field] = $gutwa->$field;
			}
		}
	}

	if (elgg_is_sticky_form('gutwa')) {
		$sticky_values = elgg_get_sticky_values('gutwa');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('gutwa');

	return $values;
}