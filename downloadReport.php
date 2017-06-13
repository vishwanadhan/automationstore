<?php session_start();
ob_start();

include('config/configure.php');
include('includes/function/autoload.php');

$loginObj = new Login();
$loginObj->checkSession();
$pageName = getPageName();

$packObj = new Pack();

	$key="";
	
 include('includes/head.php'); ?>   	


<script type="text/javascript">

</script>

</head>
<body>
<?php 
  //site header include here 

 include('includes/header.php'); 
  
 ?>
 
 <div id="nav-under-bg">
  <!-- -->
</div>

<div id="body_content" style="display: block; margin-top: 58px;">
  <div id="outerDiv1">	
 		<div id="repDownload" style="padding-left:22px;"><a href="report.shtml">Download Reports </a></div>
  </div>	
</div>
</body>
</html>




