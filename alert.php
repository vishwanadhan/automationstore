<?php 
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');
$loginObj = new Login();
$userType = $loginObj ->fetchUserType();
//$loginObj->checkSession();
$pageName = getPageName();
$packObj = new Pack();


if($userType != 1 || empty($userType) || empty($_SESSION['uid']))
    {
        
        echo"<script>window.location.href='pack-list.shtml'</script>";
        exit;
    }

if(isset($_POST['update'])) {
			//$_POST = postwithoutspace($_POST);
		/* echo "<pre>"; 
		print_r($_POST);
		exit;  */
		$packObj->editAlert($_POST);

	/* $alert-message 	= (isset($_REQUEST["alert-message"]) ? $_REQUEST["alert-message"] : null);	
	$alert-active 	= (isset($_REQUEST["alert-active"]) ? $_REQUEST["alert-active"] : null);
	$alert-inactive = (isset($_REQUEST["alert-inactive"]) ? $_REQUEST["alert-inactive"] : null);	
	
	$packObj->updateValue(TBL_ALERT,"alertId = 1","message = '".$alert-message."'"); */
	
	$_SESSION['SESS_MSG'] = msgSuccessFail("success","Pack has been added successfully!!!");	
	echo"<script>window.location.href='packDetails.shtml'</script>";					   
	exit;
}


 include('includes/head.php'); ?>   	 
	
	<!--script type="text/javascript" src="ckeditor/ckeditor.js"></script--> 
	<script type="text/javascript" src="js/uploadValidation.js"></script>    
	
<script type="text/javascript">     
$(window).load(function(){  
	$(document).ready(function () {
		alertChar = document.alertForm.alertMessage.value;
		total = 40 ;
		cal = parseInt(total - alertChar.length); 
		document.alertForm.countdown.value = cal; 
	});
});

function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

function validateForm(obj) {	
  //  var alertMessage = document.getElementById("alertMessage").value; 
  //	var alertMessage = trim(document.forms["alertForm"]["alertMessage"].value); 
	var alertMessage = trim(obj.alertMessage.value);	
//	alert(alertMessage);return false;  
	if (alertMessage == '') {  
        alert("Alert message should not be empty"); 
		document.getElementById("alertMessage").focus() ;  	  
        return false;
    } else {
        return true;
    }
}  
</script>

<style>
	.add-main-body-left-new li.lable {
		clear: both;
		float: left;
		padding: 10px 0 0;
		width: 150px;
	}
	
	.add-main-body-left-new li {
		color: #000000;
		font-family: "Arial";
		list-style: outside none none;
		font-size:9pt;
	}
	
	.add-main-body-left-new li label{
		
	    display: inline-block;
    text-align: left;
    font-size: 10pt;
    color: #000;
    line-height: 20px;
    vertical-align: top;
	padding-left:4px;
	margin-right:10px;
	    line-height: 15px !important;
}
	
	}
	
	.alert-update {
		clear: both;
		line-height: 30px;
		margin: 0 0 15px 137px;
		padding: 15px 0 0 10px;
		width: auto;
	}	
	
	label.error{
		margin: 0 auto;
		text-align: center !important;
		padding: 5px 18px 5px 18px;
		font-size: 9pt !important;
		color: #FF0000 !important;
		font-weight: bold;
		margin-top: 10px;
		width:100%;
		display:block!important;
	}
/* 	
	div#editor{
		margin-left: 147px;
	} 
*/
</style>

</head>
<body>
<?php 
  //site header include here 
 include('includes/header.php'); 
 ?>
 
<div id="nav-under-bg">
  <!-- -->
</div>

<div id="body_content" style="display: block;"> 
	<!-- <p><h2>Alert Window</h2></p> -->
	<div>
		  <? 
			echo $_SESSION['SESS_MSG'];  
			unset($_SESSION['SESS_MSG']); 
		  ?>
	</div>
	<form name="alertForm" id="alertForm" enctype="multipart/form-data" method="post" action="" onSubmit="return validateForm(this);">   
		<input type="hidden" name="alertId" value="1">  
		
		<div class="add-main-body-left-new" >
			<h2>Alert Message <span class="spancolor">*</span></h2>   
			<br>
			<ul> 				
				<?php
					
					$value = $packObj->fetchValue(TBL_ALERT,'message',"alertId=1");
					$status = $packObj->fetchValue(TBL_ALERT,'status',"alertId=1");		
					
					$status_active 		= ($status == '1') ? "checked='checked'" : " ";
					$status_inactive 	= ($status == '0') ? "checked='checked'" : " ";
					
					$genTable = '';
					/* if(empty($value)) {			
						$genTable = '<li><textarea cols="30" rows="10" name="alert-message" id="alert-message">'.stripslashes($value).'<textarea></li>';  
					} else { */
						/* $genTable .= '<li>
									<textarea placeholder="Alert Message" 
									class="field-style" id="alert-message" name="alert-message" style="white-space: normal;height: 125px;width: 350px;">'.$value.'</textarea></li>';  */ 
									
						$genTable .= '<li><div id="editor"><textarea placeholder="Alert Message" class="confirm-textbox" id="alertMessage" name="alertMessage" rows="10" cols="80" onKeyDown="limitText(this.form.alertMessage,this.form.countdown,40);" 
onKeyUp="limitText(this.form.alertMessage,this.form.countdown,40);">'.$value.'</textarea><br> 
<font size="1">(Maximum characters: 40)<br>
You have <input readonly type="text" name="countdown" size="3" value=""> characters left.</font></div></li>';
									  
						$genTable .= '<li>
						<br>
										<input type="radio" id="alert-status" name="alert-status" '.$status_active.' value="active"><label>Active</label> 
										<input type="radio" id="alert-status" name="alert-status" '.$status_inactive.' value="inactive"><label>In Active</label> 
									</li>';
									
						$genTable .= '<br/>'; 			 			
			//		}					
					echo $genTable; 
				?>
			</ul>
			
			<div class="alert-update">
				<input type="submit" name="update" class="myButton" value="Update" />
				&nbsp;
				<!-- <input type="button" name="back" id="back" value="Back" class="main-body-sub-submit" style="cursor:pointer;"  onclick="javascript:;hrefBack1()"/> -->
			</div>
		</div>	
	</form>

	
</div>
	
</div>
</body>
</html>



