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

function getParams() {
	$ret	=	array();
	foreach($_GET as $k=>$v)
		$ret[$k]	= getRequest($k);
	return $ret;
}

function loadCache($id) {
	if(!CACHE_ENABLED) return false;	
	global $globalcache;
	return $globalcache->load($id);
}

function saveCache($data,$id,$tags=array()) {
	if(!CACHE_ENABLED) return false;	
	global $globalcache;
	return $globalcache->save($data,$id,$tags);
}

function removeCache($id) {
	if(!CACHE_ENABLED) return false;	
	global $globalcache;
	return $globalcache->remove($id);
}

function cleanCache($mode,$tags) {
	if(!CACHE_ENABLED) return false;	
	global $globalcache;
	return $globalcache->clean($mode, $tags);
}