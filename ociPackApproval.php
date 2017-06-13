<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();

$packObj = new Pack();
$ociObj= new OciPack();
$userType = $loginObj ->fetchUserType();
$userObj =  new UserProfile();

if($userType != 1 || empty($userType))
    {
        
        echo"<script>window.location.href='pack-list.shtml'</script>";
        exit;
    }



// site head js include here 
include('includes/head.php');
?>   


<script src="js/jquery-1.9.1.js"></script>

<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            $("table").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							6:{sorter:'datetime'}
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

<script type="text/javascript">
	$(document).ready(function(){
            $(".js-open-modal").click(function(){
               var ociID =  $(this).attr('id');
                $('#ocipackid').val(ociID);
            });
            
		$("#submitRejection").click(function(){

            
			var id =$('#ocipackid').val();// $('.js-open-modal').attr('id');
            
			var comment = $('.inputbox').val();
			
			
			if(comment == '' || comment == 'Leave Comment')
			{
				alert("Please provide valid comment for user");
			}
			else
			{ //alert(id+comment);
                            //if($('.inputbox').val())
							$("#submitRejection").attr('disabled',true);
				$.ajax({
					url:"pass.php",
					method:"post",
					data:{action:"rejectOCIPack",type:"reject",comment:comment,id:id},
					success:function(response)
					{
                  //    alert(response);  
                     window.location.href="ociPackApproval.shtml";
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
                        <?php
                            } else {
                        ?>
                         <li><a href="user_profile.shtml" >Packs</a></li>

                        <?php }?>
                         <li ><a href="ociPackApproval.shtml" class="selected">OCI </a></li>
                         <li><a href="flaggedData.shtml">Flagged Data</a></li>
                         <li><a href="userSettings.shtml">Settings</a></li>
                           <li><a href="report.shtml">Download Statistics</a></li>
						   <li><a href="sendMail.shtml">Send Mail</a></li>
                    </ul>
              
         <div class="tab_container">
                    
             <div class="tab_content mbot" id="tab1" style="display: block;">
         <h2 style="margin-top:30px;">OCI Pending Approval</h2>
                <hr/>  
                
                  <section class="oci-admin-profile-div comman-link">


        <table cellpadding="0" cellspacing="0" border="0"  class="tablesorter">
            <thead><tr>
               
                <td>Type</td>
                <th><span>Name</span></th>
                <th><span>Version</span></th>
                <th><span>OCI Version</span></th>
                <th><span>OCI Type</span></th>              
                <th><span>Author</span></th>
                <th><span>Released On</span></th>
                <td></td>
                <td></td>
				<td></td>
				
                
              </tr>
                </thead>
                 <?php

                echo $ociObj->unapprovedPackFullInformation();
                ?> 
            </tr>
        </table>
        
    </section>
  
        </div>



            </div>
			
			

            </div>
    </div>
    
    <!-- Pop up -->
<div id="popup1" class="modal-box">
  <header> <a href="javascript:void(0)" class="js-modal-close close" style="float:right;"><img src="images/close-btn.png" border="0" /></a>
    <h2>Reject the Pack</h2>
  </header>
  <div class="modal-body">
    <!--p><input name="" type="checkbox" value="" / align="absmiddle" class="pdright">Trademark or copyright infringement</p>
    <p><input name="" type="checkbox" value="" align="absmiddle" class="pdright" />Pack details and content</p -->
    <p><textarea name="adminComment" class="inputbox" style="resize:vertical ;" placeholder="Leave Comment"></textarea>
	<input name="type" type="hidden" value="ocipackid" align="absmiddle" class="pdright"></p>
    <input name="ocipackid" id="ocipackid" type="hidden" value="" ></p>
  </div>
  <footer> 
        <input type="button" value="Reject" class="btn" id="submitRejection"/> 
        <!--input type="button" value="Cancel" class="btn" id="cancelRejection"/ --> 
        
   </footer>
</div>
<!-- pop end -->

    
<?php

// site head js include here 
include('includes/footer.php');
?>   
</body>
</html>
