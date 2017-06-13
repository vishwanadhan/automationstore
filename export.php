<?php
	require_once('config/configure.php');
	
	require_once('includes/function/autoload.php');
	
	

	$type		= $_REQUEST['filetype'];
	$startLimit	= $_REQUEST['startLimit'];
	$endLimit	= $_REQUEST['endLimit'];
	$packType   = $_REQUEST['type'];
	
	
	if($packType=='ocipack'){

	$ociObj = new OciPack();
	$ociObj->getDownloadFile($type,$startLimit,$endLimit,$packType);
	
	}else if($packType=='snapcenter'){

	$snapObj = new SnapPack();
	$snapObj->getDownloadFile($type,$startLimit,$endLimit,$packType);
	
	}
	else{
	$packObj = new Pack();
	$packObj->getDownloadFile($type,$startLimit,$endLimit,$packType);
	}
	
	
?>