<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');

$loginObj = new Login();

//function to retrieve the current page name.
$pageName = getPageName();

$packObj = new SnapPack();

// site head js include here 
include('includes/head.php');    
?>   

<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
<script type="text/javascript" src="js/uploadValidation.js"></script>

<script type="text/javascript">
    $(document).ready(function() {   
	// tag js 

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
				valueTag = valueTag + $(this).text().trim()+",";
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
				valueTag = valueTag + $(this).text().trim()+",";
				});
				$('#wfa_tags').val(valueTag);
			});	
		
		// menu work 
		var myElement = document.querySelector("#menu");
		myElement.style.display = "none"; // to hide main menu

		var myElement = document.querySelector(".sasrchBox");
		myElement.style.display = "none"; // to hide search box
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
#tags input{ margin-left:0px !important; float:left; width:98% !important; }
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

$uuid 		= $_GET['uuid'];
$version 	= $_GET['version'];
$packData = $packObj->getPacksByUuidVersion($uuid,$version);

// pack details
$wfa_name		=	(!empty($_SESSION['wfaPostDataEdit']['wfa_name']) ? $_SESSION['wfaPostDataEdit']['wfa_name'] : $packData['packName']);
$wfa_desc		=	(!empty($_SESSION['wfaPostDataEdit']['wfa_desc']) ? $_SESSION['wfaPostDataEdit']['wfa_desc'] : $packData['packDescription']);

$wfa_help_doc		=	(!empty($_SESSION['wfaPostDataEdit']['wfa_help_doc']) ? $_SESSION['wfaPostDataEdit']['wfa_help_doc'] : $packData['helpDoc']);

 $packFilePath		=	(!empty($_SESSION['wfaPostDataEdit']['packFilePath']) ? $_SESSION['wfaPostDataEdit']['packFilePath'] : $packFilePath);   

$packFileDir		=	(!empty($_SESSION['wfaPostDataEdit']['packFileDir']) ? $_SESSION['wfaPostDataEdit']['packFileDir'] : $path); 

// version details
$wfa_pack_version	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_pack_version']) ?  $_SESSION['wfaPostDataEdit']['wfa_pack_version'] : $packData['version']);

$wfa_pack_uuid		=	(!empty($_SESSION['wfaPostDataEdit']['wfa_pack_uuid']) ?  $_SESSION['wfaPostDataEdit']['wfa_pack_uuid'] : $packData['uuid']);

$wfa_pack_pre_version	=	(!empty($_SESSION['wfaPostDataEdit']['wfa_pack_pre_version']) ?  $_SESSION['wfaPostDataEdit']['wfa_pack_pre_version'] : $packData['version']);	

$wfa_version_changes	=	htmlspecialchars(trim(!empty($_SESSION['wfaPostDataEdit']['wfa_version_changes']) ?  $_SESSION['wfaPostDataEdit']['wfa_version_changes'] : $packData['whatsChanged']));

// pre-requisites var
$wfa_version =	(!empty($_SESSION['wfaPostDataEdit']['wfa_version']) ? $_SESSION['wfaPostDataEdit']['wfa_version'] : $packData['minWfaVersion']);

$wfa_min_soft_version =	trim(!empty($_SESSION['wfaPostDataEdit']['wfa_min_soft_version']) ? $_SESSION['wfaPostDataEdit']['wfa_min_soft_version'] : $packData['minsoftwareversion']);  

$wfa_max_soft_version =	trim(!empty($_SESSION['wfaPostDataEdit']['wfa_max_soft_version']) ? $_SESSION['wfaPostDataEdit']['wfa_max_soft_version'] : $packData['maxsoftwareversion']);

$wfa_other		=	htmlspecialchars(trim(!empty($_SESSION['wfaPostDataEdit']['wfa_other']) ? $_SESSION['wfaPostDataEdit']['wfa_other'] : $packData['preRequisites'])); 

$wfa_certificate	=	(!empty($_SESSION['wfaPostDataEdit']['certifiedBy']) ? $_SESSION['wfaPostDataEdit']['certifiedBy'] : $packData['certifiedBy']); 

// author and contact var
$wfa_contact_name	=	trim(!empty($_SESSION['wfaPostDataEdit']['wfa_contact_name']) ? $_SESSION['wfaPostDataEdit']['wfa_contact_name'] : $packData['contactName']);

$wfa_contact_email	=	trim(!empty($_SESSION['wfaPostDataEdit']['wfa_contact_email']) ? $_SESSION['wfaPostDataEdit']['wfa_contact_email'] : $packData['contactEmail']);

$wfa_contact_phone	=	trim(!empty($_SESSION['wfaPostDataEdit']['wfa_contact_phone']) ? $_SESSION['wfaPostDataEdit']['wfa_contact_phone'] : $packData['contactPhone']);  



$wfa_cummunity_link	=	trim(!empty($_SESSION['wfaPostDataEdit']['wfa_cummunity_link']) ? $_SESSION['wfaPostDataEdit']['wfa_cummunity_link'] : 'http://communities.netapp.com');     

// tag
$wfa_tags			=	trim(!empty($_SESSION['wfaPostDataEdit']['wfa_tags']) ? $_SESSION['wfaPostDataEdit']['wfa_tags'] : $packData['tags']);

//Added By Afsar 
$pos = strrpos($packData['packFilePath'], ".");
$begin = substr($packData['packFilePath'], 0, $pos);
$end = substr($packData['packFilePath'], $pos+1);

$name[0] = $begin;
$name[1] = $end;	

$name[0] = isset($name[0]) ? $name[0] : null;
//$name[1] Will have the .extension
$name[1] = isset($name[1]) ? $name[1] : null;    

$namePack = explode('.'.$name[1],$packData['packFilePath']);
$filePath =$docRoot. $namePack[0].'/Plugin_descriptor.xml';

if (!file_exists($filePath)) {
							$upfiletype="";
							$checkboxselect="checked";
							$checkboxhide="disabled";}else {							
							$upfiletype='readonly="true"';
							$checkboxhide="";
							$checkboxselect="";
							}

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
    <h2>Upload <?php echo CONSTANT_UCWORDS;?> Pack</h2>
    	<form enctype="multipart/form-data" method="post" action="snap-pack-review-confirm.php" name="upload_wfa_confirm" id="upload_wfa_confirm" onSubmit="return submitStoredWfa(document.upload_wfa_confirm);">
		
		<input type="hidden" name="wfa_pack_uuid" value="<?php echo $wfa_pack_uuid;?>" />
		<input type="hidden" name="wfa_pack_pre_version" value="<?php echo $_GET['version'];?>" />
		
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
                    <li><label>What's changed<span>*</span></label> 
					<textarea  rows="30" placeholder="Enter version specific changes" name="wfa_version_changes" id="wfa_version_changes" class="confirm-textbox" ><?php echo $wfa_version_changes;?></textarea>
					<div class="form-error" id="wfaVersionChangesError"></div>                                
                </ul>            
            </li>            
             <li>
            	<ul>
                	<li>Pre-requisites<hr/></li>
                    <li><label>Snap Center Version<span>*</span></label> 
						<input type="text" placeholder="0.0.0" name="wfa_version" id="wfa_version" value="<?php echo $wfa_version;?>" <?php echo $upfiletype;?> maxlength="20">
						<div class="form-error" id="wfaVersionError"></div>
                    </li>
                     
					<li><label>Windows Compatibility<span>*</span></label>	
					&nbsp;&nbsp;&nbsp;
						
						<select style="vertical-align:top; padding:4px;" name="wfa_min_soft_version" id="wfa_min_soft_version" class="smallinputbox" >
						<option value="">SELECT
						<option value="Yes" <?php echo ($wfa_min_soft_version == "Yes")? "selected":"";?>>Yes
						<option value="No" <?php echo ($wfa_min_soft_version == "No")? "selected":"";?>>No
						<option value="Partial" <?php echo ($wfa_min_soft_version == "Partial")? "selected":"";?>>Partial
						</select>
					<label class="smalllabel">Linux Compatibility<span>*</span></label>
					&nbsp;&nbsp;&nbsp;
						
						<select style="vertical-align:top; padding:4px;" name="wfa_max_soft_version" id="wfa_max_soft_version" class="smallinputbox" >
						<option value="">SELECT 
						<option value="Yes" <?php echo ($wfa_max_soft_version == "Yes")? "selected":"";?>>Yes
						<option value="No" <?php echo ($wfa_max_soft_version == "No")? "selected":"";?>>No
						<option value="Partial" <?php echo ($wfa_max_soft_version == "Partial")? "selected":"";?>>Partial
						</select>
						<div class="form-error" id="wfaMinSoftVersionError"></div>
						<div class="form-error" id="wfaMaxSoftVersionError"></div>	
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
                    	<input name="certifiedBy" type="radio" value="NETAPP" <?php if($wfa_certificate == "NETAPP") { echo 'checked="checked"'; } echo $checkboxhide; ?>>
                        <label class="tright">NetApp-supported </label>
                        <input name="certifiedBy" type="radio" value="NONE" <?php if($wfa_certificate == "NONE") { echo 'checked="checked"'; } 	 ?>>
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
					<input type="phone" name="wfa_contact_phone" id="wfa_contact_phone" placeholder="Enter author's phone number" maxlength="150" value="<?php echo htmlspecialchars($wfa_contact_phone);?>" >    
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
						<input type="button" onclick="javascript:window.location.replace('snap-upload.shtml');" name="cancel" value="Cancel" class="myButton">
						<input type="submit" class="myButton" name="confirmSubmit" value="Submit" />  
                    	<article class="othertext">(Review the report before submitting)</article>
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
