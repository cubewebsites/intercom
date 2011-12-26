<?php defined('INTERCOM_EXECUTE') or die(_("Access Denied.")); ?>
<?php
/**
 * Sanitizes querystring parameters
 * @param String $key The GET parameter to clean
 * @return String a cleaned version of the string 
 */
function getRequest($key) {
	if(!isset($_GET[$key])) return null;
	return htmlentities(stripslashes(strip_tags(trim($_GET[$key]))));
}