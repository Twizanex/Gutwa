<?php
/**
 * Elgg gutwa uploader/edit action 
 *
 * @package Elgggutwa   
 */

// Get variables
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$desc = get_input("description");
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('gutwa_guid');
$tags = get_input("tags");

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

elgg_make_sticky_form('gutwa');

// check if upload failed
if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
	register_error(elgg_echo('gutwa:cannotload'));
	forward(REFERER);
}

// check whether this is a new gutwa or an edit
$new_gutwa = true;
if ($guid > 0) {
	$new_gutwa = false;
}

if ($new_gutwa) {
	// must have a gutwa if a new gutwa upload
	if (empty($_FILES['upload']['name'])) {
		$error = elgg_echo('gutwa:nogutwa');
		register_error($error);
		forward(REFERER);
	}

	$gutwa = new GutwaPluginFile();
	$gutwa->subtype = "gutwa";

	// if no title on new upload, grab gutwaname
	if (empty($title)) {
		$title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
	}

} else {
	// load original gutwa object
	$gutwa = new GutwaPluginFile($guid);
	if (!$gutwa) {
		register_error(elgg_echo('gutwa:cannotload'));
		forward(REFERER);
	}

	// user must be able to edit gutwa
	if (!$gutwa->canEdit()) {
		register_error(elgg_echo('gutwa:noaccess'));
		forward(REFERER);
	}

	if (!$title) {
		// user blanked title, but we need one
		$title = $gutwa->title;
	}
}

$gutwa->title = $title;
$gutwa->description = $desc;
$gutwa->access_id = $access_id;
$gutwa->container_guid = $container_guid;

$tags = explode(",", $tags);
$gutwa->tags = $tags;

// we have a gutwa upload, so process it
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {

	$prefix = "gutwa/";

	// if previous gutwa, delete it
	if ($new_gutwa == false) {
		$gutwaname = $gutwa->getFilenameOnFilestore();
		if (file_exists($gutwaname)) {
			unlink($gutwaname);
		}

		// use same gutwaname on the disk - ensures thumbnails are overwritten
		$gutwastorename = $gutwa->getFilename();
		$gutwastorename = elgg_substr($gutwastorename, elgg_strlen($prefix));
	} else {
		$gutwastorename = elgg_strtolower(time().$_FILES['upload']['name']);
	}

	$gutwa->setFilename($prefix . $gutwastorename);
	$mime_type = ElggFile::detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);

	// hack for Microsoft zipped formats
	$info = pathinfo($_FILES['upload']['name']);
	$office_formats = array('docx', 'xlsx', 'pptx');
	if ($mime_type == "application/zip" && in_array($info['extension'], $office_formats)) {
		switch ($info['extension']) {
			case 'docx':
				$mime_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
				break;
			case 'xlsx':
				$mime_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
				break;
			case 'pptx':
				$mime_type = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
				break;
		}
	}

	// check for bad ppt detection
	if ($mime_type == "application/vnd.ms-office" && $info['extension'] == "ppt") {
		$mime_type = "application/vnd.ms-powerpoint";
	}

	$gutwa->setMimeType($mime_type);
	$gutwa->originalgutwaname = $_FILES['upload']['name'];
	$gutwa->simpletype = gutwa_get_simple_type($mime_type);

	// Open the gutwa to guarantee the directory exists
	$gutwa->open("write");
	$gutwa->close();
	move_uploaded_file($_FILES['upload']['tmp_name'], $gutwa->getFilenameOnFilestore());

	$guid = $gutwa->save();

	// if image, we need to create thumbnails (this should be moved into a function)
	if ($guid && $gutwa->simpletype == "image") {
		$gutwa->icontime = time();
		
		$thumbnail = get_resized_image_from_existing_file($gutwa->getFilenameOnFilestore(), 60, 60, true);
		if ($thumbnail) {
			$thumb = new ElggFile();
			$thumb->setMimeType($_FILES['upload']['type']);

			$thumb->setFilename($prefix."thumb".$gutwastorename);
			$thumb->open("write");
			$thumb->write($thumbnail);
			$thumb->close();

			$gutwa->thumbnail = $prefix."thumb".$gutwastorename;
			unset($thumbnail);
		}

		$thumbsmall = get_resized_image_from_existing_file($gutwa->getFilenameOnFilestore(), 153, 153, true);
		if ($thumbsmall) {
			$thumb->setFilename($prefix."smallthumb".$gutwastorename);
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			$gutwa->smallthumb = $prefix."smallthumb".$gutwastorename;
			unset($thumbsmall);
		}

		$thumblarge = get_resized_image_from_existing_file($gutwa->getFilenameOnFilestore(), 600, 600, false);
		if ($thumblarge) {
			$thumb->setFilename($prefix."largethumb".$gutwastorename);
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
			$gutwa->largethumb = $prefix."largethumb".$gutwastorename;
			unset($thumblarge);
		}
	}
} else {
	// not saving a gutwa but still need to save the entity to push attributes to database
	$gutwa->save();
}

// gutwa saved so clear sticky form
elgg_clear_sticky_form('gutwa');


// handle results differently for new gutwas and gutwa updates
if ($new_gutwa) {
	if ($guid) {
		$message = elgg_echo("gutwa:saved");
		system_message($message);
		add_to_river('river/object/gutwa/create', 'create', elgg_get_logged_in_user_guid(), $gutwa->guid);
	} else {
		// failed to save gutwa object - nothing we can do about this
		$error = elgg_echo("gutwa:uploadfailed");
		register_error($error);
	}

	$container = get_entity($container_guid);
	if (elgg_instanceof($container, 'group')) {
		forward("gutwa/group/$container->guid/all");
	} else {
		forward("gutwa/owner/$container->username");
	}

} else {
	if ($guid) {
		system_message(elgg_echo("gutwa:saved"));
	} else {
		register_error(elgg_echo("gutwa:uploadfailed"));
	}

	forward($gutwa->getURL());
}	
