<?php defined('INTERCOM_EXECUTE') or die(_("Access Denied.")); ?>
<?php
define('LIBRARY_PATH','libraries'.DIRECTORY_SEPARATOR);
define('ZEND_PATH',LIBRARY_PATH.'Zend'.DIRECTORY_SEPARATOR);

//Add the Zend directory to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_PATH);
//enable loading of Zend Classes
require_once(ZEND_PATH.'Loader'.DIRECTORY_SEPARATOR.'Autoloader.php');
$loader	= Zend_Loader_Autoloader::getInstance();

include('functions.php');