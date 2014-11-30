<?php

/**  
 * Override the ElggGutwa
 */
class GutwaPluginFile extends ElggFile {
	protected function  initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "gutwa";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	public function delete() {

		$thumbnails = array($this->thumbnail, $this->smallthumb, $this->largethumb);
		foreach ($thumbnails as $thumbnail) {
			if ($thumbnail) {
				$delgutwa = new ElggFile();
				$delgutwa->owner_guid = $this->owner_guid;
				$delgutwa->setFilename($thumbnail);
				$delgutwa->delete();
			}
		}

		return parent::delete();
	}
}
