<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();

$packObj = new Pack();

if(!empty($_SESSION['uid']))
	{
	$userType = $packObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
	
    }
else
{	
	$userType = '2';	
}

unset($_SESSION['report']); // clear session for performance page

$searchText = (isset($_POST['search']) ? $_POST['search'] : null);


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

//    $("#manageGroupShow").append("<span><a href='javascript:cancel(" + dynamicvalues + ")'>cancel</a></span>")

    function clearData()
    {
        $("#filterAuthor").val($("#filterAuthor option:first").val());
        $("#minWfaVersion").val($("#minWfaVersion option:first").val());
        $('#certifiedFilter').prop('checked', false);
        $('#packForm').submit();
    }

    $(document).ready(function() {
        $('#searchContent').hide();
        $('#outerDiv').show();
    });
</script>



<script type = "text/javascript">

    function showToggle(id)
    {
        $('#random' + id).slideToggle();
    }

    var res = "<?php echo $searchText; ?>";

    $(document).ready(function() {

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


        $('a.packAction').on('click', function(e) { 
            Shadowbox.open(this);
            //Stops loading link
            e.preventDefault();
        });



    });

</script>
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
	<?php if(isset($_SESSION['SESS_MSG'])){ ?>
			<div style="margin-top:20px;">
			  <?php 	  
				echo $_SESSION['SESS_MSG']; 
				unset($_SESSION['SESS_MSG']); 
			  ?>
			</div>
	<?php } ?>
			
			<?php 
			//$userType = '1'; /*for testing*/
			if($userType == '1' ){ ?> 		
			<section class="uppck-btn">
			<input type="button" class="myButton"  onclick="javascript:window.location='upload_opm.php';" value="Upload">	
			</section>			
			<section class="reports-admin-div comman-link">
			<?php } else  {   ?>
			
			<section class="reports-div comman-link mbot">
			<?php } ?>		
    	<table cellpadding="0" cellspacing="0" border="0">
        	<thead><tr>		
				<td><?php echo orderBy("performance.php?searchtxt=$searchtxt","certifiedBy","Type")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>									
				<td> <?php echo orderBy("performance.php?searchtxt=$searchtxt","packName","Pack Name")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
                <td><?php echo orderBy("performance.php?searchtxt=$searchtxt","packVersion","Latest Version")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
				<td><?php echo orderBy("performance.php?searchtxt=$searchtxt","OpmVersion","OPM Version")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>             
                <td><?php echo orderBy("performance.php?searchtxt=$searchtxt","packDate","Released On")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderdn.png" border="0" />'; } ?></td>
                <td></td>
				<?php 		
			if($userType == '1' ){
			?> 
				<td></td>
				
				<?php } ?>
              </tr>
				</thead>
				
            	 <?php
				echo $packObj->performanceFullInformation();
                ?> 
            
        </table>
    	
	</section>
	</div>
<?php

// site head js include here 
include('includes/footer.php');
?>   
</body>
</html>
