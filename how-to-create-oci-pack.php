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

<body>
<?php
    //site header include here  
    include('includes/header-landing.php');
    ?>
    <div id="nav-under-bg">
        <!-- -->
    </div>

  <div id="body_content" style="line-height:2.5;">


<section>
<h2>Storage Automation Store  </h2>
<br>
http://automationstore.netapp.com/pack-list.shtml 
<article>
<strong>Speed Responsiveness and Agility</strong>
NetApp solutions allow IT organizations to build a single data management platform that embraces the latest technologies such as cloud, software defined, and flash. 
NetApp has been following the principles of software-defined storage for over 20 years, providing solutions for automation, integration, scale,optimal application 
performance and availability to help you meet business demands and accelerate time-to-market.
</article>
<article>
The Storage Automation Store is a place where you can download certified software packs to use with OnCommand Workflow Automation, OnCommand Unified Manager,
and OnCommand Performance Manager, to extend capabilities. 
</article>
<article>
<strong>Workflows </strong>
Workflow packs are designed for use with OnCommand Workflow Automation, to help you meet storage automation and integrations needs. Simply download the pack, 
import it, and then execute it from within Workflow Automation. 
</article>
<article>
<strong>Reports </strong>
Report packs are designed for use with OnCommand Unified Manager. [Anil, Naga, Ramya What is the process? I am assuming it is the same process,
download a report pack and import into OCUM?]
</article>

<article>
<strong>Performance</strong>
Download advanced performance dashboard packs designed for specific IT workloads, and add them into OnCommand Performance Manager. 
</article>
<article>
If you have questions about any of the packs, simply post them on the OnCommand community, which is monitored by NetApp Technical Marketing Engineers,
Engineering and many more field subject matter experts.
</article>

<article>
<strong>OnCommand Insight</strong>
Download from an extensive collection of Netapp & User community submitted Report templates, Queries, API's. Widgets and more for OnCommand Insight(OCI). Save, Import and modiry for your specific datacenter requirements.
</article>
<article>
If you have questions about any of the packs, simply post them on the OnCommand Community, which is monitored by NetApp Technical Marketing Engineers, Engineering and many more field subject matter experts.
</article>

<section>
</div>
<?php

// site head js include here 
include('includes/footer.php');
?>  

</body>
</html>

    
  
