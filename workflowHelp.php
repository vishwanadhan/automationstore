<?php
@session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();

$packObj = new Pack();

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


        $('a.packAction').live('click', function(e) {
            Shadowbox.open(this);
            //Stops loading link
            e.preventDefault();
        });



    });
	
	function goBack(url)
			{
				window.parent.location.href = url;
				return false;
			}

</script>

<style>
/* LIST #4 */
	#list4 { font-family:arial; font-size:15px; }
	#list4 ul { list-style: none; margin:0px; }
	#list4 ul li { display:block; }
	#list4 ul li a:link, #list4 ul li a:visited {  display:block; text-decoration:none; color:#000000; background-color:#FFFFFF; line-height:30px;
  border-bottom-style:solid; border-bottom-width:1px; border-bottom-color:#CCCCCC; padding-left:10px; cursor:pointer; }
	#list4 ul li a:hover { text-decoration:underline !important; color:#2E64FE;}
	#list4 ul li a strong { margin-right:10px; }
	
	
</style>

</head>


<body>
    <?php
    //site header include here  
    include('includes/header-landing.php');
	$page = basename($_SERVER['HTTP_REFERER']);
    ?>
    <div id="nav-under-bg">
        <!-- -->
    </div>
    <div id="body_content">
<section class="back-link">
    	<a href="javascript:;goBack('<?php echo $page;?>');"> &lt; Back</a>
		</section>
        <form name="packForm" id="packForm" method="post" action="">

            <input type="hidden" name="packId" id="packVal" value="" /> 
            <input type="hidden" name="packVersion" id="packVersion" value="">   
            <input type="hidden" name="packUuid" id="packUuid" value="">  

            <div style="margin-top:20px;">
                <? 
				if(isset($_SESSION['SESS_MSG']) && !empty($_SESSION['SESS_MSG'])){
					echo $_SESSION['SESS_MSG']; 
					unset($_SESSION['SESS_MSG']);
				}
                
                ?>
            </div>
		<div class="main-body-div4" id="mainDiv">	
		<section class="workflow-div">
		
		<?php
			$packId = base64_decode($_GET['packId']);
			echo  $packObj->workflowHelp($_GET['packUuid'],$_GET['packVersion']);					
		?> 	
		</section>		
		</div>	
	</div>


        </form>
<!--
        <div id="showAll" class="showAll">
            <div id="resultDiv">Packs containing '<span id="searchName"></span>' <a id="showAll" href="pack-list.shtml">Show all packs</a>
            </div>
            <div id="searchDiv" class="searchDiv">

                <div id="searchResults"></div>
            </div>	
        </div>	

    </div> -->

<?php

// site head js include here 
include('includes/footer.php');
?>   
</body>
</html>
