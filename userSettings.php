<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();

$packObj = new Pack();
$userType = $loginObj ->fetchUserType();
$userObj =  new UserProfile();

if($userType == 1)
{
    $userObj->updateMailInfo();
}

if(empty($_SESSION['uid']))
    {
        
        echo"<script>window.location.href='pack-list.shtml'</script>";
        exit;
    }

if(isset($_POST['submit']))
{

    
    $phone = $_POST['phone'];
    $email = $_POST['emailNotify'];
   

       $result =  $userObj->updateUserInfo($_POST);
       
       if($result == 1)
       {
           $_SESSION['SESS_MSG'] = msgSuccessFail("success","Data has been updated successfully!!!");
     
        echo "<script language=javascript>window.location.href='userSettings.shtml';</script>";
        exit;
       }
}


$userData = $packObj->getUserDetails();


// site head js include here 
include('includes/head.php');
?>   
                   
<script type="text/javascript">

    function fetchData(id, version, uuid)
    {

        $('#packVal').val(id);
        $('#packVersion').val(version);
        $('#packUuid').val(uuid);
        $("#packForm").attr("action", "pack-detail.php");
        $('#packForm').submit();
    }

    $("#manageGroupShow").append("<span><a href='javascript:cancel(" + dynamicvalues + ")'>cancel</a></span>")

    function clearData()
    {
        $("#filterAuthor").val($("#filterAuthor option:first").val());
        $("#minWfaVersion").val($("#minWfaVersion option:first").val());
        $('#certifiedFilter').prop('checked', false);
        $('#packForm').submit();
    }
	

</script>
<script type="text/javascript" src="js/uploadValidation.js"></script>

<style>
	
</style>

<script type = "text/javascript">

    function showToggle(id)
    {
        $('#random' + id).slideToggle();
    }
	
/* 	function setCaution(id)
	{
		var checkValue = $('#enableCaution').is(":checked");
		$.ajax({
			url:"pass.php",
			data:{action:"setCaution", type:"", packId:id, status:checkValue},
			type:"POST",
			beforesend: function(){
				$('#cautionLoader'+id).show();
			},
			success:function(response)
			{
				$('#cautionLoader'+id).show();
				return true;
			},
			complete: function(response)
			{
				$('#cautionLoader'+id).hide();
			}
			
		});
		
	} */

    var res = "<?php echo $searchText; ?>";

    $(document).ready(function() {
		$('.cautionLoader').hide();
        $('#searchContent').hide();
        $('#resultDiv').hide();
        $('#allpacks').hide();
        $('#searchResults').hide();
        if (res != "") {
            $('.breadcrum span.crumb1').remove();
            $('.breadcrum p').append("<div id='newCrum'> Search Results</div>");
            $('#resultDiv').hide();
            $('#allpacks').hide();
            $('#searchResults').show();
            $('#searchResults').html('');

            var searchValue = res;
            $('#searchName').html(searchValue);
            $('#searchContent').show();
            $('#outerDiv').hide();
            $('#result').html('');

        }


        $('a.packAction').live('click', function(e) {
            Shadowbox.open(this);
            //Stops loading link
            e.preventDefault();
        });

			
	

    });
	


</script>

<script src="js/jquery-1.9.1.js"></script>
</head>


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
						<li><a href="ociPackApproval.shtml">OCI</a></li>
                        <?php
                            } else {
                        ?>
						
                         <li><a href="user_profile.shtml" >Packs</a></li>
						 <li><a href="user_ociprofile.shtml">OCI</a></li>

                        <?php }?>
						
						<li><a href="flaggedData.shtml">Flagged Data</a></li>
                         <li><a href="#tab1" class="selected">Settings</a></li>
                          <?php if($userType !=1)
                            {?>
						 <li><a href="user_snapprofile.shtml"><?php echo CONSTANT_UCWORDS;?> Packs</a></li>
                         <?php } ?>
                         <?php
                            
                            if($userType==1)
                            {

                        ?>
                           <li><a href="report.shtml">Download Statistics</a></li>
						   <li><a href="sendMail.shtml">Send Mail</a></li>
						<?}?>
                    </ul>
              
         <div class="tab_container">

<div class="tab_content mbot" id="tab1" style="display: block;">
 <section class="upload-form">
        
   <div class="details-left"> <h2>My Details</h2></div>
   <div class="details-right">Last logged in: <?php echo date('D j, F, g:i A', strtotime($userData->lastLogin)); ?> PST</div>
   <div class="clear"></div>
         <form enctype="multipart/form-data" method="post" action="" name="user_profile_confirm" id="user_profile_confirm">
        <ul>
       
            <li>
                 <ul><li>  <hr/></li>
                    <li><label>Name</label><input type="text" value="<?php echo ucfirst($_SESSION['firstName']).' '.ucfirst($_SESSION['lastName']);?>" name="name" required="true" readonly style="margin-left: 24px;">
                        
                    </li>
                      <li><label>Email id</label> <input type="text" value="<?php echo $_SESSION['mail'];?>"  name="email" required="true" readonly>
                    </li>
                    <li><label>Phone number</label> <input type="text" value="<?php echo $userData->phone;?>" name="phone" >
                     
                    </li>                    
                </ul>
            </li>
            <li>
                <ul>
                    <li>Email<hr/></li>
                   

                   <?php 
                        if($userType == 1){
                   ?>
                    <li><input name="emailNotify" type="checkbox" disabled="disabled" <?php echo ($userData->receiveMail=='true' ? 'checked' : '');?> />   <label class="profile-tright"> Send me emails for new packs uploaded on the store</label></li></ul>
                <?php }
                    else{

                ?>
                    <li><input name="emailNotify" type="checkbox" <?php echo ($userData->receiveMail=='true' ? 'checked' : '');?> />   <label class="profile-tright"> Send me emails for new packs uploaded on the store</label></li></ul>
                <?php
                        
                    }
                ?>
            </li>
             <li>
                <ul>
                    <li>Submit<hr></li>
                    <li class="ralign">
                        
                        <!--input class="btn" name="submit" value="Save" type="submit"-->
						<input class="myButton" name="submit" value="Save" type="submit">
                        
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

// site head js include here 
include('includes/footer.php');
?>   
</body>
</html>
