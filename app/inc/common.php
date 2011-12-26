<?php defined('INTERCOM_EXECUTE') or die(_("Access Denied.")); ?>
<?php
$ds	=	DIRECTORY_SEPARATOR;
define('LIBRARY_PATH', realpath(dirname(__FILE__) .$ds. "..{$ds}") . $ds . 'libraries'.DIRECTORY_SEPARATOR);
define('ZEND_PATH',LIBRARY_PATH.'Zend'.DIRECTORY_SEPARATOR);
define('CACHE_ENABLED',true);

//Add the Zend directory to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_PATH);
//enable loading of Zend Classes
require_once(ZEND_PATH.'Loader'.DIRECTORY_SEPARATOR.'Autoloader.php');
$loader	= Zend_Loader_Autoloader::getInstance();


//setup caching
if(CACHE_ENABLED) {
	$frontend	=	array(
		'lifetime'					=>	3600,
		'automatic_serialization'	=>	true,
		'automatic_cleaning_factor'	=>	0
	);
	$backend	=	array(
		'cache_dir'	=> dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR
	);	
	$globalcache		= Zend_Cache::factory('core', 'File',$frontend,$backend);	
}

include('functions.php');