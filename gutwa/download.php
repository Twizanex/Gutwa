<?php
/**
 * Elgg gutwa download.
 * 
 * @package ElggFile
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the guid
$gutwa_guid = get_input("gutwa_guid");

forward("gutwa/download/$gutwa_guid");
