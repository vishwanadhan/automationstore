<?php

session_start();
ob_start();


require_once('config/configure.php');
require_once('includes/function/autoload.php');

$loginObj = new Login();
$packObj = new Pack();
$pageName = getPageName();
$userType = $loginObj->fetchUserType();

$userObj = new UserProfile();

if (empty($_SESSION['uid'])) {

    echo"<script>window.location.href='pack-list.shtml'</script>";
    exit;
}
if (isset($_POST['submit'])) {
if (trim($_POST['Packs_Url'])!='') {

    $Packs_Url = $_POST['Packs_Url'];
	$sendMailObj = new SendMailClass();
    $emailInfo = $sendMailObj->mailUpoadedPackNotificationTOallUser($Packs_Url);
		if ($emailInfo) {
			 $_SESSION['SESS_MSG'] = msgSuccessFail("success", "Packs Notification has been send successfully."); 
			
		
		}
	}
	else
	$_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Please enter valid message."); 
}


$userData = $packObj->getUserDetails();

// site head js include here 
include('includes/head.php');
?>   
<script src="js/jquery-1.9.1.js"></script>
<script>
function submitsendmail()
{
	var Packs_Url = document.forms["sendmail_admin"]["Packs_Url"].value;
    if (Packs_Url == null || Packs_Url == "") {	
		document.getElementById("PacksUrlerror").innerHTML = "Message is required";		
		document.sendmail_admin.Packs_Url.focus() ;		
        return false;
	}
    else
    {
    	return true;
    }
	//return true;
}
</script></head>


<body>
<?php
//site header include here  
include('includes/header-landing.php');
?>
    <div id="nav-under-bg">
        <!-- -->
    </div>
    <div id="body_content">
<?php
echo $_SESSION['SESS_MSG'];
unset($_SESSION['SESS_MSG']);
?>
        <div class="simpleTabs">
<ul class="tabs1 tabswfpck" >
                         <?php
                            
                            if($userType==1)
                            {

                        ?>
                        <li><a href="admin_profile.shtml" >Packs</a></li>
                        <?php
                            } else {
                        ?>
                         <li><a href="user_profile.shtml" >Packs</a></li>

                        <?php }?>
						<li><a href="ociPackApproval.shtml">OCI</a></li>
						<li><a href="flaggedData.shtml" >Flagged Data</a></li>
                         <li><a href="userSettings.shtml">Settings</a></li>
                           <li><a href="report.shtml">Download Statistics</a></li>
						   <li><a href="sendMail.shtml" class="selected">Send Mail</a></li>
                    </ul>
            <div class="tab_container">

                <div class="tab_content mbot" id="tab1" style="display: block;">
                    <section class="upload-form">

                        <div class="details-left"> <h2>Send Mails To Notify All Users  </h2></div>
                        <div class="details-right">Last logged in: <?php echo date('D j, F, g:i A', strtotime($userData->lastLogin)); ?> PST</div>
                        <div class="clear"></div>
                        <form enctype="multipart/form-data" method="post" action="" name="sendmail_admin" id="sendmail_admin" onSubmit="return submitsendmail();">
                            <ul>

                                <li>
                                    <ul><li>  <hr/></li>

                                        <li><label>Message:</label> 
                                            <textarea name="Packs_Url" id="Packs_Url" rows="30" placeholder="Enter Packs URL"  class="confirm-textbox" <?php echo $upfiletype; ?>></textarea>

                                            <div class="form-error" id="PacksUrlerror"><?php //echo $_SESSION['PHONE_MSG'] ?></div>
                                        </li>                    
                                    </ul>
                                </li>

                                <li>
                                    <ul>
                                        <li>Submit<hr></li>
                                        <li class="ralign">

                                            <input class="btn" name="submit" value="Send" type="submit" style="cursor: pointer;">

                                        </li>
                                    </ul>            
                                </li>   

                            </ul></form>

                    </section>
                </div>
            </div>
        </div>
    </div>



<?php
include('includes/footer.php');
?>   
</body>
</html>
