<?php
require_once('test_12april17.php');
// Server Path
error_reporting(E_ALL);
ini_set('display_errors',0);

//date_default_timezone_set("Asia/Kolkata");
//date_default_timezone_set('UTC');
 date_default_timezone_set('America/Los_Angeles');


$docRoot = $_SERVER['DOCUMENT_ROOT']; 
$docRoot.= "/workflowstore/";
// $docRoot.= "2.0/";
$host = $_SERVER['HTTP_HOST'];


$sitepath = "http://".$host."/workflowstore/";
// $sitepath = "http://".$host."/";
$imageMagickPath  = "/usr/bin/convert";

// Local Database Settings 
//$config['server'] = '10.250.242.54';
$config['server'] = 'localhost';
$config['database'] ='workflowstore';   
$config['user'] ='root';
$config['pass'] ='';

define('SITENAME','WorkFlowStore');  

define('SITEPATH',$sitepath);
define('PATH',$docRoot);
define('PATH_CK_PEM',$docRoot."/includes/");
define('LOGFILEPATH',$docRoot."log/log.txt");
define('WEBSERVICEPATH',$docRoot.'metadata/');
define('EXPOSEDMETAXML',$docRoot.'metadata/pack-info.xml');
define('EXPOSEDTIMEXML',$docRoot.'metadata/store_summary.xml');
define('EXPOSEDSTOREXML',$docRoot.'metadata/store.xml');
define('ERRORXML',$docRoot.'metadata/error.xml');

define('MAXUPLOADSIZE',307200); 

//----For amfphp------------------
define('CONFIGSERVER', $config['server']);
define('CONFIGDATABASE', $config['database']); 
define('CONFIGUSER', $config['user']);
define('CONFIGPASS', $config['pass']);
define('CONFIGIMAGEMAGICPATH', $imageMagickPath);
define("ABSOLUTEPATH" , "/var/www/html/workflowstore/");
define('SOLRSERVER', '10.250.242.54');
define('SOLRPORT', '8985');

//----FOR USER IMAGE
define('BASEUPLOADFILEPATH',"../images/");
define('USERTHUMB',BASEUPLOADFILEPATH."user/thumb/");
define('USERMAIN',BASEUPLOADFILEPATH."user/main/");

//chdir('C:/solr-4.7.1/example'); 
//shell_exec('java -jar start.jar');
header('Cache-Control: no cache'); //no cache  
session_cache_limiter('private_no_expire'); // works
//session_cache_limiter('public'); // works too
//Variable defined by Afsar
define("CONSTANT_UCWORDS" , "Data Protection");
define('CONSTANT_LOWERCASE', 'data protection');
define('CONSTANT_UFIRST', 'Data protection');
?>
