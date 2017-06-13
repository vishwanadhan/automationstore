<?php
require_once('config/configure.php');
require_once('includes/function/autoload.php');

$rootPath=$docRoot;

if(isset($_REQUEST['packPath']))
{
	$packPath	=	((isset($_REQUEST['packPath'])) ? $_REQUEST['packPath'] : '');
	$newPath=  $rootPath.(base64_decode( $packPath )); 
}

if(!isset($_REQUEST['searchText']))
{
	$_REQUEST['searchText'] = NULL;
}
$searchText	=	$_REQUEST['searchText'];
$packObj = new Pack();



?>

<html>
<head>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/eula.css" />
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
	<script type="text/javascript">
	
	$(document).ready(function(){
			var submitActor = null;
			var $form = $( '#cautionForm' );
			var $submitActors = $form.find( 'input[type=submit]' );

			$form.submit( function( )
			{
		
			  if ( null === submitActor )
			  {
				// If no actor is explicitly clicked, the browser will
				// automatically choose the first in source-order
				// so we do the same here
				submitActor = $submitActors[0];
			  }
			   if (submitActor.name == 'declineBtn')
                    {
                           var url = '<?php echo basename($_SERVER['HTTP_REFERER']); ?>';
                            window.parent.location.href = url;
                            return false;
                       
                    }

		//	  alert( submitActor.name );
		
			  if(submitActor.name == 'acceptBtn')
			  {
				$('#cautionForm').submit();
			  }

			  return false;
			});

			$submitActors.click( function( event )
			{
			  submitActor = this;
			});

		} );	
	
	 
	</script>	
</head>

<body bgcolor="#FFFFFF">

<?php 
$caution = '<p align="center"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>';
$caution .= '<p align="center"><font face="Arial" size="2"><b>Base Customer Software License </b><br><br>';
$caution .= '<strong>PACK CAUTION PAGE </strong></font></p>';

$caution .= '<font face="Arial" size="2">';
 //$packId = base64_decode($_REQUEST['packId']);
 //$uuid = $packObj->getPackUuid($packId);
if($_REQUEST['packType'] == 'snapcenter'){
	$packObj = new SnapPack();
	$cautionContent = $packObj->getCautionByPackId( $_REQUEST['packUuid'],$_REQUEST['certi']);
 	$cautionIn = base64_decode($cautionContent);			
}else{
	 $cautionContent = $packObj->getCautionByPackId( $_REQUEST['packUuid']);
	 $cautionIn = base64_decode($cautionContent);
 }
$caution .=  '<div style="margin: 40px ;"><span style="margin:auto; display:table; ">'.$cautionIn.'</span></div>'; 

$caution .=  '<form method="POST" id="cautionForm" action="eula.php?packUuid=' .  $_REQUEST['packUuid'] .'&packVersion=' .  $_REQUEST['packVersion'].'&packType='.$_REQUEST['packType'].'&certi='.$_REQUEST['certi'].'">';

$caution .= '<center>
				<table width="200" cellspacing="1" cellpadding="8" border="0">
				<tbody><tr>
				</tr><tr>
				<td valign="top" align="center" class="dark1"><input type="submit" id="btn_accept" value="I understand" name="acceptBtn"></td>
				</tr>
				<tr>
                            <td class="dark2" align="center" valign="top"><b>
                                    <input name="declineBtn" id="declineBtn" value="Decline" type="submit" class="back">        
                                </b></td>
                        </tr>				
				</tbody></table>
			</center>
			</form>
			</font>';

	echo $caution;
/*

$cautionOut = base64_encode($caution);
echo $cautionOut;exit;
$myfile = fopen("tests/test.txt", "w") or die("Unable to open file!");
fwrite($myfile, $cautionData);
fclose($myfile);
echo $content = gzuncompress($cautionData);

//echo $cautionSerialize;exit;

*/

?>


</body>
</html>