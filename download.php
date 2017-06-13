<?php
require_once('config/configure.php');
require_once('includes/function/autoload.php');
require_once('config/tables.php');

$rootPath=$docRoot;
$typePack= $_REQUEST["packType"];
$nameReport= $_REQUEST["reportName"];
$versionReport= $_REQUEST["reportVersion"];
$namePack= $_REQUEST["packName"];
$versionPack= $_REQUEST["packVersion"];
$uuidPack= $_REQUEST["packUuid"];
$packPath=$_REQUEST["packPath"];


$typePack = (isset($typePack) ? $typePack : null);
$namePack = (isset($namePack) ? $namePack : null);
$versionPack = (isset($versionPack) ? $versionPack : null);
$versionReport = (isset($versionReport) ? $versionReport : null);
$nameReport = (isset($nameReport) ? $nameReport : null);
$uuidPack = (isset($uuidPack) ? $uuidPack : null);

//$newPath .=$rootPath;

$newPath=base64_decode($packPath);
if($typePack == 'snapcenter')
{
  $snappackObj = new SnapPack();
  $snappackObj->insertDownloadDetails( $uuidPack, $versionPack, $typePack, $namePack, $nameReport, $versionReport);
}else if($typePack != 'ocipack')
{
	$packObj = new Pack();
	$packObj->insertDownloadDetails( $uuidPack, $versionPack, $typePack, $namePack, $nameReport, $versionReport);
}
else{
	$ociObj = new OciPack();
	$ociObj->insertOciDownloadDetails($versionPack, $typePack, $namePack);  
}
//setting headers
header('Content-Description: File Transfer');
   header('Cache-Control: public');
   //header('Content-Type: application/jar');
   header("Content-Transfer-Encoding: binary");
   header('Content-Disposition: attachment; filename='. basename($newPath));
   header("Content-Length: " .(string)(filesize($newPath)) );
   ob_clean(); 
   flush();
   readfile($newPath); 
   
  exit(0);
?>