<!DOCTYPE html>
<?php
session_start();
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
<!-- Head Start -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Welcome to Storage Automation Store</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
</head>
<!-- Head End -->

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
/* 	$alertStatus = $packObj->fetchValue(TBL_ALERT,'status',"alertId=1");
	$alertMessage = $packObj->fetchValue(TBL_ALERT,'message',"alertId=1");
	if($alertStatus == '1')
	{
		$alertHtml = '<div class="alert-home" id="alert-home">'; 
		$alertHtml .= '<a class="alert-close" onclick="hideAlert();"><img src="images/alertCancel.png" width="10" height="10"/></a>';
		$alertHtml .= $alertMessage;
		$alertHtml .= '</div>'; 
		echo $alertHtml;  
	} */
?>

<section class="header-img">
	<img src="images/resources/header.jpg" />
</section>
<section class="left-content">
<article><span>Speed Responsiveness and Agility</span>NetApp solutions allow IT organizations to build a single data management platform
that embraces the latest technologies such as cloud, software defined, and flash.
NetApp has been following the principles of software-defined storage for over 20 years,
providing solutions for automation, integration, scale, optimal application performance
and availability to help you meet business demands and accelerate time-to-market.
The Storage Automation Store is a place where you can download certified software
packs to use with OnCommand Workflow Automation, OnCommand Unified Manager
and OnCommand Performance Manager to extend data management capabilities.</article>

<article><span><a href="pack-list.shtml">Workflows</a></span>
Workflow packs are designed for use with OnCommand Workflow Automation, to help
you meet storage automation and integrations needs. Simply download the pack,
import it, and then execute it from within Workflow Automation.</article>

<article><span><a href="reports.shtml">Reports</a></span>
Report packs are designed for use with OnCommand Unified Manager. You can add clusters to the
Unified Manager database to monitor clusters for availability, capacity, and other details, such as
CPU usage, interface statistics, free disk space, qtree usage, and chassis environmental.</article>

<article><span><a href="onCommandInsight.shtml">OnCommand Insight</a></span>
Download from an extensive collection of NetApp & User community submitted Report templates, Queries, API's. Widgets and more for OnCommand Insight (OCI). Save, Import and modify for your specific datacenter requirements.</article>
<article>
If you have questions about any of the content, simply post them on the <a href="http://community.netapp.com/t5/forums/filteredbylabelpage/board-id/oncommand-storage-management-software-discussions/label-name/insight" target="_blank">OnCommand 
community</a>, which is monitored by NetApp Technical Marketing Engineers, Engineering and many more field subject matter experts.
</article>

<article><span><a href="performance.shtml">Performance</a></span>
Download advanced performance dashboard packs designed for specific IT workloads,
and add them into OnCommand Performance Manager.</article>
<article><span><a href="snap-list.shtml"><?php echo CONSTANT_UCWORDS;?></a></span>
Content coming soon.</article>
<article>If you have questions about any of the packs, simply post them on the <a href="http://community.netapp.com/t5/OnCommand-Storage-Management-Software-Discussions/bd-p/oncommand-storage-management-software-discussions" target="_blank">OnCommand
community</a>, which is monitored by NetApp Technical Marketing Engineers, Engineering
and many more field subject matter experts.</article>


</section>






<section class="right-content">
	<ul>
    <li><p class="link-head ">Related information</p></li>
    
   <li><a href="how-to-use-packs.shtml" target="_blank">How to use packs</a><span><img src="images/caret16.png"></span></li>
    <li><a href="how-to-create-a-pack.shtml" target="_blank">How to create packs</a><span><img src="images/caret16.png"></span></li>
    <li><a href="how-to-create-a-snap-pack.shtml" target="_blank">How to create a <?php echo CONSTANT_LOWERCASE;?> packs</a><span><img src="images/caret16.png"></span></li>
   <li> <p class="link-head"> DOCUMENTATION</p></li>
	<li><a href="http://mysupport.netapp.com/documentation/productlibrary/index.html?productID=61550" target="_blank">Workflow Automation</a><span><img src="images/caret16.png"></span></li>
	<li><a href=" http://mysupport.netapp.com/documentation/productlibrary/index.html?productID=61373" target="_blank">Unified Manager</a><span><img src="images/caret16.png"></span></li>
	<li><a href="http://mysupport.netapp.com/documentation/productlibrary/index.html?productID=61809" target="_blank">Performance Manager</a><span><img src="images/caret16.png"></span></li>
	<li><a href="http://mysupport.netapp.com/documentation/productlibrary/index.html?productID=60983" target="_blank">OnCommand Insight</a><span>></span></li>
	<li><a href="#" target="_blank"><?php echo CONSTANT_UCWORDS;?></a><span>></span></li>
    <li><p class="link-head"> DOWNLOAD</p></li>
	<li><a href="http://mysupport.netapp.com/NOW/cgi-bin/software" target="_blank">Workflow Automation</a><span><img src="images/caret16.png"></span></li>
	<li><a href="http://mysupport.netapp.com/NOW/cgi-bin/software" target="_blank">Unified Manager</a><span><img src="images/caret16.png"></span></li>
	<li><a href="http://mysupport.netapp.com/NOW/cgi-bin/software" target="_blank">Performance Manager</a><span><img src="images/caret16.png"></span></li>
	<li><a href="http://mysupport.netapp.com/NOW/cgi-bin/software" target="_blank">OnCommand Insight</a><span>></span></li>
	<li><a href="#" target="_blank"><?php echo CONSTANT_UCWORDS;?></a><span>></span></li>
	 <li><p class="link-head">COMMUNITY</p></li>
    <li><a href="http://community.netapp.com/t5/forums/filteredbylabelpage/board-id/oncommand-storage-management-software-discussions/label-name/workflow%20automation%20(wfa)" target="_blank">Workflow Automation</a><span><img src="images/caret16.png"></span></li>
	<li><a href="http://community.netapp.com/t5/forums/filteredbylabelpage/board-id/oncommand-storage-management-software-discussions/label-name/unified%20manager" target="_blank">Unified Manager</a><span><img src="images/caret16.png"></span></li>
	<li><a href="http://community.netapp.com/t5/forums/filteredbylabelpage/board-id/oncommand-storage-management-software-discussions/label-name/performance%20manager" target="_blank">Performance Manager</a><span><img src="images/caret16.png"></span></li>
	<li><a href="http://community.netapp.com/t5/forums/filteredbylabelpage/board-id/oncommand-storage-management-software-discussions/label-name/insight" target="_blank">OnCommand Insight</a><span>></span></li>
	<li><a href="#" target="_blank"><?php echo CONSTANT_UCWORDS;?></a><span>></span></li>
	<br />
	<li><a href="support-faq.shtml" target="_blank" >Support FAQ</a><span><img src="images/caret16.png"></span></li>
	<!-- <li><a href="NetAppConfidentialityAgreement.shtml">NetApp Confidentiality Agreement</a><span><img src="images/caret16.png"></span></li>-->
	<li><a href="http://community.netapp.com/t5/OnCommand-Storage-Management-Software-Discussions/WFA-training-links/m-p/105974/highlight/true#M18731" target="_blank">NetApp University training</a><span><img src="images/caret16.png"></span></li>
    </ul>
</section>

</div>
<?php

// site head js include here 
include('includes/footer.php');
?>  

</body>
</html>
