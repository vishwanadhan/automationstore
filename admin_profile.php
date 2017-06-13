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

if($userType != 1 || empty($userType) || empty($_SESSION['uid']))
    {
        
        echo"<script>window.location.href='pack-list.shtml'</script>";
        exit;
    }

$count = $packObj->countUnApprovedPacks();

//snap packs object creation
$snappackObj = new SnapPack();
$snapcount = $snappackObj->countUnApprovedPacks();
//snap packs object creation



$_SESSION['backPage']="admin_profile.shtml";

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

  //  $("#manageGroupShow").append("<span><a href='javascript:cancel(" + dynamicvalues + ")'>cancel</a></span>") 

    function clearData()
    {
        $("#filterAuthor").val($("#filterAuthor option:first").val());
        $("#minWfaVersion").val($("#minWfaVersion option:first").val());
        $('#certifiedFilter').prop('checked', false);
        $('#packForm').submit();
    }
	

</script>
<script type="text/javascript" src="js/uploadValidation.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#submitRejection").click(function(){

            $(this).attr('disabled',true);
            
			var id = $('.js-open-modal').attr('id');
            
			var comment = $('.inputbox').val();
			
			
			if(comment == '')
			{
				alert("Please provide valid comment for user");
			}
			else
			{
				$.ajax({
					url:"pass.php",
					method:"post",
					data:{action:"rejectPack",type:"reject",comment:comment,id:id},
					success:function(response)
					{
                       $(this).attr('disabled',false);
                        /*alert(response);
                        return false;*/
                      // $('#errorQuery').html(response);
						window.location.href="admin_profile.php";
					}
				
				});
			}
			
		});
		
        $("#submitsnapRejection").click(function(){
            
            
            var id = $('.js-open-modal1').attr('id');
            
            var comment = $('#inputbox1').val();
            
            
            if(comment == '')
            {
                alert("Please provide valid comment for user");
            }
            else
            {
                $(this).attr('disabled',true);
                $.ajax({
                    url:"pass.php",
                    method:"post",
                    data:{action:"rejectsnapPack",type:"reject",comment:comment,id:id},
                    success:function(response)
                    {
                       $(this).attr('disabled',false);
                        window.location.href="admin_profile.php";
                    }
                
                });
            }
            
        });

		$("#cancelRejection").click(function(){
			$(".js-modal-close close").click();
		});
	});
</script>

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


        $('a.packAction').on('click', function(e) { 
            Shadowbox.open(this);
            //Stops loading link
            e.preventDefault();
        });

			
	

    });
	


</script>

<script src="js/jquery-1.9.1.js"></script>

<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            $("table").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							5:{sorter:'datetime'}
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
        <div style="margin-top:20px; margin-bottom:10px;">
            <?php       
            echo $_SESSION['SESS_MSG']; 
            unset($_SESSION['SESS_MSG']); 
            ?>
        </div>
    <?php } ?>
        <div class="simpleTabs">
                    <ul class="tabs1 tabswfpck" >
                          <?php
                            
                            if($userType==1)
                            {

                        ?>
                        <li><a href="admin_profile.shtml" class="selected">Packs</a></li>
                        <?php
                            } else {
                        ?>
                         <li><a href="user_profile.shtml" class="selected">Packs</a></li>

                        <?php }?>
                         <li ><a href="ociPackApproval.shtml" >OCI</a></li>
                         <li><a href="flaggedData.shtml">Flagged Data</a></li>
                         <li><a href="userSettings.shtml">Settings</a></li>
                           <li><a href="report.shtml">Download Statistics</a></li>
						   <li><a href="sendMail.shtml">Send Mail</a></li>
                    </ul>
              
         <div class="tab_container">
                    
             <div class="tab_content mbot" id="tab1" style="display: block;">
                <h2>Packs Pending Approval</h2>
                <hr/>  
                
                  <section class="workflow-admin-profile-div comman-link">


        <table cellpadding="0" cellspacing="0" border="0" class="tablesorter">
            <thead><tr>
              <?php 

                if($count > 1){
              ?>
                <td>Type</td>
                <th><span>Pack Name</span></th>
                <th><span>Latest Version</span></th>
                <th><span>Min WFA Version</span></th>
                <th><span>Author</span></th>              
                <th><span>Released On</span></th>
                <td></td>
                <td></td>
                <td></td>
                <?php 

                   } else
                    {
                ?>
                <td>Type</td>
                <th><span>Pack Name</span></th>
                <th><span>Latest Version</span></th>
                <th><span>Min WFA Version</span></th>
                <th><span>Author</span></th>              
                <th><span>Released On</span></th>
                <td></td>
                <td></td>
                <td></td>

                <?php }?>
              </tr>
                </thead>
                 <?php

                echo $packObj->unapprovedPackFullInformation();
                ?> 
            </tr>
        </table>
        
    </section>
  
        </div>



            </div>

            <!--for snap center packs -->
            <div class="tab_container">
                    
             <!-- <div class="tab_content mbot" id="tab2" style="display: block;"> -->
                <h2><?php echo CONSTANT_UCWORDS;?> Packs Pending Approval</h2>
                <hr/>  
                
                  <section class="workflow-admin-profile-div comman-link">


        <table cellpadding="0" cellspacing="0" border="0" class="tablesorter">
            <thead><tr>
              <?php 

                if($snapcount > 1){
              ?>
                <td>Type</td>
                <th><span>Pack Name</span></th>
                <th><span>Latest Version</span></th>
                <th style="width:165px"><span>Min Snap Center Version</span></th>
                <th><span>Author</span></th>              
                <th><span>Released On</span></th>
                <td></td>
                <td></td>
                <td></td>
                <?php 

                   } else
                    {
                ?>
                <td>Type</td>
                <th><span>Pack Name</span></th>
                <th><span>Latest Version</span></th>
                <th style="width:165px"><span>Min Snap Center Version</span></th>
                <th><span>Author</span></th>              
                <th><span>Released On</span></th>
                <td></td>
                <td></td>
                <td></td>

                <?php }?>
              </tr>
                </thead>
                 <?php

                echo $snappackObj->unapprovedPackFullInformation();
                ?> 
            </tr>
        </table>
        
    </section>
  
        <!-- </div> -->



            </div>
            <!--snap center packs code Ends-->
            </div>
    </div>
    


    <!-- Pop up -->
<div id="popup1" class="modal-box">
  <header> <a href="javascript:void(0)" class="js-modal-close close" style="float:right;"><img src="images/close-icon.png" border="0" /></a>
    <h2>Reject the Pack</h2>
  </header>
  <div class="modal-body">
    <!--p><input name="" type="checkbox" value="" / align="absmiddle" class="pdright">Trademark or copyright infringement</p>
    <p><input name="" type="checkbox" value="" align="absmiddle" class="pdright" />Pack details and content</p -->
    <p><textarea name="adminComment" class="inputbox" style="resize:vertical ;" placeholder="Leave Comment"></textarea></p>
  </div>
  <footer> 
        <input type="button" value="Reject" class="btn" id="submitRejection"/> 
        <!--input type="button" value="Cancel" class="btn" id="cancelRejection"/ --> 
        
   </footer>
</div>
<!-- pop end -->
<!-- Snap Pop up -->
<div id="popup2" class="modal-box">
  <header> <a href="javascript:void(0)" class="js-modal-close close" style="float:right;"><img src="images/close-icon.png" border="0" /></a>
    <h2>Reject the snap Pack</h2>
  </header>
  <div class="modal-body">
    <p><textarea name="adminComment" class="inputbox" id="inputbox1" style="resize:vertical ;" placeholder="Leave Comment"></textarea></p>
  </div>
  <footer> 
        <input type="button" value="Reject" class="btn" id="submitsnapRejection"/> 
        
   </footer>
</div>
<!-- Snap pop end -->

<script src="js/support.js"></script>

    
<?php

// site head js include here 
include('includes/footer.php');
?>   
</body>
</html>
