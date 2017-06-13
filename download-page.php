<?php session_start();
	ob_start();

	require_once('config/configure.php');
	require_once('includes/function/autoload.php');

 $loginObj = new Login();
//$loginObj->checkSession();

$pageName = getPageName();
$ocipackobj   = new OciPack();
$entityObj = new Entity();
$snapentityObj = new SnapEntity();
$searchText = (isset($_POST['search']) ? $_POST['search'] : null);
$searchText= htmlspecialchars($searchText, ENT_QUOTES, 'UTF-8');
$typePack= $_REQUEST["packType"];
$nameReport= $_REQUEST["reportName"];
$versionReport= $_REQUEST["reportVersion"];
$namePack= $_REQUEST["packName"];
$versionPack= $_REQUEST["packVersion"];
$uuidPack= $_GET["packUuid"];

  // site head js include here 
 include('includes/head.php'); ?>   
	
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>	
<script type="text/javascript">
 
	$(document).ready(function(){
		
		var packId='<?php  echo $_REQUEST["packId"];?>';
		var _href = $("#packAction").attr("href");		
		//$("#packAction").attr("href", _href + '&id='+packId);
		$("td:empty").html("&nbsp;");
		
		document.getElementById('fcsd').focus();				
		$("ul#content-ul li a.current").trigger( "click" );		
		
	});	
	
	
	function contentFilter(obj, packId, entityType){		
	//	alert(packId);
		$('#contentData').html('<tr><td colSpan="2"><span><img src="images/loading_icon.gif"></span></td></tr>');
		$.post('pass.php?action=contentFilter',{packId: packId, entityType: entityType} , function(data) {	
			//alert(data);		
			$('#contentData').html();
			$('#contentData').html(data);	
			document.getElementById('captcha-form').focus();
			
		});	
		
		$(obj).addClass('current');
		$('ul#content-ul li a').removeClass('current');
		 $(obj).addClass('current');
	//	$(obj).parents('li').addClass('current');		

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


      $('#uploadpc-btn').click(function(){

       var tags = $('#packsTag').val();
       var version = $('#version').val();
       var uuid = $('#uuid').val();


          $.ajax({
              url:"pass.php",
              type:"post",
              data:{action:"updateTag", type:"tag", tags:tags, version:version, uuid:uuid},
              success:function(response)
              {
                window.location.href="pack-detail.shtml?packUuid="+uuid+"&packVersion="+version;
              }
          });
        
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
                window.location.href="download-page.shtml?packName="+packName.replace(/ /g,"!")+"&packVersion="+version+"&packType=ocipack";
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
 include('includes/header.php'); ?>
<div id="nav-under-bg">
  <!-- -->
</div>
<div id="body_content">
<?php
//print_r($_POST);
?>
<section class="back-link">
<?php
if(isset($_POST['accept']) && $_POST['accept'] != ''){
$backUrl = $_POST['accept'];
}
else{
// $backUrl = "/onCommandInsight.shtml";
$backUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/onCommandInsight.shtml";
}
?>
    	<?php if($_GET['packType'] !=='snapcenter'){
    	?><a href="<?php echo $backUrl ; ?>"> < Back</a>	
    	<?php }?>
    	
		</section>

     <form name="entityForm" id="entityForm" method="post" action="">
				<?php 
				
				$typePack = (isset($typePack) ? $typePack : null);
				$packName = (isset($namePack) ? $namePack : null);
				$packVersion = (isset($versionPack) ? $versionPack : null);
				$reportVersion = (isset($versionReport) ? $versionReport : null);
				$reportName = (isset($nameReport) ? $nameReport : null);
				$packUuid = (isset($uuidPack) ? $uuidPack : null);
				
				if($typePack=='report'){
										 if($reportName == null && reportVersion == null ){ 
															 header( 'Location: reports.php' ) ;
															} else { 
																		echo  @$entityObj->reportInformation($reportName,$reportVersion, $downloadPg='1');
																	}
										}
										
				else if($typePack=='performance'){
													if($packName == null && packVersion == null){
																		header( 'Location: performance.php' ) ;
																		}else {
																				echo  @$entityObj->performanceInformation($packName, $packVersion, $downloadPg='1');
																		}
											 	 }
				
				else if($typePack=='workflow') {
							if($packUuid == null || $packVersion == null){ 	
												header( 'Location: pack-list.php' );
												
											  }else{ 
										
													echo  @$entityObj->entityVersionInformation( $packUuid, $packVersion, $downloadPg='1');	
												    }			
					}
					else if($typePack=='snapcenter') {
							if($packUuid == null || $packVersion == null){ 	
												header( 'Location: snap-list.php' );
												
											  }else{ 
										
													echo  @$snapentityObj->entityVersionInformation( $packUuid, $packVersion, $downloadPg='1');	
												    }			
					}
				else if ($typePack == 'ocipack') {
                if ($packName == null && packVersion == null) {
                    header('Location: onCommandInsight.php');
                } else {
                    echo $ocipackobj->ociPackInformation($packName, $packVersion, $downloadPg = '1');
                }
            }
				else {
				
				header( 'Location: home.php' );
				}
								
			
				?> 	
	</form>
</div>
<p id="fcsd"></p>
<?php
  // site head js include here 
  
 /** Validate captcha */
if (!empty($_REQUEST['captcha'])) {
    if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
        $captcha_message = "Invalid captcha";
        $style = "background-color: #FF606C";

	$_SESSION['captcha'] = msgSuccessFail("fail","Error: ".$captcha_message);	
	
	 
	/* echo <<<HTML
<div id="msgbox" style="position:relative;">{$_SESSION['captcha']}</div>
HTML; */

    } 
    //  $request_captcha = htmlspecialchars($_REQUEST['captcha']);
    $request_captcha = $_REQUEST['captcha'];
    
    unset($_SESSION['captcha']);
}

if(!empty($_SESSION['captcha']))
{
	echo"<script>window.location.hash='#msgbox'</script>"; 
}else{
	echo"<script>window.location.hash='#downloadEula'</script>"; 
}

echo"<script>window.location.hash='#captchaButtons'</script>"; 
  
 include('includes/footer.php'); ?>   
</body>
</html>
