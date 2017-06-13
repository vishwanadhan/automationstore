<?php
ob_start();
session_start();
require_once('config/configure.php');
require_once('includes/function/autoload.php');

setcookie("PHPSESSID","", 1, "/","netapp.com");
setcookie("ObSSOCookie","", 1, "/","netapp.com");

if (!headers_sent()) {
 foreach (headers_list() as $header)
   header_remove($header);
   //sleep(2);
}

$loginObj = new Login();


$uid= $_SESSION['uid'];
$loginObj->lastLogin($uid);
$loginObj->Logout();


$logs = new KLogger(LOGFILEPATH, KLogger::DEBUG );
$logs->LogInfo("User with uid: ".$uid." logged out successfully - session expired.");

//Arun changes //
	
header("Cache-Control: nocache"); 
header("Pragma: "); 

//Arun changes end //


$out = ob_get_clean();
getallheaders($out);

header_remove();
session_destroy();  //function to destroy the user session


$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'].'/';    

  
//redirect('https://signin.netapp.com/sso/logout.html?redirecturl=http://automationstore.netapp.com/home.shtml');
//redirect('https://vmazsaweb01-stg.corp.netapp.com/sso/logout?end_url=https://vmazsaweb01-stg.corp.netapp.com');
//redirect(' https://vmazsaweb01-stg.corp.netapp.com/sso/logout.html?redirecturl=http://vmazsaweb01-stg.corp.netapp.com')
redirect('https://vmazsaweb01-dev.corp.netapp.com/sso/logout?end_url=https://vmazsaweb01-dev.corp.netapp.com/home.shtml');
//redirect('pack-list.shtml'); //It will redirect on home page when user access page without login

?>
