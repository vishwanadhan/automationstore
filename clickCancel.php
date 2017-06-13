<?php
session_start();
/* clickCancel.php
* To remove directory when uploading report is cancelled
*
*/	
require_once('config/configure.php');
require_once('includes/function/autoload.php');    
//creating a logger object
$log = new KLogger(LOGFILEPATH, KLogger::DEBUG);   

// get dir name value from session
$packFileDir	=	(!empty($_SESSION['wfaPostXml']['packFileDir']) ? $_SESSION['wfaPostXml']['packFileDir'] : $_SESSION['wfaPostData']['packFileDir']);    

	/***** To check and remove directory for report when upload is cancelled *****/  
	
	if (is_dir($packFileDir)) {
		rrmdir($packFileDir);
		echo "<script>window.location.href='pack-upload.shtml'</script>";   		
		exit;
	}

	unset($_SESSION['wfaPostData']);     
	unset($_SESSION['wfaPostXml']);
	
	$log->LogInfo("Upload workflow pack is cancelled.");	
	echo "<script>window.location.href='pack-upload.shtml'</script>"; 		
	exit; 
?>