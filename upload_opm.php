<?php
session_start();

/**
 * upload_opm.php
 * file for uploading a OPM pack with different extensions( zip or dar)
 * uploads the packs and store information in to database;
 * 
 */
require_once('config/configure.php');
require_once('includes/function/autoload.php');
require_once('includes/classes/KLogger.php');

//creating a logger object
$log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
$loginObj = new Login();
$pageName = getPageName();

$packObj = new Pack();
$userType = $loginObj->fetchUserType();

/* There should be a check for admin login */
$userType = $loginObj->fetchUserType();	 
	if($userType != 1 || empty($userType))
	{
		 echo "<script>window.location.href='home.shtml'</script>";
         exit;
	}

// site head js include here 
include('includes/head.php');
?>
<script type="text/javascript">
$(document).ready(function(){ 
	var myElement = document.querySelector("#menu");
	myElement.style.display = "none"; // to hide main menu
	
	var myElement = document.querySelector(".sasrchBox");
	myElement.style.display = "none"; // to hide search box
		
	var myElement = document.querySelector(".breadcrum");
	myElement.style.display = "none"; // to hide breadcrumb
});
	
</script>

<script type="text/javascript" src="js/uploadValidation.js"></script>

</head>
<body>
<?php

$reportvar		=	(isset($_SESSION['report']['opm_name']) ? $_SESSION['report']['opm_name'] : '');
$descvar		=	(isset($_SESSION['report']['opm_desc']) ? $_SESSION['report']['opm_desc'] : '');

$filevar		=	(isset($_SESSION['report']['fileuploadpath']) ? $_SESSION['report']['fileuploadpath'] : '');
$filedir		=	(isset($_SESSION['report']['fileuploaddir']) ? $_SESSION['report']['fileuploaddir'] : '');

$vervar			=	(isset($_SESSION['report']['pack_version']) ? $_SESSION['report']['pack_version'] : '');
$verdescvar		=	(isset($_SESSION['report']['opm_version_desc']) ? $_SESSION['report']['opm_version_desc'] : '');
$opmVercvar		=	(isset($_SESSION['report']['opm_version']) ? $_SESSION['report']['opm_version'] : '');
$minvercvar		=	(isset($_SESSION['report']['min_version']) ? $_SESSION['report']['min_version'] : '');
$maxvercvar		=	(isset($_SESSION['report']['max_version']) ? $_SESSION['report']['max_version'] : '');
$othertextcvar	=	(isset($_SESSION['report']['other_text']) ? $_SESSION['report']['other_text'] : '');
$authornamecvar	=	(isset($_SESSION['report']['author_name']) ? $_SESSION['report']['author_name'] : '');
$certificatevar	=	(isset($_SESSION['report']['certificate']) ? $_SESSION['report']['certificate'] : '');

/****** remove dir in case of edit ******/
$dirFlag = @$packObj->removeDir($filedir);
if($dirFlag == 1){
	$log->LogInfo("OPM report directory removed from root.");
	unset($dirFlag);
}else{
	$log->LogError("OPM report directory can not be removed from root.");
}

//site header include here 
include('includes/header.php');
?>
<!-- body-content start -->
<div id="body_content">
	<section class="upload-form">
		
		<form enctype="multipart/form-data" method="post" action="opmConfirm.php" name="upload_opm" id="upload_opm" onSubmit="return submitformopm();">
    	<div class="up-upload">
		
			<article class="othertext"><span class="red">*</span> are mandatory fields</article>
		</div>
			
					<?php  
                    echo $_SESSION['SESS_MSG'];			
                    unset($_SESSION['SESS_MSG']); 
                    ?>
			
			<h2>Upload Pack Template</h2>			
		<ul>		
			<li>
			<ul class="mtop">
			<li><label class="lalign">Pack File<span>*</span></label>
				<input type="file" name="zip_file" id="zip_upload">											
					<div class="form-error" id="fileError"></div>
			</li>			
			<hr/>
            </ul>
			</li>
		
        	<li>
            	 <ul><li>Packs details  <hr/></li>
                	<li><label>Name<span>*</span></label><input type="text" placeholder="Enter Pack Name" id="opm_name" name="opm_name" maxlength="120" value="<?php echo(isset($reportvar) ? $reportvar : '');?>">
						<div class="form-error" id="opmNameError"></div>
					</li>
					
                	<li><label>Description<span>*</span></label>
                	  <textarea placeholder="Enter Pack Description" id="opm_desc" name="opm_desc" class="confirm-textbox"><?php echo (isset($descvar)? $descvar:'');?></textarea>
					  <div class="form-error" id="opmDescError"></div>
                	</li>
                    
                </ul>
			</li>
            <li>
            	<ul>
                	<li>Version details<hr/></li>
                    <li><label>Version no.<span>*</span></label><input type="text" id="pack_version" name="pack_version" placeholder="00.00.00" maxlength="20" value="<?php echo (isset($vervar)? $vervar:'');?>" />
						<div class="form-error" id="versionError"></div>
					</li>
                    <li><label>What's changed</label>
					<textarea placeholder="Enter version specific changes" name="opm_version_desc" class="confirm-textbox"><?php echo (isset($verdescvar)? $verdescvar:'');?></textarea></li>                                  
                </ul>            
            </li>            
             <li>
            	<ul>
                	<li>Pre-requisites<hr/></li>
                    <li><label>OPM Version<span>*</span></label><input type="text" placeholder="00.00" id="opm_version" name="opm_version" maxlength="20" value="<?php echo (isset($opmVercvar)? $opmVercvar:'');?>">
						<div class="form-error" id="opmVerError"></div>
					</li>
                    <li><label>Min ONTAP Version<span>*</span></label><input type="text" name="min_version" placeholder="00.00.00" class="smallinputbox" maxlength="20" value="<?php echo (isset($minvercvar)? $minvercvar:'');?>"> 						
					<label class="smalllabel">Max ONTAP Version</label><input type="text" name="max_version" placeholder="00.00.00" class="smallinputbox" maxlength="20" value="<?php echo (isset($maxvercvar)? $maxvercvar:'');?>">
						<div class="form-error" id="minOntapError"></div>
					</li>
                    <li><label>Other</label><textarea name="other_text" placeholder="Enter other pre-requisites" class="confirm-textbox"><?php echo (isset($othertextcvar)? $othertextcvar:'');?></textarea></li>
                </ul>            
            </li>
            <li>
            	<ul>
                	<li>Author and Contact Details<hr/></li>
					
					<li><label></label>
                    	<input name="certificate" type="radio" value="NETAPP" <?php echo (($certificatevar == 'NETAPP') ? Checked : '') ; ?> Checked>
                        <label class="tright">NetApp-supported </label>
                        <input name="certificate" type="radio" value="NONE" <?php echo (($certificatevar == 'NONE') ? Checked : ''); ?> >
                        <label class="tright">Community-generated</label>
                     </li>
					
                    <li><label>Name<span>*</span></label><input type="text" placeholder="Enter author's name" id="author_name" name="author_name" maxlength="120" value="<?php echo (isset($authornamecvar)? $authornamecvar:'');?>">
						<div class="form-error" id="authorNameError"></div>
					</li>
										
                    
                </ul>        
            </li>
            <li>
            	<ul>
                	<li>Upload<hr/></li>
                    <li class="ralign">
						<input type="button" class="myButton" name="cancel" value="Cancel" onClick="javascript:window.location.replace('performance.shtml');">
						<input type="submit" class="myButton1 upload_btn" name="submit" value="Upload">
						<article class="othertext">(Review the pack before submitting)</article>
                    </li>
                </ul>            
            </li>		
        </ul></form>
    </section>
</div>
<!-- body-content end -->
<?php
	// site head js include here 
	include('includes/footer.php');	
?> 
</body>
</html>
