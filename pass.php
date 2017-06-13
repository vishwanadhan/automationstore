<?php

/**
 * pass.php
 * file for handling all the ajax calls
 */

ob_start();
session_start();

require_once('config/configure.php');

//$action = $_GET['action'];
$action = $_REQUEST['action'];
$type = ($_REQUEST['type']) ? $_REQUEST['type'] : '';
$page = ($_REQUEST['page']) ? $_REQUEST['page'] : '';
/**************************** autoload the classes ****************/
require_once('includes/function/autoload.php');


if ($action == 'admin') {
    $admObj = new AdminDetail;
    if ($type == 'changestatus') {
        $admObj->changStatus($_GET);
    }
    if ($type == 'delete') {
        $admObj->deleteRecord($_GET);
    }
}

if ($action == 'managePack') {
    $nafObj = new Pack();
    if ($type == 'changestatus') {
        $nafObj->changStatus($_GET);
    }
    if ($type == 'delete') {
        $nafObj->deleteValue($_POST);
    }
    if ($type == 'solrdelete' || $type=='userdelete') {
        $nafObj->deleteValue($_GET);
    }
}
// For OCI Packs
if ($action == 'manageOciPack') {

    $ociObj = new OciPack();
    if ($type == 'changestatus') {
        $ociObj->changStatus($_REQUEST);
    }
    if ($type == 'delete' || $type=='userdelete') {
        
        $ociObj->deleteOciPackData($_REQUEST);
    }
    if ($type == 'solrdelete' ) {
     
        $ociObj->deleteOciPackDataSolr($_REQUEST);
    }
}
// For SNAP Packs
if ($action == 'manageSnapPack') {

    $packObj = new SnapPack();
    if ($type == 'changestatus') {
        $packObj->changStatus($_GET);
    }
    if ($type == 'delete') {
        $packObj->deleteValue($_POST);
    }
    if ($type == 'snapsolrdelete' || $type=='snapuserdelete') {
        $packObj->deleteValue($_GET);
    }
}
/// for Manage User
if ($action == 'importExport') {
    $userObj = new User();
    if ($type == 'changestatus') {
        $userObj->changeStatus($_GET);
    }
    if ($type == 'delete') {

        $userObj->deleteValue($_GET);
    }
    if ($type == 'deleteall') {
        if ($_POST['Input']) {
            $userObj->deleteAllValues($_POST);
        } else {
            header("Location:importExport.php?searchtxt=" . $_POST['searchtext']);
            exit;
        }
    }

    if ($type == 'screenshot_del') {

        $deleteId = $_GET['imgid'];
        $table = $_GET['table'];

        $qry = "SELECT image FROM " . $table . " WHERE id = '" . $deleteId . "'";
        $res = mysql_query($qry);
        $num = mysql_num_rows($res);
        $row = mysql_fetch_assoc($res);

        if ($num > 0) {
            if (file_exists(PATH . $row['image'])) {
                @chmod(PATH . $row['image'], 0755);
                @unlink(PATH . $row['image']);

                @chmod(PATH . $row['imageSmall'], 0755);
                @unlink(PATH . $row['imageSmall']);

                $fgtquery = "update " . $table . " set image = '',imageSmall = '' where id =" . $deleteId;
                mysql_query($fgtquery);
                $msg = "Record has been deleted successfully";
            }
        } else {
            $msg = "record not found.";
        }
        echo $msg;
    }
}


//manage Menu
if ($action == 'menu') {
    $menuObj = new MenuDetail();
    if ($type == 'changestatus') {
        $menuObj->changeValueStatus($_GET);
    }
    if ($type == 'delete') {
        $menuObj->deleteValue($_GET);
    }
    if ($type == 'deleteall') {
        if (!$_POST['Input']) {
            header("Location:manageMenu.php?searchtxt=" . $_POST['searchtext']);
            exit;
        } else {
            $menuObj->deleteAllValues($_POST);
        }
    }
    if ($type == 'SORTORDER') {
        $menuObj->SortSequence($_GET);
    }
    if ($type == 'setmenuorder') {
        $menuObj->setmenuorder($_GET);
    }
}


if ($action == 'manageEntity') {

    $entityObj = new Entity();
    echo @$entityObj->entityFullInformation($_POST["packId"]);
}

if ($action == 'packData') {
    $packObj = new Pack();

    $result = $packObj->getPackData($_POST);
    $result = json_encode($result);
    print_r($result);
    exit;
}

if ($action == 'allPacks') {
    $packObj = new Pack();


    print_r(json_encode($packObj->getAllPacks()));
    exit;
}

if ($action == 'getFilter') {
    $packObj = new Pack();
    print_r(json_encode($packObj->getFiltersData()));
    exit;
}

if($action == 'setCaution'){

    $packObj = new Pack();
    echo $packObj->setCautionStatus($_POST);
}
if($action == 'setSnapCaution'){

    $packObj = new SnapPack();
    echo $packObj->setCautionStatus($_POST);
}


if($action == 'approvePack')
{
    $packObj = new Pack();
    echo $packObj->approvePack($_GET);
}
if($action == 'approvesnapPack')
{
    $packObj = new SnapPack();
    echo $packObj->approvePack($_GET);
}

if($action == 'rejectPack')
{
    $packObj = new Pack();
    $_POST['userEmail'] = $_SESSION['mail'];
    echo $packObj->rejectPack($_POST);
}

if($action == 'rejectsnapPack')
{
    $packObj = new SnapPack();
    $_POST['userEmail'] = $_SESSION['mail'];
    echo $packObj->rejectPack($_POST);
}
if($action == 'contentFilter'){
    $packId = ($_REQUEST['packId']) ? $_REQUEST['packId'] : '';
    $entityType = ($_REQUEST['entityType']) ? $_REQUEST['entityType'] : '';
    
    $entityObj = new Entity();
    echo $entityObj->setContentFilter($packId, $entityType);
}
if($action == 'snapcontentFilter'){
    $packId = ($_REQUEST['packId']) ? $_REQUEST['packId'] : '';
    $entityType = ($_REQUEST['entityType']) ? $_REQUEST['entityType'] : '';
    
    $entityObj = new SnapEntity();
    echo $entityObj->setContentFilter($packId, $entityType);
}

/* Flag Pack as inappropriate */
if($action == 'submitFlag'){        
    $packObj = new Pack();
    echo $packObj->addGrievance($_POST);
}

if($action == 'submitFlagOci'){        
    $ociPackObj = new OciPack();
    echo $ociPackObj->addGrievanceOci($_POST);
}
/* clear Flag Pack */
if($action == 'clearFlag'){     
    $packObj = new Pack();
    echo $packObj->clearGrievance($_GET);
}
/* clear Snap Flag Pack */
if($action == 'snapclearFlag'){     
    $packObj = new SnapPack();
    echo $packObj->clearGrievance($_GET);
}

/* if($action == 'updateTag')
{
    $packObj = new Pack();
    echo $packObj->updateTag($_POST);
} */

/* deprecation */
if($action == 'setDeprecationFlag'){
    $packObj = new Pack();
	echo $packObj->setDeprecationFlag($_POST);
}

/* deprecation oci */
if($action == 'setDeprecationFlagOci'){
    $ocipackobj = new OciPack();
	echo $ocipackobj->setDeprecationFlagOci($_POST);
}

/* deprecation snap */
if($action == 'setDeprecationFlagsnap'){
    $snappackobj = new SnapPack();
    echo $snappackobj->setDeprecationFlagSnap($_POST);
}

if($action == 'updatePhone')
{
    $userObj =  new UserProfile();
    $userObj->updateUserInfo($_POST);
}

if($action == 'fetchScroll')
{
    $packObj =  new Pack();
    $packObj->packScrollData($_POST);
}
if($action == 'GetOciPackList'){
    $ocipackObj = new OciPack();
  //  print_r($_POST);die;
    $filter = array();
     $filter['certifiedBy'] = isset($_POST['certifyType']) ? $_POST['certifyType'] : 'NETAPP';
     $filter['ociTypeId'] = $_POST['OciType'];
   echo '<tbody>'. $ocipackObj->ociFullInformation($filter).'</tbody>';
}

if($action == 'rejectOCIPack')
{
   
    $ociObj = new OciPack();
    $_POST['userEmail'] = $_SESSION['userEmail'];
     $ociObj->rejectOCIPack($_POST);
}
if($action == 'approveOciPack')
{
    $ociObj = new OciPack();
	
    echo $ociObj->approvePack($_GET);
}

if($action == 'updateTag')
{
	if($type== "ocitag")
    {
		$ociObj = new OciPack();
		echo $ociObj->updateTag($_POST);
	}else if($type== "snaptag")
    {
         $packObj = new SnapPack();
    echo $packObj->updateTag($_POST);
    }
	else
	{
		 $packObj = new Pack();
    echo $packObj->updateTag($_POST);
	}

}
?>