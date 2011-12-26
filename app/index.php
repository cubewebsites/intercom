<?php
//app can only be run from this index.php file
define('INTERCOM_EXECUTE',true);
define('LIBRARY_PATH','libraries'.DIRECTORY_SEPARATOR);
define('ZEND_PATH',LIBRARY_PATH.'Zend'.DIRECTORY_SEPARATOR);

//Add the Zend directory to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_PATH);
//enable loading of Zend Classes
require_once(ZEND_PATH.'Loader'.DIRECTORY_SEPARATOR.'Autoloader.php');
$loader	= Zend_Loader_Autoloader::getInstance();

//setup our YouTube object
$youtube	=	new Zend_Gdata_YouTube();
$youtube->setMajorProtocolVersion(2);

//do the video search
switch(strtolower(getRequest('mode'))) {
	case 'user':
		$videosearch	=	true;
		$results		=	$youtube->getUserUploads(getRequest('username'));
		break;
	case 'toprated':
		$videosearch	=	true;
		$results		=	$youtube->getTopRatedVideoFeed();
		break;
	case 'mostviewed':
		$videosearch	=	true;
		$results		=	$youtube->getMostViewedVideoFeed();
		break;
	case 'recentlyfeatured':
		$videosearch	=	true;
		$results		=	$youtube->getRecentlyFeaturedVideoFeed();
		break;
	case 'search':
		$videosearch	=	true;
		$query			=	new Zend_Gdata_YouTube_VideoQuery();		
		$query->setOrderBy('viewCount');
		$query->setSafeSearch('strict');
		$query->setVideoQuery(getRequest('query'));				
		$results		=	$youtube->getVideoFeed($query->getQueryUrl(2));		
		break;
}

//return the correct type of results
if($videosearch) {
	$videos	=	array();
	foreach($results as $video) {	
		$videos[]	=	array(
			'url'		=>	$video->getVideoWatchPageUrl(),
			'title'		=>	$video->getVideoTitle()
		);	
	}
	echo json_encode($videos);
}

/**
 * Sanitizes querystring parameters
 * @param String $key The GET parameter to clean
 * @return String a cleaned version of the string 
 */
function getRequest($key) {
	if(!isset($_GET[$key])) return null;
	return htmlentities(stripslashes(strip_tags(trim($_GET[$key]))));
}