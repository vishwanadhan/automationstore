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


$OciObj = new OciPack();

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

<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            $("table").tablesorter({dateFormat: "uk"});
        });
</script>

<style>


    table.tablesorter thead th {
        color:white;
        font-weight:bold;
        line-height:27px;
        background-color: #0067c4;
        padding: 8px !important;
    }

    table.tablesorter thead tr .header {
    background-image: url('images/orderup.png');
    background-repeat: no-repeat;
    background-position: center right;
    cursor: pointer;
    }

    table.tablesorter thead tr .headerSortUp {
        background-image: url('images/orderup.png');
    }

    table.tablesorter thead tr .headerSortDown {
        background-image: url('images/orderdown.png');
    }
</style>

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
                        <li><a href="report.shtml" >WFA Packs</a></li>                        
                        <li><a href="performanceReports.shtml">Performance</a></li>
                         <li><a href="reportReports.shtml">Reports</a></li>
                          <li><a href="ocireport.shtml" class="selected">OCI Content</a></li>
                          <li><a href="snapreport.shtml"><?php echo CONSTANT_UCWORDS;?></a></li>  
                        
                        
                    </ul>
              
         <div class="tab_container">
                    
              <div class="tab_content mbot" id="tab1" style="display: block;">
                <h2 style="clear:both;">Download History</h2>
                <hr>
                 <section class="download-reports-div comman-link">
                
            

        <table cellpadding="0" cellspacing="0" border="0" class="tablesorter">
            <thead>
             <tr>
            
                <th>User Id</th>
                <th>First Name</th>
				<th>Last Name</th>
                <th>Name</th>
				<th>Type</th>
				<th>Version </th>
                <th>Company Name</th>
				<th>Company Address</th>
                <th>Date/Time </th>         
                
            
                
              </tr>
                </thead>

                 
           <?php echo $OciObj->getDownloadReports("ocipack");?> 
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
