<?php
/**
 * Elgg gutwa thumbnail
 *
 * @package ElggGutwa
 *  gutwa_guid to file_guid 
 */

// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get gutwa GUID
$gutwa_guid = (int) get_input('gutwa_guid', 0);

// Get gutwa thumbnail size
$size = get_input('size', 'small');

$gutwa = get_entity($gutwa_guid);
if (!$gutwa || $gutwa->getSubtype() != "gutwa") {
	exit;
}

$simpletype = $gutwa->simpletype;
if ($simpletype == "image") {

	// Get gutwa thumbnail
	switch ($size) {
		case "small":
			$thumbgutwa = $gutwa->thumbnail;
			break;
		case "medium":
			$thumbgutwa = $gutwa->smallthumb;
			break;
		case "large":
		default:
			$thumbgutwa = $gutwa->largethumb;
			break;
	}

	// Grab the gutwa
	if ($thumbgutwa && !empty($thumbgutwa)) {
		$readgutwa = new ElggFile();
		$readgutwa->owner_guid = $gutwa->owner_guid;
		$readgutwa->setFilename($thumbgutwa);
		$mime = $gutwa->getMimeType();
		$contents = $readgutwa->grabFile();

		// caching images for 10 days
		header("Content-type: $mime");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("Content-Length: " . strlen($contents));

		echo $contents;
		exit;
	}
}
