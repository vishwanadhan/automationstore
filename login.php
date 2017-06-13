<?php

session_id();
ob_start();
session_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');

$loginObj = new Login();
//function to check if user session is set or not.
$loginObj->checkHeader(); 

$packObj = new Pack();

$packObj->addNewUser();

/*echo "<pre>";
print_r($_SESSION);
exit;*/

$logs = new KLogger(LOGFILEPATH, KLogger::DEBUG);
$logs->LogInfo("User with user name: " . $_SESSION['firstName'] . " " . $_SESSION['lastName'] . " and uid: " . $_SESSION['uid'] . " logged in successfully");

// header('location:pack-list.shtml');
  header('location:http://automationstore.netapp.com/home.shtml');
   // header('location:http://vmazsaweb01-dev.corp.netapp.com/home.shtml');
?>
