<?php
session_start();
/**
 * ocumConfirm.php
 * file for edit or cancel a OCUM report uploaded on upload_ocum.php 
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
	// to hide and show success messages			
});
</script>

<?php
//site header include here 
include('includes/header.php');
	
// putting all post variables to session
$_SESSION['report'] = $_POST;

if(!isset($_SESSION['report'])){
	$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Session expired!");
	echo "<script>window.location.href='reports.shtml'</script>"; 		
	exit;
}

$reportname		= (isset($_POST["ocum_name"]) ? $_POST["ocum_name"] : '');
$desc			= htmlspecialchars(isset($_POST["ocum_desc"]) ? $_POST["ocum_desc"] : '');
$version		= (isset($_POST["report_version"]) ? $_POST["report_version"] : '');
$verDesc		= htmlspecialchars(isset($_POST["report_version_desc"]) ? $_POST["report_version_desc"] : '');
$ocumversion	= (isset($_POST["ocum_version"]) ? $_POST["ocum_version"] : '');
$minversion		= (isset($_POST["min_version"]) ? $_POST["min_version"] : '');
$maxversion		= (isset($_POST["max_version"]) ? $_POST["max_version"] : '');
$otherText		= htmlspecialchars(isset($_POST["other_text"]) ? $_POST["other_text"] : '');
$authorName		= (isset($_POST["author_name"]) ? $_POST["author_name"] : '');
$authorEmail	= (isset($_POST["author_email"]) ? $_POST["author_email"] : '');
$authorPhone	= (isset($_POST["author_phone"]) ? $_POST["author_phone"] : '');
$certificate	= mysql_real_escape_string(isset($_POST["certificate"]) ? $_POST["certificate"] : '');
$dateVar		= date("jS M, Y");
$filename		= (isset($_FILES["zip_file"]["tmp_name"]) ? $_FILES["zip_file"]["tmp_name"] : '');

/* File upload */
if((isset($filename)) && ($filename != '')){
		$iMicrotime = microtime(true);
        $iTime = explode('.', $iMicrotime);
        $iMill = $iTime[1];
        $dDate = date('Y-m-d-H-i-s', $iTime[0]) . '-' . $iMill;
		
$actual_path = "wfs_data/ocum_".$dDate;
$path = $docRoot ."wfs_data/ocum_".$dDate;
		$export_path = $path . '/';
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        if (is_dir($path)) {
            @mkdir($export_path, 0777, true);
        }

$actual_path = $actual_path . '/' . basename($_FILES["zip_file"]["name"]);
$target_path = $path . '/' . basename($_FILES["zip_file"]["name"]); 

	if(move_uploaded_file($_FILES['zip_file']['tmp_name'], $target_path)) {
		//echo "<script>alert('File has been uploaded');</script>";		
	} else{
		$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "There is some error while uploading!!!");		
		echo "<script>window.location.href='reports.shtml'</script>";		
		exit;
	}
	$_SESSION['report']['fileuploadpath'] = $target_path;
	$_SESSION['report']['fileuploaddir'] = $path;
	$filename = ''; // unset value
} 

/* on submit of ocumConfirm page form submit */
if(isset($_POST['confirmsubmit'])){

 $resFlag = @$packObj->addOCUMData($_POST);
 if($resFlag == 1){
	$_SESSION['SESS_MSG'] = msgSuccessFail("success","Report has been uploaded");
 }else{
		/****** remove dir in case of duplicate ******/
		$filedir = $_POST['filedir'];
		$dirFlag = @$packObj->removeDir($filedir);
		if($dirFlag == 1){
			$log->LogInfo("OCUM report directory removed from root.");
		}else{
			$log->LogError("OCUM report directory can not be removed from root.");
		}
	$_SESSION['SESS_MSG'] = msgSuccessFail("fail","Duplicate Report can not be uploaded!");
 }		
		echo "<script>window.location.href='reports.shtml'</script>"; 		
		exit;
}	

?>
<div id="body_content">
	
  <section class="details-pack">
     <h2>Review On Command Unified Manager Report Template</h2>
	 <div class="subtitle-confirm">The report has not been submitted. Please review the report and then click Submit.</div>
   
   	<div class="download-div">
   		<div class="download-left"><h3><?php echo $reportname;?></h3></div>
        <div class="downlaod-right"> <?php if($certificate == 'NETAPP'){ ?><img src="images/netapp-certified-icon.png" width="20" height="20" align="absmiddle">This Report is NetApp-generated<?php }else{ ?><img src="images/non-netapp-certified.png" width="20" height="20" align="absmiddle">This Report is Community generated
		<?php } ?>
		</div>
	</div>
	<br />
		
  </section>
  <section class="de-left-content">
  <div class="version1">Version : <span><?php echo $version;?> </span></div>
<article><p><?php echo "<pre>".$desc."</pre>";?></p></article>



<article><span>What's changed</span>
<p><pre><?php echo (!empty($verDesc) ? $verDesc: 'Not Applicable');?></pre></p>
<br />
<span>Pre-requisites</span>
<p>OCUM Version: <?php echo trim($ocumversion);?></p>

<p> <?php echo "<pre>".$otherText."</pre>";?></p>
<p>Min ONTAP Version:<?php echo trim($minversion);?></p>
<?php $maxversion = trim($maxversion);?>
<p><pre>Max ONTAP Version: <?php echo (!empty($maxversion) ? $maxversion: 'Not Applicable');?></pre></p>
</article>

  </section>
   <div class="de-right-content">
   		<section class="de-right-div">
        <article>
        		<span>Version History</span>
           		<div>Version: <?php echo trim($version);?></div>
				<div>Released on: <?php echo $dateVar;?></div>
        </article>
		<br />
          <article>
				<div>OCUM Version: <?php echo trim($ocumversion);?></div>
				<!--div>Released on: 1st Dec, 2014</div-->
          </article>
		  <br />
          <article>
                <span>Author</span>
				<div><?php if($certificate == 'NETAPP'){ ?> NetApp <?php }else{ echo ''; }?></div>
           </article>
		   <br />
         <article>
				<span>Contact</span>
				<div><?php echo $authorName;?><br />
				<?php echo $authorEmail;?><br />
				<?php echo $authorPhone;?></div>
		</article>
        </section>                          
   
   </div>
   <div class="clear"></div>
    <section class="upload-form submit-form">
	<form name="ocumconfirm" id="ocumconfirm" method="post" action="">
		<input type="hidden" name="reportname" value="<?php echo trim($reportname);?>" />
		<input type="hidden" name="reportdesc" value="<?php echo $desc;?>" />
		<input type="hidden" name="reportfile" value="<?php echo $actual_path;?>" />
		<input type="hidden" name="filedir" value="<?php echo $path;?>" />
		<input type="hidden" name="version" value="<?php echo trim($version);?>" />
		<input type="hidden" name="verchange" value="<?php echo $verDesc;?>" />
		<input type="hidden" name="ocumversion" value="<?php echo trim($ocumversion);?>" />
		<input type="hidden" name="minversion" value="<?php echo trim($minversion);?>" />
		<input type="hidden" name="maxversion" value="<?php echo trim($maxversion);?>" />
		<input type="hidden" name="otherchanges" value="<?php echo $otherText;?>" />
		<input type="hidden" name="certificate" value="<?php echo $certificate;?>" />
		<input type="hidden" name="authorname" value="<?php echo $authorName;?>" />
		<input type="hidden" name="authoremail" value="<?php echo $authorEmail;?>" />
		<input type="hidden" name="authorphone" value="<?php echo htmlspecialchars($authorPhone);?>" />
		<input type="hidden" name="releasedate" value="<?php echo $dateVar;?>" />
		<input type="hidden" name="type" value="ocum" />
	
			<ul>
				<li>Submit<hr/></li>
				<li class="ralign">
				<input type="button" class="myButton" name="edit" value="Edit" onClick="javascript:window.location.replace('upload_ocum.shtml');" />				
				<input type="button" class="myButton" name="cancel" value="Cancel" onClick="javascript:window.location.replace('reports.shtml');" />
				<input type="submit" class="myButton1 upload_btn" name="confirmsubmit" value="Submit" />                  
				</li>                   
			</ul>
	</form>
	</section>
    </div>	
	<!-- body-content end -->
<?php
	// site head js include here 
	include('includes/footer.php');	
?> 
<script src="js/support.js"></script>
</body>
</html>



	
	