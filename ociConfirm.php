<?php
session_start();
/**
 * ociConfirm.php
 * file for edit or cancel a OCI pack uploaded on upload_oci.php 
 * uploads the pack and store information in to database;
 * 
 */
require_once('config/configure.php');
require_once('includes/function/autoload.php');
require_once('includes/classes/KLogger.php');

//creating a logger object
$log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
$loginObj = new Login();
$pageName = getPageName();

$ociPackObj = new OciPack();
$userType = $loginObj->fetchUserType();

/* There should be a check for admin login */
$userType = $loginObj->fetchUserType();	 
	if(!isset($_SESSION['uid']) )
	{		
		echo "<script>window.location.href='onCommandInsight.shtml'</script>";
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
		
//	var myElement = document.querySelector(".breadcrum");
//	myElement.style.display = "none"; // to hide breadcrumb
	// to hide and show success messages			
});
</script>

<?php
//site header include here 
include('includes/header.php');
	
// putting all post variables to session
$_SESSION['ociPostData'] = $_POST;

if(!isset($_SESSION['ociPostData'])){
	$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Session expired!");
	echo "<script>window.location.href='onCommandInsight.shtml'</script>"; 		
	exit;
}

$ociTypeId			= (isset($_POST["ociTypeId"]) ? $_POST["ociTypeId"] : '');
$oci_name			= (isset($_POST["oci_name"]) ? $_POST["oci_name"] : '');
$oci_desc			= htmlspecialchars(isset($_POST["oci_desc"]) ? $_POST["oci_desc"] : ''); 
$oci_pack_version	= (isset($_POST["oci_pack_version"]) ? $_POST["oci_pack_version"] : '');
$oci_version_desc	= htmlspecialchars(isset($_POST["oci_version_desc"]) ? $_POST["oci_version_desc"] : '');
$oci_version		= (isset($_POST["oci_version"]) ? $_POST["oci_version"] : '');

$other_text			= htmlspecialchars(isset($_POST["other_text"]) ? $_POST["other_text"] : '');
$author_name		= (isset($_POST["author_name"]) ? $_POST["author_name"] : '');
$author_email		= (isset($_POST["author_email"]) ? $_POST["author_email"] : ''); 
$author_phone		= (isset($_POST["author_phone"]) ? $_POST["author_phone"] : '');
// tag
$oci_tags			= (isset($_POST["oci_tags"]) ? $_POST["oci_tags"] : '');

$certificate		= mysql_real_escape_string(isset($_POST["certificate"]) ? $_POST["certificate"] : '');
$dateVar			= date("jS M, Y");
$filename			= (isset($_FILES["zip_file"]["tmp_name"]) ? $_FILES["zip_file"]["tmp_name"] : '');

$preview_file	    = (isset($_FILES["preview_file"]["tmp_name"]) ? $_FILES["preview_file"]["tmp_name"] : '');

/* File upload */
if((isset($filename)) && ($filename != '')){
		$iMicrotime = microtime(true);
        $iTime = explode('.', $iMicrotime);
        $iMill = $iTime[1];
        $dDate = date('Y-m-d-H-i-s', $iTime[0]) . '-' . $iMill;
		
$actual_path = "wfs_data/oci_".$dDate;
$path = $docRoot ."wfs_data/oci_".$dDate;

$actual_preview_file = "wfs_data/oci_".$dDate;
$preview_path = $docRoot ."wfs_data/oci_".$dDate; 

		$export_path = $path . '/';
        if (!is_dir($path)) {
		//	chown($path, 'root'); 
			$oldmask = umask(0);
            @mkdir($path, 0777, true);
			umask($oldmask);
        }
        if (is_dir($path)) {
		//	chown($path, 'root');
			$oldmask = umask(0);
            @mkdir($export_path, 0777, true);
			umask($oldmask);
        }

$actual_path = $actual_path . '/' . basename($_FILES["zip_file"]["name"]);
$target_path = $path . '/' . basename($_FILES["zip_file"]["name"]); 
	
	if(move_uploaded_file($_FILES['zip_file']['tmp_name'], $target_path)) {
		//echo "<script>alert('File has been uploaded');</script>";	
		$log->LogInfo("OCI file upload successful.");		
	} else{
	
		$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "There is some error while uploading!!!");
		$log->LogError("Failed to upload the OCI file.");		
		echo "<script>window.location.href='upload_oci.shtml'</script>";		
		exit;
	}
	$_SESSION['ociPostData']['fileuploadpath'] = $target_path;
	$_SESSION['ociPostData']['fileuploaddir'] = $path; 
	$filename = ''; // unset value
} 


/* Preview File upload */
if((isset($preview_file)) && ($preview_file != '')){

		$previewFileName = $_FILES["preview_file"]["name"]; 
		$pos = strrpos($previewFileName, ".");
		$begin = substr($previewFileName, 0, $pos); 
		$end = substr($previewFileName, $pos+1);

		$name[0] = $begin; 
		$name[1] = $end;	 

		$continue = (strtolower($name[1]) == 'jpg' || strtolower($name[1]) == 'jpeg' || strtolower($name[1]) == 'png') ? true : false;
		if (!$continue) {
				$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "The file you are trying to upload is not a .jpg .jpeg or .png file. Please try again.");
				$log->LogError("Failed to upload the OCI preview file.");		
				echo "<script>window.location.href='upload_oci.shtml'</script>";		
				exit;    
		}

		$iMicrotime = microtime(true);
        $iTime = explode('.', $iMicrotime);
        $iMill = $iTime[1];
        $dDate = date('Y-m-d-H-i-s', $iTime[0]) . '-' . $iMill;  

		$export_path = $preview_path . '/';
        if (!is_dir($preview_path)) {
         //   chown($path, 'root'); 
			$oldmask = umask(0);
            @mkdir($preview_path, 0777, true);
			umask($oldmask);
        }
        if (is_dir($preview_path)) { 
        //    chown($path, 'root');
			$oldmask = umask(0);
            @mkdir($preview_path, 0777, true);
			umask($oldmask);
        }

$actual_preview_file = $actual_preview_file . '/' . basename($_FILES["preview_file"]["name"]);
$target_path = $preview_path . '/' . basename($_FILES["preview_file"]["name"]); 

	if(move_uploaded_file($_FILES['preview_file']['tmp_name'], $target_path)) {
		//echo "<script>alert('File has been uploaded');</script>";	
		$log->LogInfo("OCI preview file upload successful.");		
	} else{
		$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "There is some error while uploading!!!");
		$log->LogError("Failed to upload the OCI preview file.");		
		echo "<script>window.location.href='upload_oci.shtml'</script>";		
		exit;
	}
	$_SESSION['ociPostData']['previewFilePath'] = $target_path;
	$_SESSION['ociPostData']['previewFileDir'] = $preview_path;
	$preview_file = ''; // unset value
}
else{	
	$actual_preview_file = '';
	$preview_path  = '';
} 

/* on submit of ociConfirm page form submit */
if(isset($_POST['confirmsubmit'])){  

/* echo "<pre>";
print_r($_POST);
exit; */
 $resFlag = @$ociPackObj->addOCIData($_POST);    

 if($resFlag == 1){ 
		if($userType ==1){
		$_SESSION['SESS_MSG'] = msgSuccessFail("success","Content has been uploaded successfully.");
		}
		else{
		
		$_SESSION['SESS_MSG'] = msgSuccessFail("success","Content has been uploaded successfully, Waiting for Administrator approval.");
		}
	
	unset($_SESSION['ociPostData']);
	$log->LogInfo("OCI uploaded to database.");
	
		
		echo "<script>window.location.href='onCommandInsight.shtml'</script>"; 		
		exit;
 }else{
		/****** remove dir in case of duplicate ******/
		$filedir = $_POST['filedir'];
		$dirFlag = @$ociPackObj->removeDir($filedir);
		if($dirFlag == 1){
			$log->LogInfo("OCI directory removed from root.");
		}else{
			$log->LogError("OCI directory can not be removed from root.");
		}
		$_SESSION['SESS_MSG'] = msgSuccessFail("fail","Duplicate OCI can not be uploaded!");
		$log->LogError("Failed to upload the OCI to database.");
		
		echo "<script>window.location.href='onCommandInsight.shtml'</script>"; 		
		exit;
 }		 

		
}	

?>
<div id="body_content">
	
  <section class="details-pack">
     <h2>Review On Command Performance Manager Content Template</h2>
	 <div class="subtitle-confirm">The OCI has not been submitted. Please review the OCI and then click Submit.</div>
   
   	<div class="download-div">
		<div class="download-left"><h3><?php echo $ociPackObj->ociType($ociTypeId);?></h3></div>
   		<div class="download-left"><h3><?php echo $oci_name;?></h3></div>
		
        <div class="downlaod-right"> <?php if($certificate == 'NETAPP'){ ?><img src="images/netapp-certified-icon.png" width="20" height="20" align="absmiddle">This content is NetApp-Featured<?php }
else if($certificate == 'NETAPPG'){ ?>
<img src="images/non-netapp-certified.png" width="20" height="20" align="absmiddle">This content is NetApp-Generated 
<?php }
else{ ?><img src="images/non-netapp-certified.png" width="20" height="20" align="absmiddle">This content is Community Generated
		<?php } ?>
		</div>
	</div>
	<br />
		
  </section>
  <section class="de-left-content">
  <div class="version"><span> Version : </span><?php echo trim($oci_pack_version);?></div>
<article><span>OCI Description</span>
<p><?php echo "<pre>".$oci_desc."</pre>";?></p>
</article> 



<article><span>What's changed</span>
<p><pre><?php echo (!empty($oci_version_desc) ? $oci_version_desc: 'Not Applicable');?></pre></p>
<br />
<span>Pre-requisites</span>
<p><?php echo "<pre>".$other_text."</pre>";?></p>

<?php
		if(!empty($actual_preview_file))		
				{			
					echo '<br /><span>Preview Image </span>
					<div id="dvPreview" class="dvPreview" style="display: inline-block;left: 0px !important;"><img src="'.SITEPATH.$actual_preview_file.'" border="0" title="Preview" /></div>';
				}
			
?>	


</article>

  </section>
   <div class="de-right-content">
   		<section class="de-right-div bt-btm">
        <article>
        		<span>Version History</span>
           		<div>Version: <?php echo trim($oci_pack_version);?></div>
				<div>Released on: <?php echo $dateVar;?></div>
        </article>
		<br />
          <article>
				<div>OCI Version: <?php echo trim($oci_version);?></div>
				<!--div>Released on: 1st Dec, 2014</div-->
          </article>
		<br />
			 <article>
				 <span>Tags</span>
				 <div style="word-wrap: break-word;"><?php echo (!empty($oci_tags) ?  $oci_tags : "");?></div>
			 </article>
		<br /> 
          <article>
                <span>Author</span>
				<div><?php if($certificate == 'NETAPP'){ ?> NetApp <?php }else{ echo ''; }?></div>
           </article>
		   <br />
         <article>
				<span>Contact</span>
				<div>
					<?php echo $author_name;?><br />
					<?php echo $author_email;?><br /> 
					<?php echo $author_phone;?>
				</div>
		</article>
        </section>                          
   
   </div>
   <div class="clear"></div>
    <section class="upload-form submit-form">
	<form name="ociconfirm" id="ociconfirm" method="post" action="">
		<input type="hidden" name="ociTypeId" value="<?php echo trim($ociTypeId);?>" />
		<input type="hidden" name="oci_name" value="<?php echo trim($oci_name);?>" />
		<input type="hidden" name="oci_desc" value="<?php echo $oci_desc;?>" />
		<input type="hidden" name="reportfile" value="<?php echo $actual_path;?>" />
		<input type="hidden" name="filedir" value="<?php echo $path;?>" />
		
		<input type="hidden" name="previewFilePath" value="<?php echo $actual_preview_file;?>" />
		<input type="hidden" name="previewFileDir" value="<?php echo $preview_path;?>" />  
		
		<input type="hidden" name="oci_pack_version" value="<?php echo trim($oci_pack_version);?>" />
		<input type="hidden" name="oci_version_desc" value="<?php echo $oci_version_desc;?>" />
		<input type="hidden" name="oci_version" value="<?php echo trim($oci_version);?>" />
		<input type="hidden" name="other_text" value="<?php echo $other_text;?>" />
		<input type="hidden" name="certificate" value="<?php echo $certificate;?>" />
		<input type="hidden" name="author_name" value="<?php echo $author_name;?>" />   
		<input type="hidden" name="author_email" value="<?php echo $author_email;?>" />
		<input type="hidden" name="author_phone" value="<?php echo htmlspecialchars($author_phone);?>" />
		<input type="hidden" name="oci_tags" value="<?php echo (!empty($oci_tags) ?  $oci_tags : ""); ?>" />
		<input type="hidden" name="releasedate" value="<?php echo $dateVar;?>" />
		<input type="hidden" name="type" value="oci" />
	
			<ul>
				<li>Submit<hr/></li>
				<li class="ralign">
				<input type="button" class="editbtn" name="edit" onClick="javascript:window.location.replace('upload_oci.shtml');" />				
				<input type="button" class="cancelbtn" name="cancel" onClick="javascript:window.location.replace('onCommandInsight.shtml');" />
				<input type="submit" class="submitbtn" name="confirmsubmit" value="" />                  
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



	
	