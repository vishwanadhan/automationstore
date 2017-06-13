<?php
@session_start();
ob_start();
include 'class.phpmailer.php';
/**
 * Pack.shtml
 * This class implements various functionalities related to information regarding Packs.
 * 
 */


class Pack extends MySqlDriver {

    function __construct() {
       
        $obj = new MySqlDriver;
        $this->log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
    }
 
	/**
     * Function addPackReviewData for adding pack data 
     *
     * @param    postData
     * @return   packId
     *
     */
	 
	 
	 
	 
    function addPackReviewData($post) {

       
		$post['packDataXml'] = objectToArray(json_decode(base64_decode($post['packDataXml'])));	
		$pos = strrpos($post['packFilePath'], ".");
		$begin = substr($post['packFilePath'], 0, $pos);
		$end = substr($post['packFilePath'], $pos+1);

		$name[0] = $begin;
		$name[1] = $end;	

		$name[0] = isset($name[0]) ? $name[0] : null;
		$name[1] = isset($name[1]) ? $name[1] : null;
		//echo $name[0];
		//echo $name[1];
		
/* 		echo "<pre>";
		print_r($post); 
		exit;  */
		
		$packFilePath = $post['packFilePath'];
    /***

        new changes
    **/
     
        $newPack = explode('.',$packFilePath);

        $packPath = $docRoot . $newPack[0];

        
        $newPath = $packPath . '/';

        $xmlFile = $newPath . 'pack-info.xml';



    //New changes end
		$countEntity = $post['countEntity'];
		$tags = $post['wfa_tags'];
		$type = $post['wfa_type'];
		
        $now = date('Y-m-d H:i:s');
        $this->tablename = TBL_PACKDETAILS;

		$this->field_values['packName'] 		= mysql_real_escape_string($post['wfa_name']);
        $this->field_values['packDescription'] 	= mysql_real_escape_string(htmlentities($post['wfa_desc'])); 

        $solrUuid = '';
			
		 if (!file_exists($xmlFile)) {
            
					if(isset($post['wfa_pack_uuid'])){
									$uuidNew=implode("_",explode(" ",$post['wfa_name']));				
                                    $solrUuid = $uuidNew;
													 }
								}
								else {
								$uuidNew=$post['wfa_pack_uuid'];
                            }

        
								
        $this->field_values['uuid'] 			= $uuidNew;
		
        $this->field_values['author'] 			= mysql_real_escape_string($post['packDataXml']['author']);
        $this->field_values['certifiedBy'] 		= mysql_real_escape_string($post['wfa_certificate']);
        $this->field_values['version'] 			= mysql_real_escape_string($post['wfa_pack_version']);
		$this->field_values['whatsChanged'] 	= mysql_real_escape_string($post['wfa_version_changes']);
		
        $this->field_values['minWfaVersion'] 	= mysql_real_escape_string($post['wfa_version']);
	//	$this->field_values['wfaVersion'] 		  = mysql_real_escape_string($post['wfa_version']);
		$this->field_values['minsoftwareversion'] 	= mysql_real_escape_string($post['wfa_min_soft_version']);
        $this->field_values['maxsoftwareversion'] 	= mysql_real_escape_string($post['wfa_max_soft_version']);
		
		$this->field_values['preRequisites'] 	= mysql_real_escape_string($post['wfa_other']);
		
		$this->field_values['communityLink'] = mysql_real_escape_string($post['wfa_community_link']);
		
        $this->field_values['keywords'] 		= mysql_real_escape_string($post['packDataXml']['keywords']);
		
		$this->field_values['packFilePath'] 	= $packFilePath;
		
		$this->field_values['contactName'] 	= mysql_real_escape_string($post['wfa_contact_name']);
		$this->field_values['contactEmail'] = mysql_real_escape_string($post['wfa_contact_email']);
		$this->field_values['contactPhone'] = mysql_real_escape_string($post['wfa_contact_phone']);
		
        $this->field_values['packDate'] = $now;
		$this->field_values['modDate'] = $now;  
        
        $filePath = explode('.',$packFilePath);
	
		
		
        if(empty($tags))
        {
            $this->field_values['tags'] = $tags;            
        }
        else
        {
            $this->field_values['tags'] = $tags;
        }
       // $this->field_values['userId'] = $_SESSION['uid'];

        $userType =$this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");     
		
        if($userType == 1) 
        {
            $this->field_values['post_approved'] ='true';

        }
        else
        {
            $this->field_values['post_approved'] ='false';
        }

        $cautionContent ='NetApp strongly recommends that the customer attend the Service Design Workshop (SDW) conducted at their site  before using this Pack. Please contact your account team to schedule the SDW. Using this pack without attending the SDW, is done so at the risk of the Customer and against NetApp’s recommendation.';

        $this->field_values['cautionContent'] = base64_encode($cautionContent);

        $packData = $this->singleValue(TBL_PACKDETAILS, "uuid = '" .$uuidNew . "' and version = '" . $post['wfa_pack_version'] . "'");
      
        if (empty($packData)) {
			 	
            $res = $this->insertQry();
            if ($res) {			   
                $packId = $this->insert_id();
                if($userType == 1)    
                {								
                    if(empty($tags))
                    { 
                        $newTags = array();
                    }
                    else
                    {
                        $newTags = explode(",",$tags);
                    }
				if (file_exists($xmlFile)) {
				//	solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', '/var/www/html/workflowstore/'.$filePath[0] . '/pack-info.xml', $newTags, "workflow");  // for dev
				    	
					solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', '/var/www/html/workflowstore/'.$filePath[0] . '/pack-info.xml', $newTags, $type,'zip','');  // for stage           
					
				// solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', $_SESSION['exportPath']. '/pack-info.xml', $newTags, $type);  // for local				
					
                    $this->updateMetaData($packId);	 
										}else
                                        {
                                            solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', $post, $newTags, $type,'dar','');
                                        }
                    					
                }
                else
                {
                    $this->tags = $tags;
                    $this->type = $type;
                }
               
                unset($this->tablename);
                unset($this->field_values);
		  if (file_exists($xmlFile)) {
                $packEntities = array();
                $packEntities = $post['packDataXml']['entities'];

                /*echo "<pre>";
                echo $countEntity;
                exit;  */
            
            if($countEntity > 1){
                            for($i=0;$i<$countEntity;$i++)
                                {   
                                //changed the values
                                    $this->tablename = TBL_PACKENTITIES;    
                                    $this->field_values['packId'] = $packId;
                            //      $this->field_values['entityType'] = $packEntities['entity'][$i]['@attributes']['type'];
                                    
                                    $this->field_values['name']         = htmlspecialchars(mysql_real_escape_string($packEntities['entity'][$i]['name']));
                                    $this->field_values['description']  = htmlspecialchars(mysql_real_escape_string($packEntities['entity'][$i]['description']));
                                    $this->field_values['certifiedBy']  =  htmlspecialchars(mysql_real_escape_string($packEntities['entity'][$i]['certification']));
                                    $this->field_values['uuid']         = htmlspecialchars(mysql_real_escape_string($packEntities['entity'][$i]['uuid']));
                                    $this->field_values['version']      =  htmlspecialchars(mysql_real_escape_string($packEntities['entity'][$i]['version']));                            
                            //      $this->field_values['minOntapVersion'] = $packEntities['entity'][$i]['min-ontap-version'];
                                    $this->field_values['entityType']   = htmlspecialchars(mysql_real_escape_string(trim($packEntities['entity'][$i]['type'])));
                                    $this->field_values['scheme']       = htmlspecialchars(mysql_real_escape_string($packEntities['entity'][$i]['schemeNames'][$i]));                         
                            //      $this->field_values['scheme']       = $packEntities['entity'][$i]['schemes']['scheme'];
                                    $this->field_values['entityDate']   = htmlspecialchars(mysql_real_escape_string($now)); 
                                    $res = $this->insertQry();
                                    $entityId = $this->insert_id();
                                    //exit;
                                    //echo $entityId;exit;
                                }   
                        }                       
                         else{
                                $this->tablename = TBL_PACKENTITIES;    
                                    $this->field_values['packId'] = $packId;
                                    
                                    $this->field_values['name']         = htmlspecialchars(mysql_real_escape_string($packEntities['entity']['name']));
                                    $this->field_values['description']  = htmlspecialchars(mysql_real_escape_string($packEntities['entity']['description']));
                                    $this->field_values['certifiedBy']  =  htmlspecialchars(mysql_real_escape_string($packEntities['entity']['certification']));
                                    $this->field_values['uuid']         = htmlspecialchars(mysql_real_escape_string($packEntities['entity']['uuid']));
                                    $this->field_values['version']      =  htmlspecialchars(mysql_real_escape_string($packEntities['entity']['version']));
                                    
                                    $this->field_values['entityType']   = htmlspecialchars(mysql_real_escape_string(trim($packEntities['entity']['type'])));
                                    $this->field_values['scheme']       = htmlspecialchars(mysql_real_escape_string($packEntities['entity']['schemeNames']));                         
                                    $this->field_values['entityDate']   = htmlspecialchars(mysql_real_escape_string($now)); 
                                    $res = $this->insertQry();
                                    $entityId = $this->insert_id(); 
                                    //echo $entityId;exit;
                         }
				}						 
                
            }
             
            $this->log->LogInfo("Pack Details successfully added, with packId " . $packId);

            if($userType == 1)
            {
                 $_SESSION['SESS_MSG'] = msgSuccessFail("success", "Pack has been added successfully!!!");
            }
            else
            {
                $_SESSION['SESS_MSG'] = msgSuccessFail("success", "Pack has been sent for approval to administrator!!!");
            }

            unset($this->tablename);
            unset($this->field_values);
            $this->addUser($packId);

            return $packId;
        } else {
		//	
            $this->log->LogError("Unable to insert pack with uuid " .$uuidNew . " and version " . $post['wfa_pack_version'] . " -- Pack already exists.");
            return 0;
        }
    }  
	
	
	/**
     * Function editPack for update workflow pack data 
     *
     * @param    postData
     * @return   packId
     *
     */

	function editPack($post) { 
		
        $now = date('Y-m-d H:i:s');
        $this->tablename = TBL_PACKDETAILS;   
	
		$this->field_values['whatsChanged'] = mysql_real_escape_string($post['wfa_version_changes']);
		$this->field_values['minsoftwareversion'] = mysql_real_escape_string($post['wfa_min_soft_version']);
		$this->field_values['maxsoftwareversion'] = mysql_real_escape_string($post['wfa_max_soft_version']);
		$this->field_values['preRequisites'] = mysql_real_escape_string($post['wfa_other']);		
		$this->field_values['certifiedBy'] = mysql_real_escape_string($post['wfa_certificate']);
		$this->field_values['contactName'] 	= mysql_real_escape_string($post['wfa_contact_name']);
		$this->field_values['contactEmail'] = mysql_real_escape_string($post['wfa_contact_email']);
		$this->field_values['contactPhone'] = mysql_real_escape_string($post['wfa_contact_phone']);
		$this->field_values['tags'] 		= mysql_real_escape_string($post['wfa_tags']);
		
        $this->field_values['modDate'] = $now;
		
        $userType =$this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
		
		$this->condition  = "uuid = '".$post['wfa_pack_uuid']."' and version='".$post['wfa_pack_version']."'";
		
		$res = $this->updateQry();
		$this->updateSolr($post);
		
		
		if($res){
                $packId = $this->insert_id();
				return 1;
			//	return $packId;  
				// $this->log->LogInfo("Pack Details successfully added, with packId " . $packId);            
        } else {
				return 0;
		}
    }	

	
	/*
	 * function to report for new workflow pack to administrator
	 * @params type	array $post
	 * @return type bool
	 * Dev ASG
	 */	 
	function adminEmailNotify2($post){	  	
	
		$now = date('Y-m-d H:i:s');
		
		$subject = "New workFlow pack upload Notification: ". $post['wfa_name'];			  
		
	 	$adminqry = "SELECT * FROM ".TBL_USER." WHERE receiveMail ='true'";
		$sql = $this->executeQry($adminqry);
		
		// Email to all admin (adminlogin table)
		while($userline = $this->getResultObject($sql)){

            $message = '<html><body>'; 
            $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
            $message .= "<tr><td><strong>Hello ".ucfirst($userline->firstName)."  ".ucfirst($userline->lastName).",</strong> </td></tr>";
            $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
            $message .= "<tr style='background: #eee;'><td>A new pack has been uploaded :  " . $post['wfa_type'] . ".</td></tr>"; 
            $message .= "<tr style='background: #eee;'><td>Name: " . $post['wfa_name'] . ".</td></tr>"; 
            $message .= "<tr style='background: #eee;'><td>Pack Description: " . $post['wfa_desc'] . ".</td></tr>"; 

            $message .= "</table>";
            $message .= '<br>Thank you <br></br>
            *** This is an automatically generated email, please do not reply ***';
            $message .= "</body></html>";    
			$mailresponseAdmin = $this->sendEMail($message, $subject, $userline->email);		
		}	 
		
		
		return $mailresponseAdmin;
	}
	
	function adminEmailNotify($post){	    	
	
		$now = date('Y-m-d H:i:s');
		
		$subject = "New workFlow pack upload Notification: ". $post['wfa_name'];	
	 	
		// Email to all admin (adminlogin table)
		$to = "ng-store-admins@netapp.com";

            $message = '<html><body>'; 
            $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
            $message .= "<tr><td><strong>Hello ,</strong> </td></tr>";
            $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
            $message .= "<tr style='background: #eee;'><td>A new pack has been uploaded :  " . $post['wfa_type'] . ".</td></tr>"; 
            $message .= "<tr style='background: #eee;'><td>Name: " . $post['wfa_name'] . ".</td></tr>"; 
            $message .= "<tr style='background: #eee;'><td>Pack Description: " . $post['wfa_desc'] . ".</td></tr>"; 

            $message .= "</table>";
            $message .= '<br>Thank you <br></br>
            *** This is an automatically generated email, please do not reply ***';
            $message .= "</body></html>";    
			$mailresponseAdmin = $this->sendEMail($message, $subject, $to);		
		
		return $mailresponseAdmin;
	}
	
	
	/**
     *
     * getPacksByUuidVersion
     * Function to fetch all packs from the database.
     *
     * @param    uuid, version
     * @return   array of packs
     *
     */
    function getPacksByUuidVersion($uuid,$version) { 
		
        $query = "SELECT * FROM " . TBL_PACKDETAILS . " where uuid = '".$uuid."' and version = '".$version."'";
		
        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        $packArray = array();
        if ($numEntity > 0) {
				$line = $this->fetch_assoc($sql); 
                $packArray = $line;            
				return $packArray;
        }
    }
	

    /**
     *
     * Function addUser for entering data about the user during upload.
     *
     * @param    string packId
     * @return   
     *
     */
    function addUser($packId)
    {

     /*   echo "<pre>";
        print_r($_SESSION);
        exit;*/
        $now = date('Y-m-d H:i:s');

        $this->tablename = TBL_USERPACKS;
        $this->field_values['packId'] = $packId;
        $this->field_values['firstName'] = $_SESSION['firstName'];
        $this->field_values['lastName'] =  $_SESSION['lastName'];
        $this->field_values['userId'] = $_SESSION['uid'];
        $this->field_values['userEmail'] = $_SESSION['mail']; 
        //$_SESSION['mail'] = $_SESSION['userEmail'];
        $this->field_values['uploadDate'] = $now;

        $this->insertQry();
    }

	/**
     *
     * Function for adding a new user on login or update last login time for existing user.
     *
     * @param    
     * @return   String password
     * @author   Arun Verma
     *
     */
    function addNewUser()
    {
        $now = date('Y-m-d H:i:s');
        
        $query = " SELECT * FROM " . TBL_USER . " WHERE username ='".$_SESSION['uid']."'" ;
       
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);

				if($num <= 0 )
					{
					
                    if(isset($_SESSION['uid']) && isset($_SESSION['mail'])){
						$now = date('Y-m-d H:i:s');
						$this->tablename = TBL_USER;  
						$this->field_values['firstName'] = trim($_SESSION['firstName']);
						$this->field_values['lastName'] = trim($_SESSION['lastName']);
						$this->field_values['userName'] =  trim($_SESSION['uid']);
						$this->field_values['email'] = trim($_SESSION['mail']);
						$this->field_values['phone'] = ''; 
						$this->field_values['receiveNotification'] = 'true';
						$this->field_values['receiveMail'] = 'true';
						$this->field_values['lastLogin'] = $now;


						$this->insertQry();
                  
                   
				    }
                }
                
    }
	
	 /**
     *
     * Function for fetching details of the user.
     *
     * @param    string plain Text
     * @return   String userData
     * @author   Arun Verma
     *
     */

     function getUserDetails()
     {
	     $query = "Select * from ".TBL_USER." where userName = '".$_SESSION['uid']."'";

         $sql = $this->executeQry($query);
         $line = $this->getResultObject($sql);

        

         return $line;

     }
	 /*
     * Function for fetching number of records of the user.
     *
     * @param    string plain Text
     * @return   int type
     * @author   ASG
     *
     */
	 function getUserExist(){		 
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
	
    /**
     *
     * Function for encrypting simple password entered by the user.
     *
     * @param    string plain Text
     * @return   String password
     *
     */
    function encrypt_password($plain) {
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $this->tep_rand();
        }
        $salt = substr(md5($password), 0, 2);
        $password = md5($salt . $plain) . ':' . $salt;
        return $password;
    }

    function tep_rand($min = null, $max = null) {
        static $seeded;
        if (!$seeded) {
            mt_srand((double) microtime() * 1000000);
            $seeded = true;
        }
    }

    /**hc
     * packFullInformation
     * Function for fetching full information of packs.
     *
     * @param    none
     * @return   return pack information
     *
     */
    function packFullInformation($certi) {
	
		$count=1;
		$userType =$this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
        $user = (array)$userType;
       
       
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $_REQUEST['searchtxt'];
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }
		
		$cond = " certifiedBy='NETAPP' and post_approved = 'true' GROUP BY uuid";
		
		if ($certi== "NONE"){
		$cond = " certifiedBy !='NETAPP' and post_approved = 'true' GROUP BY uuid";
		} 
        		 
		$query= "Select * from " . TBL_PACKDETAILS . " join(SELECT MAX( version ) AS Vsion,uuid AS Uid FROM " . TBL_PACKDETAILS . " WHERE $cond )mvx ON version = mvx.Vsion and uuid = mvx.Uid and $cond";		 
	    $orderby = $_GET[orderby] ? $_GET[orderby] : "packDate";
	    $order = $_GET[order] ? $_GET[order] : "DESC";
	    $query .= " ORDER BY $orderby $order";	
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);

       // $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
		
        if ($num > 0) 
		{
           $delArray=array();
                while ($line = $this->getResultObject($sql)) {
						/* echo "<pre>";
						print_r($line); */
						
				
					$checkValue = (($line->cautionStatus == 'true') ? 'checked' : '');				
					$genTable .= '<tr>';
					//$user['0'] = 1; /*testing purpose only*/
					
						if ($user['0']== 1) {
					$genTable.='<td><input type="checkbox" name="caution"  class="enableCaution' . $line->id . '" onclick="setCaution('. $line->id .',\'' . $line->uuid . '\');" ' . $checkValue . '/> <span id="cautionLoader' . $line->id . '"></span></td>';
											}
											
					if ($line->certifiedBy == 'NETAPP') {
					$genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
														} else {
																	$genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
																}
																	
					$genTable .= '			
								<td><a id="packHeader" href="pack-detail.shtml?packUuid='.$line->uuid.'&packVersion='.$line->version.'">'.$line->packName.'</a></td>';
					
					
					$genTable.='
					<td><a href="javascript:void(0)" class="trigger">' .$line->version.'</a>';
					
					
					
					//pop-up on hover-start
					$genTable.='
					<div class="pop-up" id="popnew">
					<img src="images/popup-arrow.png" alt="" class="pop-arrow">
						<table cellpadding="0" cellspacing="0" border="0">
							<thead>
									<tr>													
									<td>Pack Version</td>
									<td>Min WFA Version</td>
									<td>Windows Compatibility</td>
									<td>Linux Compatibility</td>
									</tr>
							</thead>
							';
					
					$cond1 = " certifiedBy='NETAPP' and post_approved = 'true'";
		
					if ($certi== "NONE"){
					$cond1 = " certifiedBy !='NETAPP' and post_approved = 'true'";
					}
					
					$query1="SELECT * FROM " . TBL_PACKDETAILS . " WHERE uuid='" . $line->uuid . "'and $cond1 order by version DESC ";
					
					$sql1 = $this->executeQry($query1);
					$numEntity = $this->getTotalRow($sql1);
					
					if ($numEntity > 0) {

										$j = 1;
						 				$delVersion = array();
										while ($linePack = $this->getResultObject($sql1)) {												
																		
											$genTable .= '
															<tr>
																<td>' . $linePack->version . '</td>
																<td>' . $linePack->minWfaVersion . '</td>';
											if (empty( $linePack->minsoftwareversion)){
											$genTable .= '	<td class="novalue">-</td>';
											}
											else{
											$genTable .= '	<td>' . $linePack->minsoftwareversion. '</td>';
											}
											if (empty( $linePack->maxsoftwareversion)){
											$genTable .= '	<td class="novalue">-</td>';
											}
											else{
											$genTable .= '	<td>' . $linePack->maxsoftwareversion. '</td>';
											}
											$genTable .= '
											</tr>';										
											$delVer=array($linePack->id,$linePack->version );
											array_push($delVersion, $delVer);
											//print_r($delVersion);
										
											
											$j++;
																					} 
										} 
											
						
						//print_r($delArray[$line->packName][0][0]);
					//	print_r($delArray[$line->packName][0][1]);
						$delArray=array($line->packName => $delVersion);
						
						
						/* echo "<pre>";
						print_r($delArray);
						//print_r($delArray[$line->packName][0][0]);
					
					exit; */
					//pop-up on hover-start
					
					
					$genTable .= '			</table>
					</div>
					</td>';
					$genTable .= '<td>' .$line->minWfaVersion. '</td>';
					if ($line->certifiedBy == 'NETAPP' ){
															$genTable .= '<td>NetApp</td>';
														}else{
																$genTable .= '<td></td>';
															  }				
					
					$genTable .= '<td>' . date('jS F, Y', strtotime($line->packDate)) . '</td>
					<td><a href="workflowHelp.shtml?packUuid='.$line->uuid.'&packVersion='.$line->version.'"><img src="images/documentation-icon.png" border="0" title="Documentation" /></a></td>';
					
					if ($checkValue) {
					$genTable .= '<td><a href="cautionPage.shtml?packUuid=' . $line->uuid .'&packVersion=' . $line->version.'&certi='.$certi.'" name="download" class="packAction"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td>';}
					else {
					$genTable .= '<td><a href="eula.shtml?packUuid=' . $line->uuid .'&packVersion=' . $line->version.'&packType=workflow&certi='.$certi.'"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td> ';
					}
									 	
                    $cryptKey = 'sxcf10';
                    $packPath = htmlspecialchars($line->packFilePath, ENT_QUOTES, 'UTF-8');
                    $encodedPath = base64_encode($packPath);

                    $newId = htmlspecialchars($line->id, ENT_QUOTES, 'UTF-8');

       
                    if ($user['0']== 1) {

                       //delhcstart
			 			
		 				$genTable .= '<td>
		 				<a class="js-open-modal"  href="javascript:void(0)" data-modal-id="popup'.$certi.''.$count.'">
		 				<img src="images/drop.png" height="16" width="16" border="0" title="Delete" alt="Delete" />
		 				</a></td>';
		 				
		 				
		 				foreach($delArray as $key => $value)
		 				{
	 						$array1 = $value;
	 						
	 				

 //echo $key;			 	
 //print_r($value); 


						
						$genTable .= '
						<!-- Pop up --> 
													<div id="popup'.$certi.''.$count.'" class="modal-box">
													<form action="pass.shtml" method="post" name="DeleteForm" id="DeleteForm" onsubmit="return confirm(\'Are you sure you want to delete these packs ? WARNING:- This will permanently delete the packs !!\');">
													<header> <a href="javascript:void(0)" class="js-modal-close close"><img src="images/close-icon.png" border="0" /></a>
												    <h2>'.$key.'</h2>
													<h5>Select Versions of the pack for Deletion </h5>
													</header>
												    <div class="modal-body deleteppop">
													
												    <p><strong>Versions:</strong> </p>';
													$index=1;
										foreach ($value as $delIdVr){
													
			
										$genTable .= '<p><input name="idToDel[]" id="idToDel'.$index.'" type="checkbox" value="'.$delIdVr[0].'"  align="absmiddle" class="pdright">'.$delIdVr[1].'</p>';	
											 $index++;}
							
									$genTable .= '		
													<input type="hidden" name="page" value="pack-list">
													<input type="hidden" name="action" value="managePack">
													<input type="hidden" name="type" value="delete">												
			 										</div>
													<footer> 
			 												
			 												<input type="submit" value="Delete" class="btn" /> 		
			 											   </footer>
					 									   </form>
						 								   </div>
							 							<!-- pop end -->';
						 								
						 								
						 								}$count++; //delhcend

                        $genTable .= '<td><a href="edit-caution.shtml?packUuid=' .$line->uuid . '" name="download" class="packAction" ><img src="images/edit.png" height="16" width="16" border="0" title="Edit Caution" alt="Edit CautionEdit Caution" /></a></td></tr>';
                       
                    }
                    $i++;
					
                }
		}
         else {
            //$this->log->LogInfo("No records found to be displayed- inside " . __FUNCTION__);
			$genTable = '<tr><td colspan="9" > no records found</td></tr>';
				}
        return $genTable;
    }

    /**
     *
     * getPackData
     * Function to fetch pack information based on pack uuid.
     *
     * @param    array
     * @return   object of packDetails
     *
     */
    function getPackData($post) {
        $packId = $post[uuid];

        $query = "SELECT * FROM " . TBL_PACKDETAILS . " where uuid = '$packId'";
        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        if ($numEntity > 0) {
           // $this->log->LogInfo("Data for pack with packId: " . $packId . " fetched successfully - inside " . __FUNCTION__);
            return $this->getResultObject($sql);
        } else {
            $this->log->LogDebug("Pack with packId: " . $packId . " don't exist - inside " . __FUNCTION__);
            return null;
        }
    }

    /**
     *
     * getAllPacks
     * Function to fetch all packs from the database.
     *
     * @param    none
     * @return   array of packs
     *
     */
    function getAllPacks() {

        $query = "SELECT * FROM " . TBL_PACKDETAILS . " ";
        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        $packArray = array();
        if ($numEntity > 0) {
            while ($line = $this->getResultObject($sql)) {
                $packArray[] = $line;
            }
            return $packArray;
        }
    }

    /**
     * getLastId
     * Function to fetch the id of last pack record.
     *
     * @param    none
     * @return   Int id
     *
     */
    function getLastId() {
        $query = "SELECT id from " . TBL_PACKDETAILS . " ORDER BY id DESC LIMIT 1";

        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        $line = mysql_fetch_row($sql);

        return $line[0];
    }
	
	
	/**
     * getLastNonPackId
     * Function to fetch the id of last pack record.
     *
     * @param    none
     * @return   Int id
     * @author Arun Verma
     *
     */
    function getLastNonPackId($type) {          
        if($type=="reports")
            $query = "SELECT id from " . TBL_OCUMREPORTS . " ORDER BY id DESC LIMIT 1";
        else
            $query = "SELECT id from " . TBL_OPMPACKS . " ORDER BY id DESC LIMIT 1";



        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        $line = mysql_fetch_row($sql);

        return $line[0];
    }


    /* function insertDownloadDetails($packId) {
        $now = date('Y-m-d H:i:s');

        $this->tablename = TBL_REPORTS;

        if (empty($_SESSION['uid'])) {
            $this->field_values['userId'] = 'oblixanonymous';
        } else {
            $this->field_values['userId'] = $_SESSION['uid'];
        }

        $packName = $this->fetchValue(TBL_PACKDETAILS, "packName", " id = '" . $packId . "'");        
        $firstName = $_SESSION['firstName'];
        $lastName  = $_SESSION['lastName'];
        $companyName = ucfirst($_SESSION['CompanyName']);
        $companyAddress = $_SESSION['companyAddress1']." ".$_SESSION['companyAddress2'];

        $this->field_values['packId'] 		    = $packId;
        $this->field_values['firstName'] 		= mysql_real_escape_string($firstName);
        $this->field_values['lastName'] 		= mysql_real_escape_string($lastName);
	    $this->field_values['packName'] 		= mysql_real_escape_string($packName);
        $this->field_values['companyName']      = mysql_real_escape_string($companyName);
        $this->field_values['companyAddress'] 	= mysql_real_escape_string($companyAddress);
        $this->field_values['downloadDate'] 	= $now;
        
        $res = $this->insertQry();
    } */
	//hc 
/**
     * insertDownloadDetails
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
    function insertDownloadDetails( $packUuid, $packVersion, $typePack, $packName, $reportName, $reportVersion) {
        $now = date('Y-m-d H:i:s');

        $this->tablename = TBL_REPORTS;

        if (empty($_SESSION['uid'])) {
            $this->field_values['userId'] = 'oblixanonymous';
        } else {
            $this->field_values['userId'] = $_SESSION['uid'];
        }

        //$packId = $this->fetchValue(TBL_PACKDETAILS, "packName", " id = '" . $packId . "'"); 
		 if($typePack =="workflow"){
		$packQuery = "Select * from ".TBL_PACKDETAILS." where uuid='".$packUuid."' and version='".$packVersion."' ";
		}else if ($typePack == "performance"){
		$packQuery = " SELECT * FROM ". TBL_OPMPACKS." where packName='".implode(" ",explode("!",$packName))."' && packVersion='".$packVersion."'";
										}
		else if ($typePack == "report"){
		$packQuery = " SELECT * FROM ". TBL_OCUMREPORTS." where reportName='".implode(" ",explode("!",$reportName))."' && reportVersion='".$reportVersion."'";
										}
										
        $packsql = $this->executeQry($packQuery);
        $packnum = $this->getTotalRow($packsql);
		$line = $this->getResultObject($packsql);
		 
		if ($typePack == "report"){ $packName=$line->reportName; } else { $packName=$line->packName;} 
		$packId=$line->id;
        $firstName = $_SESSION['firstName'];
        $lastName  = $_SESSION['lastName'];
        $companyName = ucfirst($_SESSION['CompanyName']);
        $companyAddress = $_SESSION['companyAddress1']." ".$_SESSION['companyAddress2'];

        $this->field_values['packId'] 		    = $packId;
        $this->field_values['firstName'] 		= mysql_real_escape_string($firstName);
        $this->field_values['lastName'] 		= mysql_real_escape_string($lastName);
	    $this->field_values['packName'] 		= mysql_real_escape_string($packName);

        if($typePack == 'workflow')
            $this->field_values['packVersion']      = mysql_real_escape_string($line->version);
        else if($typePack == 'report')
            $this->field_values['packVersion']      = mysql_real_escape_string($line->reportVersion);
        else
            $this->field_values['packVersion']      = mysql_real_escape_string($line->packVersion);
            
        if($typePack == 'workflow')
            $this->field_values['minWfaVersion']      = mysql_real_escape_string($line->minWfaVersion);
        else if($typePack == 'report')
            $this->field_values['minWfaVersion']      = mysql_real_escape_string($line->OCUMVersion);
        else
            $this->field_values['minWfaVersion']      = mysql_real_escape_string($line->OPMVersion);


        $this->field_values['certifiedBy']      = mysql_real_escape_string($line->certifiedBy);

        $this->field_values['author']      = mysql_real_escape_string($line->author);
        $this->field_values['packDate']      = mysql_real_escape_string($line->packDate);

        $this->field_values['companyName']      = mysql_real_escape_string(ucfirst($_SESSION['CompanyName']));
        $this->field_values['companyAddress'] 	= mysql_real_escape_string($companyAddress);
		$this->field_values['packType'] 		= $typePack;
        $this->field_values['downloadDate'] 	= $now;
        
        $res = $this->insertQry();
    }

    /**
     * getPackById
     * Function to fetch a particular pack using id.
     * 
     * @param type $packId
     * @return type
     */
    function getPackById($packId) {
        $query = "SELECT * from " . TBL_PACKDETAILS . " where id=" . $packId . "";
        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        if ($numEntity > 0) {
            return $this->getResultObject($sql);
        }
    }

    /**
     * getAuthorData
     * Function to fetch data about author of packs.
     *
     * @param    none
     * @return   list of author of packs.
     *
     */
    function getAuthorData() {
        $query = "SELECT distinct author FROM " . TBL_PACKDETAILS . "";

        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        $numEntity = $this->getTotalRow($sql);

        $packArray = array();

        $packArray = $this->db_fetch_assoc_array_my($query);

        $tbl = "";
        if ($numEntity > 0) {
            foreach ($packArray as $value) {
                $_REQUEST['author'] = isset($_REQUEST['author']) ? $_REQUEST['author'] : null;

                if ($_REQUEST['author'] == $value['author']) {
                    $cond = 'selected="selected"';
                } else {
                    $cond = " ";
                }

                $tbl .="<option value='" . $value['author'] . "' " . $cond . " title='" . $value['author'] . "' alt='" . $value['author'] . "'>" . $value['author'] . "</option>";
            }

            return $tbl;
        } else {
            return $tbl;
        }
    }

    /**
     * getVersionData
     * Function to fetch data about version of packs.
     *
     * @param    none
     * @return   list of version of packs. 
     *
     */
    function getVersionData() {
        $query = "SELECT distinct minWfaVersion FROM " . TBL_PACKDETAILS . "";

        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        $numEntity = $this->getTotalRow($sql);

        $packArray = array();

        $packArray = $this->db_fetch_assoc_array_my($query);

        $tbl = "";
        if ($numEntity > 0) {

            foreach ($packArray as $value) {
                $_REQUEST['minWfaVersion'] = isset($_REQUEST['minWfaVersion']) ? $_REQUEST['minWfaVersion'] : null;

                if ($_REQUEST['minWfaVersion'] == $value['minWfaVersion']) {
                    $cond = 'selected="selected"';
                } else {
                    $cond = " ";
                }

                $tbl .="<option value='" . $value['minWfaVersion'] . "' " . $cond . " title='" . $value['minWfaVersion'] . "' alt='" . $value['minWfaVersion'] . "'>" . $value['minWfaVersion'] . "</option>";
            }

            return $tbl;
        } else {
            return $tbl;
        }
    }
	
	 function getPackByTypeId($name, $version, $type) {
        if($type=="report")
            $query = "SELECT * from " . TBL_OCUMREPORTS . " where reportName='" . $name . "' and reportVersion='".$version."'";
        else
            $query = "SELECT * from " . TBL_OPMPACKS . " where packName='" . $name . "' and packVersion='".$version."'";

        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        if ($numEntity > 0) {
            return $this->getResultObject($sql);
        }
    }
	/* deleteValue
	 * Function for deleting of many versions of a particular pack/reports/performance on the basis of their respective unique elements .
	 * @param    $get	 
	 * author: hc
	*/
	function deleteValue($get)
	{	
		  /* echo "<pre>";	
		print_r($get);
		exit;  */
		/*  print_r($get[idToDel]);
		 exit; */
		 
		 if (!isset($get[idToDel]) || $get[idToDel]== null ){
		 $_SESSION['SESS_MSG'] = msgSuccessFail("fail","No versions were selected to delete");
		 header( 'Location:'.$get[page].'.shtml' );
		exit;
		 }
	
		foreach($get[idToDel] as $idToDel)
			{ 
				/* echo $idToDel;
				exit; */
    $type="";
	
				if($get[page] == "pack-list" ||$get[page] == "user_profile" || $get[page] == "admin_profile")
					{
				
						$packFilePath = $this->fetchValue(TBL_PACKDETAILS,"packFilePath","id = '".$idToDel."'");
						$query="SELECT * from ".TBL_PACKDETAILS." WHERE id='".$idToDel."' ";
						$queryDelete = " DELETE FROM ".TBL_PACKDETAILS." WHERE id = '".$idToDel."' ";
						$fieldName="pack";
						$type="workflow";
						
					}else if($get[page] == "reports")
					{
						
						$packFilePath = $this->fetchValue(TBL_OCUMREPORTS,"reportFilePath","id = '".$idToDel."'");
						$query="SELECT * from ".TBL_OCUMREPORTS." WHERE id='".$idToDel."' ";	
						$queryDelete = " DELETE FROM ".TBL_OCUMREPORTS." WHERE id = '".$idToDel."' ";		
						$fieldName="reports";
						$type="reports";
					}
						else if($get[page] == "performance")
					{
						
						$packFilePath = $this->fetchValue(TBL_OPMPACKS,"packFilePath","id = '".$idToDel."'");
						$query="SELECT * from ".TBL_OPMPACKS." WHERE id='".$idToDel."' ";
						$queryDelete = " DELETE FROM ".TBL_OPMPACKS." WHERE id = '".$idToDel."' ";
						$fieldName="performance";
						$type="performance";
					}
						else if($get[page] == "onCommandInsight") 
					{ 
						
						$packFilePath = $this->fetchValue(TBL_OCIPACKS,"packFilePath","id = '".$idToDel."'");
						$query="SELECT * from ".TBL_OCIPACKS." WHERE id='".$idToDel."' ";
						$queryDelete = " DELETE FROM ".TBL_OCIPACKS." WHERE id = '".$idToDel."' ";
						$fieldName="onCommandInsight";
						$type="onCommandInsight";
					}
					else 
					{						
								header( 'Location: home.php' );
					}
							
				/* echo $query."</br>";
				echo $queryDelete."</br>";
				echo $packFilePath."</br>";
				echo $fieldName;
				exit;   
				 */
							
				$sql = $this->executeQry($query);
				$line = $this->getResultObject($sql);

				if ($get[page] == "reports"){ $packName=$line->reportName; } else { $packName=$line->packName;}
	
				 if(!empty($packFilePath))
					{
						$path_parts = pathinfo(PATH.$packFilePath);
						$unlinkDir = $path_parts['dirname'];
						//echo $unlinkDir;exit;
						rrmdir($unlinkDir);								
						$packId=$idToDel;
						
						//deletetion query execute
						$rst = $this->executeQry($queryDelete);

				
				
if($rst)
	{ 
       
      
     if($type == "workflow")
                $solrDelete="http://".SOLRSERVER.":".SOLRPORT."/solr/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:".$idToDel."%3C/query%3E%3C/delete%3E&commit=true";
            else if($type=="reports")
                $solrDelete="http://".SOLRSERVER.":".SOLRPORT."/solr/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:r_".$idToDel."%3C/query%3E%3C/delete%3E&commit=true";
            else
                $solrDelete="http://".SOLRSERVER.":".SOLRPORT."/solr/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:p_".$idToDel."%3C/query%3E%3C/delete%3E&commit=true";

    
						$content = file_get_contents($solrDelete); 
							
						//xml deletion-for workflow	
						if($type=="workflow"){  
						$packVersion=$line->version;
						$sqlinner = " DELETE FROM ".TBL_PACKENTITIES." WHERE packId = '".$packId."' ";
						$rst_entity = $this->executeQry($sqlinner);
						$this->deletePackFromXML($packName,$packVersion);  }
					 	}
					
					}  
			}
				//$this->log->LogInfo("Pack with packId: " . $get[id] . " deleted successfully");
				
				$_SESSION['SESS_MSG'] = msgSuccessFail("success"," Selected versions of ".$packName." ".$fieldName." has been deleted successfully!!!");
				
				echo "<script language=javascript>window.location.href='".$get[page].".shtml';</script>";
    exit;
		
		
	}
			
	function deletePackFromXML($packName, $packVersion)
	{
		$dom = new DOMDocument;
		$dom->load(PATH.'metadata/pack-info.xml');
		
		$xpath = new DOMXPath($dom);
		$query = sprintf('/workflowstore/wfa-packs/pack[./version = "%s" and ./name = "%s"]', $packVersion,$packName);

		foreach($xpath->query($query) as $record) {
		$record->parentNode->removeChild($record);
		}
		//echo $dom->saveXml();
		$dom->save(PATH.'metadata/pack-info.xml');


		// timestamp updated
		
		$xml = WEBSERVICEPATH. 'store_summary.xml';
		$xmlObj = simplexml_load_file($xml); 
		$sxe = new SimpleXMLElement($xmlObj->asXML());
		$tmp = $sxe->xpath("//update_timestamp"); 
        $timeStampNew = time();
		$tmp[0][0] = $timeStampNew;	
        

		$xmlTime=new SimpleXMLElement($sxe->asXML());
		$xmlTime->asXML($xml); 

		//$this->log->LogInfo("Pack deleted successfully from wfsmeta");

         $store_summary = '';
                $store_summary .="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                $store_summary .="<store_summary>\n\t\t";
                $store_summary .= "<update_timestamp>" . $timeStampNew . "</update_timestamp>\n\t\t";
                $store_summary.="</store_summary>\n\r";

                $xmlTime = new SimpleXMLElement($store_summary);
                $xmlTime->asXML(WEBSERVICEPATH . 'store_summary.xml');


             $metaXml = WEBSERVICEPATH. 'pack-info.xml';
                $metaObj = simplexml_load_file($metaXml); 
                $metaSxe = new SimpleXMLElement($metaObj->asXML());
                
                $tmp1 = $metaSxe->xpath("//publish-timestamp");            
                if (!empty($tmp1)) {
                        $tmp1[0][0] = $timeStampNew;
                    } else {   
                        $new = new SimpleXMLElement('<publish-timestamp>'.$timeStampNew.'</publish-timestamp>');              
                        $img1 = $metaSxe->xpath("//workflowstore"); // xpath returns an array
                        insertAfter($new, $img1[0][0]);                                   
                    }      
               
                $xml1New = new SimpleXMLElement($metaSxe->asXML());
                
                $xml1New->asXML(WEBSERVICEPATH . 'pack-info.xml');


        
	} 


	// new changes
 	function create_guid($namespace = '') {
	
		static $guid = '';
		$uid = uniqid ( "", true );
		$data = $namespace;
		$data .= $_SERVER ['REQUEST_TIME'];
		$data .= $_SERVER ['HTTP_USER_AGENT'];
		$data .= $_SERVER ['SERVER_ADDR'];
		$data .= $_SERVER ['SERVER_PORT'];
		$data .= $_SERVER ['REMOTE_ADDR'];
		$data .= $_SERVER ['REMOTE_PORT'];
		$hash = strtoupper ( hash ( 'ripemd128', $uid . $guid . md5 ( $data ) ) );
		$guid = substr ( $hash, 0, 8 ) . '-' . substr ( $hash, 8, 4 ) . '-' . substr ( $hash, 12, 4 ) . '-' . substr ( $hash, 16, 4 ) . '-' . substr ( $hash, 20, 12 );		
		$_SESSION['uid'] = 'admin';
		/* if(!empty($_SESSION['uid']))
		{ */
			$query = "update ".TBL_ADMINUSER." set hash = '$guid' where userName = '".$_SESSION['uid']."'";
			$this->executeQry($query);
		//}
		
		
		return $guid;
		
	}
	
	
	function getDownloadReports($type)
	{
		
    if($type=='workflow'){
		$query ="SELECT * FROM ".TBL_REPORTS." where packType = '".$type."' or packType = '' ORDER BY downloadDate DESC ";
    }
    else
    {
        $query ="SELECT * FROM ".TBL_REPORTS." where packType = '".$type."' ORDER BY downloadDate DESC ";
    }
		
		
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
			$totalrecords = $this->numrows;
			
			$totalpage = $this->getNoOfPages();
			$pagenumbers = $this->getPageNo();		

			//-------------------------Paging------------------------------------------------
		//	$recordsPerPage  = 10;
			$orderby = $_GET[orderby]? $_GET[orderby]:"packDate";
		    $order = $_GET[order]? $_GET[order]:"DESC";   
            $query .=  "LIMIT ".$offset.", ". $recordsPerPage;

				$sql = $this->executeQry($query);			
			if($num > 0) {	
	                     
				$i = $offset+1;		

				 while($line = $this->getResultObject($sql)) {
							$genTable .= '<tr>';
                            $genTable .='
											<td>'.$line->userId.'</td>
											<td>'.$line->firstName.'</td>
											<td>'.$line->lastName.'</td>
											<td>'.$line->packName.'</td>
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
						 Display <select name='limit' id='limit' onchange='pagelimit(\"$limit\");' class='page_info' style='width:65px;'>
						 <option value='10' $sel1>10</option>
						 <option value='50' $sel2>50</option>
						 <option value='100' $sel3>100</option> 
						 <option value='".$totalrecords."' $sel4>All</option>  
						   </select> Records Per Page
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
		 
		
		
		return $genTable;
	}
	

	  function cleanData(&$str)
  		{
   			 $str = preg_replace("/\t/", "\\t", $str);
    			$str = preg_replace("/\r?\n/", "\\n", $str);
   			 if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  		}


	function getDownloadFile($type,$startLimit,$endLimit,$packType)
	{
		 $csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";
    $sql_query = "select * from download_history where packType = '".$packType."' ORDER BY downloadDate DESC ";

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

		for ($i = 0; $i < $fields_cnt; $i++)
   		 	{
        			$l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
            			stripslashes(mysql_field_name($result, $i))) . $csv_enclosed;
       		 	$schema_insert .= $l;
        			$schema_insert .= $csv_separator;
    			} // end for
 
   	 		$out = trim(substr($schema_insert, 0, -1));
    			$out .= $csv_terminated;
 
    			// Format the data
    			while ($row = mysql_fetch_array($result))
    				{
        				$schema_insert = '';
        				for ($j = 0; $j < $fields_cnt; $j++)
        				{
            					if ($row[$j] == '0' || $row[$j] != '')
            						{
 
                						if ($csv_enclosed == '')
                							{
                   	 							$schema_insert .= $row[$j];
                							} else
                						{
                    							$schema_insert .= $csv_enclosed . 
									str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$j]) . $csv_enclosed;
                						}
            						} else
            							{
               		 					$schema_insert .= '';
            							}
 
           					 if ($j < $fields_cnt - 1)
           			 			{
                						$schema_insert .= $csv_separator;
           		 				}
       				 } // end for
 
        				$out .= $schema_insert;
        				$out .= $csv_terminated;
    				} // end while
 
    			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    			header("Content-Length: " . strlen($out));
    			// Output to browser with appropriate mime type, you choose ;)
    			header("Content-type: application/x-msexcel");
    			//header("Content-type: text/csv");
    			//header("Content-type: application/csv");
    			
			header("Content-Disposition: attachment; filename=\"$filename\"");
    			echo $out;
    			exit;				
	}
 
   }


    /**
     * getCautionByPackId
     * Function to fetch the cautionContent of the pack.
     *
     * @param    none
     * @return   Int id
     *
     */
	 
	function getCautionByPackId($uuid)
		{
			$query="SELECT * FROM ".TBL_PACKDETAILS." where uuid = '$uuid'";				
			$sql = $this->executeQry($query);
			$numEntity=$this->getTotalRow($sql);
			
			if($numEntity > 0)
			{
				$rs = $this->getResultObject($sql);				
				return $rs->cautionContent;
			}
			
		}
		
	function getPackUuid($id)
	{
	$id = base64_decode($id);
		$query="SELECT uuid FROM ".TBL_PACKDETAILS." where id = '$id'";		

			$sql = $this->executeQry($query);
			$numEntity=$this->getTotalRow($sql);
			
			if($numEntity > 0)
			{
				$rs = $this->getResultObject($sql);		

				return $rs->uuid;
			}
	}
	
		/**
		 * editCautionPage
		 * Function to edit the cautionContent of the pack.
		 *
		 * @param    none
		 * @return   String message
		 *
		 */
		 
		function editCautionPage($post){	

           
			$_SESSION['SESS_MSG'] = "";	
			$this->tablename = TBL_PACKDETAILS;		
			$this->field_values['cautionContent'] = base64_encode($post['cautionContent']) ;
			$this->condition  = "uuid = '".$post['packUuid']."' ";
			$res = $this->updateQry();
			$_SESSION['SESS_MSG'] =	msgSuccessFail("success","Caution Notice has been updated successfully.");			  		
				 
		}
	
	

      /**
     * setCautionStatus
     * Set the status of caution for a particular pack.
     * 
     * @param type $post
     * @return type
     */
    function setCautionStatus($post)
    {
		
        $checkValue=$post['status'];        
        
        $query = "update ". TBL_PACKDETAILS . " set cautionStatus = '$checkValue' where uuid='".$post["packUuid"]."'";
        $this->executeQry($query);
        echo $query;
        exit;
    }

	
	/**
     *
     * editAlert
     * Function to edit alert message and status.
     *
     * @param    array of post value
     * @return   none
     *
     */
	function editAlert($post){
		// echo"<pre>"; print_r($_POST);exit;	
		  $this->tablename = TBL_ALERT;
		  $timeStampNew = time();
		  $alertId = base64_encode($post['alertId']);		 		  
		
		  $this->field_values['message'] = mysql_real_escape_string($post['alertMessage']);  
		  
		   
		  if(!empty($post['alert-status']) && $post['alert-status'] == 'active')
		  {
			$this->field_values['status'] = '1';
		  }
		  
		 if(!empty($post['alert-status']) && $post['alert-status'] == 'inactive')
		  {
			$this->field_values['status'] = '0';
		  }		 

		  $this->condition = "alertId='".$post['alertId']."'"; 
		  $res = $this->updateQry();
		  if($res)
		  { 
			$_SESSION['SESS_MSG'] = msgSuccessFail("success","Alert message has been updated successfully!!!");
			
			$alert_message = $this->fetchValue(TBL_ALERT,'message',"alertId=1");			
			$alert_status = $this->fetchValue(TBL_ALERT,'status',"alertId=1");
			
			$metadatapath = WEBSERVICEPATH . 'pack-info.xml';	
			$xmlObj = simplexml_load_file($metadatapath);
			$sxe = new SimpleXMLElement($xmlObj->asXML());
			
			$node = $sxe->xpath("//alertMessage");		
			
			if($post['alert-status'] == 'active')
				{							
				//	print_r($node);exit;		
					if (!empty($node)) {
							$node[0][0] = $alert_message; 
						} 
					else {	
						$new = new SimpleXMLElement('<alertMessage>'.$alert_message.'</alertMessage>');				
						$img = $sxe->xpath("//wfa-packs"); // xpath returns an array
						//insertAfter($new, $img[0][0]);	
						insertAfter($new, $img[0][0]);		 				
					} 
				}
			else{
				
					if (!empty($node)) {
							$node[0][0] = '';
						} 
					else {	
						$new = new SimpleXMLElement('<alertMessage></alertMessage>');				
						$img = $sxe->xpath("//wfa-packs"); // xpath returns an array
						//insertAfter($new, $img[0][0]);	
						insertAfter($new, $img[0][0]);					
					} 	
				}	
				
				// timestamp update on alert	 
				$node = $sxe->xpath("//publish-timestamp");		
				if (!empty($node)) {
						$node[0][0] = $timeStampNew;   
					} 
				else {	
					$new = new SimpleXMLElement('<publish-timestamp>'. $timeStampNew .'</publish-timestamp>');				
					$img = $sxe->xpath("//workflowstore"); // xpath returns an array
					insertAfter($new, $img[0][0]);	  
				} 
				$xml2New = new SimpleXMLElement($sxe->asXML()); 		 		
				$xml2New->asXML($metadatapath); 
				
				// timestamp update on store_summary(timestamp)	    
				$metadatapath = WEBSERVICEPATH . 'store_summary.xml';	
				$xmlObj = simplexml_load_file($metadatapath);
				$sxe = new SimpleXMLElement($xmlObj->asXML());
				$node = $sxe->xpath("//update_timestamp");		
				if (!empty($node)) {
						$node[0][0] = $timeStampNew;
					} 
				else {	
					$new = new SimpleXMLElement('<update_timestamp>'.$timeStampNew.'</update_timestamp>');				
					$img = $sxe->xpath("//store_summary"); // xpath returns an array
					insertAfter($new, $img[0][0]);	  
				} 				
				
				$xml2New = new SimpleXMLElement($sxe->asXML()); 	  	 		
				$xml2New->asXML($metadatapath); 
				
			 //$_SESSION['SESS_MSG'] = "satasfsaf sa";
		  	
			// echo "hello";$_SESSION['SESS_MSG'];exit;	
		  }
		  redirect('alert.shtml');  
		  exit;		  
	 }


 //Pack approval changes 


 /**
 * count unapprovedPacks
 *
 * @author: Arun Verma
 *
 */

 function countUnApprovedPacks()
 {
     
     
        
        $query = " SELECT * FROM " . TBL_PACKDETAILS . " WHERE post_approved ='false'";
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);

        return $num;

 }

 function unapprovedPackFullInformation() {
        $cond = " 1=1 ";
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $_REQUEST['searchtxt'];
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }

        
        $cond .= " and post_approved ='false'";
        
        
        $query = " SELECT * FROM " . TBL_PACKDETAILS . " WHERE $cond";

       
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

                   

                   $genTable .= '           
                                            <td><a id="packHeader" href="pack-detail.shtml?packUuid='.$line->uuid.'&packVersion='.$line->version.'">'.$line->packName.'</a></td>';
                                                                    
                                            $genTable .= '<td>' .$line->version. '</td>';
                                           
                                            $genTable.='
                                            <td>' .$line->minWfaVersion.'</td>';
                                           
                                         
                                        
                                        $encodedPath = base64_encode($line->packFilePath);
                                        $genTable .= '          
                                            <td>' .$line->author . '</td>
                                            <td>' . date('jS F, Y', strtotime($line->packDate)) . '</td>';
                                            

                                            $genTable .="<td><a class='js-open-modal'   href='javascript:void(NULL);' data-modal-id='popup1' id='".$line->id."'><img src='images/close_small.png' height='16' width='16' border='0' title='Reject' alt='Reject' /></a></td>";

                                            $genTable .="<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure you want to approve this Pack  ?')){window.location.href='pass.shtml?action=approvePack&type=approve&id=".$line->id."'}else{}\" ><img src='images/success.png' height='16' width='16' border='0' title='Approve' alt='Approve' /></a></td>";
                                            $genTable .='<td><a href="eula.shtml?packUuid=' . $line->uuid .'&packVersion=' . $line->version.'&packType=workflow&certi='.$line->certifiedBy.'"><img src="images/downlaod-icon.png" border="0" title="Download"/></a></td>';

                    $cryptKey = 'sxcf10';
                    $encodedPath = base64_encode($line->packFilePath);
                    
                   // $genTable .= '</tr>';
                    $i++;
                }
            
                $genTable .= '</tr>';
            }
        } else {
           // $this->log->LogInfo("No records found to be displayed- inside " . __FUNCTION__);
             $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
        return $genTable;
    }


    function approvePack($get)
    {
       $query = "UPDATE ".TBL_PACKDETAILS." set post_approved = 'true' where id='$get[id]'";

       $this->executeQry($query);
        
        $nameQuery = "SELECT * FROM ".TBL_PACKDETAILS." WHERE id='$get[id]'";

        
        $sql = $this->executeQry($nameQuery);
        $line = $this->getResultObject($sql);

       /* echo "<pre>";
        print_r($line);*/
         $filePath = explode('.',$line->packFilePath);
         $xmlFile= PATH.$filePath[0].'/pack-info.xml';
		 

       if(empty($line->tags))
        { 
            $newTags = array();
        }
        else
        {
            $newTags = explode(",",$line->tags);
        }     

      
        

       // solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', '/var/www/html/workflowstore/releases/20140808143809/'.$filePath[0]. '/pack-info.xml', $newTags, "workflow");

        if (file_exists($xmlFile))
        {        
        solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', '/var/www/html/workflowstore/'.$filePath[0]. '/pack-info.xml', $newTags, "workflow",'zip','');

        }
        else
        {

            solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', $line, $newTags, "workflow",'dar','approvePack');
        }
      

        $userQry = "SELECT * FROM ".TBL_USER." WHERE receiveMail='true'";

                     $mailsql = $this->executeQry($userQry);  
                     $mailNum =$this->getTotalRow($mailsql);
                    

                     if ($mailNum > 0) {
                        
                        while ($userline = $this->getResultObject($mailsql)) {
                                

                            $subject = "New workFlow pack upload Notification: ". $line->packName;
                            $message = '<html><body>'; 
                            $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
                            $message .= "<tr><td><strong>Hello, ".ucfirst($userline->firstName)." ".ucfirst($userline->lastName)."</strong> </td></tr>";
                            $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
                            $message .= "<tr style='background: #eee;'><td>A new pack has been uploaded .</td></tr>";  
                            $message .= "<tr style='background: #eee;'><td>Name: " . $line->packName . ".</td></tr>";     
                            $message .= "<tr style='background: #eee;'><td>Pack Description: " . htmlentities($line->packDescription) . ".</td></tr>"; 
                            $message .= "</table>";
                            $message .= '<br>Thank you <br></br>
                            *** This is an automatically generated email, please do not reply ***';
                            $message .= "</body></html>"; 
                            

                            $this->sendEMail($message, $subject, $userline->email);

                           }

                            

                       }
       
         
        if (file_exists($xmlFile))
            $this->updateMetaData($get[id]);

               
        $_SESSION['SESS_MSG'] = msgSuccessFail("success","User Pack has been approved successfully!!!");
                
        echo "<script language=javascript>window.location.href='admin_profile.shtml';</script>";
        exit;
        
    }

	function rejectPack($post)
    {

        $packFilePath = $this->fetchValue(TBL_PACKDETAILS,"packFilePath","id = '$post[id]'");
        $query="SELECT * from ".TBL_PACKDETAILS." WHERE id='$post[id]' ";

        $packQuery = "SELECT * from ".TBL_USERPACKS." WHERE packId='$post[id]'";
        
        $sql = $this->executeQry($query);
        $line = mysql_fetch_row($sql);

        $packSql = $this->executeQry($packQuery);
        $packLine = mysql_fetch_row($packSql);
        
        $packName=$line[2];
        $packVersion= $line[6];
         
        $this->tablename = TBL_REJECTEDPACKS;
        $this->field_values['packName'] = $packName;
        $this->field_values['userEmail'] = $packLine[4];
        $this->field_values['firstName'] = $packLine[2];
        $this->field_values['lastName']= $packLine[3];
       // $this->field_values['companyName']= $_SESSION['CompanyName'];
        $this->field_values['adminComments']=  mysql_real_escape_string($post['comment']);
        $this->field_values['adminName'] = $_SESSION['firstName'].' '.$_SESSION['lastName'];
        $this->field_values['adminEmail'] = $_SESSION['mail'];
        $this->field_values['rejectDate'] = date('Y-m-d H:i:s');
        
        $this->insertQry();

                        $subject = "Pack Rejected";
                        $message = '<html><body>'; 
                        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
                        $message .= "<tr><td><strong>Hello ".ucfirst($packLine[2])." ".ucfirst($packLine[3]).",</strong> </td></tr>";
                        $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
                        
                        $message .= "<tr style='background: #eee;'><td>Your pack has been rejected by the Store Administrator. Below are the comments from the Administrator : </td></tr>"; 
                         $message .= "<tr style='background: #eee;'><td>Name: " . $packName. "</td></tr>";
                         $message .= "<tr style='background: #eee;'><td>Pack Description: " . htmlentities($line[3]). "</td></tr>";
                           $message .= "<tr style='background: #eee;'><td>Type: Workflow </td></tr>";
                        $message .= "<tr style='background: #eee;'><td>" . $post['comment']. "</td></tr>";
                        $message .= "</table>";
                        $message .= '<br>Thank you <br></br>
                        *** This is an automatically generated email, please do not reply ***';
                        $message .= "</body></html>"; 

                        $mailResult = $this->sendEMail($message, $subject, $packLine[4]);


       
        if(!empty($packFilePath)){

           
            $path_parts = pathinfo(PATH.$packFilePath);


            $unlinkDir = $path_parts['dirname'];
            //echo $unlinkDir;exit;
            rrmdir($unlinkDir);
            
            $packId=$post['id'];

            $sql = " DELETE FROM ".TBL_PACKDETAILS." WHERE id = '$post[id]' ";

          
            $rst = $this->executeQry($sql);
            if($rst)
            {
                $sql = " DELETE FROM ".TBL_PACKENTITIES." WHERE packId = '$post[id]' ";
                $rst_entity = $this->executeQry($sql);
                
            }

            $this->deletePackFromXML($packName,$packVersion);
            
        }

       

        
        
        //$this->log->LogInfo("Pack with packId: " . $get[id] . " deleted successfully");
        $_SESSION['SESS_MSG'] = msgSuccessFail("success","User Pack has been rejected successfully!!!");
        
        echo "<script language=javascript>window.location.href='admin_profile.shtml';</script>";
    }
	
    function updateMetaData($packId)
    {
        $xml1 = WEBSERVICEPATH . 'pack-info.xml';
        $nameQuery = "SELECT packFilePath FROM ".TBL_PACKDETAILS." WHERE id='$packId'";
        $sql = $this->executeQry($nameQuery);
        $line = $this->getResultObject($sql);
        
        $pieces = explode("/", $line->packFilePath);
        
        /* echo "<pre>";
        print_r($pieces);
        exit; */
        
        $path = PATH . 'wfs_data/' . $pieces[1];
        $export_path = $path . '/';
        
        /* echo "export path is: ".$export_path;
        exit; */
        $pos = strrpos($pieces[2], ".");
        
        $fileName = substr($pieces[2], 0, $pos);
        $end = substr($pieces[2], $pos+1);
        $timestampNew=time();
        
        
     //   $packFilePath = SITEPATH . 'wfs_data/build_' . $dDate . '/' . basename($filename);

                if (!file_exists($xml1)) {
                    $string = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<workflowstore xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="sfa-store-interface.xsd">
EOD;

$string .= "<publish-timestamp>".$timestampNew."</publish-timestamp>";

 $string .= <<<EOD
<data-base-url>host.btc.netapp.com</data-base-url>
<wfa-packs></wfa-packs>
</workflowstore>
EOD;
                    $file = fopen($xml1, "w");
                    fwrite($file, $string);
                    fclose($file);
                }

				$packUuid 		= $this->fetchValue(TBL_PACKDETAILS,'uuid',"id=$packId");
				$packVersion	= $this->fetchValue(TBL_PACKDETAILS,'version',"id=$packId");
                //xml join start    
                $xml2 = $export_path . $fileName . '/pack-info.xml';
                
                //echo "xml2 file is: ".$xml2;exit;
                
                joinXML($xml1, $xml2, 'wfa-packs');
                // timestamp xml create 

                $xmlObj = simplexml_load_file($xml1);
                $sxe = new SimpleXMLElement($xmlObj->asXML());
               
                $nodeBefore = $sxe->xpath('//pack');
                $packCount = count($nodeBefore);                
                
                $packDetailPath =  SITEPATH . "download-page.shtml?packUuid=".$packUuid."&packVersion=".$packVersion."&packType=workflow";	
            
                $tmp = $sxe->xpath("//pack[$packCount]/downloadUrl");
            
                if (!empty($tmp)) {
                        $tmp[0][0] = $packDetailPath; 
                    } else {                

                        $new = new SimpleXMLElement('<downloadUrl>'.$packDetailPath.'</downloadUrl>');              
                        $img = $sxe->xpath("//pack[$packCount]/entities"); // xpath returns an array
                        insertAfter($new, $img[0][0]);
                                   
                    }
               
				// update timestamp on pack upload  
				
				$tmp = $sxe->xpath("//publish-timestamp");            
                if (!empty($tmp)) {
                        $tmp[0][0] = time();
                    } else {   
                        $new = new SimpleXMLElement('<publish-timestamp>'.time().'</publish-timestamp>');              
                        $img = $sxe->xpath("//workflowstore"); // xpath returns an array
                        insertAfter($new, $img[0][0]);                                   
                    }	   
			   
				$xml1New = new SimpleXMLElement($sxe->asXML());   
				
				$xml1New->asXML(WEBSERVICEPATH . 'pack-info.xml');

				// code for pack-detail hyperlink 
				
				
				$packUrl 		= SITEPATH ."pack-detail.shtml?packUuid=".$packUuid."&packVersion=".$packVersion; 
				
				$metadatapath = WEBSERVICEPATH . 'pack-info.xml';       	
				$xmlObj = simplexml_load_file($metadatapath);
				
				$sxe = new SimpleXMLElement($xmlObj->asXML());
				
				$tmp = $sxe->xpath("//pack[$packCount]/packUrl");
				
                if (!empty($tmp)) {
                        $tmp[0][0] = $packUrl;
                    } else {		
						//	$new2 = new SimpleXMLElement("<hyperlink><![CDATA[$packUrl]]></hyperlink>");		
							$new2 = new SimpleXMLElement("<packUrl>".htmlspecialchars($packUrl, ENT_XML1, 'UTF-8')."</packUrl>");  
							
							$img = $sxe->xpath("//pack[$packCount]/downloadUrl"); // xpath returns an array							
							insertAfter($new2, $img[0][0]);    
                    }
				
				$xml2New = new SimpleXMLElement($sxe->asXML()); 					
				$xml2New->asXML($metadatapath); 
				
				// code end for pack-detail hyperlink
 
                //----------------------------------------------------------
                $store_summary = '';
                $store_summary .="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                $store_summary .="<store_summary>\n\t\t";
                $store_summary .= "<update_timestamp>" . time() . "</update_timestamp>\n\t\t";
                $store_summary.="</store_summary>\n\r";

                $xmlTime = new SimpleXMLElement($store_summary);
                $xmlTime->asXML(WEBSERVICEPATH . 'store_summary.xml');
                
                
    }

    function getRejectedPackData()
    {

        $query = " SELECT * FROM rejectedPacks WHERE userEmail = '".$_SESSION['mail']."' and packType!='ocipack' and packType!='snapcenter'";   
       
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
                                            
                                            <td>' . date('jS F, Y', strtotime($line->rejectDate)) . '</td>
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

    function getUserPacks() {
       $cond = " 1=1 ";
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $_REQUEST['searchtxt'];
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }

         $query = " SELECT pd.* FROM " . TBL_PACKDETAILS . " as pd inner join ".TBL_USERPACKS." on pd.id= ".TBL_USERPACKS.".packId and ".TBL_USERPACKS.".userId='".$_SESSION['uid']."' where pd.post_approved='false' GROUP BY pd.uuid";

        
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
                    if ($line->certifiedBy == 'NETAPP' ) {
                    $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                                                        } else {
                                                                    $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                                                                }
                 
                                $genTable .= '          
                                            <td><a id="packHeader" href="pack-detail.shtml?packUuid='.$line->uuid.'&packVersion='.$line->version.'">'.$line->packName.'</a></td> ';
                                            //<!--<td><a href="javascript:void(0)" class="trigger">' .$line->minWfaVersion.'</a>-->
                                            if ($userType== 1) {
                                            $genTable.='
                                            <td><a href="javascript:void(0)" class="triggerAdmin">' .$line->version.'</a>';
                                            }else {
                                            $genTable.='
                                            <td><a href="javascript:void(0)" class="trigger">' .$line->version.'</a>';
                                            }
                                            $genTable.='
                                            <div class="pop-up">
                                            <img src="images/popup-arrow.png" alt="" class="pop-arrow">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <thead>
                                                            <tr>                                                    
                                                            <td>Pack Name</td>
                                                            <td>Min WFA Version</td>
                                                            <td>Min Software Version</td>
                                                            </tr>
                                                    </thead>
                                                    ';
                                                    
                    $query1 = "SELECT * FROM " . TBL_PACKDETAILS . " where uuid = '".$line->uuid."'";

                                      

                                        $orderby = $_GET[orderby] ? $_GET[orderby] : "version";
                                        
                                        $query .= " ORDER BY $orderby ";

                                        $sql1 = $this->executeQry($query1);
                                        $numEntity = $this->getTotalRow($sql1);
                                        
                                        if ($numEntity > 0) {

                                            $j = 1;

                                            while ($linePack = $this->getResultObject($sql1)) {

                                                

                                                $genTable .= '
                                                                <tr>
                                                                    <td>' . $linePack->version . '</td>
                                                                    <td>' . $linePack->minWfaVersion . '</td>';
                                                if (empty( $linePack->minSoftVersion)){
                                                $genTable .= '  <td class="novalue">-</td>';
                                                }
                                                else{
                                                $genTable .= '  <td>' . $linePack->minSoftVersion. '</td>';
                                                }
                                                $genTable .= '
                                                </tr>';
                                                                

                                                $j++;
                                            }

                                          
                                        } 
                                        $genTable .= '          </table>
                                            </div>';

                                        $genTable .= '<td>' .$line->minWfaVersion. '</td>';
                                           $genTable.=' </td>
                                            <td>' . date('jS F, Y', strtotime($line->packDate)) . '</td>';
                                            
                                            
                                        
                    $cryptKey = 'sxcf10';
                    $packPath = htmlspecialchars($line->packFilePath, ENT_QUOTES, 'UTF-8');
                    $encodedPath = base64_encode($packPath);

                    $newId = htmlspecialchars($line->id, ENT_QUOTES, 'UTF-8');
                   

                        $genTable .= "<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure to delete this Record  ?')){window.location.href='pass.shtml?action=managePack&type=userdelete&idToDel[]=" . $line->id . "&page=user_profile'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Delete' alt='Delete' /></a></td>";

                   
                        $genTable .= '<td><a href="eula.shtml?packUuid=' . $line->uuid .'&packVersion=' . $line->version.'&packType=workflow&certi='.$line->certifiedBy.'"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td> ';
                    
                       
                    $i++;
                }
                
                //echo $genTable;exit;
            }
        } else {
           // $this->log->LogInfo("No records found to be displayed- inside " . __FUNCTION__);
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }


        return $genTable;
    }

    function getUserDownloadedPacks()
    {
         $cond = " 1=1 ";
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $_REQUEST['searchtxt'];
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }

       
        
        
        $query = " SELECT * FROM " . TBL_REPORTS . " where userId = '".$_SESSION['uid']."' and packType='workflow'";
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);

        if ($num > 0) {
            
                while ($line = $this->getResultObject($sql))
                {                            
                        $genTable .= '<tr>';
                         if ($line->certifiedBy == 'NETAPP' ) {
                    $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                                                        } else {
                                                                    $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                                                                }
                                $genTable .= '          
                                            <td>'.$line->packName.'</td> 
                                            <td>' .wrapText($line->packVersion, 10) . '</td>';
                                            //<!--<td><a href="javascript:void(0)" class="trigger">' .$line->minWfaVersion.'</a>-->
                                            $genTable.='
                    <td>' .$line->packType.'</td>';
                                            
                    
                                        $genTable .= '
                                            </td>

                                            <td style="width:140px;">' . date('jS F, Y', strtotime($line->downloadDate)) . '</td>
                                            <td>'.$line->author.'</td><td></td>';
                                            
                            
                    
                    $i++;
                }

            
                
                //echo $genTable;exit;
            
        } else {
           // $this->log->LogInfo("No records found to be displayed- inside " . __FUNCTION__);
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }


        return $genTable;

    }


    
      // end new changes 

	/* 
	 *	function to trim spaces and html special chars from given variable
	 *	@params type string(char or int)
	 *	@return type string(char or int)
	 * 	Dev ASG
	 */
	 function trimStrip_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	/*
	 * function to add OCUM report data in to database
	 * @params type	array($POST) 
	 * @return type bool
	 * Dev ASG
	 */
	function addOCUMData($POST) {
        $now = date('Y-m-d H:i:s');
        $this->tablename =  TBL_OCUMREPORTS;

        $this->field_values['reportName'] 			= mysql_real_escape_string($POST['reportname']); 
        $this->field_values['reportDescription'] 	= mysql_real_escape_string($POST['reportdesc']); 
        $this->field_values['reportVersion'] 		= mysql_real_escape_string($POST['version']); 
        $this->field_values['versionChanges'] 		= mysql_real_escape_string($POST['verchange']); 
        $this->field_values['OCUMVersion'] 			= mysql_real_escape_string($POST['ocumversion']); 
        $this->field_values['minONTAPVersion'] 		= mysql_real_escape_string($POST['minversion']); 
        $this->field_values['maxONTAPVersion'] 		= mysql_real_escape_string($POST['maxversion']); 
        $this->field_values['maxONTAPVersion'] 		= mysql_real_escape_string($POST['maxversion']); 
        $this->field_values['otherPrerequisite'] 	= mysql_real_escape_string($POST['otherchanges']);
		$this->field_values['certifiedBy'] 			= mysql_real_escape_string($POST['certificate']); 
        $this->field_values['authorName'] 			= mysql_real_escape_string($POST['authorname']); 
        $this->field_values['authorEmail'] 			= mysql_real_escape_string($POST['authoremail']); 
        $this->field_values['authorContact'] 		= mysql_real_escape_string($POST['authorphone']); 
        $this->field_values['reportFilePath'] 		= mysql_real_escape_string($POST['reportfile']); 
        $this->field_values['reportDate'] 			= $now;            
		
		$cond = "(reportName = '" . mysql_real_escape_string(strtolower($POST['reportname'])) . "' OR  reportName = '". mysql_real_escape_string(strtolower($POST['reportname'])) ."' OR reportName = '".mysql_real_escape_string($POST['reportname'])."') AND reportVersion = '" . $POST['version'] . "'";
		
		$reportData = $this->singleValue(TBL_OCUMREPORTS, $cond);
		
			if (empty($reportData)){
					$res = $this->insertQry();
					$newTags = array();


                 solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', $POST, $newTags, "reports");
					if($res) {
					return 1;
					$this->log->LogInfo("OCUM report inserted.");
					}else{
					$this->log->LogError("Unable to insert OCUM report.");
					return 0;
					}
			}else{
			 return 0;
			}		
	}
	
	/*
	 * function to add OPM pack data in to database
	 * @params type	array ($POST)
	 * @return type bool
	 * Dev ASG
	 */
	function addOPMData($POST) {
        $now = date('Y-m-d H:i:s');
        $this->tablename = TBL_OPMPACKS;

        $this->field_values['packName'] 			= mysql_real_escape_string($POST['reportname']); 
        $this->field_values['packDescription'] 		= mysql_real_escape_string($POST['reportdesc']); 
        $this->field_values['packVersion'] 			= mysql_real_escape_string($POST['version']); 
        $this->field_values['versionChanges'] 		= mysql_real_escape_string($POST['verchange']); 
        $this->field_values['OPMVersion'] 			= mysql_real_escape_string($POST['opmversion']); 
        $this->field_values['minONTAPVersion'] 		= mysql_real_escape_string($POST['minversion']); 
        $this->field_values['maxONTAPVersion'] 		= mysql_real_escape_string($POST['maxversion']);         
        $this->field_values['otherPrerequisite'] 	= mysql_real_escape_string($POST['otherchanges']); 
		$this->field_values['certifiedBy'] 			= mysql_real_escape_string($POST['certificate']); 
        $this->field_values['authorName'] 			= mysql_real_escape_string($POST['authorname']);         
        $this->field_values['packFilePath'] 		= mysql_real_escape_string($POST['reportfile']); 
        $this->field_values['packDate'] 			= $now;            
		
		$cond = "(packName = '" . mysql_real_escape_string(strtolower($POST['reportname'])) . "' OR  packName = '". mysql_real_escape_string(strtolower($POST['reportname'])) ."' OR packName = '".mysql_real_escape_string($POST['reportname'])."') AND packVersion = '" . $POST['version'] . "'";
		
		$reportData = $this->singleValue(TBL_OPMPACKS, $cond);
		
			if (empty($reportData)){
					$res = $this->insertQry();
					$newTags = array();
					solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', $POST, $newTags, "performance");
					if($res) {
					return 1;
					$this->log->LogInfo("OPM pack inserted.");
					}else{
					$this->log->LogError("Unable to insert OPM pack.");
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
	 * Dev ASG
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
     * function to report a grievance(flag as inapropriate)
     * @params type array $post
     * @return type bool
     * @author ASG
     */  
    function addGrievance($post){       
    
        $now = date('Y-m-d H:i:s');
        $this->tablename = TBL_REPORTFLAG;
		$detailPageUrl = $post['detailPageUrl'];
		if(!empty($_SESSION['uid']))    {
			$userExist = $this->getUserExist();
			//print_r($userExist); exit;
		}else{
			$_SESSION['SESS_MSG'] = msgSuccessFail("error","Flag has not been reported!");			
			header('Location:'.$detailPageUrl);
			exit;
		}
		if($userExist == 0){
			$_SESSION['SESS_MSG'] = msgSuccessFail("error","Flag has not been reported!");			
			header('Location:'.$detailPageUrl);
			exit;
		}
		
		$packname = flagReplace(trim($post['packName'])); 
		$flagcomment = flagReplace(trim($post['flagComment'])); 
		$packversion = flagReplace(trim($post['packVersion']));
		$packtype = flagReplace(trim($post['packType']));
		
		if($packname != 0 || $flagcomment != 0 || $packversion != 0 || $packtype != 0){		
			echo "<script>alert('Illlegal tags are not allowed!');</script>";
			$_SESSION['SESS_MSG'] = msgSuccessFail("error","Flag has not been reported!");			
            header('Location:'.$detailPageUrl);
			exit;
		}
        //$adminEmail = array("ashutosh.garg@netapp.com", "ashish.joshi@netapp.com", "arun.verma@netapp.com");
                        
        /*$adminqry = "SELECT emailId FROM ".TBL_ADMINUSER." WHERE userType ='1'";
        $sql = $this->executeQry($adminqry);*/    
                        
        if($post['packType'] == 'Workflow'){
		$this->field_values['flagPackUuid'] 		=	(isset($post['packUuid']) ? $post['packUuid'] : '');
		}else{
		$this->field_values['flagPackUuid'] 		=	'';
		}            
        $this->field_values['flagPackName']         =   $post['packName'];
        $this->field_values['flagPackVersion']      =   $post['packVersion'];        
        $this->field_values['trademark']            =   (($post['checkTrademark'] == 'true') ? 'true' : 'false');
        $this->field_values['infringement']         =   (($post['checkInfringement'] == 'true') ? 'true' : 'false');
        $this->field_values['flagComment']          =   mysql_real_escape_string($post['flagComment']);
        $this->field_values['flagPackType']         =   $post['packType'];
        $this->field_values['flagBy']               =   $_SESSION['uid'];
		if($post['packType'] == 'Workflow'){
		$this->field_values['flagPackOwner']        =   $post['packOwnerEmail'];
		}else{
		$this->field_values['flagPackOwner']        =   '';
		}
        $this->field_values['flagDate']             =   $now;
        $this->field_values['flagStatus']           =   '1';
        
                $res = $this->insertQry();
                    if($res){
                        /* Email to creator of pack */
                        $subject = "Grievance reported: ". $post['packName'];
                        $message = '<html><body>'; 
                        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
                        $message .= "<tr><td><strong>Hello,</strong> </td></tr>";
                        $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
                        $message .= "<tr style='background: #eee;'><td>A grievance has been reported for your " . $post['packType'] . ".</td></tr>"; 
                        $message .= "<tr style='background: #eee;'><td>Name: " . $post['packName'] . ".</td></tr>"; 
                        $message .= "<tr style='background: #eee;'><td>Comment: " . $post['flagComment'] . ".</td></tr>";                                                                   
                        $message .= "</table>";
                        $message .= '<br>Thank you <br></br>
                        *** This is an automatically generated email, please do not reply ***';
                        $message .= "</body></html>"; 
                                                
                       $creatorEmail = "SELECT * FROM ".TBL_USER." WHERE email='".$post['packContactEmail']."' AND receiveMail= 'true'";						
						$mailsql = $this->executeQry($creatorEmail);  
						$mailNum = $this->getTotalRow($mailsql);
					 
						$ownerEmail = "SELECT * FROM ".TBL_USER." WHERE email='".$post['packOwnerEmail']."' AND receiveMail= 'true'";						
						$mailowner = $this->executeQry($ownerEmail); 
						$mailNumOwner = $this->getTotalRow($mailowner);
					 
						if($post['packType'] != 'OPM'){
							if($mailNum > 0){
								$mailresponseUser = $this->sendEMail($message, $subject, $post['packContactEmail']);
							}							
						}
						if($post['packType'] == 'Workflow'){
								$mailresponseOwner = $this->sendEMail($message, $subject, $post['packOwnerEmail']);
						}
                        
                        /* Email to all admin (Test array)*/
                        /*foreach($adminEmail as $admEmail => $emailval){
                            $mailresponseAdmin = $this->sendEMail($message, $subject, $emailval);
                        }*/
                        /* Email to all admin (adminlogin table)*/
                        /*while($adminEmail = mysql_fetch_assoc($sql)){
                            $mailresponseAdmin = $this->sendEMail($message, $subject, $adminEmail['emailId']);      
                        }*/
						$adminEmail = "ng-store-admins@netapp.com";
						$mailresponseAdmin = $this->sendEMail($message, $subject, $adminEmail);
						
                        
                    $_SESSION['SESS_MSG'] = msgSuccessFail("success","Flag has been reported!");
                        return 1;
                    }else{
                    $_SESSION['SESS_MSG'] = msgSuccessFail("error","Flag has not been reported!");
                        return 0;
                    }
					header('Location:'.$detailPageUrl);
					exit;
    }
	/*
     * function to clear a grievance(flag as inapropriate)
     * @params type int $id
     * @return type bool
     * @author ASG
     */  
    function clearGrievance($getid){
    
        $clearQuery = "SELECT * FROM ".TBL_REPORTFLAG." WHERE id='".$getid[id]."'";  
        
        $sql = $this->executeQry($clearQuery);
        $line = mysql_fetch_object($sql);
        $userName = $line->flagBy;
        $getEmail = "SELECT * FROM ".TBL_USER." WHERE username='".$userName."'";
        $sqlEmail = $this->executeQry($getEmail);
        $lineEmail = mysql_fetch_object($sqlEmail);
        $contactEmail = $lineEmail->email;
        //var_dump($line); echo $contactEmail; die();
                        /* Email to grievance reporter of pack */
                        $subject = "Grievance cleared";
                        $message = '<html><body>'; 
                        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
                        $message .= "<tr><td><strong>Hello,</strong> </td></tr>";
                        $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
                        $message .= "<tr style='background: #eee;'><td>A grievance you reported has been cleared: " . $line->flagPackType . ".</td></tr>"; 
                        $message .= "<tr style='background: #eee;'><td>Name: " . $line->flagPackName . ".</td></tr>"; 
                        
                        $message .= "</table>";
                        $message .= '<br>Thank you <br></br>
                        *** This is an automatically generated email, please do not reply ***';
                        $message .= "</body></html>"; 
                                                
                        $mailGrievanceClear = $this->sendEMail($message, $subject, $contactEmail);
                
            if($sql){
                $del = " UPDATE ".TBL_REPORTFLAG." SET flagStatus = '0' WHERE id = '$getid[id]' ";
                $rst = $this->executeQry($del);
            }else{
                $_SESSION['SESS_MSG'] = msgSuccessFail("error","Your Grievance has not been cleared successfully!!!");
                exit;
            }       
        $_SESSION['SESS_MSG'] = msgSuccessFail("success","Grievance has been cleared successfully!!!");
        
        echo "<script language=javascript>window.location.href='flaggedData.shtml';</script>";
    }
	/*
    * function for listing all flagged packs on admin profile
    *   @params type Null
    *   @return type string
    *   @author ASG
    */
    function flaggedPackFullInformation(){
        $cond = " flagStatus ='1' and flagPackType!='OCI' and flagPackType!='Snapcenter' order by flagDate desc";                
        
        $query = "SELECT * FROM " . TBL_REPORTFLAG . " WHERE $cond";   
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);           
        
        if ($num > 0) {         
                while ($line = $this->getResultObject($sql)) {   
                    $genTable .= '<tr>';
					
                        $genTable .= '<td title="'.$line->flagPackName.'" alt="'.$line->flagPackName.'">'.wrapText($line->flagPackName,20).'</td>';
						/* $genTable .= '<td title="'. preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($line->flagPackType)) .'" alt="'. preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($line->flagPackType)) .'">'.wrapText(preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($line->flagPackType)),20).'</td>'; */
						
                        $genTable .= '<td title="'.$line->flagPackVersion.'" alt="'.$line->flagPackVersion .'">'. $line->flagPackVersion .'</td>';	
						
                        $genTable .= '<td title="'.$line->flagBy.'" alt="'.$line->flagBy .'">'. wrapText($line->flagBy,20).'</td>';
                        $genTable .= '<td title="'.$line->trademark.'" alt="'.$line->trademark .'">'. wrapText(ucfirst($line->trademark),20).'</td>';
						
                        $genTable .= '<td title="'.$line->infringement.'" alt="'.$line->infringement .'">'. wrapText(ucfirst($line->infringement),20).'</td>';
						
                        $genTable .= '<td title="'.preg_replace("/[^a-zA-Z0-9\w ]+/", "", html_entity_decode($line->flagComment)).'" alt="'. preg_replace("/[^a-zA-Z0-9\w\ ]+/", "", html_entity_decode($line->flagComment)).'">'.wrapText(preg_replace("/[^a-zA-Z0-9\w\ ]+/", "", html_entity_decode($line->flagComment)),40) .'</td>';
						
						$genTable .= '<td title="'.$line->flagDate.'" alt="'.$line->flagDate .'">'. date('j F, Y', strtotime($line->flagDate)).'</td>';
						
                        $genTable .= "<td><a class='packAction' href='javascript:void(NULL);' onClick=\"if(confirm('Are you sure you want to clear this Grievance?')){window.location.href='pass.php?action=clearFlag&type=clear&id=".$line->id."'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Clear Flag' alt='Clear Flag' /></a></td>";    
                                                                        
                    $genTable .= '</tr>';               
                }                   
        }else {         
             $genTable = '<tr><td colspan="9" > no records found</td></tr>';   
        }
        return $genTable;       
    }
	
	
	/*
    * function for listing all flagged packs on admin profile
    *   @params type Null
    *   @return type string
    *   @author ASG
    */
    function flaggedPackOciFullInformation(){
        $cond = " flagStatus ='1' and flagPackType='OCI'  order by flagDate desc";                
        
        $query = "SELECT * FROM " . TBL_REPORTFLAG . " WHERE $cond";   
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);           
        
        if ($num > 0) {         
                while ($line = $this->getResultObject($sql)) {   
                    $genTable .= '<tr>';
					
                        $genTable .= '<td title="'.$line->flagPackName.'" alt="'.$line->flagPackName.'">'.wrapText($line->flagPackName,20).'</td>';
						
						
                        $genTable .= '<td title="'.$line->flagPackVersion.'" alt="'.$line->flagPackVersion .'">'. $line->flagPackVersion .'</td>';	
						
                        $genTable .= '<td title="'.$line->flagBy.'" alt="'.$line->flagBy .'">'. wrapText($line->flagBy,20).'</td>';
                        $genTable .= '<td title="'.$line->trademark.'" alt="'.$line->trademark .'">'. wrapText(ucfirst($line->trademark),20).'</td>';
						
                        $genTable .= '<td title="'.$line->infringement.'" alt="'.$line->infringement .'">'. wrapText(ucfirst($line->infringement),20).'</td>';
						
                        $genTable .= '<td title="'.preg_replace("/[^a-zA-Z0-9\w ]+/", "", html_entity_decode($line->flagComment)).'" alt="'. preg_replace("/[^a-zA-Z0-9\w\ ]+/", "", html_entity_decode($line->flagComment)).'">'.wrapText(preg_replace("/[^a-zA-Z0-9\w\ ]+/", "", html_entity_decode($line->flagComment)),30) .'</td>';
						
						$genTable .= '<td title="'.$line->flagDate.'" alt="'.$line->flagDate .'">'. date('j F, Y', strtotime($line->flagDate)).'</td>';
						
                        $genTable .= "<td><a class='packAction' href='javascript:void(NULL);' onClick=\"if(confirm('Are you sure you want to clear this Grievance?')){window.location.href='pass.php?action=clearFlag&type=clear&id=".$line->id."'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Clear Flag' alt='Clear Flag' /></a></td>";    
                                                                        
                    $genTable .= '</tr>';               
                }                   
        }else {         
             $genTable = '<tr><td colspan="9" > no records found</td></tr>';   
        }
        return $genTable;       
    }
	/*
    * function for listing all flagged packs on user profile
    *   @params type Null
    *   @return type string
    *   Dev ASG
    */
    function flaggedPackFullInformationUserProfile(){
        //$cond = " flagStatus ='1' ";                
        $cond = " flagStatus ='1' AND flagPackOwner= '".$_SESSION['mail']."'  and flagPackType!='OCI' and flagPackType!='Snapcenter'  order by flagDate desc" ;                
        
        $query = "SELECT * FROM " . TBL_REPORTFLAG . " WHERE $cond";   
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);    
            
        
        if ($num > 0) { 
    //$_SESSION['userEmail'] = "ashutosh.garg@netapp.com";      
                $flag= 'false';
				while ($line = $this->getResultObject($sql)) {
                   // $queryUser = "SELECT * FROM " . TBL_PACKDETAILS . " WHERE version='".$line->flagPackVersion."' AND uuid ='".$line->flagPackUuid."' AND contactEmail ='".$_SESSION['mail']."'"; 
				   
                   $queryUser = "SELECT * FROM " . TBL_PACKDETAILS . " WHERE version='".$line->flagPackVersion."' AND uuid ='".$line->flagPackUuid."'";

                   
                    $sqlUser = $this->executeQry($queryUser);
                    $numUser = $this->getTotalRow($sqlUser);
                    if ($numUser > 0) {             
                        $flag='true';       
                        $genTable .= '<tr>';
                            $genTable .= '<td>'.$line->flagPackName.'</td>';                            
                            $genTable .= '<td>'.$line->flagPackVersion.'</td>';                         
                            $genTable .= '<td>'.ucfirst($line->trademark).'</td>';
                            $genTable .= '<td>'.ucfirst($line->infringement).'</td>';
                            $genTable .= '<td>'.$line->flagComment.'</td>';                         
                            $genTable .= '<td>'.date('j F, Y', strtotime($line->flagDate)).'</td>';                            
                                                                            
                        $genTable .= '</tr>';                       
                    } 
       if($flag == 'false')
        {
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
                }
                    // while    
        }else {         
             $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
        return $genTable;
    }
	
		/*
    * function for listing all flagged packs on user profile
    *   @params type Null
    *   @return type string
    *   Dev ASG
    */
    function flaggedPackOciFullInformationUserProfile(){
        //$cond = " flagStatus ='1' ";                
        $cond = " flagStatus ='1' AND flagPackOwner= '".$_SESSION['mail']."' and flagPackType='OCI'  order by flagDate desc" ;                
        
        $query = "SELECT * FROM " . TBL_REPORTFLAG . " WHERE $cond";   
        $sql = $this->executeQry($query);
        $num = $this->getTotalRow($sql);    
            
        
        if ($num > 0) { 
    //$_SESSION['userEmail'] = "ashutosh.garg@netapp.com";      
                $flag= 'false';
				while ($line = $this->getResultObject($sql)) {
                   // $queryUser = "SELECT * FROM " . TBL_PACKDETAILS . " WHERE version='".$line->flagPackVersion."' AND uuid ='".$line->flagPackUuid."' AND contactEmail ='".$_SESSION['mail']."'"; 
				   
                   $queryUser = "SELECT * FROM " . TBL_OCIPACKS . " WHERE packVersion='".$line->flagPackVersion."' AND packName ='".$line->flagPackName."'";

                   
                    $sqlUser = $this->executeQry($queryUser);
                    $numUser = $this->getTotalRow($sqlUser);
                    if ($numUser > 0) {             
                        $flag='true';       
                        $genTable .= '<tr>';
                            $genTable .= '<td>'.$line->flagPackName.'</td>';                            
                            $genTable .= '<td>'.$line->flagPackVersion.'</td>';                         
                            $genTable .= '<td>'.ucfirst($line->trademark).'</td>';
                            $genTable .= '<td>'.ucfirst($line->infringement).'</td>';
                            $genTable .= '<td>'.$line->flagComment.'</td>';                         
                            $genTable .= '<td>'.date('j F, Y', strtotime($line->flagDate)).'</td>';                            
                                                                            
                        $genTable .= '</tr>';                       
                    } 
       if($flag == 'false')
        {
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
                }
                    // while    
        }else {         
             $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
        return $genTable;
    }
    
	/*
	 * function to send email 
	 * @params type	string body, subject, packType, packName
	 * @return type bool
	 * @author ASG
	 */
	   
function sendEMail($message, $subject, $to){
    $mail = new PHPMailer(); 
    $mail->SMTPAuth   = true;
    $mail->Host       = "smtp.netapp.com";
    $mail->Port       = 25; 

    $mail->SetFrom('no-reply@netapp.com', 'No Reply');
    // $mail->AddAddress('ng-acn-utility-services-request@netapp.com', 'NetApp Group platform');
    $mail->AddAddress($to, ''); 
    //$mail->AddAddress('ashutosh.garg@netapp.com', 'Ashutosh Garg'); 
    
    $mail->Subject = $subject ; 

    $mail->MsgHTML($message); 

    $mail->Send();

       /* if(!$mail->Send()){
            echo "Message could not be sent. <p>";
            echo "Mailer Error: " . $mail->ErrorInfo;
               
        }*/
    }

	
	/** hc
     * workflowHelp
     * Function for fetching full information of workflow help.
     *
     * @param    Int	 
     * @return   String
     */
    function workflowHelp($packUuid,$packVersion) {
		//$packFilePath 	= $this->fetchValue(TBL_PACKDETAILS,'packFilePath',"uuid='".$packUuid."'");	
		 $packQuery = "Select * from ".TBL_PACKDETAILS." where uuid='".$packUuid."' and version='".$packVersion."' ";
		
        $packsql = $this->executeQry($packQuery);
        $packnum = $this->getTotalRow($packsql);
		$line = $this->getResultObject($packsql);

		
		$packFilePath = $line-> packFilePath;
		$path_info  = pathinfo($packFilePath);

		$filePath = PATH.$path_info['dirname']."/".$path_info['filename']."/workflow-help";
		$siteFilePath = SITEPATH.$path_info['dirname']."/".$path_info['filename']."/workflow-help";
		$directories = glob($filePath . '/*' , GLOB_ONLYDIR);
	//	echo $filePath;
		
        if (count($directories) > 0) {			
			$genTable = '<div id="list4"><section class="comman-link wfhelp"><table><thead><tr><td>WorkFlow Help</td></tr></thead>';		
                foreach($directories as $key => $value) {
				//basename($value)
					$key = $key+1;
					$genTable .= '<tr>
								<td id="list-'.$key.'"><a target="_blank" href="'.$siteFilePath.'/'.basename($value).'/index.htm"><strong>'.$line->packName.'-Help-'.$key.'</strong></a></td></tr>';
					
				}
			
			$genTable .= '</table>
</section></div>';
		
		 } else {
            $genTable = '<div>&nbsp;</div><div class="Error-Msg"> no help found for this pack</div>';
        }
        return $genTable;
	}
	
	/* report information 
	 *Function for fetching full information of reports .
	 * @param    none
     * @return   String
	 * author: hc
	*/
	  function reportFullInformation() {	
	  $count=1;//used in delhc
			$queryMXversion = "Select * from ". TBL_OCUMREPORTS." join(SELECT MAX( reportVersion ) AS Vsion,reportName AS Nme FROM ". TBL_OCUMREPORTS." GROUP BY reportName )mvx ON reportVersion = mvx.Vsion and reportName = mvx.Nme GROUP BY reportName";
			$orderby = $_GET[orderby] ? $_GET[orderby] : "reportDate";
			$order = $_GET[order] ? $_GET[order] : "DESC";
			$queryMXversion .= " ORDER BY $orderby $order";
			$sqlMXversion = $this->executeQry($queryMXversion);
			$numMXversion = $this->getTotalRow($sqlMXversion);
							
		
		  if ($numMXversion > 0){
			
			
						while ( $lineReportMXversion= $this->getResultObject($sqlMXversion)) 
								{
														
							$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
							//$userType= '1';/*for testing*/											
							$encodedPath = base64_encode($lineReportMXversion->reportFilePath);	
							if ($lineReportMXversion->certifiedBy == 'NETAPP') {
					$genTable .= '<tr><td ><img src="images/netapp-certified-icon.png"></td>';                    
														} else {
																	$genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
																}
							$genTable .= '<td><a href="reports-detail.shtml?reportName='.implode("!",explode(" ",$lineReportMXversion->reportName)).'&reportVersion='.$lineReportMXversion->reportVersion.'">'.$lineReportMXversion->reportName.'</a></td>';								
							
					
					$genTable.='
					<td><a href="javascript:void(0)" class="triggersmall">' .$lineReportMXversion->reportVersion.'</a>';
					
										  
					$genTable.='
					<div class="pop-up">
					<img src="images/popup-arrow.png" alt="" class="pop-arrow">
						<table cellpadding="0" cellspacing="0" border="0">
							<thead>
									<tr>													
									<td>Version</td>
									<td>OCUM Version</td>
									</tr>
							</thead>
							';	
					$query1="SELECT * FROM " . TBL_OCUMREPORTS . " WHERE reportName='" . $lineReportMXversion->reportName . "'order by reportVersion DESC ";					
					$sql1 = $this->executeQry($query1);
					$numEntity = $this->getTotalRow($sql1);
					if ($numEntity > 0) {

										$j = 1;
										$delVersion = array();
										while ($linePack = $this->getResultObject($sql1)) { 
											$genTable .= '
															<tr>
																<td>' . $linePack->reportVersion . '</td>
																<td>' . $linePack->OCUMVersion . '</td>
															</tr>';	
										
										$delVer=array($linePack->id,$linePack->reportVersion );
										array_push($delVersion, $delVer);
											//print_r($delVer);	
											$j++;
																					} 
										}
					$delArray=array($lineReportMXversion->reportName => $delVersion);
					/* echo "<pre>";
					print_r($delArray); */		
							$genTable.='</table>
							</div>
							</td>';
					$genTable .= '<td>' .$lineReportMXversion->OCUMVersion. '</td>		
							<td>' . date('j F, Y', strtotime($lineReportMXversion->reportDate)) . '</td>	
										  <td><a href="eula.shtml?reportName=' . implode("!",explode(" ",$lineReportMXversion->reportName)).'&reportVersion=' . $lineReportMXversion->reportVersion .'&packType=report" name="download" class="packAction"><img src="images/downlaod-icon.png" border="0" /></a></td> ';
							
					
												
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
																	<input type="hidden" name="page" value="reports">
																	<input type="hidden" name="action" value="managePack">
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
						}else {
           $genTable = '<tr><td colspan="9" >Sorry no records found</td></tr>';
        }
						 return $genTable;
		}
		
	/* performance information 
	 * Function for fetching full information of performance .
	 * @param    none
     * @return   String
	 * author: hc
	*/
	function performanceFullInformation() {								
			$count=1;//used in delhc
			$queryMXversion = " Select * from ".TBL_OPMPACKS." join(SELECT MAX( packVersion ) AS Vsion,packName AS Nme FROM ".TBL_OPMPACKS." GROUP BY packName )mvx ON packVersion = mvx.Vsion and packName = mvx.Nme GROUP BY packName";
			$orderby = $_GET[orderby] ? $_GET[orderby] : "packDate";
			$order = $_GET[order] ? $_GET[order] : "DESC";
			$queryMXversion .= " ORDER BY $orderby $order";
			$sqlMXversion = $this->executeQry($queryMXversion);
			$numMXversion = $this->getTotalRow($sqlMXversion);
			

						
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
							$genTable .= '<td><a href="performance-detail.shtml?packName='.implode("!",explode(" ",$lineReportMXversion->packName)).'&packVersion='.$lineReportMXversion->packVersion.'">'.$lineReportMXversion->packName.'</a></td>';								
								
					
					$genTable.='
					<td><a href="javascript:void(0)" class="triggersmall">' .$lineReportMXversion->packVersion.'</a>';
					
										  
					$genTable.='
					<div class="pop-up">
					<img src="images/popup-arrow.png" alt="" class="pop-arrow">
						<table cellpadding="0" cellspacing="0" border="0">
							<thead>
									<tr>													
									<td>Version</td>
									<td>OPM Version</td>
									</tr>
							</thead>
							';		
					$query1="SELECT * FROM " . TBL_OPMPACKS . " WHERE packName='" . $lineReportMXversion->packName . "'order by packVersion DESC ";					
					$sql1 = $this->executeQry($query1);
					$numEntity = $this->getTotalRow($sql1);							
					if ($numEntity > 0) {

										$j = 1;
										$delVersion = array();
										while ($linePack = $this->getResultObject($sql1)) {
										
										$genTable .= '
														<tr>
															<td>' . $linePack->packVersion . '</td>
															<td>' . $linePack->OPMVersion . '</td>
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
					$genTable .= '<td>' .$lineReportMXversion->OPMVersion. '</td>		
							<td>' . date('j F, Y', strtotime($lineReportMXversion->packDate)) . '</td>	
										  <td><a href="eula.shtml?packName='.implode("!",explode(" ",$lineReportMXversion->packName)).'&packVersion='.$lineReportMXversion->packVersion.'&packType=performance" name="download" class="packAction"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td> ';
										  
							
					
								
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
																						<input type="hidden" name="page" value="performance">
																						<input type="hidden" name="action" value="managePack">
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


    
    
     /**
     *
     * Function for updating tags of pack uploaded by user.
     *
     * @param    post data
     * @return   
     * @author   Arun Verma
     *
     */
     function updateTag($post)
     {
       
         $query = "Update ".TBL_PACKDETAILS." set tags = '".$post['tags']."' where uuid = '".$post['uuid']."' and version = '".$post['version']."' ";
         $this->executeQry($query);

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
		/* Function for add Deprecate (WFA/Report/Performance)
		 * @params type array $post
		 * @author ASG
		 */
		function addDeprecate($post){
			//print_r($post);exit;
			
			$now = date('Y-m-d H:i:s');
			$this->tablename = TBL_DEPRECATED;
			$detailPageUrl = $post['detailPageUrl'];
			if(!empty($_SESSION['uid'])) {
				$userExist = $this->getUserExist();				
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
		* function for deprecation deletion
		* @author - ASG
		*/
		function setDeprecationFlag($post){			
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
		
	function updateSolr($post)
	{
	
		$query = "SELECT id from packdetails where uuid='".$post['wfa_pack_uuid']."' and version='".$post['wfa_pack_version']."'";
		
		$sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);

        $line = mysql_fetch_row($sql);

        $id=$line[0];
		/* echo "<pre>";
		print_r($post); */
		$newTags = explode(",",trim($post['wfa_tags']));
		
		$newString="";
		for($j=0;$j< count($newTags);$j++)
		{
			if(!empty($newTags[$j]))
			{
				if($j < (count($newTags)-2))
					$newString .='"'.$newTags[$j].'",';
				else
					$newString .='"'.$newTags[$j].'"';
			}
			
		}
		
		
$data_string = '[{"id":"'.$id.'","certifiedBy":{"set":"'.$post['wfa_certificate'].'"},"tags":{"set":['.$newString.']}}]';      

	//	echo $data_string;exit;
                                                                                    
		$ch = curl_init("http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/update?commit=true");                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  
		curl_setopt($ch, CURLOPT_USERPWD, "wfsuser:wfs@123#$");
		//url_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);                                                                   
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
																															 
		$result = curl_exec($ch);
		//print_r($result );
	}

}
?>
