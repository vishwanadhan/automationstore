<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();

//function to retrieve the current page name.
$pageName = getPageName();

$entityObj = new SnapEntity();
$packobj = new SnapPack();

$searchText = (isset($_POST['search']) ? $_POST['search'] : null);

// site head js include here 
include('includes/head.php');
?>   
<SCRIPT src="js/support.js"></SCRIPT>
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>	
<script type="text/javascript" src="js/uploadValidation.js"></script>	
<script type="text/javascript">
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


      $('#uploadpc-btn').click(function(){

       var tags = $('#packsTag').val();
       var version = $('#version').val();
       var uuid = $('#uuid').val();


       if(tags == '')
       {
         alert("Please enter a new tag to update");
         return false;
       }
       else{

          $.ajax({
              url:"pass.php",
              type:"post",
              data:{action:"updateTag", type:"snaptag", tags:tags, version:version, uuid:uuid},
              success:function(response)
              {
                window.location.href="snap-detail.shtml?packUuid="+uuid+"&packVersion="+version;
              }
          });
        }
      });
    });

	function contentFilter(obj, packId, entityType){		
		$('#contentData').html('<tr><td colSpan="2"><span><img src="images/loading_icon.gif"></span></td></tr>');
		$.post('pass.php?action=snapcontentFilter',{packId: packId, entityType: entityType} , function(data) {	
			$('#contentData').html();
			$('#contentData').html(data);													  
		});	
		
		$(obj).addClass('current');
		$('ul#content-ul li a').removeClass('current');
		 $(obj).addClass('current');

	}
</script>
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
        $.ajax({
            url:"pass.php",
            data:{action:"setDeprecationFlagsnap", packtype:"Snapcenter", packId: id, packVesion:version, status:checkValue},
            type:"POST",
            success:function(response)
            {
               $('#cautionLoader'+id).html('');              
               location.reload();
                return true;
            }           
        });       
    }
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
	/* Grievance Reporting for Snap Start */
	if($_POST['submitFlag'] == 'Flag'){
		$responseReport =  @$packobj->addGrievance($_POST); 
		$_POST = '';
	}
	/* Grievance Reporting for Snap End */
	
	/* Star rating for Snap Start */
	if(isset($_POST['submit_rating'])){
		$responseRating =  @$packobj->addStarRating($_POST); 
		$_POST = '';
	}
	/* Star rating for Snap End */
	/*** Deprecate pack for Snap Start ***/
	if(isset($_POST['submitDep'])){
		$responseDeprecate =  @$packobj->addDeprecate($_POST); 
		$_POST = '';
	}
	/*** Deprecate pack for Snap End ***/
	?>
     <div id="nav-under-bg"></div> 
    
    <div id="body_content">
					<?php  
                    echo $_SESSION['SESS_MSG'];			
                    unset($_SESSION['SESS_MSG']); 
                    ?>
        <form name="entityForm" id="entityForm" method="post" action="">

            <?php
            $packUuid = (isset($_REQUEST["packUuid"]) ? $_REQUEST["packUuid"] : null);
            $packVersion = (isset($_REQUEST["packVersion"]) ? $_REQUEST["packVersion"] : null);
            if ($packUuid == null || $packVersion == null) {
                header('Location: snap-list.php');
            } else {
                echo @$entityObj->entityVersionInformation($packUuid, $packVersion," ");
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
  <header> <a href="javascript:void(0)" class="js-modal-close close"><img src="images/close-icon.png" border="0" /></a>
    <h2>Flag as inappropriate</h2>
  </header>
  <form name="grievanceReport" id="grievanceReport" action="" method="post">
  <div class="modal-body">
  <p>Select reasons(s) for flagging:</p>
    <p><input name="checkTrademark" id="checkTrademark" type="checkbox" value="true" align="absmiddle" class="pdright">Trademark or copyright infringement</p>
    <p><input name="checkInfringement" id="checkInfringement" type="checkbox" value="true" align="absmiddle" class="pdright">Infringement pack details and content</p>
    <p>Comment <span class="red">*</span><textarea name="flagComment" class="inputbox" style="resize:none;" placeholder="Leave Comment"></textarea>
	</p><div class="popup-error" id="comment_error"></div>
	
   <input type="hidden" name="detailPageUrl" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" />
    <input type="hidden" name="packType" id="packType" value="Snapcenter" />
    <input type="hidden" name="packUuid" id="packUuid" value="<?php echo $packUuid; ?>" />
    <input type="hidden" name="packVersion" id="packVersion" value="<?php echo $packVersion; ?>" />
    <input type="hidden" name="packName" id="packName" value="<?php echo ($packName = $entityObj->getPackName($packUuid, $packVersion)); ?>" />
    <input type="hidden" name="packContactEmail" id="packContactEmail" class="inputbox" value="<?php echo ($packContactEmail = $entityObj->getPackContactEmail($packUuid, $packVersion)); ?>" />
	<input type="hidden" name="packOwnerEmail" id="packOwnerEmail" class="inputbox" value="<?php echo ($packOwnerEmail = $entityObj->getPackOwnerEmail($packUuid, $packVersion)); ?>" />
  </div>
   <footer> 
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
	<form name="packDeprecation" id="packDeprecation" action="" method="post" onSubmit="return submitDepForm();">
		<div class="modal-body">
		<p>Your comments will be stored and others can view it.</p>
			
		<p>Comment <span class="red">*</span><textarea name="depComment" class="inputbox" style="resize:none;" placeholder="Leave reason for requesting to deprecate the pack" autofocus="autofocus"></textarea>
		<div class="popup-error" id="depComment_error"></div></p>
		
		<input type="hidden" name="detailPageUrl" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" />
		<input type="hidden" name="depPackid" id="depPackid" value="<?php echo $packid = $entityObj->getPackId($packUuid, $packVersion); ?>" />
		<input type="hidden" name="depPackType" id="depPackType" value="Snapcenter" />
		<input type="hidden" name="depPackVersion" id="depPackVersion" value="<?php echo $packVersion; ?>" />
				
		</div>
		<footer> 
			<input type="submit" value="Deprecate" class="myButton" id="submitDep" name="submitDep" />   		
	   </footer>
   </form>
</div>
<!-- Pop up deprecation end -->
</body>
</html>
