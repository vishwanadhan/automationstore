<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();

$packObj = new SnapPack();

if(!empty($_SESSION['uid']))
	{
	$userType = $packObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
    }
else
{
	$userType = '2';
	
}

$_SESSION['backPage']="snappack-listNC.shtml";

$searchText = (isset($_POST['search']) ? $_POST['search'] : null);

// site head js include here 
include('includes/head.php');
?>   
<title>Automation Store Pack List </title>
<meta name="description" content="Displays list of workflow packs uploaded by users on the 
            dev site of Automation Store site.">

<meta name="keywords" content="Automation, store, packs, list, workflow">


<script type="text/javascript">

    function fetchData(id, version, uuid)
    {

        $('#packVal').val(id);
        $('#packVersion').val(version);
        $('#packUuid').val(uuid);
        $("#packForm").attr("action", "snap-detail.shtml");
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

    function setSnapCaution(id,uuid)
    {
        var checkValue = $('.enableCaution'+id).is(":checked");
      
        $('#cautionLoader'+id).html('<img src="images/loading_icon.gif" />');
        $.ajax({
            url:"pass.php",
            data:{action:"setSnapCaution", type:"NONE", packId: id, packUuid:uuid, status:checkValue},
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
    <div id="nav-under-bg">
        <!-- -->
    </div>
    <div id="body_content">
	<?php if(isset($_SESSION['SESS_MSG'])){ ?>
			<div style="margin-top:20px;">
			  <?php 	  
				echo $_SESSION['SESS_MSG']; 
				unset($_SESSION['SESS_MSG']); 
			  ?>
			</div>
	<?php } ?>
	   <section> 
		<div class="simpleTabs">
		<ul class="tabs1 tabswfpck" >
			<li><a href="snap-list.shtml" ><img src="images/netapp-certified-icon.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px;">NetApp-Supported Packs</a></li>
 			 <li><a href="#tab1" class="selected"><img src="images/non-netapp-certified.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px; margin-left:14px;">Community-Generated Packs</a></li>	
		</ul>
		 <section class="uppck-btn">
			<?php 
        	if(!empty($_SESSION['uid']) ){?> 	
            <input type="button" class="myButton" onclick="javascript:window.location='uploadsnapEula.shtml';" value="Upload"><?php } ?>
				<div><a href="how-to-create-a-snap-pack.shtml" >How to create a <?php echo CONSTANT_LOWERCASE;?> pack?</a></div>
			</section>	
       
	<div class="tab_container">
					
		<div id="tab1" class="tab_content mbot">
		<br>
		<section class="non-certfy-msg"><b>WARNING :</b>
		The workflow packs that you are accessing were developed by third parties unaffiliated with NetApp (Third Party Content). NetApp does not provide any support for Third Party Content. Questions/issues/concerns pertaining to Third Party Content will need to be provided to the author of Third Party Content directly or the NetApp community. The Third Party Content is being provided "AS IS" and without warranty of any kind.
		</section>
		<br>
						<?php 
				if($userType == '1' ){
				?> 			
				<section class="workflow-admin-div comman-link">
				<?php } else  {?>
					<section class="workflow-div comman-link">
					
				<?php  } ?>
		  <table cellpadding="0" cellspacing="0" border="0">
				<thead><tr>
					<?php 		
				if($userType == '1' ){
				?> 
					<td>Caution</td>
					<?php } ?>
					<td>Type</td>
					<td><?php echo orderBy("snappack-listNC.php?searchtxt=$searchtxt","packName","Pack Name")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
					<td><?php echo orderBy("snappack-listNC.php?searchtxt=$searchtxt","version","Latest Version")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
					<td style="width:186px"><?php echo orderBy("snappack-listNC.php?searchtxt=$searchtxt","minWfaVersion","Min Snap Center Version")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
					<td><?php echo orderBy("snappack-listNC.php?searchtxt=$searchtxt","certifiedBy","Author")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>              
					<td><?php echo orderBy("snappack-listNC.php?searchtxt=$searchtxt","packDate","Released On")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderdown.png" border="0" />'; } ?></td>
					<td></td>
					<td></td>
					<?php 		
				if($userType == '1' ){
				?> 
					<td></td>
					<td></td>
					<?php } ?>
				  </tr>
					</thead>
					 <?php
					echo $packObj->packFullInformation("NONE");
					?> 
				</tr>
			</table>
			
		</section>
		</div>
	</div>
</section>
</div>
<?php
// site head js include here 
include('includes/footer.php');
?>    
</body>
</html>
