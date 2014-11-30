<?php
/**
 * Elgg gutwa download.
 *
 * @package ElggGutwa  
 */

// Get the guid
$gutwa_guid = get_input("guid");

// Get the gutwa
$gutwa = get_entity($gutwa_guid);
if (!$gutwa) {
	register_error(elgg_echo("gutwa:downloadfailed"));
	forward();
}

$mime = $gutwa->getMimeType();
if (!$mime) {
	$mime = "application/octet-stream";
}

$gutwaname = $gutwa->originalgutwaname;

// fix for IE https issue
header("Pragma: public");

header("Content-type: $mime");
if (strpos($mime, "image/") !== false || $mime == "application/pdf") {
	header("Content-Disposition: inline; filename=\"$gutwaname\"");
} else {
	header("Content-Disposition: attachment; filename=\"$gutwaname\"");
}

ob_clean();
flush();
readfile($gutwa->getFilenameOnFilestore());
exit;