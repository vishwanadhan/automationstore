<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();


$packObj = new Pack();
$snappackObj = new SnapPack();

$userType = $loginObj ->fetchUserType();
$userObj =  new UserProfile();

/*if($userType != 1 || empty($userType))
    {
        
        echo"<script>window.location.href='pack-list.shtml'</script>";
        exit;
    }

*/


// site head js include here 
include('includes/head.php');
?>   

<script src="js/jquery-1.9.1.js"></script>

<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            $("#table1").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							6:{sorter:'datetime'}
						} 
				} );  
			$("#table2").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							6:{sorter:'datetime'}
						} 
				} );
           $("#table3").tablesorter( 
                {   dateFormat: 'd M, Y', 
                    headers: 
                        {
                            6:{sorter:'datetime'}
                        } 
                } );  
			
        });
</script>
                   
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
						
						<li><a href="flaggedData.shtml" class="selected" >Flagged Data</a></li>
                         <li><a href="userSettings.shtml">Settings</a></li>
                         
						 <?php
                            
                            if($userType==1)
                            {

                        ?>
                           <li><a href="report.shtml">Download Statistics</a></li>
						   <li><a href="sendMail.shtml">Send Mail</a></li>
						<?}else{?>
                        <li><a href="user_snapprofile.shtml"><?php echo CONSTANT_UCWORDS;?> Packs</a></li>
                        <?php }?>
                    </ul>
              
         <div class="tab_container">
                    
        

 <div class="tab_content mbot" id="tab1" style="display: block;">
        <h2>Flagged Packs</h2>
         <hr>
         
         <section class="flagged_packs comman-link">
                <table cellpadding="0" cellspacing="0" border="0" id="table1" class="tablesorter">
                        <thead>
                        <tr>
                        <?php if($userType==1){ ?>
                        <th style="text-align:left !important;"><span>Pack Name</span></th>						
                        <th><span>Latest Version</span></th>
                        <th><span>Flag By</span></th>
                        <th><span>Trademark Flag</span></th>
                        <th><span>Content Flag</span></th>
                        <th><span>Comment</span></th>					
                        <th><span>Date</span></th>							
                        <td>Clear</td>
						<?php }else{ ?>
						<th><span>Pack Name</span></th>                      
                        <th><span>Latest Version</span></th>                        
                        <th><span>Trademark Flag</span></th>
                        <th><span>Content Flag</span></th>
                        <th><span>Comment</span></th>
                        <th><span>Date</span></th> 
                        <?php } ?>
                        </tr>
                        </thead>
                            <?php
							
								if($userType==1)
									echo $packObj->flaggedPackFullInformation();
								else	
									echo $packObj->flaggedPackFullInformationUserProfile();
                            ?>
                    </tr>
                    </table>
         </section>
		 
		 <br/>
		  <h2>Flagged OCI</h2>
         <hr>
         
         <section class="flagged_packs comman-link">
                <table cellpadding="0" cellspacing="0" border="0" id="table2" class="tablesorter">
                        <thead>
                        <tr>
                        <?php if($userType==1){ ?>
                        <th style="text-align:left !important;"><span>OCI Name</span></th>						
                        <th><span>Latest Version</span></th>
                        <th><span>Flag By</span></th>
                        <th><span>Trademark Flag</span></th>
                        <th><span>Content Flag</span></th>
                        <th><span>Comment</span></th>					
                        <th><span>Date</span></th>							
                        <td>Clear</td>
						<?php }else{ ?>
						<th><span>OCI Name</span></th>                      
                        <th><span>Latest Version</span></th>                        
                        <th><span>Trademark Flag</span></th>
                        <th><span>Content Flag</span></th>
                        <th><span>Comment</span></th>
                        <th><span>Date</span></th> 
                        <?php } ?>
                        </tr>
                        </thead>
                            <?php
							
								if($userType==1)
									echo $packObj->flaggedPackOciFullInformation(); 
								else	
									echo $packObj->flaggedPackOciFullInformationUserProfile();
                            ?>
                    </tr>
                    </table>
         </section>
          <br/>
          <h2>Flagged <?php echo CONSTANT_UCWORDS;?></h2>
         <hr>
         
         <section class="flagged_packs comman-link">
                <table cellpadding="0" cellspacing="0" border="0" id="table3" class="tablesorter">
                        <thead>
                        <tr>
                        <?php if($userType==1){ ?>
                        <th style="text-align:left !important;"><span>Snap Center Name</span></th>                      
                        <th><span>Latest Version</span></th>
                        <th><span>Flag By</span></th>
                        <th><span>Trademark Flag</span></th>
                        <th><span>Content Flag</span></th>
                        <th><span>Comment</span></th>                   
                        <th><span>Date</span></th>                          
                        <td>Clear</td>
                        <?php }else{ ?>
                        <th><span>Snap Center Name</span></th>                      
                        <th><span>Latest Version</span></th>                        
                        <th><span>Trademark Flag</span></th>
                        <th><span>Content Flag</span></th>
                        <th><span>Comment</span></th>
                        <th><span>Date</span></th> 
                        <?php } ?>
                        </tr>
                        </thead>
                            <?php
                            
                                if($userType==1)
                                    echo $snappackObj->flaggedPackFullInformation(); 
                                else    
                                    echo $snappackObj->flaggedPackFullInformationUserProfile();
                            ?>
                    </tr>
                    </table>
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
