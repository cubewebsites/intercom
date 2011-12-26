<?php
define('INTERCOM_EXECUTE',true);
include('inc/common.php');

//setup our YouTube object
$youtube	=	new Zend_Gdata_Youtube();
$youtube->setMajorProtocolVersion(2);

//set some initial variables
$perpage	=	getRequest('perpage')?getRequest('perpage'):10;
$page		=	getRequest('page')?getRequest('page'):1;

//generate a cache id
$params		=	array(
	'mode'		=>	getRequest('mode'),
	'username'	=>	getRequest('username'),
	'query'		=>	getRequest('query'),
	'page'		=>	$page,
	'perpage'	=>	$perpage
);
$cacheid	=	md5(serialize($params));

//try loading from cache
$results		=	loadCache($cacheid);
//not cached, fetch a new set of results
if(!$results) {
	//setup the query
	$query			=	new Zend_Gdata_YouTube_VideoQuery();
	//setup pagination
	$query->setMaxResults($perpage);
	$query->setStartIndex($perpage*($page-1)+1);

	//do the video search
	$videosearch	=	false;
	switch(strtolower(getRequest('mode'))) {
		case 'user':		
			$results		=	$youtube->getUserUploads(getRequest('username'),$query);
			$videosearch	=	true;
			break;
		case 'toprated':		
			$results		=	$youtube->getTopRatedVideoFeed($query);
			$videosearch	=	true;
			break;
		case 'mostviewed':		
			$results		=	$youtube->getMostViewedVideoFeed($query);
			$videosearch	=	true;
			break;
		case 'recentlyfeatured':		
			$results		=	$youtube->getRecentlyFeaturedVideoFeed($query);
			$videosearch	=	true;
			break;
		case 'search':		
			$videosearch	=	true;
			$query->setOrderBy('viewCount');
			$query->setSafeSearch('strict');
			$query->setVideoQuery(getRequest('query'));		
			$results		=	$youtube->getVideoFeed($query->getQueryUrl(2));		
			break;
	}
	if($videosearch)
		saveCache($results, $cacheid,array('videosearch'));
}
else {	
	$videosearch	=	true;
}

//return the correct type of results
if($videosearch) {
	$videos	=	array();
	foreach($results as $video) {	
		$videos[]	=	array(
			'url'		=>	$video->getVideoWatchPageUrl(),
			'title'		=>	$video->getVideoTitle(),
			'videoid'	=>	$video->getVideoId()
		);	
	}
	echo json_encode($videos);
}
else echo "false";