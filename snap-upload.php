<?php
session_start(); 

/**
 * snap-upload.php
 * file for uploading a pack with different extensions( zip or dar)
 * uploads the pack, extracts the pack and index the pack;
 * 
 */
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');
require_once('includes/classes/KLogger.php');

//creating a logger object
$log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
$loginObj = new Login();
$pageName = getPageName();

$loginObj->checkSession();

$packObj = new SnapPack();
$userType = $loginObj->fetchUserType();	  
	if($userType != 1 || empty($userType))
	{
	}
	

// site head js include here 
include('includes/head.php');
?>   

<script type="text/javascript">
    $(document).ready(function() {        

        var filename="";
		
		$('#zip_upload').change(function() {			
			fileName = $(this).val().split('/').pop().split('\\').pop();           
			$('#zipFile').val(fileName);
			$(this).css('width','auto');
			$(this).css('color','fff'); 
		});  
    
    });

</script>	  


	<!--[if lte IE 7]> <html class="ie7"> <![endif]-->  
	<!--[if IE 8]>     <html class="ie8"> <![endif]-->  
	<!--[if !IE]><!--> <html><!--<![endif]--> 

</head>
<body>
<?php
//site header include here 
include('includes/header.php'); 
?>
    <div id="nav-under-bg">  
    </div>
<div id="body_content">
	<section class="upload-form">
		<form enctype="multipart/form-data" method="post" action="snap-pack-upload-edit.php" name="upload_form" id="upload_form">
		
			<div class="up-upload">		
			<article class="othertext"><span class="red">*</span> are mandatory fields</article>			
			</div>
			
			<?php  
			echo $_SESSION['SESS_MSG'];			
			unset($_SESSION['SESS_MSG']); 
			unset($_SESSION['wfaPostData']);
			unset($_SESSION['wfaPostXml']);
			unset($_SESSION['wfaPostDataEdit']);
			?>

			<h2>Upload <?php echo CONSTANT_UCWORDS;?> Pack</h2>			
			<ul>		
				<li>
					<ul class="mtop">
						<li>
							<label class="lalign">Pack File<span>*</span></label>
							<input type="file" name="zip_file" id="zip_upload" class="wel" />	
							<span> upload 'zip' or 'dar' file only.</span>					
							<div class="form-error" id="fileError">
								<span class="message">
									<?php
										if (!empty($message)) { 
										echo $message;
										unset($message);
									}
									?>
								</span>
							</div>
						</li>
						<hr/>
					</ul>
				</li>
						
			<li>  
						<ul>
							<li class="ralign">
							<input type="button" class="myButton" name="cancel" value="Cancel" onClick="javascript:window.location.replace('snap-list.shtml');" />   
							<input type="submit" name="submit" id="uploadButton" value="Upload" class="myButton1 upload_btn" />
							<article class="othertext">(Review the report before submitting)</article>
							</li>
						</ul>

						</li>
					
								
			<?php 
			$userType = $loginObj->fetchUserType();	      			
			if($userType == 1)
			{
			?>	
				<li>
				 OR         
				</li> 
				<li>
				<ul>
					<li>Edit From Existing Packs<hr/></li>
					<section class="existingpack-div  comman-link">
						<table cellpadding="0" cellspacing="0" border="0">
							<thead>
								<tr>								
									<td>Pack Name </td>
									<td>Version </td>
									<td>Category </td>
								</tr>
							</thead>
								
				<?php
					$sql = "SELECT uuid, packName, version, certifiedBy, packFilePath  FROM ".TBL_SNAPDETAILS." where post_approved='true'";  		
					$rst = $packObj->executeQry($sql);
					$exec = $rst->execute();
					$num = $packObj->getTotalRow($rst);	 
					
					if($num > 0){
						 $genTable = ""; 
						 $i = 0;
						while($line = $packObj->getResultObject($rst)) {      
							$genTable .= "<tr><td title='".$line->packName ."' alt='". $line->packName ."'>";
							$genTable .= '<a href="snap-pack-upload-confirm.shtml?uuid='.$line->uuid.'&version='.$line->version.'">'.wrapText($line->packName,40).'</a>'; 
							$genTable .= "</td>";
							
							$genTable .= "<td title='". $line->version ."' alt='".$line->version ."'>". wrapText($line->version,20)."</td>"; 
							$genTable .= "<td title='". $line->certifiedBy ."' alt='". $line->certifiedBy ."'>".wrapText($line->certifiedBy,20)."</td>";
							
							$genTable .= "</tr>";
							
							$i++;
						}
						echo $genTable;
					}
					else{
						echo 'No record found';
					}
				?>	
								
								</td>
								
							</tr>           	
								
						</table>    	
						</section>      
				</ul>
				</li>
		<?php 
			}
		
		?>		
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
