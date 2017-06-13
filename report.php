<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');



$loginObj = new Login();
$userType = $loginObj ->fetchUserType();
$pageName = getPageName();

if(empty($_SESSION['uid']))
{
    header("Location: http://automationstore-stg.netapp.com/404.shtml");
}


$packObj = new Pack();
$userObj =  new UserProfile();



/* if(!empty($_SESSION['uid']))    {
    $userType = $packObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
    }else{
    $userType = '2';    
} */

$_SESSION['backPage']="user_profile.shtml";

$searchText = (isset($_POST['search']) ? $_POST['search'] : null);
//$phone = $packObj->getUserPhone();

$userData = $packObj->getUserDetails();



// site head js include here 
include('includes/head.php');
?>   
<title>Automation Store Pack List </title>
<meta name="description" content="Displays list of workflow packs uploaded by users on the 
            dev site of Automation Store site.">

<meta name="keywords" content="Automation, store, packs, list, workflow">
<script type="text/javascript" src="js/uploadValidation.js"></script>



<script src="js/jquery-1.9.1.js"></script>

</head>


<body>

    <?php
    //site header include here  
    include('includes/header-landing.php');
    ?>
    
    <div id="body_content">
	<p><a href="admin_profile.shtml"> < Back</a></p>
    <?php if(isset($_SESSION['SESS_MSG'])){ ?>
            <div style="margin-top:20px; margin-bottom:10px;">
              <?php       
                echo $_SESSION['SESS_MSG']; 
                unset($_SESSION['SESS_MSG']); 
              ?>
            </div>
    <?php } ?>
            
        <div class="simpleTabs">
                    <ul class="tabs1 tabswfpck" >
                        <li><a href="#tab1" class="selected">WFA Packs</a></li>                        
                        <li><a href="performanceReports.shtml">Performance</a></li>
                         <li><a href="reportReports.shtml">Reports</a></li>
                         <li><a href="ocireport.shtml">OCI Content</a></li>
                         <li><a href="snapreport.shtml"><?php echo CONSTANT_UCWORDS;?></a></li>
                         
                        
                    </ul>
              
         <div class="tab_container">
                    
              <div class="tab_content mbot" id="tab1" style="display: block;">
                <h2 style="clear:both;">Download History</h2>
                <hr>
                 <section class="download-reports-div comman-link">
                
            

        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
             <tr>
            
                <td>User Id</td>
                <td>First Name</td>
				<td>Last Name</td>
                <td>Pack Name</td>
				<td>Pack Version </td>
                <td>Company Name</td>
				<td>Company Address</td>
                <td>Date/Time </td>         
                
            
                
              </tr>
                </thead>

                 
           <?php echo $packObj->getDownloadReports("workflow");?> 
        </table>
        
    </section>
   
<br/><br/>
                
     </div>
        
            </div>
            </div>
    </div>



<script src="js/support.js"></script>
<?php

// site head js include here 
include('includes/footer.php');
?>    
</body>
</html>
