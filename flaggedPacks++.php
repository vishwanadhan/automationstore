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

/*if($userType != 1 || empty($userType))
    {
        
        echo"<script>window.location.href='pack-list.shtml'</script>";
        exit;
    }

*/


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
                        <?php
                            } else {
                        ?>
                         <li><a href="user_profile.shtml" >Packs</a></li>

                        <?php }?>
                         <li><a href="#tab1" class="selected">Flagged Packs</a></li>
                         <li><a href="userSettings.shtml">Settings</a></li>
                    </ul>
              
         <div class="tab_container">
                    
        

 <div class="tab_content mbot" id="tab1" style="display: block;">
        <h2>Flagged Packs</h2>
         <hr>
         
         <section class="flagged_packs comman-link">
                <table cellpadding="0" cellspacing="0" border="0">
                        <thead>
                        <tr>
                        <?php if($userType==1){ ?>
                        <td>Pack Name</td>						
                        <td>Category</td>						
                        <td>Version</td>
                        <td>Flag By</td>
                        <td>Trademark Flag</td>
                        <td>Content Flag</td>
                        <td>Comment</td>						
                        <td>Clear</td>
						<?php }else{ ?>
						<td>Pack Name</td>                      
                        <td>Version</td>                        
                        <td>Trademark Flag</td>
                        <td>Content Flag</td>
                        <td>Comment</td>
                        <td>Date</td> 
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
