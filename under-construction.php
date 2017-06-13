<?php session_start();
	ob_start(); 

	require_once('config/configure.php');
	require_once('includes/function/autoload.php');


 $loginObj = new Login();
//$loginObj->checkSession();
$pageName = getPageName();



$packObj = new Pack();

$searchText = (isset($_POST['search']) ? $_POST['search'] : null); 


  // site head js include here 
 include('includes/head.php'); ?>   

<!-- <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script> -->
<style>
	.errorImg
		{
			width:30%;
			height:67%;
			border:1px solid #ccc;
			border-radius: 5px;
		}
		
		#error
		{
			height: 60%;
			margin-left: 5px;
			margin-top:25px;
			float:left;
		}
		
		#errorHead
		{
			font-size:3.5em;
			color:#454545;
			font-weight:bold;
			text-decoration:none;
			margin-left:30px;
		}
		
		#subMsg
		{
			font-size:1.5em;
			color:#454545;
			
		}
</style>
</head>

<body>
<?php 

  //site header include here  
  include('includes/adminHeader.php'); 

?>
<div id="nav-under-bg">
  <!-- -->
</div>


<table width="100%" cellspacing="0" cellpadding="0" border="0"  summary="" style="margin-top:76px;"><tbody><tr>
</td><td valign="middle" align="center">
  <table cellspacing="0" cellpadding="0" border="0" summary=""><tbody><tr>
  <td><img width="237" height="200" alt="" src="images/under-construction.gif"></td>
  <td align="center" >
     <h2 id="errorHead">Thank you for visiting our site.</h2>
     <div id="subMsg">Our website is under maintenance. <br> 
     please visit again soon !<br>
     if you need any further information, please contact us at <b>ng-nCloud-DevOps@netapp.com </b>.<br>
     </div>
  </td></tr></tbody></table>
</td></tr></tbody></table>

<?php
  // site head js include here 
 include('includes/footer.php'); ?>   
</body>
</html>
