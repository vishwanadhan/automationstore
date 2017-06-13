<?php
session_start(); 
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');  

$log = new KLogger(LOGFILEPATH, KLogger::DEBUG);  
$loginObj = new Login();

//function to retrieve the current page name.
$pageName = getPageName();
$packObj = new Pack();


// site head js include here 
include('includes/head.php');    

/*
 if(isset($_SESSION['wfaPostXml']['packFileDir'])){
	$filedir = $_SESSION['wfaPostXml']['packFileDir'];
	@$packObj->removeDir($filedir);
	unset($_SESSION['wfaPostXml']['packFileDir']);
} 

*/

if(isset($_POST['submit'])){ 

	$filename = $_FILES["zip_file"]["name"];
    $source 	= $_FILES["zip_file"]["tmp_name"];
    $type 	= $_FILES["zip_file"]["type"];
    $size 	= $_FILES["zip_file"]["size"]/1024;    // In KB
   

//  $tags 		= $_POST['packTags'];     
    $wfa_type 	= "workflow";  
    
    if(empty($filename))
	{
		$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Please select a file to upload.");     
	}
	else
		{
		// $name = explode(".", $filename);

		$pos = strrpos($filename, ".");
		$begin = substr($filename, 0, $pos);
		$end = substr($filename, $pos+1);

		$name[0] = $begin;
		$name[1] = $end;	

		$name[0] = isset($name[0]) ? $name[0] : null;
		$name[1] = isset($name[1]) ? $name[1] : null;


		//check to see if the file being uploaded is a zip or dar file.
		$continue = (strtolower($name[1]) == 'zip' || strtolower($name[1]) == 'dar') ? true : false;
		if (!$continue) {
			$log->LogError("Failed to upload the file - not a .zip or .dar file.");
			$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "The file you are trying to upload is not a .zip or .dar file. Please try again.");  
		} else {

		if($size >= MAXUPLOADSIZE) 
			{
				$_SESSION['SESS_MSG'] = msgSuccessFail("fail","Pack Size Issue Hint: Pack size exceeds the allowed pack size (300 MB)");	
				echo"<script>window.location.href='pack-upload.shtml'</script>";					   
				exit;	
			}

			$iMicrotime = microtime(true);
			$iTime = explode('.', $iMicrotime);
			$iMill = $iTime[1];
			$dDate = date('Y-m-d-H-i-s', $iTime[0]) . '-' . $iMill;

			$path = $docRoot . 'wfs_data/build_' . $dDate;
			$export_path = $path . '/';



			 $_SESSION['exportPath'] = $export_path.$name[0];

			
			if (!is_dir($path)) {
				@mkdir($path, 0777);
			}
			if (is_dir($path)) {
				@mkdir($export_path, 0777);
			}

			$target_path = $path . '/' . basename($filename);		  

			// echo $export_path."||hello ".is_dir($path); exit;	   				
			// echo "hello @@$target_path@@".move_uploaded_file($source, $target_path);exit;  
			
			if (move_uploaded_file($source, $target_path)) {  

				$zip = new ZipArchive();
				$x = $zip->open($target_path);
				
				
				if (strtolower($name[1]) == 'zip' || strtolower($name[1]) == 'dar') {
					$zip->extractTo($export_path . $name[0]); // change this to the correct site path	

					$zip->close();
				} else {
					if (!is_dir($export_path . $name[0])) {
						@mkdir($export_path . $name[0], 0777);
					}

					chdir($export_path . $name[0]);
					exec('jar -xvf "' . $target_path.'"', $output);
				}	
		   
				if ($x === true || !empty($output)) {
						$packFilePath = 'wfs_data/build_' . $dDate . '/' . basename($filename);

						$packName = explode(".", $packFilePath);
					   
						// xml read start

						$xml_array = array();
						$xmlFile = $export_path . $name[0] . '/pack-info.xml';

						$_SESSION['FILE_EXISTS']=file_exists($xmlFile);
						
						if (!file_exists($xmlFile)) {


							if (strtolower($name[1]) == 'zip') {
							rrmdir($path);
							$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Pack Format Issues Hint: pack-info.xml Missing");
					//		$log->LogError("Failed to upload the file -  pack-info.xml is missing.");
							echo"<script>window.location.href='pack-upload.shtml'</script>";
							exit;
							}
							else{

							
							$upfiletype="";
							$checkboxselect="checked";
							$checkboxhide="disabled";
							}
						}
					   else{
								$checkXml = isXmlStructureValid($xmlFile);
								if(!$checkXml)
									{
										$_SESSION['SESS_MSG'] = msgSuccessFail("fail","Pack Format Issues Hint: pack-info.xml is not well formed.");	
										echo"<script>window.location.href='pack-upload.shtml'</script>"; 		   
										exit;
									} else {
							$upfiletype='readonly="true"';
							$checkboxhide="";
							$checkboxselect="";
								
								$xml = simplexml_load_file($xmlFile, 'SimpleXMLElement',LIBXML_NOCDATA);
								$deJson = json_encode($xml);
								$xml_array = json_decode($deJson, 1);

								if(empty($xml_array['description']) || empty($xml_array['name']) || empty($xml_array['version']))
					{
						$_SESSION['SESS_MSG'] = msgSuccessFail("fail","Fields must not be empty, check pack-info.xml file");	
						echo"<script>window.location.href='pack-upload.shtml'</script>";     		   
						exit;
					}	
						
					$countEntity = 0;
					foreach($xml_array['entities']['entity'] as $entityArray)
					{
						if(is_array($entityArray))
							{	
								$countEntity++;
							}
							else{
								$countEntity = 1;
							}
					}	
					
					//,$packFilePath,$countEntity, $tags, $type
				
					$packData =	$xml_array;

							}
							}
						

							

						/*$xml = simplexml_load_file($xmlFile, 'SimpleXMLElement',LIBXML_NOCDATA);
						$deJson = json_encode($xml);
						$xml_array = json_decode($deJson, 1);

					if(empty($xml_array['description']) || empty($xml_array['name']) || empty($xml_array['version']))
					{
						$_SESSION['SESS_MSG'] = msgSuccessFail("fail","Fields must not be empty, check pack-info.xml file");	
						echo"<script>window.location.href='pack-upload.shtml'</script>";     		   
						exit;
					}	
						
					$countEntity = 0;
					foreach($xml_array['entities']['entity'] as $entityArray)
					{
						if(is_array($entityArray))
							{	
								$countEntity++;
							}
							else{
								$countEntity = 1;
							}
					}	
					
					//,$packFilePath,$countEntity, $tags, $type
				
					$packData =	$xml_array;*/
					
					$_SESSION['wfaPostXml'] = $xml_array; 
					$_SESSION['wfaPostXml']['packFilePath'] = $packFilePath;
					$_SESSION['wfaPostXml']['packFileDir'] = $path;
					$_SESSION['wfaPostXml']['countEntity'] = $countEntity;
//					$_SESSION['wfaPostXml']['packTags'] = $_POST['packTags'];
					$_SESSION['wfaPostXml']['wfa_type'] = $wfa_type;    
				}
			} else {
				$_SESSION['SESS_MSG'] = msgSuccessFail("fail","There was a problem with the upload. Please try again.");  
			//	$log->LogDebug("Failed to upload the file - file needs to be uploaded again.");
				echo"<script>window.location.href='pack-upload.shtml'</script>";
				exit;
			}
		 //   $_SESSION['SESS_MSG'] = msgSuccessFail("success", "Pack has been added successfully!!!");
			
		}
	}
}
else {
	$pos = strrpos($_SESSION['wfaPostXml']['packFilePath'], ".");
	$begin = substr($_SESSION['wfaPostXml']['packFilePath'], 0, $pos);
	$end = substr($_SESSION['wfaPostXml']['packFilePath'], $pos+1);




						$packName = explode(".", $_SESSION['wfaPostXml']['packFilePath']);
					   
						// xml read start

						$xml_array = array();
						$xmlFile = $docRoot. $packName[0] . '/pack-info.xml';

						


	$pack[0] = $begin; 
	$pack[1] = $end;	
	
	if (!file_exists($xmlFile))  {
		$upfiletype="";
		$checkboxselect="checked";
		$checkboxhide="disabled";  
	}//only for zip
	else {
	
		$upfiletype='readonly="true"';
		$checkboxhide="";
		$checkboxselect="";  
	} 	
}

if(!isset($_SESSION['wfaPostXml']))
{
	echo"<script>window.location.href='pack-upload.shtml'</script>";					   
	exit;	
}
?>   

<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
<script type="text/javascript" src="js/uploadValidation.js"></script>

<script type="text/javascript">
    $(document).ready(function() { 
		/* $("#html_upload_link").click(function () {
			$("#wfa_help_doc_html").trigger('click');
		}); */
		
		$('#packTag').attr('autocomplete','off');
		
		$('#tags input').on('focusout',function(){ 
		var valueTag = '';
		var txt= this.value.replace(/[^a-zA-Z0-9\+\-\.\#]/g,''); // allowed characters
		if(txt.length >20)
		{
			$('#wfaContactTagError').html("Length of 'Tag' cannot be greater than 20 characters");
				this.value="";
				return false;
		}
		if(txt.charAt(0) == '#')
		{
			txt= txt.substring(1);
			$('#wfaContactTagError').html("");
		}
		else
		{
			if(txt.length ==0)
				$('#wfaContactTagError').html("");
			else{
				$('#wfaContactTagError').html("Tag must precede a pound sign ( '#' )");
				this.value="";
				return false;
			}
		}
		if(txt) {
		  $(this).before('<article class="tag">'+ txt.toLowerCase() +'<article class="delete-tag" title="remove this tag"></article></article>');

		  
		}

		$('article.tag').each(function(){
			valueTag = valueTag + $(this).text()+",";
		});
		$('#wfa_tags').val(valueTag);
		this.value="";
	  }).on('keyup',function( e ){
// if: comma,enter (delimit more keyCodes with | pipe)
		if(/(32)/.test(e.which)) $(this).focusout(); 

	  });


	  $('#tags').on('click','.tag',function(){
		  $(this).remove(); 
		  var valueTag = '';
		  $('article.tag').each(function(){
				valueTag = valueTag + $(this).text()+",";
			});
			$('#wfa_tags').val(valueTag);
	  });
		
				// menu work 
		var myElement = document.querySelector("#menu");
		myElement.style.display = "none"; // to hide main menu

		var myElement = document.querySelector(".sasrchBox");
		myElement.style.display = "none"; // to hide search box

	//	var myElement = document.querySelector(".breadcrum");
	//	myElement.style.display = "none"; // to hide breadcrumb 
		// to hide and show success messages	
		
		
	});
	
	
</script>
	
	
<style>


#tags{
	border: 1px solid #ccc;  
    display: inline-block;
    min-height: 25px !important;
    padding-left: 5px;
    width: 780px !important;
	margin-left: 20px;
	padding-bottom:10px;
	
}
#tags input{ margin-left:0px !important; float:left;  width:98% !important; }
#packTag {
    
    border: none;
    outline: none;
}

#tags { box-shadow: 0px !important; }
.tag:hover {
    color: #3e6d8e;
    background-color: #dae6ef;
    border: 1px solid #dae6ef;
}
#tags .post-tag {
    margin: 6px 3px 0 3px;
}
.tag:hover {
    text-decoration: none;
}


.tags
{
    
}
#tags article.tag{
    color: #566e76;
    background: #e4edf4;
    border: 1px solid #c0d4db;
    padding: .4em .5em;
   
   margin: 4px 3px 0 3px;
    text-decoration: none;
    text-align: center;
    font-size: 11px;
    line-height: 1;
    white-space: nowrap;
    display: inline-block;
	width:auto !important;
	float:left;
}

.delete-tag
{
    background-image: url("images/sprites.png?v=c4222387135a");
    
}

.delete-tag {
    width: 14px;
    height: 14px;
    vertical-align: middle;
    display: inline-block;
    background-position: -40px -319px;
    cursor: pointer;
    margin-left: 3px;
    margin-top: 1px;
    margin-bottom: -1px;
}

.delete-tag:hover, .delete-tag-active {
    background-position: -40px -340px;
}

</style>	
	
</head>


<body>

<?php

/* 
 echo "<pre>"; 
print_r($_SESSION['wfaPostXml']);
exit;  */ 
// pack details
$wfa_name		=	(!empty($_SESSION['wfaPostXml']['name']) ? $_SESSION['wfaPostXml']['name'] : $packData['name']);
$wfa_desc		=	(!empty($_SESSION['wfaPostXml']['description']) ? $_SESSION['wfaPostXml']['description'] : $packData['description']);

// version details
$wfa_pack_version	=	(!empty($_SESSION['wfaPostXml']['version']) ? $_SESSION['wfaPostXml']['version'] :  $packData['version']);
$wfa_pack_uuid		=	(!empty($_SESSION['wfaPostXml']['uuid']) ? $_SESSION['wfaPostXml']['uuid'] : $packData['uuid']);

// pre-requisites var
$wfa_version =	(!empty($_SESSION['wfaPostXml']['minWfaVersion']) ? $_SESSION['wfaPostXml']['minWfaVersion'] : $packData['minWfaVersion']);  

$wfa_certificate	=	(!empty($_SESSION['wfaPostXml']['certification']) ? $_SESSION['wfaPostXml']['certification'] : $packData['certification']);   

 $packFilePath		=	(!empty($_SESSION['wfaPostXml']['packFilePath']) ? $_SESSION['wfaPostXml']['packFilePath'] : $packFilePath);   

$packFileDir		=	(!empty($_SESSION['wfaPostXml']['packFileDir']) ? $_SESSION['wfaPostXml']['packFileDir'] : $path); 


$wfa_version_changes	=	htmlspecialchars(trim(!empty($_SESSION['wfaPostData']['wfa_version_changes']) ?  $_SESSION['wfaPostData']['wfa_version_changes'] : ''));   

$wfa_min_soft_version =	trim(!empty($_SESSION['wfaPostData']['wfa_min_soft_version']) ? $_SESSION['wfaPostData']['wfa_min_soft_version'] : '');

$wfa_max_soft_version =	trim(!empty($_SESSION['wfaPostData']['wfa_max_soft_version']) ? $_SESSION['wfaPostData']['wfa_max_soft_version'] : '');

$wfa_other		=	htmlspecialchars(trim(!empty($_SESSION['wfaPostData']['wfa_other']) ? $_SESSION['wfaPostData']['wfa_other'] : '')); 

// author and contact var
$wfa_contact_name	=	trim(!empty($_SESSION['wfaPostData']['wfa_contact_name']) ? $_SESSION['wfaPostData']['wfa_contact_name'] : '');

$wfa_contact_email	=	trim(!empty($_SESSION['wfaPostData']['wfa_contact_email']) ? $_SESSION['wfaPostData']['wfa_contact_email'] : '');

$wfa_contact_phone	=	trim(!empty($_SESSION['wfaPostData']['wfa_contact_phone']) ? $_SESSION['wfaPostData']['wfa_contact_phone'] : ''); 

$wfa_cummunity_link	=	trim(!empty($_SESSION['wfaPostData']['wfa_cummunity_link']) ? $_SESSION['wfaPostData']['wfa_cummunity_link'] : 'http://link'); 

// tag
$wfa_tags	=	trim(!empty($_SESSION['wfaPostData']['wfa_tags']) ? $_SESSION['wfaPostData']['wfa_tags'] : '');  

$wfa_type	=	trim(!empty($_SESSION['wfaPostXml']['wfa_type']) ? $_SESSION['wfaPostXml']['wfa_type'] : $wfa_type); 

$countEntity	=	(!empty($_SESSION['wfaPostXml']['countEntity']) ? $_SESSION['wfaPostXml']['countEntity'] : $countEntity); 

$xml_array	=	(!empty($_SESSION['wfaPostXml']) ? $_SESSION['wfaPostXml'] : $xml_array); 
?>

<!-- header start -->
		<?php
		//site header include here  
		include('includes/header.php');
		?>
<!--header end -->
<div id="body_content">
	
	<div style="margin-top:20px;">
			  <?php 	  
				echo $_SESSION['SESS_MSG']; 
				unset($_SESSION['SESS_MSG']);  
			  ?>
	</div>
		
	<section class="upload-form">
    	<div class="up-upload">
		<article class="othertext"><span class="red">*</span> are mandatory fields</article></div>
    <h2>Upload Workflow Pack</h2>
    	<form enctype="multipart/form-data" method="post" action="wfa-pack-review.php" name="upload_wfa_confirm" id="upload_wfa_confirm" onSubmit="return submitWfa(document.upload_wfa_confirm);">
		
		<input type="hidden" name="wfa_pack_uuid" value="<?php echo $wfa_pack_uuid;?>" />
		<input type="hidden" name="wfa_pack_version" value="<?php echo $wfa_pack_version;?>" />
		<input type="hidden" name="packFilePath" value="<?php echo $packFilePath;?>" /> 
		<input type="hidden" name="packFileDir" value="<?php echo $packFileDir;?>" /> 
		<input type="hidden" name="wfa_type" value="<?php echo $wfa_type;?>" />   
		<input type="hidden" name="countEntity" value="<?php echo $countEntity;?>" />   
		<input type="hidden" name="packDataXml" value="<?php echo base64_encode(json_encode($xml_array));?>" /> 
		<ul>
        	<li>
            	 <ul><li>Pack details  <hr/></li>
                	<li><label>Name<span>*</span></label>
					
					<input type="text" placeholder="Enter Pack Name" name="wfa_name" id="wfa_name" value="<?php echo $wfa_name;?>" <?php echo $upfiletype;?> maxlength="500">                    
						<div class="form-error" id="wfaNameError"></div>
                    </li>
                	  <li><label>Description<span>*</span></label> 
                	  <textarea name="wfa_desc" id="wfa_desc" rows="30" placeholder="Enter Pack Description" class="confirm-textbox" <?php echo $upfiletype;?>><?php echo $wfa_desc;?></textarea>
					  <div class="form-error" id="wfaDescError"></div> 
                	</li>
					  
                    <!--li><label>Documentation<span>*</span></label>  
						<textarea id="wfa_help_doc_text"  name="wfa_help_doc_text" rows="30" class="confirm-textbox" placeholder="Enter help documentation" <?php echo $upfiletype;?> ><?php echo $wfa_help_doc;?></textarea>   
						<label></label>
						<input type="file" id="wfa_help_doc_html"  name="wfa_help_doc_html" disabled="disabled"/>	
						<span id="span_wfa_help_doc_html"> upload html or htm file.only.</span>	 						
						<div class="form-error" id="wfaHelpDocError"></div>
                    </li--> 
                    
                </ul>
			</li>
            <li>
            	<ul>
                	<li>Version details<hr/></li>
                    <li><label>Version no.<span>*</span></label>
					<!-- pattern="[0-9]{1}.[0-9]{1}.[0-9]{1}"  -->
					<input type="text" placeholder="0.0.0" name="wfa_pack_version" id="wfa_pack_version" value="<?php echo $wfa_pack_version;?>" <?php echo $upfiletype;?> maxlength="20">
					<div class="form-error" id="wfaPackVersionError"></div>
					</li>
                    <li><label>What's changed<!-- <span>*</span> --></label> 
					<textarea  rows="30" placeholder="Enter version specific changes" name="wfa_version_changes" id="wfa_version_changes" class="confirm-textbox" ><?php echo $wfa_version_changes;?></textarea>
					<div class="form-error" id="wfaVersionChangesError"></div>                                
                </ul>            
            </li>            
             <li>
            	<ul>
                	<li>Pre-requisites<hr/></li> 
                    <li><label>WFA Version<span>*</span></label>
						<input type="text" maxlength="20" placeholder="0.0.0" name="wfa_version" id="wfa_version" value="<?php echo $wfa_version;?>" <?php echo $upfiletype;?>>
						<div class="form-error" id="wfaVersionError"></div>
                    </li>
                    <li><label>Windows Compatibility<span>*</span></label>  	    
					
					<!--<input type="text" name="wfa_min_soft_version" id="wfa_min_soft_version" placeholder="Yes/No/Partial" class="smallinputbox"  value="<?php echo $wfa_min_soft_version;?>" maxlength="20" >  					
					-->
					&nbsp;&nbsp;&nbsp;
					<select style="vertical-align:top; padding:4px;" name="wfa_min_soft_version" id="wfa_min_soft_version" class="smallinputbox" >
					<option value="">SELECT
					<option value="Yes" <?= ($wfa_min_soft_version == "Yes")? "selected":"";?>>Yes
					<option value="No" <?= ($wfa_min_soft_version == "No")? "selected":"";?>>No
					<option value="Partial" <?= ($wfa_min_soft_version == "Partial")? "selected":"";?>>Partial
					</select>
					<label class="smalllabel">Linux Compatibility<span>*</span></label>
					&nbsp;&nbsp;&nbsp;
						<!--<input name="wfa_max_soft_version" id="wfa_max_soft_version" type="text" placeholder="Yes/No/Partial" class="smallinputbox" value="<?php echo $wfa_max_soft_version;?>" maxlength="20" >	
						-->
					<select style="vertical-align:top; padding:4px;" name="wfa_max_soft_version" id="wfa_max_soft_version" class="smallinputbox" >
					<option value="">SELECT
					<option value="Yes" <?= ($wfa_max_soft_version == "Yes")? "selected":"";?>>Yes
					<option value="No" <?= ($wfa_max_soft_version == "No")? "selected":"";?>>No
					<option value="Partial" <?= ($wfa_max_soft_version == "Partial")? "selected":"";?>>Partial
					</select>	
					<div class="linux-error">
						<span class="form-error wfa-linux-error" id="wfaMinSoftVersionError"></span>
						<span class="form-error wfa-windows-error" id="wfaMaxSoftVersionError"></span>
					</div>						
					</li>
					 
                    <li><label>Other</label>
						<textarea class="confirm-textbox" name="wfa_other" id="wfa_other" placeholder="Enter other pre-requisites" rows="30"><?php echo $wfa_other;?></textarea> 
					</li>
                </ul>
            
            </li>
            <li>
            	<ul>
                	<li>Author and Contact Details<hr/></li>
                    <li><label></label>
                    	<input name="certifiedBy" type="radio" value="NETAPP" <?php if($wfa_certificate == "NETAPP") { echo 'checked="checked"'; } ?> <?php echo $checkboxhide ?>>
                        <label class="tright">NetApp-supported </label>
                        <input name="certifiedBy" type="radio" value="NONE" <?php if($wfa_certificate == "NONE") { echo 'checked="checked"'; } ?> <?php echo $checkboxselect ?>>
                        <label class="tright">Community-generated</label>
                     </li>
                    <li>
                    <label>Name<span>*</span></label>
                    <input type="text" name="wfa_contact_name" id="wfa_contact_name" value="<?php echo $wfa_contact_name;?>" placeholder="Enter author's name" maxlength="120">
						<div class="form-error" id="wfaContactNameError"></div>
                    </li>
                    <li><label>Email id</label>
						<input type="email" name="wfa_contact_email" id="wfa_contact_email" placeholder="Enter author's email"  value="<?php echo $wfa_contact_email;?>" maxlength="120">
						<div class="form-error" id="wfaContactEmailError"></div> 
                    </li>
                    <li><label>Phone number<span>*</span></label>
					<input type="phone" name="wfa_contact_phone" id="wfa_contact_phone" placeholder="Enter author's phone number" maxlength="150" value="<?php echo htmlspecialchars($wfa_contact_phone);?>"> 
						<div class="form-error" id="wfaContactPhoneError"></div>
                    </li>
                     <li><label>Community Link<span>*</span></label>
					 <input type="text" name="wfa_cummunity_link" id="wfa_cummunity_link" placeholder="Enter link for the discussion of the pack in the community, if any" value="<?php echo $wfa_cummunity_link;?>">  
						<div class="form-error" id="wfaCommunityLinkError"></div>
                    </li> 
                </ul>        
            </li>
            <li>
            	<ul>
                	<li>Tags<hr/></li>
                    <li><label>Tags</label>
					
					<!--input type="tags" name="wfa_tags" id="wfa_tags" value="<?php echo $wfa_tags;?>"-->				
						
							<div style="width: 657.777777671814px;" id="tags">
							<?php
								$packTags = explode(",",$wfa_tags);
								$numTags = count($packTags);
								$countTag = 0;
								$txt = '';
								foreach($packTags as $key => $value)
									{
										if(!empty($value))
										{
											$txt .= '<article class="tag">'.$value.'<article title="remove this tag" class="delete-tag"></article>
										</article>';
										}
									} 	
								echo $txt;   
									
							?>
							
							<input type="text" name="tags" style="width: 649.777777671814px;height: 25px;" id="packTag" placeholder="" tabindex="103" autocomplete="off">
							</div> 
						<br>
						<input type="hidden" id="wfa_tags" name="wfa_tags" value="<?php echo $wfa_tags;?>">
                    	<span>( #snapmirror, #load-sharing, #version-flexible" , Use Space to separate tags)</span>
						<div class="form-error" id="wfaContactTagError"></div>
                    </li>
                </ul>
            </li>
            <li>
            	<ul>
                	<li>Upload<hr/></li>
                    <li class="ralign">
						<input type="button" onclick="javascript:window.location.replace('clickCancel.shtml');" name="cancel" value="Cancel" class="myButton">
						 <!--input type="submit" name="submit" value="Upload" style="cursor:pointer;margin-top:40px;z-index:99999;"/-->
						<!-- <input type="image" src="images/upload-btn.png" name="submit" onClick=javascript:submitform();> -->
						<input type="submit" class="myButton" name="confirmSubmit" value="Submit" />         
                    	<article class="othertext">(Review the pack before submitting)</article>         
                    </li> 

                </ul>
            
            </li> 
        </ul></form>
    
    </section>
</div>


<!-- footer start -->
<?php
	include('includes/footer.php');
?> 
<!-- footer end -->
</body>
</html>
