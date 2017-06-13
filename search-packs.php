<?php session_start();
	ob_start();

	require_once('config/configure.php');
	require_once('includes/function/autoload.php');


 $loginObj = new Login();

$pageName = getPageName();


$solrObj = new SolrResponse();

if(isset($_GET['search']))
{
	$searchText = "#".$_GET['search'];

}
else
{
	$searchText = (isset($_POST['search']) && ($_POST['search']!='Enter Search Text...')? $_POST['search'] : '');
}



//$searchText = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($searchText))))));

  // site head js include here 
 include('includes/head.php'); ?>
 
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>	
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>  

	<script type="text/javascript">
		
		function fetchData(id)
		{
			$('#packVal').val(id);
			$("#packForm").attr("action", "pack-detail.shtml");
			$('#packForm').submit();
		}
				
		function clearData()
		{	
			$("#filterAuthor").val($("#filterAuthor option:first").val());
			$("#minWfaVersion").val($("#minWfaVersion option:first").val());
			$('#certifiedFilter').prop('checked', false);
			$('#packForm').submit();
		}
		
		$(document).ready(function(){
			$('#searchContent').hide();
			$('#outerDiv').show();
			$("#mytable").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							4:{sorter:'datetime'}
						} 
				} ); 
			$("#mytable1").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							4:{sorter:'datetime'}
						} 
				} ); 
			$("#mytable2").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							4:{sorter:'datetime'}
						} 
				} ); 
			$("#mytable4").tablesorter( 
				{   dateFormat: 'd M, Y', 
					headers: 
						{
							4:{sorter:'datetime'}
						} 
				} ); 
		});
	</script>
		
	<script type = "text/javascript">

	function showToggle(id)
		{			
			$('#random'+id).slideToggle();	
			//$('#random'+id).slideToggle({direction: "up"}, 300);	
		}			

	</script>
		
<title>Search Packs for AutomationStore </title>
<meta></meta>
<style>
	
h3#searchHeader
{
	margin-bottom: -26px;
    margin-top: 50px;
    margin-left: 84px;
    color: #2668b1;
	font-size:16px;
}

h3#searchHeader span
{
	color: #76B407;
	
}

table.tablesorter thead th {
        color:white;
        font-weight:bold;
        line-height:27px;
        background-color: #0067c4;
        padding: 8px !important;
		text-align:center;
    }

    table.tablesorter thead tr .header {
    background-image: url('images/orderup.png');
    background-repeat: no-repeat;
    background-position: center right;
    cursor: pointer;
    }

    table.tablesorter thead tr .headerSortUp {
        background-image: url('images/orderup.png');
    }

    table.tablesorter thead tr .headerSortDown {
        background-image: url('images/orderdown.png');
    }
</style>
</head>


<body>
<?php
    //site header include here  
    include('includes/header-landing.php');
    ?>
    <div id="nav-under-bg">
        <!-- -->
		<h3 id="searchHeader">Search resuts for "<span><?php echo $searchText; ?></span>"</h3>
    </div>
   <div id="body_content">
					<?php  
                    echo $_SESSION['SESS_MSG'];			
                    unset($_SESSION['SESS_MSG']); 
                    ?>
        <div class="simpleTabs">
					<ul class="tabs1 tabswfpck" style="width:100%;">
						<li><a href="#tab1" class="selected"><img src="images/netapp-certified-icon.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px;">NetApp-Supported </a></li>
						
						 <li><form name="submitForm1" method="post" action="search-packsNG.shtml">
							<input type="hidden" name="search" value="<?php echo $searchText;?>" />
						 <a href="javascript:document.submitForm1.submit()"><img src="images/non-netapp-certified.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px; margin-left:14px;">NetApp-Generated </a></form></li>	
						 <li><form name="submitForm" method="post" action="search-packsNC.shtml">
							<input type="hidden" name="search" value="<?php echo $searchText;?>" />
						 <a href="javascript:document.submitForm.submit()"><img src="images/non-netapp-certified.png" width="20" height="20" border="0" align="absmiddle" style="margin-right:5px; margin-left:14px;">Community-Generated </a></form></li>	
					</ul>
					<br/>
                    <ul class="tabs" >
                         <li><a href="#tab1" >Workflow</a></li>
						 <li><a href="#tab2">Report</a></li>
                         <li><a href="#tab3">Performances</a></li>
						 <li><a href="#tab4">OCI</a></li>
                    </ul>
              
         <div class="tab_container">
		 
		 <br>
		<section class="certfy-msg"><b>NOTE :</b>
		Please select categories from above to view the appropriate data.
		</section>
		<br>
                    
             <div class="tab_content mbot" id="tab1" style="display: block;">
			 
				
				
	  <section class="workflow-div comman-link">

 
      <table cellpadding="0" cellspacing="0" border="0"  class="tablesorter" id="mytable4">
        	<thead><tr>
				<td>Type</td>
                <th>Pack Name</th>
                <th>Latest Version</th>
				<th>Min WFA Version</th>
                <th>Author</th>              
                <th>Released On</th>
                <td></td>
                <td></td>
                <?php 
	               if($userType == 1){
					?> 
				<td></td>
				<td></td>
				<?php } ?>
				
              </tr>
				</thead>
            	 <?php			 
					echo  $solrObj->getWorkflowResponse($searchText,"NETAPP");
				 ?> 
            </tr>
        </table>
        
    </section>
  
		</div>
		
		<div class="tab_content mbot" id="tab2" >
		
					
	  <section class="reports-div comman-link mbot">


      <table cellpadding="0" cellspacing="0" border="0" class="tablesorter" id="mytable2">
        	<thead><tr>
				<td>Type</td>
                <th>Report Name</th>
                <th>Latest Version </th>
                <th>OCUMVersion</th>
				<td>Released On</td>
                <td></td>   
                <?php 
	               if($userType == 1){
					?> 
				<td></td>
			
				<?php } ?>           

			              </tr>
				</thead>
				<?php			 
					echo  $solrObj->getReportResponse($searchText);
				 ?> 
            	 
            </tr>
        </table>
        
    </section>
		
		</div>
		
	<div class="tab_content mbot" id="tab3" >
		
					
	  <section class="reports-div comman-link mbot">


      <table cellpadding="0" cellspacing="0" border="0" class="tablesorter" id="mytable1">
        	<thead><tr>
				<td>Type</td>
                <th>Performance Name</th>
                <th>Latest Version </th>
                <th>OPMVersion</th>
				<td>Released On</td>
                <td></td>    
                      
 				<?php 
	               if($userType == 1){
					?> 
				<td></td>
			
				<?php } ?>   
              </tr>
				</thead>
            	 <?php			 
					echo  $solrObj->getPerformanceResponse($searchText);
				 ?> 
            </tr>
        </table>
        
    </section>
		
		</div>
		
		<div class="tab_content mbot" id="tab4" >
		
					
	  <section class="workflow-div comman-link mbot">


      <table cellpadding="0" cellspacing="0" border="0" class="tablesorter" id="mytable">
        	<thead><tr>
				<th>Type</th>
                <th>OCI Name</th>
                <th>Latest Version </th>
                <th>OCI Version</th>
				<th>Released On</th>
                <th>OCI Type</th>    
				<td></td>
                <td></td>      
 				   
              </tr>
				</thead>
            	 <?php			 
					echo  $solrObj->getOCIResponse($searchText,"NETAPP");
				 ?> 
            </tr>
        </table>
        
    </section>
		
		</div>
		
		


            </div>
            </div>
    </div>
	


<script src="js/support.js"></script>
	
<?php

// site head js include here 
include('includes/footer.php');
?>   
</body>
</html>
