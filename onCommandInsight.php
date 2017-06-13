<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');

//print_r($_REQUEST);
$loginObj = new Login();

$pageName = getPageName();

$ociPackObj = new OciPack();

if (!empty($_SESSION['uid'])) {
    $userType = $ociPackObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
} else {
    $userType = '2';
}
unset($_SESSION['ociPostData']); //aj     
//$userType = '1';    /*testing */

$searchText = (isset($_REQUEST['search']) ? $_REQUEST['search'] : null);


// site head js include here 
include('includes/head.php');
?>   
<title>Automation Store OCI List </title>
<meta name="description" content="Displays list of workflow packs uploaded by users on the 
      dev site of Automation Store site.">

<meta name="keywords" content="Automation, store, packs, list, workflow">

<script type="text/javascript" src="js/jquery.tablesorter.js"></script>

<script type="text/javascript">
        $(document).ready(function() {
				
			 $("#mytable").tablesorter({dateFormat: "d M, Y"});
			
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

<!--delete packs author:hc -->

<script type="text/javascript">
   $(document).ready(function() {
<?php
if (empty($_REQUEST)) { 
    ?>
			 
            $("#ociTypecertify li a:first").addClass('selected');
            $("#oci_type li a:first").addClass('selected');
    <?php
}
?>      

        if ($("#oci_type li a.selected")) {

            var type = $("#oci_type li a.selected").attr('id');
            var certifyBy = $("#ociTypecertify li a.selected").attr('href');
         // alert('ghj'+certifyBy);
          $("#ocitype").val(type);
            $("#certifiedBy").val(certifyBy);
           //$('#ociFrm').submit();// getOciFullData ('GetOciPackList',type, certifyBy); 
            // $('#ociTypecertify li').find('a[href="NetApp"]').addClass('selected');
        }

        $("#oci_type li a").click(function() {
               // alert();
               
            var type = $(this).attr('id');
            var certifyBy = $("#ociTypecertify li a.selected").attr('href');
            $("#ocitype").val(type);
            $("#certifiedBy").val(certifyBy);
            $('#ociFrm').submit();

        });
        $("#ociTypecertify li a").click(function(ev) { 
            var str1 = $(this).attr('href');
			if (str1.indexOf('http') == -1)   
				{	
					ev.preventDefault();
					var type = $("#oci_type li a.selected").attr('id');
					var certifyBy = $(this).attr('href');
					//alert(type+certifyBy);
					$("#ocitype").val(type);
					$("#certifiedBy").val(certifyBy);
					$('#ociFrm').submit();
				}
        });

        /* Tab content on type click*/
        $('#oci_type li a').click(function(ev) {

            ev.preventDefault();
			$('table tbody').html('<tr><td colspan="9" ><b>Loading...</b></td></tr>');
            var el = $(this),
                    ociId = this.id;

            var certifytype = $("#ociTypecertify li a.selected").attr('href');
            if (el.hasClass('selected'))
                return;

            $('#usual1 ul li a').removeClass('selected');

            el.addClass('selected');
            $("#ocitype").val(ociId);
	$("#certifiedBy").val(certifytype);
		$('#ociFrm').submit();		
          //  getOciFullData ('GetOciPackList',ociId, certifytype); 
            // $('#tab_container #'+ target).fadeIn('fast');
        });



});

</script> <script type="text/javascript">

                       function clicked() {

                           if (confirm('Are you sure you want to delete these these OCIs ? This will permanently delete these OCIs !!')) {


                               document.getElementById("DeleteForm").submit();
                           } else {
                               return false;
                           }
                       }
</script>


<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>
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
    <?php if (isset($_SESSION['SESS_MSG'])) { ?>
            <div style="margin-top:20px;">
    <?php
    echo $_SESSION['SESS_MSG'];
    unset($_SESSION['SESS_MSG']);
    ?>
            </div>
            <?php } ?>


        <section class="uppck-btn">
        <?php
               
        if (!empty($_SESSION['uid'])) {
            ?> 
				<input type="button" class="myButton" value="Upload" onclick="javascript:window.location = 'upload_oci.php';">
                <div><a href="http://community.netapp.com/t5/OnCommand-Storage-Management-Software-Articles-and-Resources/tkb-p/oncommand-storage-management-software-articles-and-resources/label-name/insight?labels=insight" target="_blank">How to create a OCI content?</a></div>
                <?php
            }
            ?>
        </section>	

        <div id="usual1" class="simpleTabs usual">              

            <ul id="oci_type"> 
            <?php
            $typeArr = $ociPackObj->getAllociType();
            $i = 1;
            foreach ($typeArr as $type) {?>
               
               <li><a  <?php if($type->typeId== $_REQUEST['type']) echo "class= 'selected'" ; ?> href="#tab<?php echo $type->typeId?>" id="<?php echo  $type->typeId; ?>" ><?php echo  ucfirst($type->typeName); ?></a></li> 
             <?php
             $i++;
            }
            ?>
            </ul>                    
        </div> 

        <form action="onCommandInsight.shtml" method="post" name="ociFrm" id="ociFrm">
            <p><input type="hidden" name="certifiedBy" id="certifiedBy" value="" /></p>
            <p><input type="hidden" name="type"id="ocitype" value="" /></p>
            <p></p>
        </form>
        <ul class="tabs tabswfpck" id="ociTypecertify">

            <li><a   href="NETAPP"  <?php if (isset($_REQUEST['certifiedBy']) && $_REQUEST['certifiedBy'] == 'NETAPP') {
                    echo "class='selected'";
                } ?> onclick=""><img src="images/netapp-certified-icon.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px;">NetApp-Featured</a></li>
            <li><a   href="NETAPPG"  <?php if (isset($_REQUEST['certifiedBy']) && $_REQUEST['certifiedBy'] == 'NETAPPG') {
                    echo "class='selected'";
                } ?> onclick=""><img src="images/non-netapp-certified.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px;">NetApp-Generated</a></li>
            <li><a href="NONE" <?php if (isset($_REQUEST['certifiedBy']) && $_REQUEST['certifiedBy'] == 'NONE') {
                    echo "class='selected'";
                } ?> onclick=""><img src="images/non-netapp-certified.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px; margin-left:14px;">Community-Generated</a></li>

            <li><a href="http://community.netapp.com/t5/forums/filteredbylabelpage/board-id/oncommand-storage-management-software-discussions/label-name/insight
                   " target="_blank">Community Discussions</a></li>					
        </ul> 

        <div class="tab_container">
            <?php
           
              //  $filter = array();
               $filter['certifiedBy'] = isset($_REQUEST['certifiedBy']) ? $_REQUEST['certifiedBy'] : 'NETAPP';
	       $filter['ociTypeId'] =isset($_REQUEST['type']) ? $_REQUEST['type'] : '1';
                ?>

                <div id="tab<?php echo $type->typeId; ?>">
					<?php 
					if ((isset($_REQUEST['certifiedBy']) && $_REQUEST['certifiedBy'] == 'NETAPP')|| empty($_REQUEST)) {
					?>
                    <section class="non-certfy-msg"><b>WARNING :</b>
                        The content that you are accessing was developed by NetApp. However NetApp does not provide any support for these Contents. Questions/issues/concerns pertaining to these Contents will need to be provided to the author directly or the NetApp community. The NetApp-Featured Contents is being provided "AS IS" and without warranty of any kind. 
                    </section>
					<?php
					}
					?>
					<?php 
					if (isset($_REQUEST['certifiedBy']) && $_REQUEST['certifiedBy'] == 'NETAPPG') {
					?>
                    <section class="non-certfy-msg"><b>WARNING :</b> 
                        The content that you are accessing was developed by NetApp. However NetApp does not provide any support for these Contents. Questions/issues/concerns pertaining to these Contents will need to be provided to the author directly or the NetApp community. The NetApp-Generated Content is being provided "AS IS" and without warranty of any kind. 
                    </section>
					<?php
					}
					?>
					<?php 
					if (isset($_REQUEST['certifiedBy']) && $_REQUEST['certifiedBy'] == 'NONE') {
					?>
					<section class="non-certfy-msg"><b>WARNING :</b>
					The content that you are accessing was developed by third parties unaffiliated with NetApp (Third Party Content). NetApp does not provide any support for Third Party Content. Questions/issues/concerns pertaining to Third Party Content will need to be provided to the author of Third Party Content directly or the NetApp community. The Third Party Content is being provided "AS IS" and without warranty of any kind.
					</section>
                    
					<?php
					}
					?>
                    <?php
                    //$userType = '1';			
                    if ($userType == '1') {
                        ?> 			
                        <section class="oci-admin-div comman-link">
    <?php } else { ?>
                            <section class="oci-div comman-link">

    <?php } ?>


                            <table cellpadding="0" cellspacing="0" border="0" id="mytable" class="tablesorter"  data-sortlist="[[0,0]]">
                                <thead><tr>

                                        <td>Type</td>
                                        <th><span>Name</span></th>
                                        <th><span>Latest Version</span></th>
                                        <th><span>OCI Version</span></th>

                                        <th><span>Released On</span></th>

                                        <td>DL</td>
                                        <td>Preview</td>
                                        <?php
									if ($userType == '1') {
										?> 
										<td></td>

									<?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                   
                                   echo $ociPackObj->ociFullInformation($filter);
                                    ?> 
                                </tbody>
                            </table>	 	

							
                        </section>                        

                </div> 

                <?php
               // $i++;
           // }
            ?></div>



    </section>

</div>													
<script type="text/javascript">
 //          $("#usual1 ul").idTabs();

</script>

<?php
// site head js include here 
include('includes/footer.php');
?>    
</body>
</html>
