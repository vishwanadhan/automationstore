<?php
session_start();

/**
 * upload_ocum.php
 * file for uploading a OCUM pack with different extensions( zip or dar)
 * uploads the report and store information in to database;
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

$reportvar		=	(isset($_SESSION['report']['ocum_name']) ? $_SESSION['report']['ocum_name'] : '');
$descvar		=	(isset($_SESSION['report']['ocum_desc']) ? $_SESSION['report']['ocum_desc'] : '');

$filevar		=	(isset($_SESSION['report']['fileuploadpath']) ? $_SESSION['report']['fileuploadpath'] : '');
$filedir		=	(isset($_SESSION['report']['fileuploaddir']) ? $_SESSION['report']['fileuploaddir'] : '');

$vervar			=	(isset($_SESSION['report']['report_version']) ? $_SESSION['report']['report_version'] : '');
$verdescvar		=	(isset($_SESSION['report']['report_version_desc']) ? $_SESSION['report']['report_version_desc'] : '');
$ocumvercvar	=	(isset($_SESSION['report']['ocum_version']) ? $_SESSION['report']['ocum_version'] : '');
$minvercvar		=	(isset($_SESSION['report']['min_version']) ? $_SESSION['report']['min_version'] : '');
$maxvercvar		=	(isset($_SESSION['report']['max_version']) ? $_SESSION['report']['max_version'] : '');
$othertextcvar	=	(isset($_SESSION['report']['other_text']) ? $_SESSION['report']['other_text'] : '');
$authornamecvar	=	(isset($_SESSION['report']['author_name']) ? $_SESSION['report']['author_name'] : '');
$authoremailvar	=	(isset($_SESSION['report']['author_email']) ? $_SESSION['report']['author_email'] : '');
$authorphnvar	=	(isset($_SESSION['report']['author_phone']) ? $_SESSION['report']['author_phone'] : '');
$certificatevar	=	(isset($_SESSION['report']['certificate']) ? $_SESSION['report']['certificate'] : '');

/****** remove dir in case of edit ******/
$dirFlag = @$packObj->removeDir($filedir);
if($dirFlag == 1){
	$log->LogInfo("OCUM report directory removed from root.");
	unset($dirFlag);
}else{
	$log->LogError("OCUM report directory can not be removed from root.");
}

//site header include here 
include('includes/header.php');
?>
<!-- body-content start -->
<div id="body_content">
	<section class="upload-form">
		
		<form enctype="multipart/form-data" method="post" action="ocumConfirm.shtml" name="upload_ocum" id="upload_ocum" onSubmit="return submitformocum();">
    	<div class="up-upload">
		
			<article class="othertext"><span class="red">*</span> are mandatory fields</article>
		</div>
			
					<?php  
                    echo $_SESSION['SESS_MSG'];			
                    unset($_SESSION['SESS_MSG']); 
                    ?>
			
			<h2>Upload Report Template</h2>			
		<ul>		
			<li>
			<ul class="mtop">
			<li><label class="lalign">Report File<span>*</span></label>
				<input type="file" name="zip_file" id="zip_upload">											
					<div class="form-error" id="fileError"></div>
			</li>			
			<hr/>
            </ul>
			</li>
		
        	<li>
            	 <ul><li>Reports Details  <hr/></li>
                	<li><label>Name<span>*</span></label><input type="text" placeholder="Enter Report Name" id="ocum_name" name="ocum_name" maxlength="120" value="<?php echo(isset($reportvar) ? $reportvar : '');?>">
						<div class="form-error" id="ocumNameError"></div>
					</li>
					
                	<li><label>Description<span>*</span></label>
                	  <textarea placeholder="Enter Report Description" id="ocum_desc" name="ocum_desc" class="confirm-textbox"><?php echo (isset($descvar)? $descvar : '');?></textarea>
					  <div class="form-error" id="ocumDescError"></div>
                	</li>
                    
                </ul>
			</li>
            <li>
            	<ul>
                	<li>Version Details<hr/></li>
                    <li><label>Version no.<span>*</span></label><input type="text" id="report_version" name="report_version" placeholder="00.00.00" maxlength="20" value="<?php echo (isset($vervar)? $vervar:'');?>" />
						<div class="form-error" id="versionError"></div>
					</li>
                    <li><label>What's changed</label>
					<textarea placeholder="Enter version specific changes" name="report_version_desc" class="confirm-textbox"><?php echo (isset($verdescvar)? $verdescvar:'');?></textarea>
					</li> 
                </ul>            
            </li>            
             <li>
            	<ul>
                	<li>Pre-requisites<hr/></li>
                    <li><label>OCUM Version<span>*</span></label><input type="text" placeholder="00.00" id="ocum_version" name="ocum_version" maxlength="20" value="<?php echo (isset($ocumvercvar)? $ocumvercvar:'');?>">
						<div class="form-error" id="ocumVerError"></div>
					</li>
                    <li><label>Min ONTAP Version<span>*</span></label><input type="text" name="min_version" placeholder="00.00.00" class="smallinputbox" maxlength="20" value="<?php echo (isset($minvercvar)? $minvercvar:'');?>"> 						
					<label class="smalllabel">Max ONTAP Version</label><input type="text" name="max_version" placeholder="00.00.00" class="smallinputbox" maxlength="20" value="<?php echo (isset($maxvercvar)? $maxvercvar:'');?>">
						<div class="form-error" id="minOntapError"></div>
					</li>
                    <li><label>Other</label>
					<textarea name="other_text" placeholder="Enter other pre-requisites" class="confirm-textbox"><?php echo (isset($othertextcvar)? $othertextcvar:'');?></textarea>
					</li>
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
                    <li><label>Email id</label><input type="email" placeholder="Enter author's email" id="author_email" name="author_email" maxlength="120" value="<?php echo (isset($authoremailvar)? $authoremailvar:'');?>">
						<div class="form-error" id="authorEmailError"></div>
					</li>
                    <li><label>Phone number</label><input type="text" placeholder="Enter author's phone number" id="author_phone" name="author_phone" maxlength="150" value="<?php echo (isset($authorphnvar)? htmlspecialchars($authorphnvar):'');?>" /></li>
                </ul>        
            </li>
            <li>
            	<ul>
                	<li>Upload<hr/></li>
                    <li class="ralign">
						<input type="button" class="myButton" name="cancel" value="Cancel" onClick="javascript:window.location.replace('reports.shtml');">
						<input type="submit" class="myButton1 upload_btn" name="submit" value="Upload">
						<article class="othertext">(Review the report before submitting)</article>
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
