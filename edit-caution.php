<?php
ob_start();
session_start();
require_once('config/configure.php');
require_once('includes/function/autoload.php');
include_once("ckeditor/ckeditor.php");

$loginObj = new Login();
$packObj = new Pack();

$packId = base64_decode($_REQUEST['packUuid']);


if($_GET['type'] =='snapcenter'){
$packObj = new SnapPack();
$cautionContent = $packObj->getCautionByPackId($_GET['packUuid'],$_GET['certi']);
$cautionIn = base64_decode($cautionContent);	
}else{
$cautionContent = $packObj->getCautionByPackId($_GET['packUuid']);
$cautionIn = base64_decode($cautionContent);		
}
// $row = $newsletterObj->newsLetterTempToEdit($id);

 if(isset($_POST['cautionSubmit'])) {		
 			
		//$_POST = postwithoutspace($_POST);
		$_POST['packUuid'] =$_GET['packUuid'];

		
 		if($_GET['type'] =='snapcenter'){
		$packObj = new SnapPack();
		$packObj->editCautionPage($_POST,$_GET['certi']);
		if($_GET['type'] =='snapcenter' && $_GET['certi'] =='NONE'){
			header("Location:snappack-listNC.shtml"); 
		}else{
			header("Location:snap-list.shtml"); 
		}
		exit;
 		}else{
 		$packObj->editCautionPage($_POST);			
		header("Location:pack-list.shtml"); 
		exit;	
 		}
		
} 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<!-- <title>Welcome To <?=SITENAME?> administrative panel</title> -->
	<link rel="stylesheet" type="text/css" href="css/style1.css" /> 
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>	
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	
	<script type="text/javascript">	
	
		function goBack(url)
			{
				window.parent.location.href = url;
				return false;
			}
	
		/* $(document).ready(function() {
				$('#cautionSubmit').click(function(){
					parent.Shadowbox.close();
					$('#editCaution').submit();
					//$("#shadowbox_container").css('visibility','disabled');
				});
		}); */
	
	</script>
</head>
<body>
<?php
    //site header include here  
     include('includes/header.php');  
?>
    <div id="nav-under-bg">
        <!-- -->
    </div>
    <div id="body_content">


  <form name="editCaution" id="editCaution" method="post" enctype="multipart/form-data">
		<div class="main-body-div-new">
          <h2>Edit Caution Page</h2> 
		  <br/>
		  <!-- left position -->
            <div class="main-body-div4" id="mainDiv">
              <div class="add-main-body-left-new">
					
                     <?php											
						$message = $_POST['message']?$_POST['message']:$row['templateContaint']; 
						
					/* 	//$message = $_POST['message']?$_POST['message']:$row['templateContaint'];
						$oFCKeditor = new FCKeditor('cautionContent') ;
						$oFCKeditor->BasePath = 'fckeditor/' ;
						$oFCKeditor->Height	= 400;
						$oFCKeditor->Width	= 600;
						$oFCKeditor->Value = stripslashes($cautionIn); 
					//	$oFCKeditor->ToolbarSet = 'MyToolbar' ;
						$oFCKeditor->Create(); */
					?>
					
				<textarea class="ckeditor" name="cautionContent"><?php echo stripslashes($cautionIn); ?></textarea>
              </div>
			  <br/>
              <div class="main-body-sub">
                <input type="submit" id="cautionSubmit" name="cautionSubmit" class="myButton" value="Submit" />
                &nbsp;
                <input type="button" name="back" id="back" value="Back" class="myButton" <?php if($_GET['type'] =='snapcenter' && $_GET['certi'] =='NONE'){?>onclick="javascript:;goBack('snappack-listNC.shtml');"<?php }elseif ($_GET['type'] =='snapcenter' && $_GET['certi'] =='NETAPP') {?>
                	onclick="javascript:;goBack('snap-list.shtml');"<?php 
                } else { ?>onclick="javascript:;goBack('pack-list.shtml');" <?php }?> />
              </div>
            </div>
</div>

 <input type="hidden" name="packId" value="<?php echo $packId; ?>" />
 <input type="hidden" name="packUuid" value="<?php echo $packUuid; ?>" />

</form>
</div>
<?php
// site head js include here 
include('includes/footer.php');
?>   
</body>
<? unset($_SESSION['SESS_MSG']); ?>