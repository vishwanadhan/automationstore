<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();

//function to retrieve the current page name.
$pageName = getPageName();


$ocipackobj   = new OciPack();

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
function setDeprecationFlagOci(id,version) { 
        var checkValue = $('.deprecationFlag'+id).is(":checked");
      
        $('#cautionLoader'+id).html('<img src="images/loading_icon.gif" />');
        /* alert("checked value is: "+checkValue);
        return false; */
        $.ajax({
            url:"pass.php", 
            data:{action:"setDeprecationFlagOci", packtype:"OCI", packId: id, packVesion:version, status:checkValue},
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
<script type="text/javascript">
	/* Start block -> Dev ASG */
		//<?php if(isset($packIds)){ ?>
		/* var url = "pack-detail.php?packId=<?php echo $packIds; ?>&packUuid=<?php echo $packUuid; ?>&packVersion=<?php echo $packVersion; ?>";   		
			$(location).attr('href',url); */
		//	window.location.href = url;
	//	<?php } ?>
	/* End block -> Dev ASG */
	
    $(document).ready(function() {
		
        $("#packVersion").change(function() {
            this.form.submit();
        });

        $("td:empty").html("&nbsp;");
		
		$("ul#content-ul li a.current").trigger( "click" );

		$('#tags input').on('focusout',function(){ 
            var valueTag = '';
            var txt= this.value.replace(/[^a-zA-Z0-9\+\-\.\#]/g,''); // allowed characters
			if(txt.length >20)
			{
				$('#wfaContactTagError').html("Length of 'Tag' cannot be greater than 20 characters");
					this.value="";
					return false;
			}
           if(txt.charAt(0) == '#')
		{
			txt= txt.substring(1);
			$('#wfaContactTagError').html("");
		}
		else
		{
			if(txt.length ==0)
				$('#wfaContactTagError').html("");
			else{
				$('#wfaContactTagError').html("Tag must precede a pound sign ( '#' )");
				this.value="";
				return false;
			}
		}
            if(txt) {
              $(this).before('<span class="tag">'+ txt.toLowerCase() +'<span class="delete-tag" title="remove this tag"></span></span>');

              
            }

            $('span.tag').each(function(){
                valueTag = valueTag + $(this).text()+",";
            });
            $('#packsTag').val(valueTag);
            this.value="";
          }).on('keyup',function( e ){
    // if: comma,enter (delimit more keyCodes with | pipe)
            if(/(32)/.test(e.which)) $(this).focusout(); 

          });
  
    $('#tags').on('click','.tag',function(){
          $(this).remove(); 
          var valueTag = '';
          $('span.tag').each(function(){
                valueTag = valueTag + $(this).text()+",";
            });
            $('#packsTag').val(valueTag);
      });


      $('#uploadpc-btn-oci').click(function(){

       var tags = $('#packsTag').val();
       var version = $('#version').val();
       var packName = $('#packName').val();
	   packName=packName.replace(/ /g,"!");


       
		  $('#loading').html('<img src="images/loading_icon.gif" />');
          $.ajax({
              url:"pass.php",
              type:"post",
              data:{action:"updateTag", type:"ocitag", tags:tags, version:version, name:packName},
              success:function(response)
              {
				//alert(response);
                window.location.href="ocipack-detail.shtml?packName="+packName+"&packVersion="+version;
              }
          });
        
		
		
      });
	  
	
    });
	
	</script>
	<style>
#tags{
  float:left;
  border:1px solid #ccc;
  padding:5px;
  font-family:Arial;
}
#tags span.tag{
  cursor:pointer;
  display:block;
  float:left;
 
  margin: 6px 3px 0 3px;
  color: #566e76;
  background: #e4edf4;
  border: 1px solid #c0d4db;
  padding: .4em .5em;
}
#tags span.tag:hover{
  opacity:0.7;
}

#tags input{
  background:#eee;
  border:0;
  margin:4px;
  padding:7px;
  width:auto;
}

.delete-tag
{
    background-image: url("images/sprites.png?v=c4222387135a");
    
}

.delete-tag {
    width: 14px;
    height: 14px;
    vertical-align: middle;
    display: inline-block;
    background-position: -40px -319px;
    cursor: pointer;
    margin-left: 3px;
    margin-top: 1px;
    margin-bottom: -1px;
    float:right;
}

.delete-tag:hover, .delete-tag-active {
    background-position: -40px -340px;
}

</style>


</head>


<body>
    <?php
    //site header include here  
    include('includes/header.php');
    ?>
	<?php
	/***** Grievance Reporting for Performance Start *****/
	if($_POST['submitFlagOci'] == 'Flag'){
		$responseReport =  @$ocipackobj->addGrievanceOci($_POST); 
		$_POST = '';
	}
	/***** Grievance Reporting for Performance End *****/
	
	/***** Star rating for Performance Start *****/
	if(isset($_POST['submit_rating'])){
		//print_r($_POST); exit;
		$responseRating =  @$ocipackobj->addStarRating($_POST); 
		$_POST = '';
	}
	/***** Star rating for Performance End *****/
	/*** Deprecate pack for Reports Start ***/
	if(isset($_POST['submitDep'])){		
/* 		echo "<pre>";
		print_r($_POST); 
		exit; */
		$responseDeprecate =  @$ocipackobj->addDeprecateOci($_POST);  
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
               header('Location: onCommandInsight.php');
            } else {
                echo $ocipackobj->ociPackInformation($packName,$packVersion,"");
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
  <form name="grievanceReport" id="grievanceReport" action="" method="post"   onSubmit="return submitFlagForm();">
  <div class="modal-body">
  <p>Select reasons(s) for flagging:</p>
    <p><input name="checkTrademark" id="checkTrademark" type="checkbox" value="true" align="absmiddle" class="pdright">Trademark or copyright infringement</p>
    <p><input name="checkInfringement" id="checkInfringement" type="checkbox" value="true" align="absmiddle" class="pdright">Infringement pack details and content</p>
    <p>Comment <span class="red">*</span><textarea name="flagComment" class="inputbox" style="resize:none;" placeholder="Leave Comment"></textarea>
	<div class="popup-error" id="comment_error"></div></p>
	
   <input type="hidden" name="detailPageUrl" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" />
    <input type="hidden" name="packType" id="packType" value="OCI" />    
    <input type="hidden" name="packVersion" id="packVersion" value="<?php echo $packVersion; ?>" />
    <input type="hidden" name="packName" id="packName" value="<?php echo $packnameget; ?>" />
    
  </div>
   <footer> 
  		<!--input type="button" name="cancel" value="Cancel" class="myButton" id="cancelFlag"--> 
        <input type="submit" value="Flag" class="myButton" id="submitFlagOci" name="submitFlagOci"/>   		
   </footer>  
    </form>
</div>
<!-- pop end -->
<!-- Pop up deprecation -->
<div id="popup2" class="modal-box">
	<header> <a href="javascript:void(0)" class="js-modal-close close"><img src="images/close-icon.png" border="0" /></a>
		<h2>Deprecate Pack </h2>
	</header>
	<form name="packDeprecation" id="packDeprecation" action="" method="post">
		<div class="modal-body">
		<p>Your comments will be stored and others can view it.</p>
			
		<p>Comment <span class="red">*</span><textarea name="depComment" class="inputbox" style="resize:none;" placeholder="Leave reason for requesting to deprecate the pack" autofocus="autofocus"></textarea>
		<div class="popup-error" id="depComment_error"></div></p>
		
		<input type="hidden" name="detailPageUrl" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" />
		<input type="hidden" name="depPackid" id="depPackid" value="<?php echo $packid = $ocipackobj->getPackIdOCI($packnameget, $packVersion); ?>" />
		<input type="hidden" name="depPackType" id="depPackType" value="OCI" />
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
