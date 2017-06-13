<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();

$packObj = new Pack();

if(!empty($_SESSION['uid']))
	{
	$userType = $packObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
    }
else
{
	$userType = '2';
	
}
//$userType = '1';/*testing */


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
        $("#packForm").attr("action", "pack-detail.shtml");
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

    function setCaution(id,uuid)
    {
        var checkValue = $('.enableCaution'+id).is(":checked");
      
        $('#cautionLoader'+id).html('<img src="images/loading_icon.gif" />');
        /* alert("checked value is: "+checkValue);
        return false; */
        $.ajax({
            url:"pass.php",
            data:{action:"setCaution", type:"", packId: id, packUuid:uuid, status:checkValue},
            type:"POST",
            success:function(response)
            {
			  // alert(response);
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
		<ul class="tabs tabswfpck" >
			<li><a href="#tab1" ><img src="images/netapp-certified-icon.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px;">NetApp-Supported Packs</a></li>
 			 <li><a href="#tab2"><img src="images/non-netapp-certified.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px; margin-left:14px;">Community-generated Packs</a></li>	
		</ul>
		 <section class="uppck-btn">
			<?php 
            //$userType = '1';          
            if(!empty($_SESSION['uid']) ){?> 	
            <input type="button" class="myButton" onclick="javascript:window.location='uploadWfaEula.shtml';" value="Upload"><?php } ?>
				<div><a href="how-to-create-a-pack.shtml" target="_blank">How to create a workflow pack?</a></div>
			</section>	
       
	<div class="tab_container">
			<div id="tab1" class="tab_content mbot">
							<?php 
					//$userType = '1';			
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
						<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","","Type")?></td>
						<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","packName","Pack Name")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
						<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","version","Latest Version")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
						<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","minWfaVersion","Min WFA Version")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
						<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","certifiedBy","Author")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>              
						<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","packDate","Released On")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderdown.png" border="0" />'; } ?></td>
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
						echo $packObj->packFullInformation("NETAPP");
						?> 
					</tr>
				</table>				
			</section>
			</div>		
		<div id="tab2" class="tab_content mbot">
		<br>
		<section class="non-certfy-msg"><b>WARNING :</b>
		The workflow packets that you are accessing were developed by third parties unaffiliated with NetApp (Third Party Content). NetApp does not provide any support for Third Party Content. Questions/issues/concerns pertaining to Third Party Content will need to be provided to the author of Third Party Content directly or the NetApp community. The Third Party Content is being provided "AS IS" and without warranty of any kind.
		</section>
		<br>
						<?php 
				//$userType = '1';			
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
					<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","","Type")?></td>
					<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","packName","Pack Name")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
					<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","version","Latest Version")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
					<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","minWfaVersion","Min WFA Version")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>
					<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","certifiedBy","Author")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderup.png" border="0" />'; } ?></td>              
					<td><?php echo orderBy("pack-list.php?searchtxt=$searchtxt","packDate","Released On")?><?php if(!isset($_GET['order'])) {echo '<a href="#"><img src="images/orderdown.png" border="0" />'; } ?></td>
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
