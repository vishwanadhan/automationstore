<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');  


$loginObj = new Login();

//function to retrieve the current page name.
$pageName = getPageName();

$packObj = new Pack();
$entityObj = new Entity();

// site head js include here  
include('includes/head.php');     
?>   

<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
<script type="text/javascript" src="js/uploadValidation.js"></script>
  
<script type="text/javascript">   
$(document).ready(function(){   
	var myElement = document.querySelector("#menu");
	myElement.style.display = "none"; // to hide main menu
	
	var myElement = document.querySelector(".sasrchBox");
	myElement.style.display = "none"; // to hide search box
		
	//var myElement = document.querySelector(".breadcrum");
	//myElement.style.display = "none"; // to hide breadcrumb
	// to hide and show success messages			
});
</script>

</head>
<!-- Head End -->

<body>
<!-- header start -->
<?php	include('includes/header.php');  ?>
<!-- header end -->

<?php

if(isset($_POST['confirmSubmit']))
{
/* 	echo "<pre>";
	print_r($_POST);
	exit;  */
	$_SESSION['wfaPostDataEdit'] = $_POST;  

	/* confirm submit start */   
			

	/* confirm submit end */ 
	
} 

 if(!isset($_SESSION['wfaPostDataEdit']))
	{
		echo"<script>window.location.href='pack-upload.shtml'</script>";  	 				   
		exit;	
	} 
// pack details
$wfa_name		=	(!empty($_SESSION['wfaPostDataEdit']['wfa_name']) ? $_SESSION['wfaPostDataEdit']['wfa_name'] : $_POST['wfa_name']);
$wfa_desc		=	htmlspecialchars(!empty($_SESSION['wfaPostDataEdit']['wfa_desc']) ? $_SESSION['wfaPostDataEdit']['wfa_desc'] : $_POST['wfa_desc']);

/*
$wfa_help_doc	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_help_doc_html']) ? $_SESSION['wfaPostDataEdit']['wfa_help_doc_html'] : $_POST['helpDoc']);

 $packFilePath		=	(!empty($_SESSION['wfaPostDataEdit']['packFilePath']) ? $_SESSION['wfaPostDataEdit']['packFilePath'] : $_POST['packFilePath']);

$packFileDir		=	(!empty($_SESSION['wfaPostDataEdit']['packFileDir']) ? $_SESSION['wfaPostDataEdit']['packFileDir'] : $_POST['packFileDir']);     */

// version details
$wfa_pack_version	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_pack_version']) ?  $_SESSION['wfaPostDataEdit']['wfa_pack_version'] : $_POST['wfa_pack_version']);

$wfa_pack_uuid		=	(!empty($_SESSION['wfaPostDataEdit']['wfa_pack_uuid']) ?  $_SESSION['wfaPostDataEdit']['wfa_pack_uuid'] : $_POST['wfa_pack_uuid']);

$wfa_pack_pre_version	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_pack_pre_version']) ?  $_SESSION['wfaPostDataEdit']['wfa_pack_pre_version'] : $_POST['wfa_pack_pre_version']);	

$wfa_version_changes	=	htmlspecialchars(!empty($_SESSION['wfaPostDataEdit']['wfa_version_changes']) ?  $_SESSION['wfaPostDataEdit']['wfa_version_changes'] : $_POST['wfa_version_changes']);    

// pre-requisites var
$wfa_version =	(!empty($_SESSION['wfaPostDataEdit']['wfa_version']) ? $_SESSION['wfaPostDataEdit']['wfa_version'] : $packData['wfa_version']);
$wfa_min_soft_version =	(!empty($_SESSION['wfaPostDataEdit']['wfa_min_soft_version']) ? $_SESSION['wfaPostDataEdit']['wfa_min_soft_version'] : $_POST['wfa_min_soft_version']);

$wfa_max_soft_version =	(!empty($_SESSION['wfaPostDataEdit']['wfa_max_soft_version']) ? $_SESSION['wfaPostDataEdit']['wfa_max_soft_version'] : $_POST['wfa_max_soft_version']);

$wfa_other		=	htmlspecialchars(!empty($_SESSION['wfaPostDataEdit']['wfa_other']) ? $_SESSION['wfaPostDataEdit']['wfa_other'] : $_POST['wfa_other']); 

$wfa_certificate  =	(!empty($_SESSION['wfaPostDataEdit']['certifiedBy']) ? $_SESSION['wfaPostDataEdit']['certifiedBy'] : $_POST['certifiedBy']); 

$wfa_community_link = (!empty($_SESSION['wfaPostDataEdit']['wfa_cummunity_link']) ? $_SESSION['wfaPostDataEdit']['wfa_cummunity_link'] : $_POST['wfa_cummunity_link']);

// author and contact var
$wfa_contact_name	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_contact_name']) ? $_SESSION['wfaPostDataEdit']['wfa_contact_name'] : $_POST['wfa_contact_name']);

$wfa_contact_email	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_contact_email']) ? $_SESSION['wfaPostDataEdit']['wfa_contact_email'] : $_POST['wfa_contact_email']);

$wfa_contact_phone	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_contact_phone']) ? $_SESSION['wfaPostDataEdit']['wfa_contact_phone'] : $_POST['wfa_contact_phone']);

// tag
$wfa_tags	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_tags']) ? $_SESSION['wfaPostDataEdit']['wfa_tags'] : $_POST['wfa_tags']);  
$dateVar		= date("jS M, Y");   

$wfa_type	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_type']) ? $_SESSION['wfaPostDataEdit']['wfa_type'] : $_POST['wfa_type']); 

/* on submit of wfaConfirm page form submit */ 
if(isset($_POST['reviewSubmit'])){ 
		  $_SESSION['wfaPostDataEdit'] = $_POST;
		  $packPostData = (!empty($_SESSION['wfaPostDataEdit']) ? $_SESSION['wfaPostDataEdit'] : $_POST);  
		/*   echo "<pre>";
		  print_r($packPostData); 
		  exit;  */
		  $packId = @$packObj->editPack($packPostData);	  
                if (!$packId) {				
					$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Problem in pack edit.");
					//    $log->LogError("Failed to upload the file - Problem in pack edit.");
					echo "<script>window.location.href='workflow-pack-upload-confirm.shtml?uuid=".$packPostData['wfa_pack_uuid']."&version=".$packPostData['wfa_pack_version']."'</script>";
					exit;
                } 				
				
				$_SESSION['SESS_MSG'] = msgSuccessFail("success", "workflow pack edited successfully.");
					
				unset($_SESSION['wfaPostDataEdit']);
				echo "<script>window.location.href='pack-upload.shtml'</script>"; 		
				exit;
}

//confirmsubmit
	
?>

<div id="body_content">
	<!-- section class="back-link">
		<a href="workflows.html"> < Back</a>
	</section -->
	<section class="details-pack">	 
	 <h2>Review workflow pack upload</h2> 
    <div class="subtitle-confirm">The pack has not been submitted. Please review the pack and then click Submit.</div> 
   	<div class="download-div">
   		<div class="download-left"><h3><?php echo $wfa_name;?></h3></div>
        <div class="downlaod-right"> <?php if($wfa_certificate == 'NETAPP'){ ?><img src="images/netapp-certified-icon.png" width="20" height="20" align="absmiddle">This pack is NetApp supported<?php }else{ ?><img src="images/non-netapp-certified.png" width="20" height="20" align="absmiddle">This pack is Community generated
		<?php } ?>    
		</div>
	</div>
	<br />	

	<!--div class="download-div">
		<div class="download-left"><a href="#"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a> 
		<a href="#"> <img src="images/documentation-icon.png" width="20" height="21" border="0" align="absmiddle"  /> Documentation</a></div>
		<div class="downlaod-right"> <img src="images/netapp-certified-icon.png" width="20" height="20"  align="absmiddle"/> This pack is NetApp-supported</div>
	</div-->

	</section>
   <section class="de-left-content">
	<div class="version1">Version : <span><?php echo $wfa_pack_version; ?> </span></div>   
<article><pre> <?php echo html_entity_decode($wfa_desc); ?></pre></article>   


<article><span>What's changed</span>
<pre>
<?php echo (!empty($wfa_version_changes) ? $wfa_version_changes : "Nothing"); ?> 
</pre>
</article>

<article><span>Pre-requisites</span>
<pre>
<?php  echo "Windows Compatibility : ". $wfa_min_soft_version; ?>
<br />
<?php  echo "Linux Compatibility : ". $wfa_max_soft_version; ?>
<br />
<?php  echo "Other : ". (!empty($wfa_other) ?  $wfa_other : "Nothing"); ?>
</pre>
</article> 


<article><span>Community Link</span>
<pre>
<?php echo (!empty($wfa_community_link) ? $wfa_community_link : "Nothing"); ?> 
</pre>
</article> 
<!-- content artice start -->

<!-- content article end -->

<!-- div class="download-left"><a href="#">
<img src="images/download-btn.png" border="0"  align="absmiddle"/></a> 
<a href="#"> <img src="images/documentation-icon.png" width="20" height="21" border="0" align="absmiddle"  /> Documentation</a></div -->

</section>
   <div class="de-right-content">
   		<section class="de-right-div bt-btm">
        <article>
        		<span>Version History</span>
           		<div>Version: <?php echo trim($wfa_pack_version);?></div>
				<div>Released on: <?php echo $dateVar;?></div>
        </article>
		 <br />
         <article>
			 <span>Tags</span>
			 <div style="word-wrap: break-word;"><?php echo $wfa_tags;?></div> 
		 </article>
		   <br /> 
		 <article>
                <span>Author</span>
				<div><?php if($wfa_certificate == 'NETAPP'){ ?> NetApp <?php }else{ echo ''; }?></div> 
           </article>
		   <br />
         <article>
				<span>Contact</span>
				<div><?php echo $wfa_contact_name;?><br />
				<?php echo $wfa_contact_email;?><br />
				<?php echo $wfa_contact_phone;?></div>
		</article>
		
        </section>
   
   </div>
   
			<div class="clear"></div>
			<section class="upload-form submit-form">
			<form enctype="multipart/form-data" method="post" action="" name="upload_wfa_review" id="upload_wfa_review">
			
			<input type="hidden" name="wfa_name" value="<?php echo $wfa_name;?>" />
			<input type="hidden" name="wfa_desc" value="<?php echo $wfa_desc;?>" />
			<!-- help doc -->
			<input type="hidden" name="wfa_pack_version" value="<?php echo $wfa_pack_version;?>" />			
			<input type="hidden" name="wfa_pack_uuid" value="<?php echo $wfa_pack_uuid;?>" />			
			
			<input type="hidden" name="wfa_version_changes" value="<?php echo $wfa_version_changes;?>" />
			<input type="hidden" name="wfa_version" value="<?php echo $wfa_version;?>" />
			<input type="hidden" name="wfa_min_soft_version" value="<?php echo $wfa_min_soft_version;?>" />
			<input type="hidden" name="wfa_max_soft_version" value="<?php echo (!empty($wfa_max_soft_version) ?  $wfa_max_soft_version : "Nothing"); ?>" />
			<input type="hidden" name="wfa_other" value="<?php echo (!empty($wfa_other) ?  $wfa_other : "Nothing"); ?>" />
			<input type="hidden" name="wfa_certificate" value="<?php echo $wfa_certificate;?>" />
			<input type="hidden" name="wfa_community_link" value="<?php echo $wfa_community_link;?>" />
			
			<input type="hidden" name="wfa_contact_name" value="<?php echo $wfa_contact_name;?>" />
			<input type="hidden" name="wfa_contact_email" value="<?php echo $wfa_contact_email;?>" />
			<input type="hidden" name="wfa_contact_phone" value="<?php echo htmlspecialchars($wfa_contact_phone);?>" /> 
			  
			<input type="hidden" name="wfa_type" value="<?php echo $wfa_type;?>" />   
			<input type="hidden" name="wfa_tags" value="<?php echo $wfa_tags; ?>" />  
			
			<ul>
				<li>Submit<hr/></li>
				<li class="ralign">
					<input type="button" class="myButton" value="Edit" name="edit" onClick="javascript:window.location.replace('workflow-pack-upload-confirm.shtml?uuid=<?php echo $wfa_pack_uuid; ?>&version=<?php echo $wfa_pack_version;?>');" />		 
					
					<input type="button" class="myButton" value="Cancel" name="cancel" onclick= "javascript:window.location.replace('pack-upload.shtml');" />	 				
					
					<input type="submit" class="myButton1 upload_btn" name="reviewSubmit" value="Submit" />      		   
				</li>
			</ul>
			
			</form>
			</section>
   
</div>


<!-- footer start -->
<?php
	include('includes/footer.php');
?> 
<!-- footer end -->

</body>
</html>
