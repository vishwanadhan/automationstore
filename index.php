<?php 
session_id();
ob_start();
session_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
//$loginObj->checkHeader();

header('location:home.shtml');
?>
