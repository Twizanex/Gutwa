<?php
/**
 * Elgg gutwa browser download action.
 *
 * @package Elgggutwa
 */

// @todo this is here for backwards compatibility (first version of embed plugin?)
$download_page_handler = elgg_get_plugins_path() . 'gutwa/download.php';

include $download_page_handler;
