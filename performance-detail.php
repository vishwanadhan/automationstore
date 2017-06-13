<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();

//function to retrieve the current page name.
$pageName = getPageName();

$entityObj = new Entity();
$packobj   = new Pack();

$searchText = (isset($_POST['search']) ? $_POST['search'] : null);
		
// site head js include here 
include('includes/head.php');
?>   
<SCRIPT src="js/support.js"></SCRIPT>
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
<script type="text/javascript" src="js/uploadValidation.js"></script>
<!-- Flag report start -->
<script type="text/javascript">
	$(document).ready(function(){
		// flag click
			$("#submitFlag").click(function(){				
				$("a.js-modal-close").click(function() {
					$('#comment_error').html('');
				});
					
			//submit form validation check	
				if(submitFlagForm()){
					// model box click 	
					$("a.js-modal-close").trigger("click");					
					document.grievanceReport.submit();
				}else{
					return false;
				}
				
			});	
				//cancel click on flag	
				$("#cancelFlag").click(function(){
					$(':input','#grievanceReport')
					 .not(':button, :submit, :reset, :hidden')
					 .val('')
					 .removeAttr('checked')
					 .removeAttr('selected');
					$("a.js-modal-close").trigger("click");			
				});
		// deprecate click
			$("#submitDep").click(function(){				
				$("a.js-modal-close").click(function() {
					$('#depComment_error').html('');
				});				
				//submit form validation check	
				if(submitDepForm()){
					// model box click 	
					$("a.js-modal-close").trigger("click");					
					document.packDeprecation.submit();
				}else{
					return false;
				}				
			});
			//cancel click on deprecate	
			$("#cancelDep").click(function(){
				$(':input','#packDeprecation')
					 .not(':button, :submit, :reset, :hidden')
					 .val('')
					 .removeAttr('checked')
					 .removeAttr('selected');
				$("a.js-modal-close").trigger("click");			
			});
		// star rating form submit		
		$(".star").click(function(){
			//alert('star clicked');						
			$("#submit_rating").trigger("click");
			return true;
		});
	});
</script>
<!-- Flag report end -->
<script type="text/javascript">
function setDeprecationFlag(id,version) {
        var checkValue = $('.deprecationFlag'+id).is(":checked");
      
        $('#cautionLoader'+id).html('<img src="images/loading_icon.gif" />');
        /* alert("checked value is: "+checkValue);
        return false; */
        $.ajax({
            url:"pass.php",
            data:{action:"setDeprecationFlag", packtype:"Performance", packId: id, packVesion:version, status:checkValue},
            type:"POST",
            success:function(response)
            {
			   //alert(response);
               $('#cautionLoader'+id).html('');              
               location.reload();
                return true;
            }           
        });       
    }
</script>
</head>


<body>
    <?php
    //site header include here  
    include('includes/header.php');
    ?>
	<?php
	/***** Grievance Reporting for Performance Start *****/
	if($_POST['submitFlag'] == 'Flag'){
		$responseReport =  @$packobj->addGrievance($_POST); 
		$_POST = '';
	}
	/***** Grievance Reporting for Performance End *****/
	
	/***** Star rating for Performance Start *****/
	if(isset($_POST['submit_rating'])){
		//print_r($_POST); exit;
		$responseRating =  @$packobj->addStarRating($_POST); 
		$_POST = '';
	}
	/***** Star rating for Performance End *****/
	/*** Deprecate pack for Reports Start ***/
	if(isset($_POST['submitDep'])){		
		$responseDeprecate =  @$packobj->addDeprecate($_POST); 
		$_POST = '';
	}
	/*** Deprecate pack for Reports End ***/
	?>
     <div id="nav-under-bg"></div> 
    
    <div id="body_content">
					<?php  
                    echo $_SESSION['SESS_MSG'];			
                    unset($_SESSION['SESS_MSG']); 
                    ?>
        <form name="entityForm" id="entityForm" method="post" action="">
            <?php
            $packVersion = (isset($_REQUEST["packVersion"]) ? $_REQUEST["packVersion"] : null);
			$packName = (isset($_REQUEST["packName"]) ? $_REQUEST["packName"] : null);
            if ($packName == null || $packVersion == null) {
               header('Location: performance.php');
            } else {
                echo @$entityObj->performanceInformation($packName,$packVersion,"");
           }
            ?> 	
        </form>
    </div>
<?php
// site head js include here 
include('includes/footer.php');
?>   
<!-- Pop up -->
<div id="popup1" class="modal-box">
<?php
$packnameget = implode(" ",explode("!",$packName));
?>
  <header> <a href="javascript:void(0)" class="js-modal-close close"><img src="images/close-icon.png" border="0" /></a>
    <h2>Flag as inappropriate</h2>
  </header>
  <form name="grievanceReport" id="grievanceReport" action="" method="post">
  <div class="modal-body">
  <p>Select reasons(s) for flagging:</p>
    <p><input name="checkTrademark" id="checkTrademark" type="checkbox" value="true" align="absmiddle" class="pdright">Trademark or copyright infringement</p>
    <p><input name="checkInfringement" id="checkInfringement" type="checkbox" value="true" align="absmiddle" class="pdright">Infringement pack details and content</p>
    <p>Comment <span class="red">*</span><textarea name="flagComment" class="inputbox" style="resize:none;" placeholder="Leave Comment"></textarea>
	<div class="popup-error" id="comment_error"></div></p>
	
   <input type="hidden" name="detailPageUrl" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" />
    <input type="hidden" name="packType" id="packType" value="OPM" />    
    <input type="hidden" name="packVersion" id="packVersion" value="<?php echo $packVersion; ?>" />
    <input type="hidden" name="packName" id="packName" value="<?php echo $packnameget; ?>" />
    
  </div>
   <footer> 
  		<!--input type="button" name="cancel" value="Cancel" class="myButton" id="cancelFlag"--> 
        <input type="submit" value="Flag" class="myButton" id="submitFlag" name="submitFlag"/>   		
   </footer>  
    </form>
</div>
<!-- pop end -->
<!-- Pop up deprecation -->
<div id="popup2" class="modal-box">
	<header> <a href="javascript:void(0)" class="js-modal-close close"><img src="images/close-icon.png" border="0" /></a>
		<h2>Deprecate Pack</h2>
	</header>
	<form name="packDeprecation" id="packDeprecation" action="" method="post">
		<div class="modal-body">
		<p>Your comments will be stored and others can view it.</p>
			
		<p>Comment <span class="red">*</span><textarea name="depComment" class="inputbox" style="resize:none;" placeholder="Leave reason for requesting to deprecate the pack" autofocus="autofocus"></textarea>
		<div class="popup-error" id="depComment_error"></div></p>
		
		<input type="hidden" name="detailPageUrl" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" />
		<input type="hidden" name="depPackid" id="depPackid" value="<?php echo $packid = $entityObj->getPackIdOPM($packnameget, $packVersion); ?>" />
		<input type="hidden" name="depPackType" id="depPackType" value="Performance" />
		<input type="hidden" name="depPackVersion" id="depPackVersion" value="<?php echo $packVersion; ?>" />
				
		</div>
		<footer> 
			<!--input type="button" name="cancel" value="Cancel" class="myButton" id="cancelDep"-->  
			<input type="submit" value="Deprecate" class="myButton" id="submitDep" name="submitDep" />   		
	   </footer>
   </form>
</div>
<!-- Pop up deprecation end -->

</body>
</html>
