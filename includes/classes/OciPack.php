<?php
@session_start();
ob_start();
//include 'class.phpmailer.php';
/**
 * Pack.shtml
 * This class implements various functionalities related to information regarding Packs.
 * 
 */


class OciPack extends MySqlDriver {

    function __construct() {
       
        $obj = new MySqlDriver;
        $this->log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
    }
	
	/* OCI pack information 
	 * Function for fetching full information of performance .
	 * @param    none
     * @return   String
	 * author: AJ
	*/
	function ociFullInformation($filter) {
       
        $cond = '';
        if (!empty($filter)) {
            foreach ($filter as $key => $value) {
                if ($cond != '')
                    $cond .= " and ";
                $cond .= $key . "='" . $value . "'";
            }
        }
			$cond.="and post_approved=true";
			$count=1;//used in delhc
			$queryMXversion = " Select * from ".TBL_OCIPACKS." join(SELECT MAX( packVersion ) AS Vsion,packName AS Nme FROM ".TBL_OCIPACKS."  where " . $cond . " GROUP BY packName )mvx ON packVersion = mvx.Vsion and packName = mvx.Nme where " . $cond . "  GROUP BY packName";
			
			
			//echo $queryMXversion;exit;
			
			$orderby = $_GET[orderby] ? $_GET[orderby] : "packDate";
			$order = $_GET[order] ? $_GET[order] : "DESC";
			$queryMXversion .= " ORDER BY $orderby $order";
			$sqlMXversion = $this->executeQry($queryMXversion);
			$numMXversion = $this->getTotalRow($sqlMXversion);
		
			//echo 	$queryMXversion ;
						
			if ($numMXversion > 0){
			while ( $lineReportMXversion= $this->getResultObject($sqlMXversion)) 
								{
			
							$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
							//$userType= '1';/*for testing*/											
							$encodedPath = base64_encode($lineReportMXversion->packFilePath);
							if ($lineReportMXversion->certifiedBy == 'NETAPP') {
								$genTable .= '<tr><td ><img src="images/netapp-certified-icon.png"></td>';                    
							} else {
								$genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
							}
			//				$genTable .='<td>' . $this->ociType($lineReportMXversion->ociTypeId) . '</td>';
							$genTable .= '<td><a title="'.$lineReportMXversion->packName.'"  href="ocipack-detail.shtml?packName=' . implode("!", explode(" ", $lineReportMXversion->packName)) . '&packVersion=' . $lineReportMXversion->packVersion . '">'.$lineReportMXversion->packName.'</a></td>';								
								 
					
					$genTable.='
					<td><a href="javascript:void(0)" class="triggersmall">' .$lineReportMXversion->packVersion.'</a>'; 
					
										  
					$genTable.='
					<div class="pop-up">
					<img src="images/popup-arrow.png" alt="" class="pop-arrow">
						<table cellpadding="0" cellspacing="0" border="0">
							<thead>
									<tr>													
									<td>Latest Version</td>
									<td>OCI Version</td>
									</tr>
							</thead>
							';		
					$query1="SELECT * FROM " . TBL_OCIPACKS . " WHERE ociTypeId = ".$lineReportMXversion->ociTypeId." and packName='" . $lineReportMXversion->packName . "'order by packVersion DESC ";					
					$sql1 = $this->executeQry($query1);
					$numEntity = $this->getTotalRow($sql1);							
					if ($numEntity > 0) {

										$j = 1;
										$delVersion = array();
										while ($linePack = $this->getResultObject($sql1)) {
										
										$genTable .= '
														<tr>
															<td>' . $linePack->packVersion . '</td>
															<td>' . $linePack->OCIVersion . '</td>
														</tr>';	

										
										$delVer=array($linePack->id,$linePack->packVersion );
										array_push($delVersion, $delVer);
											//print_r($delVer);	
											$j++;
																					} 
										}
					$delArray=array($lineReportMXversion->packName => $delVersion);			  
					$genTable .= ' </table>
								</div>
							</td>';
					$genTable .= '<td>' .$lineReportMXversion->OCIVersion. '</td>		
							<td>' . date('d M, Y', strtotime($lineReportMXversion->packDate)) . '</td>	
										  <td><a href="eula.shtml?packName='.implode("!",explode(" ",$lineReportMXversion->packName)).'&packVersion='.$lineReportMXversion->packVersion.'&packType=ocipack" name="download" class="packAction"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td> ';
							
						
					if(!empty($lineReportMXversion->previewFilePath))
					{
						$genTable .= '<td><a target= "_blank" href="'.SITEPATH.$lineReportMXversion->previewFilePath.'" name="download" class="packAction"><img src="images/preview.png" border="0" title="Preview" /></a></td>';
					}	
					else{
						// $genTable .= '<td><img src="images/no-preview.png" border="0" title="No Preview" /></td>';
						  $genTable .= '<td></td>';
					} 
						
						//delhc-start			  
					if ($userType== 1) {										
							$genTable .= '<td>
								<a class="js-open-modal"  href="javascript:void(0)" data-modal-id="popupNETAPP'.$count.'">
								<img src="images/drop.png" height="16" width="16" border="0" title="Delete" alt="Delete" />
								</a></td>'; 
								
								
							foreach($delArray as $key => $value)
								{
									$array1 = $value;
									$genTable .= '
													<!-- Pop up -->
													<div id="popupNETAPP'.$count.'" class="modal-box">
													<form action="pass.php" method="post" name="DeleteForm" id="DeleteForm" onsubmit="return confirm(\'Are you sure you want to delete these packs ? WARNING:- This will permanently delete the packs !!\');">
													<header> <a href="javascript:void(0)" class="js-modal-close close"><img src="images/close-icon.png" border="0" /></a>
													<h2>'.$key.'</h2>
													<h5>Select Versions of the report for Deletion </h5>
													</header>
													<div class="modal-body deleteppop">
													
													<p><strong>Versions:</strong> </p>';
										$index=1;
										foreach ($value as $delIdVr)
										{																														
											$genTable .= '<p><input name="idToDel[]" id="idToDel'.$index.'" type="checkbox" value="'.$delIdVr[0].'"  align="absmiddle" class="pdright">'.$delIdVr[1].'</p>';	
											$index++;
										}
										
										$genTable .= '		
														<input type="hidden" name="page" value="onCommandInsight">
														<input type="hidden" name="action" value="manageOciPack">
														<input type="hidden" name="type" value="delete">												
														</div>
														<footer> 
														
														<input type="submit" value="Delete" class="btn" /> 	
														</footer>
														</form>
														</div>
														<!-- pop end -->';
															
													
								}$count++;
								
							}//delhc-end
							$genTable .= "</tr>";
						  }
								
							
			
								}
                            else
                                {
                                     $genTable = '<tr><td colspan="9" >Sorry no records found</td></tr>';
                                }
						
						 return $genTable;
		}
		
	function getLastOciId()
	{
		$query = "SELECT id from ocipacks ORDER BY id DESC LIMIT 1";
		
		$sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        $line = mysql_fetch_row($sql);

        return $line[0];
	}
	
	/*
	 * function to add OCI pack data in to database
	 * @params type	array ($POST)
	 * @return type bool
	 * Dev AJ
	 */
	function addOCIData($POST) { 
	
        $now = date('Y-m-d H:i:s');
        $this->tablename = TBL_OCIPACKS;
		
		$tags = $POST['oci_tags'];
		
		$this->field_values['ociTypeId'] 			= mysql_real_escape_string($POST['ociTypeId']); 
        $this->field_values['packName'] 			= mysql_real_escape_string($POST['oci_name']); 
        $this->field_values['packDescription'] 		= mysql_real_escape_string($POST['oci_desc']); 
        $this->field_values['packVersion'] 			= mysql_real_escape_string($POST['oci_pack_version']); 
        $this->field_values['versionChanges'] 		= mysql_real_escape_string($POST['oci_version_desc']); 
        $this->field_values['OCIVersion'] 			= mysql_real_escape_string($POST['oci_version']);         
        $this->field_values['otherPrerequisite'] 	= mysql_real_escape_string($POST['other_text']); 
		$this->field_values['certifiedBy'] 			= mysql_real_escape_string($POST['certificate']); 
        $this->field_values['authorName'] 			= mysql_real_escape_string($POST['author_name']);   
        $this->field_values['authorEmail'] 			= mysql_real_escape_string($POST['author_email']); 
        $this->field_values['authorContact'] 		= mysql_real_escape_string($POST['author_phone']); 		
		
		
        $this->field_values['tags'] = $tags;
         
		
		$packFilePath_info = pathinfo($POST['reportfile']);		
		if($packFilePath_info['extension'] == 'zip')  
		{		
			$this->field_values['packFilePath'] 		= mysql_real_escape_string($POST['reportfile']); 
		}
		
/* 		$previewFilePath_info = pathinfo($POST['previewFilePath']);	
		if($previewFilePath_info['extension'] == 'jpg' || $previewFilePath_info['extension'] == 'jpeg' || $previewFilePath_info['extension'] == 'png')
		{ */
			$this->field_values['previewFilePath'] 		= mysql_real_escape_string($POST['previewFilePath']); 
//		}
		
        $this->field_values['packDate'] 			= $now;      

		$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
		if($userType==1)
		{
			$this->field_values['post_approved']='true';
		}
		
		$cond = "(packName = '" . mysql_real_escape_string(strtolower($POST['oci_name'])) . "' OR  packName = '". mysql_real_escape_string(strtolower($POST['oci_name'])) ."' OR packName = '".mysql_real_escape_string($POST['oci_name'])."') AND packVersion = '" . $POST['oci_pack_version'] . "'";   
		
		$reportData = $this->singleValue(TBL_OCIPACKS, $cond);
		
			if (empty($reportData)){
					$res = $this->insertQry();    
					   
					if(empty($tags))
                    { 
                        $newTags = array();    
                    }
                    else
                    {
                        $newTags = explode(",",$tags);
                    }
					
					$typequery="Select typeName from ocitypes where typeId = '".$POST['ociTypeId']."' ";
					$typsesql = $this->executeQry($typequery);
					$numEnt = $this->getTotalRow($typsesql);

					$ociline = mysql_fetch_row($typsesql);
				
				
					if($res) {
					if($userType==1)
					{
						solrOciSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', $POST, $POST['oci_tags'], $ociline[0],'');
					}
					else
					{
						$this->adminEmailNotify($POST);
					}
					
					unset($this->tablename);
					unset($this->field_values);
					
					/* echo "<pre>";
					print_r($res);exit; */
					
					$this->addUser($res[1],$POST['ociTypeId']);
					return 1;
					$this->log->LogInfo("OCI inserted.");
					}else{
					$this->log->LogError("Unable to insert OCI.");
					return 0;
					}
			}else{
			 return 0;
			}		
	}	
	 /*
     * function to remove directory from root/wfs_data
     * @params type	string filename
     * @return type bool
     * Dev AJ
     */

    function deleteOciPackData($get) {
		/* echo "<pre>";
		print_r($get);exit; */
       
        if (!isset($get[idToDel]) || $get[idToDel] == null) {
            $_SESSION['SESS_MSG'] = msgSuccessFail("fail", "No versions were selected to delete");
            header('Location:' . $get[page] . '.shtml');
            exit;
        }
	if(!is_array($get[idToDel])) $get[idToDel] = array($get[idToDel]);
        foreach ($get[idToDel] as $idToDel) {

            $type = "";

            if ($get[page] == "onCommandInsight" || $get[page] == "user_ociprofile" || $get[page] == "admin_profile") {

                $packFilePath = $this->fetchValue(TBL_OCIPACKS, "packFilePath", "id = '" . $idToDel . "'");
                $query = "SELECT * from " . TBL_OCIPACKS . " WHERE  id='" . $idToDel . "' ";
				
				
                $queryDelete = " DELETE FROM " . TBL_OCIPACKS . " WHERE  id = '" . $idToDel . "' ";
            //    $fieldName = "OciPack";
			    $fieldName = "OCI content";
                $type = "ocipack";
            } else {
                header('Location: home.php');
            }

           /*   echo $query."</br>";
              echo $queryDelete."</br>";
              echo $packFilePath."</br>";
              echo $fieldName;
              exit; */
             
			 
            $sql = $this->executeQry($query);
            $line = $this->getResultObject($sql);

          
                $packName = $line->packName;
            

            if (!empty($packFilePath)) {
                $path_parts = pathinfo(PATH . $packFilePath);
                $unlinkDir = $path_parts['dirname'];
                //echo $unlinkDir;exit;
                rrmdir($unlinkDir);
                $packId = $idToDel;

                //deletetion query execute
                $rst = $this->executeQry($queryDelete);
				
					{ 
                $solrDelete="http://".SOLRSERVER.":".SOLRPORT."/solr/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:oci_".$idToDel."%3C/query%3E%3C/delete%3E&commit=true";
				
				
						$content = file_get_contents($solrDelete); 
						
					 	}
            }
			else{
			$rst = $this->executeQry($queryDelete);
			}
        }
        //$this->log->LogInfo("Pack with packId: " . $get[id] . " deleted successfully");

        $_SESSION['SESS_MSG'] = msgSuccessFail("success", " Selected versions of " . $packName . " " . $fieldName . " has been deleted successfully!!!");

        echo "<script language=javascript>window.location.href='" . $get[page] . ".shtml';</script>";
        exit;
    }

	/*
	 * function to remove directory from root/wfs_data
	 * @params type	string filename
	 * @return type bool
	 * Dev AJ
	 */
	function removeDir($filedir){		
		if(is_dir($filedir)){
			$it = new RecursiveDirectoryIterator($filedir, RecursiveDirectoryIterator::SKIP_DOTS);
			$files = new RecursiveIteratorIterator($it,RecursiveIteratorIterator::CHILD_FIRST);
			foreach($files as $file) {
				if ($file->isDir()){
					rmdir($file->getRealPath());
				} else {
					unlink($file->getRealPath());
				}
			}
			rmdir($filedir);
			return 1;
		}else{
			return 0;
		}
	}
	
	/*
	 * function to fetch the OCI pack type
	 * @params type	string oci id
	 * @return type string
	 * author  Asmita
	 */
	function ociType($id){	 
	 	$query = "SELECT * FROM " . TBL_OCIPACKTYPE . " where typeId = $id";

        $result = $this->executeQry($query);

        $num = $this->getTotalRow($result);
        if ($num > 0) {
            $line = $this->getResultRow($result);
            return ucfirst($line['typeName']);
        }
		else
		{
		return 'NA';
		}
  }
  
  
	/*
	 * function to fetch the user information by packId
	 * @params type	string oci id
	 * @return type string
	 * author  Asmita
	 */
	function getUserDetailByociPackID($id){	 
	 	$query = "SELECT * FROM " . TBL_OCIPACKUSER . " WHERE `packId` = $id";

        $result = $this->executeQry($query);

        $num = $this->getTotalRow($result);
        if ($num > 0) {
            $line = $this->getResultRow($result);
            return $line;
        }
  }
	 function getAllociType() {
		$typeArr=array();
       $query = "SELECT * FROM " . TBL_OCIPACKTYPE . " WHERE `status` = '1' ";


        $result = $this->executeQry($query);

       $num = $this->getTotalRow($result);
        if ($num > 0) {
            while ($line = $this->getResultObject($result)) {
                $typeArr[] = $line;
            }
        }
        return $typeArr;
    }
	
    		/**
     * Function to fetch full information of entities of a performance pack based on packName and packVersion, changing the content if funtion is used for download
     * @param type $packName
     * @param type $packVersion
	 * @param type $downloadPg
     * @return string
     */
     //hc-funtion	 
	function ociPackInformation($packName ,$packVersion ,$downloadPg = null) {

	 	$query = " SELECT * FROM ". TBL_OCIPACKS." where packName='".implode(" ",explode("!",$packName))."' && packVersion='".$packVersion."'";
		$rst = $this->executeQry($query);
        $num = $this->getTotalRow($rst);
		$lineReport = $this->getResultObject($rst); 
        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
		$encodedPath = base64_encode(PATH . $lineReport->packFilePath);
		$qry = "select distinct packVersion,packDate as reportDate from " . TBL_OCIPACKS. " where ociTypeId = ".$lineReport->ociTypeId." and  packName = '" . implode(" ",explode("!",$packName)) . "'ORDER BY packVersion desc";
        $packVersionS = $this->db_fetch_assoc_array_my($qry);
		//$genTable .= '	';
		//echo $_SERVER['REQUEST_URI'];
           $pos = strpos($_SERVER['REQUEST_URI'],'ocipack-detail.shtml');
		 $ref = strpos($_SERVER['HTTP_REFERER'],'ociPackApproval.shtml');
		 
			 if ($pos !== false) {
			  if (strpos($_SERVER['HTTP_REFERER'],'ociPackApproval.shtml') !== false) {
		  $genTable .= '<section><a href="ociPackApproval.shtml" > < back </a></section> ';
		  }
		  else{
			$genTable .= '<section><a href="onCommandInsight.shtml?type='.$lineReport->ociTypeId.'&certifiedBy='.$lineReport->certifiedBy.'" > < back </a></section> ';
			}
			
		}
        if ($num > 0) {
            //Added dynamic divs and dynamic data
            $genTable .= ' <section class="details-pack">
							<h2>'. ucfirst($lineReport->packName).'</h2>
							<div class="version">Version :
							<span>'.$lineReport->packVersion.' </span>';
						  
						foreach ($packVersionS as $ver) {
													if ($ver['packVersion'] == $lineReport->packVersion) {} else 
													{
														$genTable .= '<a href="ocipack-detail.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$ver['packVersion'] .'"> ' . $ver['packVersion'] . '</a>';
													}
												}
																	
			$genTable .= '</div>	<div class="download-div">
			<div class="download-left">';
			
			if($downloadPg != '1'){
			/*$genTable .= '<a href="eula.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=performance" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a>';*/
			
			$genTable .= '<a href="eula.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=ocipack" class="myButton1 downlaod_btn">Download</a>';
			}
			$genTable .='</div>
			<div class="downlaod-right"> ';
			if ($lineReport->certifiedBy == "NETAPP") 
											{
												$genTable .= '<img src="images/netapp-certified-icon.png" width="20" height="20"  align="absmiddle"> This content is NetApp-Featured .'; 
											}
											else if($lineReport->certifiedBy == "NETAPPG")
											{
												$genTable .= '<img src="images/non-netapp-certified.png" width="20" height="20"  align="absmiddle"> This content is NetApp-Generated .';
											}
											else 
											{
												$genTable .= '<img src="images/non-netapp-certified.png" width="20" height="20"  align="absmiddle">This content is Community-Generated .';  
											}
			$genTable .= ' </div>
							</section>
						<section class="de-left-content"><article>
						<span>OCI Description</span><pre>
						' .html_entity_decode($lineReport->packDescription) . '
						</pre></article>
						<article>
						<span>OCI Type</span>
						<pre>' . $this->ociType($lineReport->ociTypeId) . '</pre>
						<span>Whats changed</span>
						<pre>'.$lineReport->versionChanges.'</pre>
						<br />
						<span>Pre-requisites</span>
						<pre>'.$lineReport->otherPrerequisite.'</pre></br>
						<ul><li> OCI Version: '.$lineReport->OCIVersion.'</li>
						</ul>';
						
			if(!empty($lineReport->previewFilePath))		
					{		
						$genTable .= '<br /><span>Preview Image </span><div id="dvPreview" class="dvPreview" style="display: inline-block;left: 0px !important;">'; 
						$genTable .= '<a target= "_blank" href="'.SITEPATH.$lineReport->previewFilePath.'"><img src="'.SITEPATH.$lineReport->previewFilePath.'" border="0" title="Preview" /></a>'; 
						$genTable .= '</div>';
					}
			else{
					//	$genTable .= '<img src="'.SITEPATH.'/images/no-preview.jpg" border="0" title="Preview" />';
				}		
				 
			$genTable .= '</article>
						<div class="download-left">';
						if($downloadPg != '1'){
						/*$genTable .= '<a href="eula.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=performance" name="download" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a>';*/
						
						$genTable .= '<a href="eula.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=ocipack" name="download" class="myButton1 downlaod_btn">Download</a>';
						}
						$genTable .='</div>';
						if($downloadPg == '1'){
						if (!empty($_REQUEST['captcha'])) {

                    if (!empty($_SESSION['captcha']) and trim(strtolower($_REQUEST['captcha'])) == $_SESSION['captcha']) {

                        $genTable .= '<div class="down" STYLE="padding-top: 0px; left: 21px; position: absolute; top: 104px;"><a href="download.shtml?packPath=' . base64_encode($lineReport->packFilePath) . ' &packName=' . implode("!", explode(" ", $lineReport->packName)) . '&packVersion=' . $lineReport->packVersion . '&packType=ocipack" name="download" id="packAction" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a></div>';

                        $flag = true;
                    }
                }
						$genTable .='<article> ';
					
						$flag = false;
								if (!empty($_REQUEST['captcha'])) {

								if (!empty($_SESSION['captcha']) and trim(strtolower($_REQUEST['captcha'])) == $_SESSION['captcha']) {					
									
									$genTable .= '<div class="download-left"><a href="download.shtml?packPath='.base64_encode($lineReport->packFilePath).' &packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=ocipack" name="download" id="packAction" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a></div>';	
									  
									$flag = true;	  
								}					
							}
							if(!$flag)	
							{								
							
								$genTable .= '<img src="captcha.shtml" id="captcha"/><br/>';

								$genTable .= '<a href="javascript:void(0);" onclick="
								document.getElementById(\'captcha\').src=\'captcha.shtml?\'+Math.random();
								document.getElementById(\'captcha-form\');"
								id="change-image">Change text </a> ( Note : Captcha is Case Sensitive )<br/> <br/>';

								$genTable .= '<div id="captchaButtons"><input type="text" name="captcha" id="captcha-form" autocomplete="off" autofocus/><br/><br/>';
								$genTable .= '<input type="submit" value="Submit Captcha" class="myButton"/></div>';
	
								if(!empty($_SESSION['captcha']) && !empty($_REQUEST['captcha'])){									
											$genTable .='<div class="captcha_error"><img src="images/error.png" width="16" border="0" height="16">&nbsp;&nbsp;&nbspInvalid captcha</div>';
											}
								if (isset($_REQUEST['captcha'])){			
								if (empty($_REQUEST['captcha'])){$genTable .='<div class="captcha_error"><img src="images/error.png" width="16" border="0" height="16">&nbsp;&nbsp;&nbspInvalid captcha</div>';}}
									
									
							}
						$genTable .='</article> ';	
						
						}
						
			$genTable .= '  </section>';
			$genTable .= '	<div class="de-right-content">
							<section class="de-right-div bt-btm">
							<article>
							<span>Version History</span>
							';
			foreach ($packVersionS as $ver)
							{
							$time = new DateTime($ver['reportDate']);
							$relaeseDate = $time->format('d M,Y');					
							$genTable .= '
											<div>Version:'.$ver['packVersion'].' </div>
											<div>Released on:'.$relaeseDate.'</div>
											<div>&nbsp;</div>';
							}
			$genTable .= '
							</article>	
							<article><span>Tags</span>';

							$packTags = explode(",",$lineReport->tags);

							$numTags = count($packTags);
							$countTag = 0;

							$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
							$user = (array)$userType;
							

							
							if(($user['0']==1) && $lineReport->post_approved=='false'){

								$genTable .='<input type="hidden" id="packName" name="packName" value="'.$lineReport->packName.'" />';
								$genTable .='<input type="hidden" name="version" id="version" value="'.$lineReport->packVersion.'" />';

								$genTable .='<div>';
								

								$genTable .='<div id="tags" style=" max-width: 240.777777671814px; min-height: 34.777777671814px;height:auto;clear:both;">';

								
								foreach($packTags as $key => $value)
								{
									if(!empty($value))
									{
										$genTable .= '<span class="tag">'.(strtolower($value)).'<span class="delete-tag" title="remove this tag"></span></span>';
									}

									
								} 


								$genTable .='<input type="text" tabindex="103" placeholder="" id="packTag" style="width: 200.777777671814px;height: 25px;" name="tags" placeholder="Add a tag" autocomplete="off"/><span></span></div> <br />
								 <input type="hidden" name="packTags" id="packsTag" />
								';
							}
							else{
								$genTable .='<div>';

								if(count($packTags) ==0)
								{
									$genTable .='No Tags found';
								}
								else
								{
									
									foreach($packTags as $key => $value)
									{
										$countTag ++;
										if($countTag == ($numTags-1) && !empty($value))
											$genTable .= '<a href="search-packs.shtml?search='.$value.'">#'.$value.'</a>';
										if($countTag < ($numTags-1) && !empty($value))
											$genTable .= '<a href="search-packs.shtml?search='.$value.'">#'.$value.'</a>, ';

										
									} 
								}	
							}
							
							$genTable .= '</div>';

							if(($user['0']==1) && $lineReport->post_approved=='false'){
								$genTable .='<br/><div id="loading"></div><input type="button" class="myButton" id="uploadpc-btn-oci" value="Update Tags"/><div class="form-error" id="wfaContactTagError"></div>';
							}
							$genTable .='</article><div>&nbsp;</div><span>Author</span>';
					if ($lineReport->certifiedBy == "NETAPP") {
					$genTable .= '<div>NetApp</div>';
					}
					else{
					$genTable .= '<div></div>';
					}
							/* 5 star rating code 
							 * to display total number of rating 
							 * and 
							 * average rating 
							 * for that pack/report - ASG
							 */
							$select_rating = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$lineReport->id."' AND ratingPackVersion='".$lineReport->packVersion."' AND ratingPackType='ocipack'");
							$total = mysql_num_rows($select_rating);
							while($row = mysql_fetch_array($select_rating))	{
								$ratearr[] = $row['ratingValue'];
							}
							$total_star_rating = round(array_sum($ratearr)/$total);
							$countStar = 0;
								/* merging star images to display */
								for($ab = 0; $ab < $total_star_rating; $ab++){									
									$imgPrint = $imgPrint.'<img src="images/star-fill-icon.png">';
									$countStar++;
								}
								while($countStar != 5){
									$imgPrint = $imgPrint.'<img src="images/star-blank-icon.png">';
									$countStar++;
								}
					$genTable .= '<div>&nbsp;</div>
							</article>
							<article><span>Rating</span>
							 '.$imgPrint.' <span class="display-star">('.$total.')</span>
							</article><br />';
							/* 5 star displayed end - ASG */
					$genTable .= '<article>
								<span>Contact</span>
								<div>'.$lineReport->authorName.'<br /><a target="_blank" href="mailto:'.$lineReport->authorEmail.'">'.$lineReport->authorEmail.'</a><br />'.$lineReport->authorContact.'<br /></div>
								
							</article>
							</section>';
			$genTable .= '	<div class="de-right-div">
								<p></p>';
						if((isset($_SESSION['uid'])) && ($_SESSION['uid'] != '') && ($downloadPg != '1')){
							// TBL_RATINGSTAR = rating,	 TBL_REPORTS = download_history								
								/* 
								 * 5 Star rating 
								 * code start ASG
								 */
								 
						$selcetUserRating = mysql_query("SELECT * FROM " .TBL_REPORTS." WHERE packId='".$lineReport->id."' AND packVersion='".$lineReport->packVersion."' AND userId='".$_SESSION['uid']."' AND packType='ocipack'");												
							$userRatedValue = mysql_num_rows($selcetUserRating);
														
							$userRatingEligibleqry = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$lineReport->id."' AND ratingPackVersion='".$lineReport->packVersion."' AND ratingPackType='ocipack' AND ratingBy='".$_SESSION['uid']."'");
							
							$userRatingEligible = mysql_num_rows($userRatingEligibleqry);
							
							if($userRatedValue > 0){
								if($userRatingEligible <= 0){
								$genTable .= '<article><span>Rate this content</span>
										<form name="ratingForm" id="ratingForm" method="post" action="ocipack-detail.php">
										<div class="div">										  
										  <input type="hidden" id="star1_hidden" value="1">
										  <img src="images/star-blank-icon.png" onmouseover="change(this.id);" id="star1" class="star">
										  <input type="hidden" id="star2_hidden" value="2">
										  <img src="images/star-blank-icon.png" onmouseover="change(this.id);" id="star2" class="star">
										  <input type="hidden" id="star3_hidden" value="3">
										  <img src="images/star-blank-icon.png" onmouseover="change(this.id);" id="star3" class="star">
										  <input type="hidden" id="star4_hidden" value="4">
										  <img src="images/star-blank-icon.png" onmouseover="change(this.id);" id="star4" class="star">
										  <input type="hidden" id="star5_hidden" value="5">
										  <img src="images/star-blank-icon.png" onmouseover="change(this.id);" id="star5" class="star">
										  	
										</div> 
										<input type="hidden" name="starrating" id="starrating" value="0" />
										<input type="hidden" name="starpackid" id="starpackid" value='.$lineReport->id.' />
										<input type="hidden" name="starpacktype" id="starpacktype" value="ocipack" />
										<input type="hidden" name="starpackversion" id="starpackversion" value='.$lineReport->packVersion.' />
										<input type="submit" value="Submit" name="submit_rating" id="submit_rating" style="display:none;">
									</form></article><br />';
								}else{
								$genTable .= '<article><span>Rate this content</span><div class="div">
								<img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><br />';
								$genTable .= "You already rated this content!</div></article><br />";
								}
							}else{
								$genTable .= '<article><span>Rate this content</span><div class="div">
								<img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><br />';
								$genTable .= "Download this content to rate!</div></article><br />";
							}	
						$genTable .= "<article><span>Feedback</span><a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup1' id='".$lineReport->id."'>Flag as inappropriate</a></article><br />";
						}
						
						/* Deprecation link Start - ASG */
							$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
						
							if($userType == 1 && $downloadPg != '1'){
								$genTable .= "<article><span>Deprecate</span>";	
							}
							
							$getDepData = $this->getDepData($lineReport->id, $lineReport->packVersion, 'oci');
							if($getDepData <= 0 ){
									
								if($userType == 1 && $downloadPg != '1'){
									$genTable .= "<a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup2' id='".$lineReport->id."'>Post Deprecation</a>";									
								}
							}else if($getDepData > 0){													
							$records = $this->getDepDataDetailed($lineReport->id, $lineReport->packVersion, 'oci');
																		
								if($userType == 1 && $downloadPg != '1'){
									$checkValue = (($records['flag'] == '1') ? 'Checked' : '');	
									$genTable .= 'Uncheck to Delete Comment: <input type="checkbox" name="deprecationFlag" class="deprecationFlag" onclick="setDeprecationFlagOci('. $lineReport->id .',\'' . $lineReport->packVersion . '\')"; ' .$checkValue. ' alt="Delete Comment"/> <span id="cautionLoader' . $lineReport->id . '"></span>';
								}
								if($records['depComment'] != ''){
									$genTable .= "<pre><font color='red'>".$records['depComment']."</font></pre></article>";
								}																  							
							}
						
							/* Deprecation link End - ASG */
							/*<a class="js-open-modal"  href="javascript:void(0)" data-modal-id="popup1">Flag as inappropriate</a>*/ 
						$genTable .= '	</div>	</div>';
        } else {
            $genTable = '<tr><td colspan="5" >No Records Found</td></tr>';
        }
		
        return $genTable;  
        }
	
	/*
	*	function to get number of Deprecation
	*	@param type string, id, version, type
	* 	@return int
	*	@Author - ASG
	*/
	function getDepData($id, $version, $type){	
		$depqry = mysql_query("SELECT * FROM ".TBL_DEPRECATED." WHERE depPackId='".$id."' AND depPackVersion='".$version."' AND depPackType='".$type."' AND flag='1'");
		$total = mysql_num_rows($depqry);
		
		return $total;			
	}
	
	/*
	*	function to get detail of Deprecation
	*	@param type string, id, version, type
	* 	@return array
	*	@Author - ASG
	*/
	function getDepDataDetailed($id, $version, $type){				
		$depRecordqry = mysql_query("SELECT * FROM ".TBL_DEPRECATED." WHERE depPackId='".$id."' AND depPackVersion='".$version."' AND depPackType='".$type."' AND flag='1'");
		$totalRecord = mysql_fetch_assoc($depRecordqry);			
		return $totalRecord;			
	}

	function getPackByTypeId($name, $version) {
       
            $query = "SELECT * from ocipacks where packName='" . $name . "' and packVersion='".$version."'";
        
			

        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        if ($numEntity > 0) {
            return $this->getResultObject($sql);
        }
    } 
	
	
	/**
     * insertOciDownloadDetails
     * Function for inserting data in download history table for every pack downloaded.
     * @param  packUuid
	 * @param  packVersion
	 * @param  typePack
	 * @param  packName
	 * @param  reportName
	 * @param  reportVersion
     * @return none
     *
     */  
    function insertOciDownloadDetails($packVersion, $typePack, $packName) {  
        $now = date('Y-m-d H:i:s');

        $this->tablename = TBL_REPORTS;   

        if (empty($_SESSION['uid'])) {
            $this->field_values['userId'] = 'oblixanonymous';
        } else {
            $this->field_values['userId'] = $_SESSION['uid'];
        }

        //$packId = $this->fetchValue(TBL_PACKDETAILS, "packName", " id = '" . $packId . "'"); 
		
		$packQuery = " SELECT * FROM ". TBL_OCIPACKS." where packName='".implode(" ",explode("!",$packName))."' && packVersion='".$packVersion."'";
									
        $packsql = $this->executeQry($packQuery);
        $packnum = $this->getTotalRow($packsql);
		$line = $this->getResultObject($packsql);
		 
		$packName		=	$line->packName;
		$packId			=	$line->id;
        $firstName 		= $_SESSION['firstName'];
        $lastName  		= $_SESSION['lastName'];
        $companyName 	= ucfirst($_SESSION['CompanyName']);
        $companyAddress = $_SESSION['companyAddress1']." ".$_SESSION['companyAddress2'];

        $this->field_values['packId'] 		    = $packId;
        $this->field_values['firstName'] 		= mysql_real_escape_string($firstName);
        $this->field_values['lastName'] 		= mysql_real_escape_string($lastName);
	    $this->field_values['packName'] 		= mysql_real_escape_string($packName);
 
        $this->field_values['packVersion']      = mysql_real_escape_string($line->packVersion);
        $this->field_values['minWfaVersion']      = mysql_real_escape_string($line->OCIVersion);

        $this->field_values['certifiedBy']      = mysql_real_escape_string($line->certifiedBy);

        $this->field_values['author']      = mysql_real_escape_string($line->author);
        $this->field_values['packDate']      = mysql_real_escape_string($line->packDate);

        $this->field_values['companyName']      = mysql_real_escape_string(ucfirst($_SESSION['CompanyName']));
        $this->field_values['companyAddress'] 	= mysql_real_escape_string($companyAddress);
		$this->field_values['packType'] 		= $typePack;
        $this->field_values['downloadDate'] 	= $now;
        
        $res = $this->insertQry();
    }
	
	function unapprovedPackFullInformation() {
        $cond = " 1=1 ";
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $_REQUEST['searchtxt'];
           
        }
		
        $cond .= " and post_approved ='false'";
        
        
        $query = " SELECT * FROM " . TBL_OCIPACKS . " WHERE $cond";

       
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);

        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;


        if ($num > 0) {
            
            $orderby = $_GET[orderby] ? $_GET[orderby] : "packDate";
            $order = $_GET[order] ? $_GET[order] : "DESC";
            $query .= " ORDER BY $orderby $order ";
            $sql = $this->executeQry($query);
            if ($num > 0) {

                
                while ($line = $this->getResultObject($sql)) {

                    $highlight = $i % 2 == 0 ? "main-body-bynic" : "main-body-bynic2";
                    $div_id = "status" . $line->id;

                    if (strtolower($line->certifiedBy) == 'netapp') {
                        $class = 'certifiedImage';
                    } else {
                        $class = '';
                    }

                    $certifiedmaxVersion=$line->certifiedBy;
                    $genTable .= '<tr>';

                    if (strtolower($certifiedmaxVersion) == 'netapp') 
                    {
                        $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';
                    }
                    else
                    {
                        $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                    }

                    $userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");   
                    
                    $user = (array)$userType;

                   $ociType= $this->ociType($line->ociTypeId);
				   

                   $genTable .= '           
                                           <td><a href="ocipack-detail.shtml?packName=' . implode("!", explode(" ", $line->packName)) . '&packVersion=' . $line->packVersion . '">'.$line->packName.'</a></td>';
                                                                    
                                            $genTable .= '<td>' .$line->packVersion. '</td>';
                                           
                                            $genTable.='
                                            <td>' .$line->OCIVersion.'</td>';
											
					$genTable.='<td>' .$ociType.'</td>';
											 
                                           
                                         
                                        
                                        $encodedPath = base64_encode($line->packFilePath);
                                        $genTable .= '          
                                            <td>' .$line->authorName . '</td>
                                            <td>' . date('d M, Y', strtotime($line->packDate)) . '</td>';
                                            

                                            $genTable .="<td><a class='js-open-modal'   href='javascript:void(NULL);' data-modal-id='popup1' id='".$line->id."'><img src='images/close_small.png' height='16' width='16' border='0' title='Reject' alt='Reject' /></a></td>";

                                            $genTable .="<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure you want to approve this content.  ?')){window.location.href='pass.shtml?action=approveOciPack&type=approve&id=".$line->id."'}else{}\" ><img src='images/success.png' height='16' width='16' border='0' title='Approve' alt='Approve' /></a></td>";
                                            $genTable .='<td><a href="eula.shtml?packName='.implode("!",explode(" ",$line->packName)).'&packVersion='.$line->packVersion.'&packType=ocipack"><img src="images/downlaod-icon.png" border="0" title="Download"/></a></td>';

                    $cryptKey = 'sxcf10';
                    $encodedPath = base64_encode($line->packFilePath);
                    
                   // $genTable .= '</tr>';
                    $i++;
                }
            
                $genTable .= '</tr>';
            }
        } else {
           // $this->log->LogInfo("No records found to be displayed- inside " . __FUNCTION__);
             $genTable = '<tr><td colspan="9" >Sorry no records found</td></tr>';
        }
        return $genTable;
    }
	
	function approvePack($get)
    {

         $sendMailObj = new SendMailClass();
        $query = "UPDATE ".TBL_OCIPACKS." set post_approved = 'true' where id='$get[id]'";

        $this->executeQry($query);       
        
        $_SESSION['SESS_MSG'] = msgSuccessFail("success","OCI content has been approved successfully!!!");
		
		$packQuery="Select * from ".TBL_OCIPACKS." where id='$get[id]'";
		$packSql=$this->executeQry($packQuery);
		$line = $this->getResultRow($packSql);
	
		
				$typename = $this->ociType($line['ociTypeId']);
			//die('sdsd'); */	

		
		
		solrOciSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', $line, $line['tags'], $typename,'');
              
       
		$packUserinfo = $this->getUserDetailByociPackID($line['id']);
		
		
        $userQry = "SELECT u.* FROM " . TBL_USER . " as u INNER JOIN " . TBL_ADMINUSER . " as a on u.userName = a.userName WHERE a.userType = 1 and u.receiveMail = true and u.email != '".$packUserinfo['userEmail']."' " ;
		
	
        $mailsql = $this->executeQry($userQry);
        $mailNum = $this->getTotalRow($mailsql);


        if ($mailNum > 0) {

            while ($userline = $this->getResultObject($mailsql)) {


                $subject = "New OCI content upload Notification: " . $line['packName'];
                $message = '<html><body>';
                $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
                $message .= "<tr><td><strong>Hello " . ucfirst($userline->firstName) . " " . ucfirst($userline->lastName) . ",</strong> </td></tr>";
                $message .= "<tr style='background: #eee;'><td>" . $subject . "</td></tr>";
                $message .= "<tr style='background: #eee;'><td>A new OCI Content has been approved .</td></tr>";
                $message .= "<tr style='background: #eee;'><td>Name: " . $line['packName'] . ".</td></tr>";
                $message .= "<tr style='background: #eee;'><td>Description: <pre><strong>" . htmlentities($line['packDescription']) . ".</strong></pre></td></tr>";
                $message .= "</table>";
                $message .= '<br>Thank you <br></br>
                        *** This is an automatically generated email, please do not reply ***';
                $message .= "</body></html>";


                 $this->sendEMail($message, $subject, $userline->email);
            }
        }
        /* Mail send to Pack user to inform that pack is approved
         */
      
        if (!empty($packUserinfo)) {
            $userEmail = $packUserinfo['userEmail'];
            $userFname = $packUserinfo['firstName'];
            $userLname = $packUserinfo['lastName'];

            $subject = "New OCI content upload Notification: " . $line['packName'];
            $message = '<html><body>';
            $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
            $message .= "<tr><td><strong>Hello " . ucfirst($userFname) . " " . ucfirst($userLname) . ",</strong> </td></tr>";
            $message .= "<tr style='background: #eee;'><td>" . $subject . "</td></tr>";
            $message .= "<tr style='background: #eee;'><td>Your OCI Content has been approved .</td></tr>";
            $message .= "<tr style='background: #eee;'><td>Name: " . $line['packName'] . ".</td></tr>";
            $message .= "<tr style='background: #eee;'><td>Description: " . htmlentities($line['packDescription']) . ".</td></tr>";
            $message .= "</table>";
            $message .= '<br>Thank you <br></br>
                        *** This is an automatically generated email, please do not reply ***';
            $message .= "</body></html>";


            $this->sendEMail($message, $subject, $userEmail);
        }
        
        
		/* Mail send to All admin to inform that pack is approved
		*/
		
		
		
        echo "<script language=javascript>window.location.href='ociPackApproval.shtml';</script>";
       exit;
        
    }
	
	/**
     *
     * Function addUser for entering data about the user during upload.
     *
     * @param    string packId
     * @return   
     *
     */
    function addUser($packId, $typeId)
    {
	
		
        $now = date('Y-m-d H:i:s');

        $this->tablename = TBL_OCIPACKUSER;
        $this->field_values['packId'] = $packId;
        $this->field_values['firstName'] = $_SESSION['firstName'];
        $this->field_values['lastName'] =  $_SESSION['lastName'];
        $this->field_values['userId'] = $_SESSION['uid'];
        $this->field_values['userEmail'] = $_SESSION['mail']; 
        //$_SESSION['mail'] = $_SESSION['userEmail'];
        $this->field_values['uploadDate'] = $now;
		$ociType = $this->ociType($typeId);
		$this->field_values['ociType']=$ociType;

        $this->insertQry();
		
    }
	
	function getUserDownloadedOCIPacks()
    {
        $cond = " 1=1 ";
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $_REQUEST['searchtxt'];
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }

       
       //$query= "SELECT * FROM ".TBL_REPORTS." join(SELECT MAX( packVersion ) AS Vsion,uuid AS Uid FROM ".TBL_REPORTS." where userId='".$_SESSION['uid']."' GROUP BY uuid )mvx ON packVersion = mvx.Vsion and uuid = mvx.Uid where userId = '".$_SESSION['uid']."' GROUP BY uuid";
	   
	  // $query = "Select * from ".TBL_REPORTS." where userId = '".$_SESSION['uid']."' ";

     
       $query = " SELECT * FROM " . TBL_REPORTS . " where packType = 'ocipack' and  userId = '".$_SESSION['uid']."' order by downloadDate desc";
	 

        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);
		
		

        if ($num > 0) {
            
                while ($line = $this->getResultObject($sql))
                {
                        
						
						/* $typequery = "SELECT ociTypeId from ocipacks where id='".$line->packId."'";
						
						$typeSql= $this->executeQry($typequery);
						$typeline = mysql_fetch_row($typeSql);
						
						
						$ociType= $this->ociType($typeline[0]); */
                  
                 if ($line->certifiedBy == 'NETAPP' ) {
                    $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                                                        } else {
                                                                    $genTable .= '<td ><img src="images/non-netapp-certified.png" /></td>';
                                                                }
                                $genTable .= '          
                                            <td>'.$line->packName.'</td> 
                                            <td>' .wrapText($line->packVersion, 10) . '</td>';
                                            //<!--<td><a href="javascript:void(0)" class="trigger">' .$line->minWfaVersion.'</a>-->
                                            
                   
                    $genTable.=' <td>'.$line->certifiedBy.'</td>';
                   

                                            
                    
                                        $genTable .= '
                                            </td>

                                            <td>' . date('d M, Y', strtotime($line->packDate)) . '</td>
                                            <td>'.$line->firstName.' '.$line->lastName.'</td><td></td></tr>';
                          
                    
                    $i++;
                }

            
                
              //  echo $genTable;exit;
            
        } else {
           // $this->log->LogInfo("No records found to be displayed- inside " . __FUNCTION__);
            $genTable = '<tr><td colspan="9" >Sorry no records found</td></tr>';
        }


        return $genTable;

    }
	
    /*
     * Get Packs list of Rejected Packs By Admin
     * Author: Asmita
     */
    function getRejectedOCIPackData()
    {
		
        $query = " SELECT distinct  * FROM rejectedPacks WHERE packType = 'ocipack' and userEmail = '".$_SESSION['mail']."' ";   
       
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);
        


        if ($num > 0) {
            //-------------------------Paging------------------------------------------------           
            
            $orderby = $_GET[orderby] ? $_GET[orderby] : "rejectDate";
            $order = $_GET[order] ? $_GET[order] : "DESC";
            $query .= " ORDER BY $orderby $order";
            
            
            $sql = $this->executeQry($query);
            
            if ($num > 0) {

                $i = $offset + 1;
                while ($line = $this->getResultObject($sql)) {
				
                $genTable .= '<tr>';
                    
                  
                                if ($line->certifiedBy == 'NETAPP' ) {
                    $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                                                        } else {
                                                                    $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                                                                }
                                $genTable .= '          
                                            <td>'.$line->packName.'</td> 
                                            <td>' .$line->adminName. '</td>
                                           <td>' .$line->adminEmail.'</td>';
                                          
                                                    
                              
                                      $genTable .=' <td>' .$line->adminComments. '</td>
                                            
                                            <td>' . date('d M, Y', strtotime($line->rejectDate)) . '</td>
                                            <td>'.$line->author.'</td><td></td>';

                    $i++;
                }
                  $genTable .= '</table>';
                
            }
        } else {
           // $this->log->LogInfo("No records found to be displayed- inside " . __FUNCTION__);
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
        return $genTable;
    }

    /*
     * Reject Pack by admin
     * author: Asmita
     */
    function rejectOCIPack($post)
    {
	  $sendMailObj = new SendMailClass();
        $packFilePath = $this->fetchValue(TBL_OCIPACKS,"packFilePath","id = '$post[id]'");
		$query="SELECT * from ".TBL_OCIPACKS." WHERE id='$post[id]' ";

        
        
        $sql = $this->executeQry($query);
        $line = $this->getResultRow($sql);

        $packQuery = "SELECT * from ".TBL_OCIPACKUSER." WHERE packId='$post[id]'";
        $packSql = $this->executeQry($packQuery);
        $packLine = $this->getResultRow($packSql);
        
        $packName=$line['packName'];
        $packVersion= $line['packVersion'];
         
        $this->tablename = TBL_REJECTEDPACKS;
        $this->field_values['packName'] = $packName;
         $this->field_values['packType'] = 'ocipack';
        $this->field_values['userEmail'] = $packLine['userEmail'];
        $this->field_values['firstName'] = $packLine['firstName'];
        $this->field_values['lastName']= $packLine['lastName'];
       // $this->field_values['companyName']= $_SESSION['CompanyName'];
        $this->field_values['adminComments']=  mysql_real_escape_string($post['comment']);
        $this->field_values['adminName'] = $_SESSION['firstName'].' '.$_SESSION['lastName'];
        $this->field_values['adminEmail'] = $_SESSION['mail'];
        $this->field_values['rejectDate'] = date('Y-m-d H:i:s');
       
        $this->insertQry();

						$subject = "OCI Content Rejected";
                        $message = '<html><body>'; 
                        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
                        $message .= "<tr><td><strong>Hello ".ucfirst($packLine['firstName'])." ".ucfirst($packLine['lastName']).",</strong> </td></tr>";
                        $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
                        $message .= "<tr style='background: #eee;'><td>Your OCI Content has been rejected by the Store Administrator. Below are the comments from the Administrator : </td></tr>"; 
                        $message .= "<tr style='background: #eee;'><td>Name: " . $packName. "</td></tr>";
                        $message .= "<tr style='background: #eee;'><td>Description: " . htmlentities($line['packDescription']). "</td></tr>";
                        $message .= "<tr style='background: #eee;'><td>Type: OCI Type (".$this->ociType($line['ociTypeId']).")</td></tr>";
                        $message .= "<tr style='background: #eee;'><td>" . $post['comment']. "</td></tr>";
                        $message .= "</table>";
                        $message .= '<br>Thank you <br></br>*** This is an automatically generated email, please do not reply ***';
                        $message .= "</body></html>"; 

                        $mailResult = $sendMailObj->sendEMail($message, $subject, $packLine['userEmail']);
			//$mailResult = $sendMailObj->sendEMail($message, $subject, 'asmita.sharma@netapp.com');

      
	     

        if(!empty($packFilePath)){
           
            $path_parts = pathinfo(PATH.$packFilePath);


            $unlinkDir = $path_parts['dirname'];
            
            rrmdir($unlinkDir);
            
            $packId=$post['id'];
			$sql = " DELETE FROM ".TBL_OCIPACKS." WHERE id = '$post[id]' ";

          
            $rst = $this->executeQry($sql);
            if($rst)
            {
               $_SESSION['SESS_MSG'] = msgSuccessFail("success","OCI content has been rejected successfully!!!");
              
            }
           
         
            
        }
        $this->log->LogInfo("Pack with packId: " . $get[id] . " deleted successfully");
        
        
        echo "<script language=javascript>window.location.href='ociPackApproval.shtml';</script>";
    }
	
	function getUserPacks() {
       $cond = " 1=1 ";
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $_REQUEST['searchtxt'];
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }

         $query = " SELECT pd.* FROM " . TBL_OCIPACKS . " as pd inner join ".TBL_OCIPACKUSER." on pd.id= ".TBL_OCIPACKUSER.".packId and ".TBL_OCIPACKUSER.".userId='".$_SESSION['uid']."' where pd.post_approved='false' order by packDate desc ";
		 
		 /* echo $query;
		 exit; */  

        
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);

        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
        


        if ($num > 0) {
            

            $orderby = $_GET[orderby] ? $_GET[orderby] : "packDate";
            $order = $_GET[order] ? $_GET[order] : "DESC";
          //  $query .= " ORDER BY $orderby $order LIMIT " . $offset . ", " . $recordsPerPage;
            $sql = $this->executeQry($query);
            if ($num > 0) {

                $i = $offset + 1;
                while ($line = $this->getResultObject($sql))
                {
                   

                    $genTable .= '<tr>';
                    
                  
                    /* else
                    {
                        $genTable .= '<td></td>';


                    } */
                     if ($line->certifiedBy == 'NETAPP' ) {
                    $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                                                        } else {
                                                                    $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                                                                }
                                $genTable .= '          
                                            <td><a href="ocipack-detail.shtml?packName=' . implode("!", explode(" ", $line->packName)) . '&packVersion=' . $line->packVersion . '">'.$line->packName.'</a></td>';
                                            //<!--<td><a href="javascript:void(0)" class="trigger">' .$line->minWfaVersion.'</a>-->
                                           
                                            $genTable.='<td>' .$line->packVersion.'</td>';
                                            $genTable.='<td>' .$line->OCIVersion.'</td>';
                                            
                                            
                                  $genTable.='<td>' . date('d M, Y', strtotime($line->packDate)) . '</td>';
                                            
                                            
                                        
                    $cryptKey = 'sxcf10';
                    $packPath = htmlspecialchars($line->packFilePath, ENT_QUOTES, 'UTF-8');
                    $encodedPath = base64_encode($packPath);

                    $newId = htmlspecialchars($line->id, ENT_QUOTES, 'UTF-8');

                        $genTable .= "<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure to delete this Record  ?')){window.location.href='pass.shtml?action=manageOciPack&type=delete&idToDel[]=" . $line->id . "&page=user_ociprofile'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Delete' alt='Delete' /></a></td>";
                        
                        $genTable .= '<td><a href="eula.shtml?packName='.implode("!",explode(" ",$line->packName)).'&packVersion='.$line->packVersion.'&packType=ocipack" name="download" class="packAction"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td></tr>';

                       
                    $i++;
                }
                
                //echo $genTable;exit;
            }
        } else {
           // $this->log->LogInfo("No records found to be displayed- inside " . __FUNCTION__);
            $genTable = '<tr><td colspan="9" >Sorry no records found</td></tr>';
        }


        return $genTable;
    }
	
	 /*
     * Function for updating tags of pack uploaded by user.
     *
     * @param    post data
     * @return   
     * @author   Arun Verma
     *
     */
     function updateTag($post)
     {
       
	   $name= str_replace('!', ' ', $post['name']);
         $query = "Update ".TBL_OCIPACKS." set tags = '".$post['tags']."' where packName = '".$name."' and packVersion = '".$post['version']."' ";
		 
		 
		 
		 $this->executeQry($query);

         $_SESSION['SESS_MSG'] = msgSuccessFail("success","Tag has been updated successfully!");
         

     }
	 
  function sendEMail($message, $subject, $to){  
    $mail = new PHPMailer(); 
    $mail->SMTPAuth   = true;
    $mail->Host       = "smtp.netapp.com";
    $mail->Port       = 25; 

    $mail->SetFrom('no-reply@netapp.com', 'No Reply');
	
    $mail->AddAddress($to, ''); 
   
    $mail->Subject = $subject ; 

    $mail->MsgHTML($message); 

    $mail->Send();

      
    }
	
	function adminEmailNotify($post){	    	
	
		$now = date('Y-m-d H:i:s');
		
		
		$subject = "New OCI Content upload Notification: ". $post['oci_name'];	
	 	
		// Email to all admin (adminlogin table)
		//$to = "ng-store-admins@netapp.com";
		$to= "ng-store-admins@netapp.com";
            $message = '<html><body>'; 
            $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
            $message .= "<tr><td><strong>Hello Administrator,</strong> </td></tr>";
            $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
            $message .= "<tr style='background: #eee;'><td>A new OCI Content has been uploaded.</td></tr>"; 
            $message .= "<tr style='background: #eee;'><td>Name: " . $post['oci_name'] . ".</td></tr>"; 
            $message .= "<tr style='background: #eee;'><td>Description: " . $post['oci_desc'] . ".</td></tr>"; 

            $message .= "</table>";
            $message .= '<br>Thank you <br></br>
            *** This is an automatically generated email, please do not reply ***';
            $message .= "</body></html>";    
			$mailresponseAdmin = $this->sendEMail($message, $subject, $to);		
		
		return $mailresponseAdmin;
	}

	/* Function for star rating (WFA/Report/Performance)
		 * @params type array $post
		 * @author ASG
		 */
		function addStarRating($post){
			//print_r($post);exit;
			
			$now = date('Y-m-d H:i:s');
			$this->tablename = TBL_RATINGSTAR;
			
			$userRatingEligibleqry = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$post['starpackid']."' AND ratingPackVersion='".$post['starpackversion']."' AND ratingPackType='".$post['starpacktype']."' AND ratingBy='".$_SESSION['uid']."'");						
			$userRatingEligible = mysql_num_rows($userRatingEligibleqry);
				if($userRatingEligible <= 0){
					 $this->field_values['ratingValue']         =  $post['starrating'];
					 $this->field_values['ratingPackVersion']   =  $post['starpackversion'];
					 $this->field_values['ratingPackId']        =  $post['starpackid'];
					 $this->field_values['ratingPackType']      =  $post['starpacktype'];
					 $this->field_values['ratingBy']        	=  (isset($_SESSION['uid']) ? $_SESSION['uid'] : '');
					 $this->field_values['ratingDate']        	=  $now;
					 
						$res = $this->insertQry();
						if($res){
							$_SESSION['SESS_MSG'] = msgSuccessFail("success","Rating has been saved successfully!");
						}else{
							$_SESSION['SESS_MSG'] = msgSuccessFail("error","Rating can not be saved!");
						}
				}					
		}
		
		
		/* Function to get any OPM pack id based on name and version
		* @param type string, string
		* @return string	
		* (Start block -> @Author ASG)
		*/
		function getPackIdOCI($name, $version){	  
			$idqry = mysql_query("SELECT id FROM ".TBL_OCIPACKS." WHERE packName = '".$name."' AND packVersion='".$version."'"); 
			$Idsres = mysql_fetch_array($idqry); 
			$Id = $Idsres['id']; 
			return $Id; 
		}
	
		/* Function for add Deprecate (WFA/Report/Performance)
		 * @params type array $post
		 * @author ASG
		 */
		function addDeprecateOci($post){		 	
			
			$now = date('Y-m-d H:i:s');
			$this->tablename = TBL_DEPRECATED; 
			
			$detailPageUrl = $post['detailPageUrl'];
			if(!empty($_SESSION['uid']))    {
				$userExist = $this->getUserExistOci();				
			}else{
				$_SESSION['SESS_MSG'] = msgSuccessFail("error","Deprecation can not be posted!");			
				header('Location:'.$detailPageUrl);
				exit;
			}
			if($userExist == 0){
				$_SESSION['SESS_MSG'] = msgSuccessFail("error","Deprecation can not be posted!");			
				header('Location:'.$detailPageUrl);
				exit;
			}
			
					 $this->field_values['depComment']        	=  mysql_real_escape_string($post['depComment']);
					 $this->field_values['depPackVersion']   	=  $post['depPackVersion'];
					 $this->field_values['depPackId']        	=  $post['depPackid'];
					 $this->field_values['depPackType']      	=  $post['depPackType'];
					 $this->field_values['depBy']       		=  (isset($_SESSION['uid']) ? $_SESSION['uid'] : '');
					 $this->field_values['depDate']        		=  $now;
					 $this->field_values['flag']        		=  '1';
					 
						$res = $this->insertQry();
						if($res){
							$_SESSION['SESS_MSG'] = msgSuccessFail("success","Deprecation has been posted successfully!");
						}else{
							$_SESSION['SESS_MSG'] = msgSuccessFail("error","Deprecation can not be posted!");
						}
				header('Location:'.$detailPageUrl);
				exit;
		}
	
	 /*
     * Function for fetching number of records of the user.
     *
     * @param    string plain Text
     * @return   int type
     * @author   ASG
     *
     */
	 function getUserExistOci(){		 
		$sessionuid = trim($_SESSION['uid']);	 
		 if(empty($sessionuid)){			 
			 return 0;
		 }else{
			 $query = "SELECT * FROM ".TBL_USER." WHERE username = '".$sessionuid."'";
			 $sql = $this->executeQry($query);
			 $line = $this->getTotalRow($sql);
			 return $line;
		 }

     }
	 
	 
	 	/*
		* function for deprecation deletion
		* @author - ASG
		*/
		function setDeprecationFlagOci($post){		 	
			$this->tablename = TBL_DEPRECATED;							
				
				$updateQry = mysql_query("DELETE FROM ".TBL_DEPRECATED." WHERE depPackVersion ='". $post['packVesion']."' AND depPackId ='".$post['packId']."' AND depPackType='".$post['packtype']."'");
			
			if($updateQry){ 
				$_SESSION['SESS_MSG'] = msgSuccessFail("success","Deprecation has been deleted successfully!");
				return true;
			}else{ 
				$_SESSION['SESS_MSG'] = msgSuccessFail("error","Deprecation can not be deleted!");
				return false;
			}
		}
		
		/*
     * function to report a grievance(flag as inapropriate)
     * @params type array $post
     * @return type bool
     * @author ASG
     */  
    function addGrievanceOci($post){   

	/* echo "SELECT authorEmail FROM ".TBL_OCIPACKS." WHERE packName = '".$post['packName']."' AND version='".$post['packVersion']."'";exit; */
	
	
	$contactEmail = $this->getPackContactEmail($post['packName'],$post['packVersion']);
	$ownerEmail = $this->getPackOwnerEmail($post['packName'],$post['packVersion']);
	

	
	
		$detailPageUrl = $post['detailPageUrl'];
		if(!empty($_SESSION['uid']))    {
			$userExist = $this->getUserExist();
			
		}else{
			$_SESSION['SESS_MSG'] = msgSuccessFail("error","Flag has not been reported!");
			return 0;
			header('Location:'.$detailPageUrl);
		}
		if($userExist == 0){
			$_SESSION['SESS_MSG'] = msgSuccessFail("error","Flag has not been reported!");
			return 0;
			header('Location:'.$detailPageUrl);
		}
        $now = date('Y-m-d H:i:s');
        $this->tablename = TBL_REPORTFLAG;		

		
		$packname = flagReplace(trim($post['packName'])); 
		$flagcomment = flagReplace(trim($post['flagComment'])); 
		$packversion = flagReplace(trim($post['packVersion']));
		$packtype = flagReplace(trim($post['packType']));
		
		
		
		if($packname != 0 || $flagcomment != 0 || $packversion != 0 || $packtype != 0 || (!isset($_SESSION['uid']))){
			$_SESSION['SESS_MSG'] = msgSuccessFail("error","Flag has not been reported!");
			echo "<script>alert('Illlegal tags are not allowed!');</script>";
			return 0;
            header('Location:'.$detailPageUrl);
		}
        //$adminEmail = array("ashutosh.garg@netapp.com", "ashish.joshi@netapp.com", "arun.verma@netapp.com");
                        
        /*$adminqry = "SELECT emailId FROM ".TBL_ADMINUSER." WHERE userType ='1'";
        $sql = $this->executeQry($adminqry); */   
                        
       
		$this->field_values['flagPackUuid'] 		=	'';
			            
        $this->field_values['flagPackName']         =   mysql_real_escape_string($post['packName']);
        $this->field_values['flagPackVersion']      =   $post['packVersion'];        
        $this->field_values['trademark']            =   (($post['checkTrademark'] == 'true') ? 'true' : 'false');
        $this->field_values['infringement']         =   (($post['checkInfringement'] == 'true') ? 'true' : 'false');
        $this->field_values['flagComment']          =   mysql_real_escape_string($post['flagComment']);
        $this->field_values['flagPackType']         =   $post['packType'];
        $this->field_values['flagBy']               =   $_SESSION['uid'];
		
		$this->field_values['flagPackOwner']        =   $ownerEmail;
		
        $this->field_values['flagDate']             =   $now;
        $this->field_values['flagStatus']           =   '1';
	
                $res = $this->insertQry();
				
				$name= explode("@",$contactEmail);
				$contactName = explode(".",$name[0]);
				
				$owner = explode("@",$ownerEmail);
				$ownerName = explode(".",$owner[0]);
				
						
						if(!empty($contactName)){
							$contactMessage = '<html><body>'; 
							$contactMessage .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
							$contactMessage .= "<tr><td><strong>Hello ".ucfirst($contactName[0])." ".ucfirst($contactName[1]).",</strong> </td></tr>";
						}else
						{
							$contactMessage = '<html><body>'; 
							$contactMessage .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
							$contactMessage .= "<tr><td><strong>Hello ,</strong> </td></tr>";
						}
						
						if(!empty($ownerName)){
							$ownerMessage = '<html><body>'; 
							$ownerMessage .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
							$ownerMessage .= "<tr><td><strong>Hello ".ucfirst($ownerName[0])." ".ucfirst($ownerName[1]).",</strong> </td></tr>";
						}
						else
						{
							$ownerMessage = '<html><body>'; 
							$ownerMessage .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
							$ownerMessage .= "<tr><td><strong>Hello ,</strong> </td></tr>";
						}
				
                    if($res){
                        /* Email to creator of pack */
                        $subject = "Grievance reported: ". $post['packName'];
                        $message='';
                        $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
                        $message .= "<tr style='background: #eee;'><td>A grievance has been reported for your " . $post['packType'] . ".</td></tr>"; 
                        $message .= "<tr style='background: #eee;'><td>Name: " . $post['packName'] . ".</td></tr>"; 
                        $message .= "<tr style='background: #eee;'><td>Comment: " . $post['flagComment'] . ".</td></tr>";                                                                   
                        $message .= "</table>";
                        $message .= '<br>Thank you <br></br>
                        *** This is an automatically generated email, please do not reply ***';
                        $message .= "</body></html>"; 
                                                
                        $creatorEmail = "SELECT * FROM ".TBL_USER." WHERE email='".$contactEmail."' AND receiveMail= 'true'";		
							
						$mailsql = $this->executeQry($creatorEmail);  
						$mailNum = $this->getTotalRow($mailsql);
						
						
					 
						$ownerEmail = "SELECT * FROM ".TBL_USER." WHERE email='".$ownerEmail."' AND receiveMail= 'true'";
						
						$mailowner = $this->executeQry($ownerEmail); 
						$mailNumOwner = $this->getTotalRow($mailowner);
						
						$contactMessage .= $message;
								$mailresponseUser = $this->sendEMail($contactMessage, $subject, $contactEmail);
						
						$ownerMessage .= $message;
								$mailresponseOwner = $this->sendEMail($ownerMessage, $subject, $ownerEmail);
						
                        
                        /* Email to all admin (Test array)*/
                        /*foreach($adminEmail as $admEmail => $emailval){
                            $mailresponseAdmin = $this->sendEMail($message, $subject, $emailval);
                        }*/
                        /* Email to all admin (adminlogin table)*/
                        /*while($adminEmail = mysql_fetch_assoc($sql)){
                            $mailresponseAdmin = $this->sendEMail($message, $subject, $adminEmail['emailId']);      
                        }*/
						//$adminEmail = "ng-store-admins@netapp.com"; 
						$adminEmail = "ng-store-admins@netapp.com"; 
						 $adminMessage = '<html><body>'; 
							$adminMessage .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
							$adminMessage .= "<tr><td><strong>Hello Administrator,</strong> </td></tr>";
							
							$adminMessage .= $message; 
						
						$mailresponseAdmin = $this->sendEMail($adminMessage, $subject, $adminEmail);						
                        
                    $_SESSION['SESS_MSG'] = msgSuccessFail("success","Flag has been reported!");
                        return 1;
                    }else{
                    $_SESSION['SESS_MSG'] = msgSuccessFail("error","Flag has not been reported!");
                        return 0;
                    }
					header('Location:'.$detailPageUrl);
					exit;
    }
	
	/* Function to get any pack name based on uuid and version
	* @param type string
    * @return string   
	* @Author 
	*/
   function getPackContactEmail($packName, $packVersion){
	
		
            $emailQry = mysql_query("SELECT authorEmail FROM ".TBL_OCIPACKS." WHERE packName = '".$packName."' AND packVersion='".$packVersion."'"); 
            $packContactEmail = mysql_fetch_array($emailQry);
			//print_r($packContactEmail);exit;
            $email = $packContactEmail['authorEmail']; 
            return $email;
   }
   
   /* Function to get any pack owner email based on uuid and version
	* @param type string
    * @return string   
	* @Author ASG
	*/
   function getPackOwnerEmail($packName, $packVersion){
			$packid = $this->getPackId($packName, $packVersion);			
            $emailQry = mysql_query("SELECT userEmail FROM ". TBL_OCIPACKUSER ." WHERE packId = '".$packid."'"); 
            $packContactEmail = mysql_fetch_array($emailQry);
            $email = $packContactEmail['userEmail']; 
            return $email;
   }
   
   /* Function to get any pack id based on uuid and version
	* @param type string
    * @return string	
	* (Start block -> @Author ASG)
	*/
   function getPackId($packName, $packVersion){
   
				
				//echo $query;exit;
            $idqry = mysql_query("SELECT id FROM ".TBL_OCIPACKS." WHERE packName = '".$packName."' AND packVersion='".$packVersion."'"); 
           
		
		   $packIdsres = mysql_fetch_array($idqry);
		   
		  
            $packId = $packIdsres[0]; 
            return $packId;
   }
   /* Function to get any pack id based on uuid and version
	* @param type string
    * @return string	
	* (Start block -> @Author ASG)
	*/
   function getOciTypeIdByPackId($packid){
   
            $idqry = mysql_query("SELECT ociTypeId FROM ".TBL_OCIPACKS." WHERE id = '".$packid."' "); 
           
		
		   $packIdsres = mysql_fetch_array($idqry);
		   
		  if(!empty($packIdsres)){
            $packtypeId = $this->ociType($packIdsres[0]); 
            return $packtypeId;
			}
			else{
			return "NA";
			}
   }
    function getUserExist()
     {
		 if(trim($_SESSION['uid']) == ''){
			 return 0;
		 }else{
			 $query = "SELECT * FROM ".TBL_USER." WHERE username = '".$_SESSION['uid']."'";
			 
			 $sql = $this->executeQry($query);
			 $line = $this->getTotalRow($sql);
			 
			
			 return $line;
		 }

     }
   
   
	function getDownloadReports($type)
    {
        
   
          $query ="SELECT * FROM ".TBL_REPORTS." where packType = '".$type."' ORDER BY downloadDate DESC ";
	
    
        
        
        $sql = $this->executeQry($query);
        $num=$this->getTotalRow($sql);
    
        if($num > 0) {          
        
            
        
            //-------------------------Paging------------------------------------------------           
            $paging = $this->paging($query); 
            if(isset($_GET[limit]))
            {
                $this->setLimit($_GET[limit]);          
                $recordsPerPage = $this->getLimit(); 
            }
            else{
                $this->setLimit(10);
                $recordsPerPage = 10;
            }
            $offset = $this->getOffset($_GET["page"]); 
            $this->setStyle("redheading"); 
            $this->setActiveStyle("smallheading"); 
            $this->setButtonStyle("boldcolor");
            $currQueryString = $this->getQueryString();
            $this->setParameter($currQueryString);
            $totalrecords = $num;//$this->numrows;
            
            $totalpage = $this->getNoOfPages();
            $pagenumbers = $this->getPageNo();      

            //-------------------------Paging------------------------------------------------
        //  $recordsPerPage  = 10;
            $orderby = $_GET[orderby]? $_GET[orderby]:"packDate";
            $order = $_GET[order]? $_GET[order]:"DESC";   
            $query .=  " LIMIT ".$offset.", ". $recordsPerPage;

             $sql = $this->executeQry($query);   
			$num=$this->getTotalRow($sql);
            if($num > 0) {  
                         
                $i = $offset+1;     

                 while($line = $this->getResultObject($sql)) {
				 
				$typeName = $this->getOciTypeIdByPackId($line->packId);	
				
                            $genTable .= '<tr>';
                            $genTable .='
                                            <td>'.$line->userId.'</td>
                                            <td>'.$line->firstName.'</td>
                                            <td>'.$line->lastName.'</td>
                                            <td>'.$line->packName.'</td>
											<td>'.$typeName.'</td>
											<td>'.$line->packVersion.'</td>
                                            <td>'.$line->companyName.'</td>
                                            <td>'.$line->companyAddress.'</td>
                                            <td>'.$line->downloadDate.'</td>
                                        </tr>'; 
                        
                        $i++;   
                    } 
                    
                $genTable.='</table></section></div>';
                
                
                switch($recordsPerPage)
                {
                     case 10:
                      $sel1 = "selected='selected'";
                      break;
                     case 50:
                      $sel2 = "selected='selected'";
                      break;
                     case 100:
                      $sel3 = "selected='selected'";
                      break;
                     case $this->numrows:
                      $sel4 = "selected='selected'";                                    
                      break;
                }
                $currQueryString = $this->getQueryString();
                $limit = basename($_SERVER['PHP_SELF'])."?".$currQueryString;
                        
                       if($_GET["page"])
                        {
                            $startLimit = ($_GET["page"]-1) * $recordsPerPage;
                        }
                        else
                        {
                            $startLimit=0;
                        }
                        $endLimit   = $recordsPerPage;

            if($totalrecords > 10)  
                    {
                        
                $genTable.="<div style='overflow:hidden; margin:0px 0px 0px 0px;padding-left:20px; padding-right:10px;'><table border='0' width='100%' height='50'>
                         <tr><td align='left' width='220' class='page_info' >
                         Display <form name='limitfrm' id='limitfrm' action='".$_SERVER["PHP_SELF"]."'><select name='limit' id='limit' onchange='this.form.submit();' class='page_info' style='width:65px;'>
                         <option value='10' $sel1>10</option>
                         <option value='50' $sel2>50</option>
                         <option value='100' $sel3>100</option> 
                         <option value='".$totalrecords."' $sel4>All</option>  
                           </select></form> Records Per Page
                        </td><td align='center' class='page_info'><inputtype='hidden' name='page' value='".$currpage."'></td><td 
                        class='page_info' align='center' width='100'>".$totalrecords." records found</td><td width='0' 
                        align='right'>".$pagenumbers."</td></tr></table>";
        
                        
                    
                    }
             $genTable.='<form method="post" action="export.shtml">
                            <input type="hidden" name="startLimit" value="'.$startLimit.'"/>
                            <input type="hidden" name="endLimit" value="'.$endLimit.'"/>
                            <div id="fileSelect">Select File Type : 
                            <select name="filetype" id="fileType" style="width:80px;">
                                  <option value="">--Select--</option>
                                  <option value="csv">CSV</option>
                                  <option value="xls">EXCEL </option>
                            </select>';

                            $genTable .= '<input type="hidden" name="type" value="'.$type.'">';
                        
                 $genTable.='<input type="submit" id="genReport" class="myButton" name="genReport" value="Generate Report" style="margin-left:10px;" /></div></form></div></div>';
            }       
            
            
            
        } else {
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
         
        
     //   echo $genTable;
        return $genTable;
    }

	
	
	function getDownloadFile($type,$startLimit,$endLimit)
	{
		 $csv_terminated = "\n";
    $csv_separator = ",";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";
    $sql_query = "select `userId`, `packId`, `packType`, `firstName`, `lastName`, `packName`, `packVersion`,  `certifiedBy`, `packDate`, `companyName`, `companyAddress`, `downloadDate` from ".TBL_REPORTS."  where packType = 'ocipack'  ORDER BY downloadDate DESC ";

    $cond = '';
    if(!empty($startLimit) || !empty($endLimit))
    {
        $cond = " limit $startLimit,$endLimit";
    }

   
  //  $sql_query .=$cond; 
   
    // Gets the data from the database
    $result = mysql_query($sql_query);
    $fields_cnt = mysql_num_fields($result);
    	
    $filename = "download_" . date('Ymd') . ".$type";
    $schema_insert = '';
	
	if($type=="xls")
	{
		$contentType="x-msexcel";

		  // file name for download
  	 //	$filename = "export_" . date('Ymd') . ".$type";

  		header("Content-Disposition: attachment; filename=\"$filename\"");
 	 	header("Content-Type: application/vnd.ms-excel");

 		$flag = false;
  		while ($row = mysql_fetch_assoc($result))
    		{
			
			$row['packType'] = $this -> getOciTypeIdByPackId($row['packId']);
			
			
    			if(!$flag) {
      				// display field/column names as first row
     		 		echo implode("\t", array_keys($row)) . "\n";
      				$flag = true;
    			}
    		array_walk($row, 'cleanData');
    		echo implode("\t", array_values($row)) . "\n";
  		}

  		exit; 		        

	}
	else
	{
			$contentType="x-csv";
			$fp = fopen('php://output', 'w'); 
		//	$header = array("userId","packId","packType","firstName","lastName","packName","packVersion","certifiedBy","packDate","companyName","companyAddress","downloadDate");
			$header = array("userId","Id","Type","firstName","lastName","Name","Version","certifiedBy","Date","companyName","companyAddress","downloadDate");
		
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);

			fputcsv($fp, $header);


			while($row = mysql_fetch_row($result)) {
			$row[2] = $this -> getOciTypeIdByPackId($row['1']);
			 
			fputcsv($fp, $row);
			}
			exit;
		
	}
 
   }


   
	
}
?>