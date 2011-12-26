<?php
define('INTERCOM_EXECUTE',true);
include('inc/common.php');

//setup our YouTube object
$youtube	=	new Zend_Gdata_YouTube();
$youtube->setMajorProtocolVersion(2);

$videoid	=	getRequest('video');
if($videoid) {
	$cacheid	=	'videoinfo'.$videoid;
	$video		= loadCache($cacheid);
	if(!$video) {
		$video	=	$youtube->getVideoEntry($videoid);
		saveCache($video,$cacheid,array('videoinfo'));
	}
	if($video) {
		//get the category name		
		$categorystring		=	'';
		foreach($video->getCategory() as $cat) {	$categorystring	.=	$cat->getLabel();	}
		
		//sort out the author
		$authorstring	=	'';
		foreach($video->getAuthor() as $author) { $authorstring = $author->getName(); }
		
		$ret	=	array(
			'title'			=>	$video->getVideoTitle(),
			'author'		=>	$authorstring,			
			'date'			=>  date('d M Y g:i:sa',strtotime($video->getPublished())),
			'category'		=>	$categorystring,
			'views'			=>	$video->getVideoViewCount(),			
			'description'	=>	$video->getVideoDescription()
		);
		$video->getStatistics();
		$echo	=	'';
		foreach($ret as $k=>$v) {
			$echo .=  ucfirst($k) . ': ' . $v .  '<br />';
		}
		echo $echo;
		//echo json_encode($ret);
	}
	else {
		echo "false";
	}
}
else 
	echo "false";