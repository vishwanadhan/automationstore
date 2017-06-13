<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');

$loginObj = new Login();
$userType = $loginObj ->fetchUserType();
$pageName = getPageName();

if(empty($_SESSION['uid']) || $userType == 1)
{
    header("Location: http://automationstore-stg.netapp.com/404.shtml");
}


$packObj = new SnapPack();
$userObj =  new UserProfile();

$_SESSION['backPage']="user_profile.shtml";

$searchText = (isset($_POST['search']) ? $_POST['search'] : null);

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
            $("table").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							4:{sorter:'datetime'}
						} 
				} );  
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

     table.tablesorter thead tr th span { 
        padding-right:12px;
        background-repeat: no-repeat;
  
    }

    table.tablesorter thead tr th.header span{
        background-image: url('images/orderup.png');
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
    }

    table.tablesorter thead tr th.headerSortUp span {
        background-image: url('images/orderup.png');
    }

    table.tablesorter thead tr th.headerSortDown span{
        background-image: url('images/orderdown.png');
    }
</style>
<script type="text/javascript">
$(document).ready(function(){

   
    $('#saveInfo').click(function(){

        var newPhone =  $('#userPhone').val();
        var email = $('#emailNotify').val();
        var userEmail = $('#userEmail').val();

           $.ajax({
                url:'pass.php',
                type:'POST',
                data:{action:'updatePhone',type:'updatePhone', phone:newPhone,emailNotify:email, email:userEmail},
                success:function(response)
                {
                   $('#phoneError').html('Data has been updated successfully');
                }

            });

             setTimeout(function() {
                $("#phoneError").hide('blind', {}, 500)
            }, 2000);
       
    });
});

</script>

<script type="text/javascript">

    function fetchData(id, version, uuid)
    {

        $('#packVal').val(id);
        $('#packVersion').val(version);
        $('#packUuid').val(uuid);
        $("#packForm").attr("action", "pack-detail.shtml");
        $('#packForm').submit();
    }

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

    function setCaution(id,uuid)
    {
        var checkValue = $('.enableCaution'+id).is(":checked");
      
        $('#cautionLoader'+id).html('<img src="images/loading_icon.gif" />');
     
        $.ajax({
            url:"pass.php",
            data:{action:"setCaution", type:"", packId: id, packUuid:uuid, status:checkValue},
            type:"POST",
            success:function(response)
            {
               $('#cautionLoader'+id).html('');              
               location.reload();
                return true;
            }
            
        });
        
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
    
    <div id="body_content">
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
                        <li><a href="user_profile.shtml">Packs</a></li>   
						<li><a href="user_ociprofile.shtml">OCI</a></li>						
                        <li><a href="flaggedData.shtml">Flagged Data</a></li>
                        <li><a href="userSettings.shtml">Settings</a></li>
                        <li><a href="#tab1" class="selected"><?php echo CONSTANT_UCWORDS;?> Packs</a></li>
	              </ul>
              
         <div class="tab_container">
              <div class="tab_content mbot" id="tab1" style="display: block;">
                <h2 style="clear:both;">Packs Downloaded</h2>
                <hr>
                 <section class="packupload-div comman-link">
        <table cellpadding="0" cellspacing="0" border="0" class="tablesorter">
            <thead>
             <tr>
            
                <td>Type</td>
                <th><span>Pack Name</span></th>
                <th><span>Latest Version</span></th>
                <th><span>Pack Type</span></th>
                          
                <th style="width:140px;"><span>Download Date</span></th>
                 <th style="width:70px;"><span>Author</span></th> 
                <td></td>
            
                
              </tr>
                </thead>

                 
           <?php echo $packObj->getUserDownloadedPacks();?> 
        </table>
        
        </section>
   
    <br/><br/>
                <h2 style="clear:both;">Packs Pending Administrator Approval</h2>
                <hr>
                <section class="packupload-div comman-link mbot" style="margin-bottom: 26px !important;">
        
        <table cellpadding="0" cellspacing="0" border="0" class="tablesorter">
            <thead>
            <tr>
            
                <td>Type</td>
                <th><span>Pack Name</span></th>
                <th><span>Latest Version</span></th>
                <th style="width:165px"><span>Min Snap Center Version</span></th>
                            
                <th><span>Released On</span></th>
                <td></td>
                <td></td>
            
                
              </tr>
                </thead>

                 
           <?php echo $packObj->getUserPacks();?> 
        </table>
        
    </section>
                  <h2 style="clear:both;">Packs Rejected</h2>
                    <hr>
        <section class="packrejected-div comman-link">     
                

            <table cellpadding="0" cellspacing="0" border="0"  class="tablesorter">
                <thead>
                 <tr>
                
                    <td>Type</td>
                    <th><span>Pack Name</span></th>
                    <th><span>Admin Name</span></th>
                    <th><span>Admin Email</span></th>
                    <th><span>Comments</span></th>
                    <th><span>Rejected On</span></th>
                  </tr>
                    </thead>
                <?php echo $packObj->getRejectedPackData();?>  
            </table>
            
        </section>
     </div>
        <br/>
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
