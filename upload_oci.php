<?php
session_start();

/**
 * upload_oci.php
 * file for uploading a OCI pack with different extensions( zip or dar)
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

$ociPackObj = new OciPack();

$userType = $loginObj->fetchUserType();

/* There should be a check for admin login */
$userType = $loginObj->fetchUserType();	  

	if(!isset($_SESSION['uid']) )
	{
		 echo "<script>window.location.href='onCommandInsight.shtml'</script>";
         exit; 
	}

// site head js include here 
include('includes/head.php');
?>
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/uploadValidation.js"></script>

<script type="text/javascript">
    $(document).ready(function() { 
		/* $("#html_upload_link").click(function () {
			$("#oci_help_doc_html").trigger('click');
		}); */
		
		$('#packTag').attr('autocomplete','off');
		
		$('#tags input').on('focusout',function(){ 
		var valueTag = '';
		var txt= this.value.replace(/[^a-zA-Z0-9\+\-\.\#]/g,''); // allowed characters
		
		if(txt.length >21)
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
		$('#oci_tags').val(valueTag);
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
			$('#oci_tags').val(valueTag);
	  });
		
				// menu work 
		var myElement = document.querySelector("#menu");
		myElement.style.display = "none"; // to hide main menu

		var myElement = document.querySelector(".sasrchBox");
		myElement.style.display = "none"; // to hide search box
		
	});	
	
</script>

<script language="javascript" type="text/javascript">
	  
	  $(function() {
	  $("#dvPreview").hide();
	  $("#removefile").hide();
			$("#preview_file").on("change", function()
			{
				var files = !!this.files ? this.files : [];
				if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
		 
				if (/^image/.test( files[0].type)){ // only image file
					var reader = new FileReader(); // instance of the FileReader
					reader.readAsDataURL(files[0]); // read the local file
		 
					reader.onloadend = function(){ // set image data as background of div
					$("#dvPreview").show();
					$("#removefile").show();
						$("#dvPreview").css("background-image", "url("+this.result+")");
					}
				}
			});
		
		$("#removefile").on("click", function(){
		 $("#removefile").hide();
		 $("#dvPreview").hide();
		$("#preview_file").val('');
		});
		});
	  
	  
	  
</script>

<style>
#removefile{
color: #0077cc;
font-style: normal;
    cursor: pointer;
    font-family: "Trebuchet MS";
    font-size: 13px;
    text-decoration: none !important;
	}

#tags{
	border: 1px solid #ccc;  
    display: inline-block;
    min-height: 25px !important;
    padding-left: 5px;
    width: 780px !important;
	margin-left: 20px;
	/* padding-bottom:10px; */
	
}
#tags input{ margin-left:0px !important; float:left;  width:99% !important; }
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

$ociTypeId		=	(isset($_SESSION['ociPostData']['ociTypeId']) ? $_SESSION['ociPostData']['ociTypeId'] : '');  
$oci_name		=	(isset($_SESSION['ociPostData']['oci_name']) ? $_SESSION['ociPostData']['oci_name'] : '');
$oci_desc		=	(isset($_SESSION['ociPostData']['oci_desc']) ? $_SESSION['ociPostData']['oci_desc'] : '');

$filevar		=	(isset($_SESSION['ociPostData']['fileuploadpath']) ? $_SESSION['ociPostData']['fileuploadpath'] : '');
$filedir		=	(isset($_SESSION['ociPostData']['fileuploaddir']) ? $_SESSION['ociPostData']['fileuploaddir'] : '');

$previewFileVar		=	(isset($_SESSION['ociPostData']['previewFilePath']) ? $_SESSION['ociPostData']['previewFilePath'] : '');
$previewFileDir		=	(isset($_SESSION['ociPostData']['previewFileDir']) ? $_SESSION['ociPostData']['previewFileDir'] : '');

$oci_pack_version			=	(isset($_SESSION['ociPostData']['oci_pack_version']) ? $_SESSION['ociPostData']['oci_pack_version'] : '');
$verdescvar		=	(isset($_SESSION['ociPostData']['oci_version_desc']) ? $_SESSION['ociPostData']['oci_version_desc'] : '');
$oci_version	=	(isset($_SESSION['ociPostData']['oci_version']) ? $_SESSION['ociPostData']['oci_version'] : '');

$other_text		=	(isset($_SESSION['ociPostData']['other_text']) ? $_SESSION['ociPostData']['other_text'] : '');
$author_name	=	(isset($_SESSION['ociPostData']['author_name']) ? $_SESSION['ociPostData']['author_name'] : '');
$author_email	=	(isset($_SESSION['ociPostData']['author_email']) ? $_SESSION['ociPostData']['author_email'] : '');
$author_phone	=	(isset($_SESSION['ociPostData']['author_phone']) ? $_SESSION['ociPostData']['author_phone'] : '');
// tag
$oci_tags	=	trim(!empty($_SESSION['ociPostData']['oci_tags']) ? $_SESSION['ociPostData']['oci_tags'] : '');  
$certificate	=	(isset($_SESSION['ociPostData']['certificate']) ? $_SESSION['ociPostData']['certificate'] : '');

/****** remove dir in case of edit ******/
$dirFlag = @$ociPackObj->removeDir($filedir);
if($dirFlag == 1){
	$log->LogInfo("OCI pack directory removed from root.");
	unset($dirFlag);
}else{
	$log->LogError("OCI pack directory can not be removed from root.");
}

/****** remove preview dir in case of edit ******/
$previewDirFlag = @$ociPackObj->removeDir($previewFileDir);
if($previewDirFlag == 1){
	$log->LogInfo("OCI pack preview directory removed from root.");
	unset($previewDirFlag);
}else{
	$log->LogError("OCI pack preview directory can not be removed from root.");
}

//site header include here 
include('includes/header.php');
?>
<!-- body-content start -->
<div id="body_content">
	<section class="upload-form">
		
		<form enctype="multipart/form-data" method="post" action="ociConfirm.php" name="upload_oci" id="upload_oci" onSubmit="return submitformoci(document.upload_oci);">
    	<div class="up-upload">
		
			<article class="othertext"><span class="red">*</span> are mandatory fields</article>
		</div>
			
					<?php  
                    echo $_SESSION['SESS_MSG'];			
                    unset($_SESSION['SESS_MSG']); 
                    ?>
			
			<h2>Upload Content Template</h2>			
			<ul>
			<li>
				<ul class="mtop">
				<li>
					<label>OnCommand Types<span>*</span></label>
					&nbsp;&nbsp;&nbsp; 

					<select class="smallinputbox" id="ociTypeId" name="ociTypeId" style="vertical-align:top; padding:4px;">
						<option value="0" <?php if($ociTypeId == '0'){ echo ' selected="selected"'; } ?>>Select</option>
						<option value="1" <?php if($ociTypeId == '1'){ echo ' selected="selected"'; } ?>>Reports</option>
						<option value="2" <?php if($ociTypeId == '2'){ echo ' selected="selected"'; } ?>>Queries</option>
						<option value="3" <?php if($ociTypeId == '3'){ echo ' selected="selected"'; } ?>>Widgets</option>
						<option value="4" <?php if($ociTypeId == '4'){ echo ' selected="selected"'; } ?>>Apis</option>
						<option value="5" <?php if($ociTypeId == '5'){ echo ' selected="selected"'; } ?>>Others</option>
					</select>
					<div id="ociTypeError" class="form-error"></div>					
				</li>
				
				<hr/>
				</ul>					
			</li>

		
			<li>
			<ul class="mtop">
			<li><label class="lalign">OCI File<span>*</span></label> 
				<input type="file" name="zip_file" id="zip_upload">											
					<div class="form-error" id="fileError"></div>
			</li>			
			<hr/>
            </ul>
			</li> 
		
			<li>
				<ul class="mtop">
				<li><label class="lalign">OCI Preview File</label>
					<input type="file" name="preview_file" id="preview_file" onchange="check_upload(this)">											
						<div class="form-error" id="previewFileError"></div>
						<div id="dvPreview" class="dvPreview" style="display: inline-block;">
							<!-- img id="output"/ -->
							
						</div>
						<span><a id="removefile">Remove</a></span>
				</li>			
				<hr/>
				</ul>
			</li>
		
        	<li>
            	 <ul><li>OCI details  <hr/></li>
                	<li><label>Name<span>*</span></label><input type="text" placeholder="Enter Pack Name" id="oci_name" name="oci_name" maxlength="120" value="<?php echo(isset($oci_name) ? $oci_name : '');?>">
						<div class="form-error" id="ociNameError"></div>
					</li>
					
                	<li><label>Description<span>*</span></label>
                	  <textarea placeholder="Enter Pack Description" id="oci_desc" name="oci_desc"><?php echo (isset($oci_desc)? $oci_desc:'');?></textarea>
					  <div class="form-error" id="ociDescError"></div>
                	</li>
                    
                </ul>
			</li>
            <li>
            	<ul>
                	<li>Version details<hr/></li>
                    <li><label>Version no.<span>*</span></label><input type="text" id="oci_pack_version" name="oci_pack_version" placeholder="00.00.00" maxlength="20" value="<?php echo (isset($oci_pack_version)? $oci_pack_version:'');?>" />
						<div class="form-error" id="versionError"></div>
					</li>
                    <li><label>What's changed</label>
					<textarea placeholder="Enter version specific changes" name="oci_version_desc"><?php echo (isset($verdescvar)? $verdescvar:'');?></textarea></li>                                  
                </ul>            
            </li>            
             <li>
            	<ul>
                	<li>Pre-requisites<hr/></li>
                    <li><label>OCI Version<span>*</span></label><input type="text" placeholder="00.00" id="oci_version" name="oci_version" maxlength="20" value="<?php echo (isset($oci_version)? $oci_version:'');?>">
						<div class="form-error" id="ociVerError"></div>
					</li>
                   
                    <li><label>Other</label><textarea name="other_text" placeholder="Enter other pre-requisites"><?php echo (isset($other_text)? $other_text:'');?></textarea></li>
                </ul>            
            </li>
            <li>
            	<ul>
                	<li>Author and Contact Details<hr/></li>
					
					<li><label></label>
                    	<input name="certificate" type="radio" value="NETAPP" <?php echo (($certificate == 'NETAPP') ? Checked : '') ; ?> Checked>
                        <label class="tright">NetApp-Featured </label>
						<input name="certificate" type="radio" value="NETAPPG" <?php echo (($certificate == 'NETAPPG') ? Checked : ''); ?> >
                        <label class="tright">NetApp-Generated</label>
                        <input name="certificate" type="radio" value="NONE" <?php echo (($certificate == 'NONE') ? Checked : ''); ?> >
                        <label class="tright">Community-Generated</label>
						
                     </li>
					
                    <li><label>Name<span>*</span></label><input type="text" placeholder="Enter author's name" id="author_name" name="author_name" maxlength="120" value="<?php echo (isset($author_name)? $author_name:'');?>">
						<div class="form-error" id="authorNameError"></div>
					</li>
					
					<li><label>Email id</label><input type="text" placeholder="Enter author's email" id="author_email" name="author_email" maxlength="120" value="<?php echo (isset($author_email)? $author_email:'');?>">
						<div class="form-error" id="authorEmailError"></div>
					</li>
					
                    <li>
						<label>Phone number</label><input type="text" placeholder="Enter author's phone number" id="author_phone" name="author_phone" maxlength="150" value="<?php echo (isset($author_phone)? htmlspecialchars($author_phone):'');?>" />
						<div class="form-error" id="authorPhoneError"></div> 
					</li>					
                    
                </ul>        
            </li>
			
			<li>
            	<ul>
                	<li>Tags<hr/></li>
                    <li><label>Tags</label>						
						
							<div style="width: 657.777777671814px;" id="tags">
							<?php
								$packTags = explode(",",$oci_tags);
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
						<input type="hidden" id="oci_tags" name="oci_tags" value="<?php echo $oci_tags;?>">
                    	<span>( #snapmirror, #load-sharing, #version-flexible" , Use Space to separate tags)</span>
						<div class="form-error" id="wfaContactTagError"></div>
                    </li>
                </ul>
            </li>
			
            <li>
            	<ul>
                	<li>Upload<hr/></li>
                    <li class="ralign">
						<input type="button" class="cancelbtn" name="cancel" onClick="javascript:window.location.replace('onCommandInsight.shtml');">
						<input type="submit" class="uploadbtn" name="submit" value="">
						<article class="othertext">(Review the pack before submitting)</article>
                    </li>
                </ul>            
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
</body>
</html>
