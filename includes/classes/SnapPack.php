<?php
@session_start();
ob_start();
require_once('class.phpmailer.php');
/**
 * SnapPack.shtml
 * This class implements various functionalities related to information regarding Packs.
 * 
 */

class SnapPack extends MySqlDriverPDO {
    function __construct() {
    
        $obj = new MySqlDriverPDO;
        parent::__construct();
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
        $packFilePath = $post['packFilePath'];
        /***new changes**/
        $newPack = explode('.'.$name[1],$packFilePath);
        $packPath = $docRoot . $newPack[0];
        $newPath = $packPath . '/';
        $xmlFile = $newPath . 'Plugin_descriptor.xml';
        //New changes end
        $countEntity = $post['countEntity'];
        $tags = $post['wfa_tags'];
        $type = $post['wfa_type'];
        $now = date('Y-m-d H:i:s');
        $this->tablename = TBL_SNAPDETAILS;
        $this->field_values['packName']         = $post['wfa_name'];
        $this->field_values['packDescription']  = addslashes(htmlentities($post['wfa_desc'])); 
        $this->field_values['readme_txt']  = addslashes(htmlentities($post['snap_help'])); 
        $this->field_values['plugin_type']  = htmlspecialchars($post['plugin_type']); 
        $solrUuid = '';
            
         if (!file_exists($xmlFile)) 
            {
                    if(isset($post['wfa_pack_uuid']))
                    {
                            $uuidNew=implode("_",explode(" ",$post['wfa_name']));               
                            $solrUuid = $uuidNew;
                    }
            }
            else {
            $uuidNew=$post['wfa_name'];
           }
        $this->field_values['uuid']             = $uuidNew;
        $this->field_values['author']           = htmlspecialchars($post['packDataXml']['author']);
        $this->field_values['certifiedBy']      = htmlspecialchars($post['wfa_certificate']);
        $this->field_values['version']          = htmlspecialchars($post['wfa_pack_version']);
        $this->field_values['whatsChanged']     = htmlspecialchars($post['wfa_version_changes']);
        $this->field_values['minWfaVersion']    = htmlspecialchars($post['wfa_version']);
        $this->field_values['minsoftwareversion']   = htmlspecialchars($post['wfa_min_soft_version']);
        $this->field_values['maxsoftwareversion']   = htmlspecialchars($post['wfa_max_soft_version']);
        $this->field_values['preRequisites']    = htmlspecialchars($post['wfa_other']);
        $this->field_values['communityLink'] = htmlspecialchars($post['wfa_community_link']);
        $this->field_values['keywords']         = htmlspecialchars($post['packDataXml']['keywords']);
        $this->field_values['packFilePath']     = $packFilePath;
        $this->field_values['contactName']  = htmlspecialchars($post['wfa_contact_name']);
        $this->field_values['contactEmail'] = htmlspecialchars($post['wfa_contact_email']);
        $this->field_values['contactPhone'] = htmlspecialchars($post['wfa_contact_phone']);
        $this->field_values['communityLink'] = htmlspecialchars($post['wfa_cummunity_link']);
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

        $packData = $this->singleValue(TBL_SNAPDETAILS, "uuid = '" .$this->sanitize($uuidNew,'string') . "' and version = '" . $this->sanitize($post['wfa_pack_version'],'float') . "'");

        if (empty($packData)) {
                
            $res = $this->insertQry();
            $res[0]->execute();
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
                    solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', '/var/www/html/workflowstore/'.$filePath[0] . '/Plugin_descriptor.xml', $newTags, $type,'zip','');  // for stage           
                    
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
          if($countEntity > 1){
                            for($i=0;$i<$countEntity;$i++)
                                {   
                                //changed the values
                                    $this->tablename = TBL_SNAPENTITIES;    
                                    $this->field_values['packId'] = $packId;
                                    $this->field_values['name']         = htmlspecialchars($packEntities['entity'][$i]['name']);
                                    $this->field_values['description']  = htmlspecialchars($packEntities['entity'][$i]['description']);
                                    $this->field_values['certifiedBy']  =  htmlspecialchars($packEntities['entity'][$i]['certification']);
                                    $this->field_values['uuid']         = htmlspecialchars($packEntities['entity'][$i]['uuid']);
                                    $this->field_values['version']      =  htmlspecialchars($packEntities['entity'][$i]['version']);
                                    $this->field_values['entityType']   = htmlspecialchars(trim($packEntities['entity'][$i]['type']));
                                    $this->field_values['scheme']       = htmlspecialchars($packEntities['entity'][$i]['schemeNames'][$i]);                         
                            
                                    $this->field_values['entityDate']   = htmlspecialchars($now); 
                                    $res = $this->insertQry();
                                    $res[0]->execute();
                                    $entityId = $this->insert_id();
                                }   
                        }                       
                         else{
                                $this->tablename = TBL_SNAPENTITIES;    
                                    $this->field_values['packId'] = $packId;
                                    
                                    $this->field_values['name']         = htmlspecialchars($packEntities['entity']['name']);
                                    $this->field_values['description']  = htmlspecialchars($packEntities['entity']['description']);
                                    $this->field_values['certifiedBy']  =  htmlspecialchars($packEntities['entity']['certification']);
                                    $this->field_values['uuid']         = htmlspecialchars($packEntities['entity']['uuid']);
                                    $this->field_values['version']      =  htmlspecialchars($packEntities['entity']['version']);
                                    
                                    $this->field_values['entityType']   = htmlspecialchars(trim($packEntities['entity']['type']));
                                    $this->field_values['scheme']       = htmlspecialchars($packEntities['entity']['schemeNames']);                         
                                    $this->field_values['entityDate']   = htmlspecialchars($now); 
                                    $res = $this->insertQry();
                                    $res[0]->execute();
                                    $entityId = $this->insert_id();   
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
        $this->tablename = TBL_SNAPDETAILS;   
        $this->field_values['whatsChanged'] = $post['wfa_version_changes'];
        $this->field_values['minsoftwareversion'] = $post['wfa_min_soft_version'];
        $this->field_values['maxsoftwareversion'] = $post['wfa_max_soft_version'];
        $this->field_values['preRequisites'] = $post['wfa_other'];        
        $this->field_values['certifiedBy'] = $post['wfa_certificate'];
        $this->field_values['contactName']  = $post['wfa_contact_name'];
        $this->field_values['contactEmail'] = $post['wfa_contact_email'];
        $this->field_values['contactPhone'] = $post['wfa_contact_phone'];
        $this->field_values['tags']         = $post['wfa_tags'];
        $this->field_values['modDate'] = $now;
        
        $userType =$this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
        $this->condition  = "uuid = '".$post['wfa_pack_uuid']."' and version='".$post['wfa_pack_version']."'";
        $res = $this->updateQry();
        $this->updateSolr($post);
        
        
        if($res){
                $packId = $this->insert_id();
                return 1;
        } else {
                return 0;
        }
    }   

    
    /*
     * function to report for new data protection pack to administrator
     * @params type array $post
     * @return type bool
     * Dev ASG
     */  
    function adminEmailNotify2($post){      
    
        $now = date('Y-m-d H:i:s');
        $subject = "New ".CONSTANT_UCWORDS." pack upload Notification: ". $post['wfa_name'];              
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
            $mailresponseAdmin = $this->sendEMail($message, $subject, 'afsar.khan@infobeans.com');      
        }
        return $mailresponseAdmin;
    }
    /*
     * function to report for new data protection pack to administrator
     * @params type array $post
     * @return type bool
     */
    function adminEmailNotify($post){           
    
        $now = date('Y-m-d H:i:s');
        
        $subject = "New ".CONSTANT_UCWORDS." pack upload Notification: ". $post['wfa_name'];    
        
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
            $mailresponseAdmin = $this->sendEMail($message, $subject, 'afsar.khan@infobeans.com');      
        
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
        
        $query = "SELECT * FROM " . TBL_SNAPDETAILS . " where uuid =:uuid  and version = :version";
        $sql = $this->executeQry($query);
        $sql->bindParam(':uuid', $this->sanitize($uuid,'string'), PDO::PARAM_INT);    
        $sql->bindParam(':version', $this->sanitize($version,'float'), PDO::PARAM_INT);
        $exec = $sql->execute();
        $numEntity = $this->getTotalRow($sql);
        $packArray = array();
        if ($numEntity > 0) {
                $line = $sql->fetch(PDO::FETCH_ASSOC); 
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

        $now = date('Y-m-d H:i:s');
        $this->tablename = TBL_SNAPUSERPACKS;
        $this->field_values['packId'] = $packId;
        $this->field_values['firstName'] = $_SESSION['firstName'];
        $this->field_values['lastName'] =  $_SESSION['lastName'];
        $this->field_values['userId'] = $_SESSION['uid'];
        $this->field_values['userEmail'] = $_SESSION['mail']; 
        $this->field_values['uploadDate'] = $now;
        $res = $this->insertQry();
        $res[0]->execute();
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
                        $res = $this->insertQry();
                        $res[0]->execute();
                  
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
         $query = "Select * from ".TBL_USER." where userName = :userName";
         $sql = $this->executeQry($query);
         $sql->bindParam(':userName', $this->sanitize($_SESSION['uid'],'string'), PDO::PARAM_INT);
         $exec = $sql->execute();
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
        $sessionuid = $this->sanitize($_SESSION['uid'],'string');    
         if(empty($sessionuid)){             
             return 0;
         }else{
             $query = "SELECT * FROM ".TBL_USER." WHERE username = :username";
             $sql = $this->executeQry($query);
             $sql->bindParam(':username', $sessionuid, PDO::PARAM_INT);
             $exec = $sql->execute();
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
    /**
    * Function to sanitize and filter the input
    * @param type $input
    * @param type $type
    * @return mixed 
    */
    function sanitize($input,$type='string') {
     
    if($type =='float'){
        $output = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    }else if($type =='int'){
        $output = filter_var($input, FILTER_SANITIZE_NUMBER_INT,FILTER_FLAG_ALLOW_FRACTION);
    }else if($type =='email'){
        $output = filter_var($input, FILTER_SANITIZE_EMAIL,FILTER_FLAG_ALLOW_FRACTION);
    }else if($type =='string'){
        $output = preg_replace("/[^a-zA-Z0-9-_.]/","", $input); 
    }
        return $output;
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
        $userType =$this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $this->sanitize($_SESSION['uid'],'string') . "'");
        $user = (array)$userType;
       
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $this->sanitize($_REQUEST['searchtxt'],'string');
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }
        
        $cond = " certifiedBy='NETAPP' and post_approved = 'true' GROUP BY uuid";
        
        if ($certi== "NONE"){
        $cond = " certifiedBy !='NETAPP' and post_approved = 'true' GROUP BY uuid";
        } 
                 
        $query= "Select * from " . TBL_SNAPDETAILS . " join(SELECT MAX( version ) AS Vsion,uuid AS Uid FROM " . TBL_SNAPDETAILS . " WHERE $cond )mvx ON version = mvx.Vsion and uuid = mvx.Uid and $cond";       
        $orderby = $_GET[orderby] ? $_GET[orderby] : "packDate";
        $order = $_GET[order] ? $_GET[order] : "DESC";
        $query .= " ORDER BY $orderby $order";  
        $sql = $this->executeQry($query);
        $exec = $sql->execute();
        $num = $this->getTotalRow($sql);
        
        if ($num > 0) 
        {
           $delArray=array();
                while ($line = $this->getResultObject($sql)) {
                    $checkValue = (($line->cautionStatus == 'true') ? 'checked' : '');              
                    $genTable .= '<tr>';
                    //$user['0'] = 1; /*testing purpose only*/
                        if ($user['0']== 1) {
                    $genTable.='<td><input type="checkbox" name="caution"  class="enableCaution' . $line->id . '" onclick="setSnapCaution('. $line->id .',\'' . $line->uuid . '\');" ' . $checkValue . '/> <span id="cautionLoader' . $line->id . '"></span></td>';
                                            }
                                            
                    if ($line->certifiedBy == 'NETAPP') {
                    $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                                                        } else {
                                                                    $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                                                                }
                                                                    
                    $genTable .= '          
                                <td><a id="packHeader" href="snap-detail.shtml?packUuid='.$line->uuid.'&packVersion='.$line->version.'">'.$line->packName.'</a></td>';
                    
                    
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
                                    <td style="width:130px">Min Snap Center Version</td>
                                    <td style="width:115px">Windows Compatibility</td>
                                    <td>Linux Compatibility</td>
                                    </tr>
                            </thead>
                            ';
                    
                    $cond1 = " certifiedBy='NETAPP' and post_approved = 'true'";
        
                    if ($certi== "NONE"){
                    $cond1 = " certifiedBy !='NETAPP' and post_approved = 'true'";
                    }
                    
                    $query1="SELECT * FROM " . TBL_SNAPDETAILS . " WHERE uuid=:uuid and $cond1 order by version DESC ";
                    
                    $sql1 = $this->executeQry($query1);
                    $sql1->bindParam(':uuid', $this->sanitize($line->uuid,'string'), PDO::PARAM_INT);
                    $exec = $sql1->execute();
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
                                            $genTable .= '  <td class="novalue">-</td>';
                                            }
                                            else{
                                            $genTable .= '  <td>' . $linePack->minsoftwareversion. '</td>';
                                            }
                                            if (empty( $linePack->maxsoftwareversion)){
                                            $genTable .= '  <td class="novalue">-</td>';
                                            }
                                            else{
                                            $genTable .= '  <td>' . $linePack->maxsoftwareversion. '</td>';
                                            }
                                            $genTable .= '
                                            </tr>';                                     
                                            $delVer=array($linePack->id,$linePack->version );
                                            array_push($delVersion, $delVer);
                                            $j++;
                                                                                    } 
                                        } 
                                            
                        
                    $delArray=array($line->packName => $delVersion);
                        
                    //pop-up on hover-start
                    
                    $genTable .= '          </table>
                    </div>
                    </td>';
                    $genTable .= '<td>' .$line->minWfaVersion. '</td>';
                    if ($line->certifiedBy == 'NETAPP' ){
                                                            $genTable .= '<td>NetApp</td>';
                                                        }else{
                                                                $genTable .= '<td></td>';
                                                              }             
                    
                    $genTable .= '<td>' . date('jS F, Y', strtotime($line->packDate)) . '</td>
                    <td><a href="snapcenterHelp.shtml?packUuid='.$line->uuid.'&packVersion='.$line->version.'"><img src="images/documentation-icon.png" border="0" title="Documentation" /></a></td>';
                    
                    if ($checkValue) {
                    $genTable .= '<td><a href="cautionPage.shtml?packUuid=' . $line->uuid .'&packVersion=' . $line->version.'&packType=snapcenter&certi='.$certi.'" name="download" class="packAction"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td>';}
                    else {
                    $genTable .= '<td><a href="eula.shtml?packUuid=' . $line->uuid .'&packVersion=' . $line->version.'&packType=snapcenter&certi='.$certi.'"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td> ';
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
                                                    <input type="hidden" name="page" value="snap-list">
                                                    <input type="hidden" name="action" value="manageSnapPack">
                                                    <input type="hidden" name="type" value="delete">                                                
                                                    </div>
                                                    <footer> 
                                                            
                                                            <input type="submit" value="Delete" class="btn" />      
                                                           </footer>
                                                           </form>
                                                           </div>
                                                        <!-- pop end -->';
                                                        
                                                        
                                                        }$count++; //delhcend

                        $genTable .= '<td><a href="edit-caution.shtml?packUuid=' .$line->uuid . '&type=snapcenter&certi=' .$certi . '" name="download" class="packAction" ><img src="images/edit.png" height="16" width="16" border="0" title="Edit Caution" alt="Edit CautionEdit Caution" /></a></td></tr>';
                       
                    }
                    $i++;
                    
                }
        }
         else {
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
        $query = "SELECT * FROM " . TBL_SNAPDETAILS . " where uuid = '$packId'";
        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);
        if ($numEntity > 0) {
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

        $query = "SELECT * FROM " . TBL_SNAPDETAILS . " ";
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
        $query = "SELECT id from " . TBL_SNAPDETAILS . " ORDER BY id DESC LIMIT 1";
        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);
        $line = mysql_fetch_row($sql);
        return $line[0];
    }
    
    
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
        $packQuery = "Select * from ".TBL_SNAPDETAILS." where uuid=:uuid and version=:version ";
        $packsql = $this->executeQry($packQuery);
        $packsql->bindParam(':uuid', $this->sanitize($packUuid,'string'), PDO::PARAM_INT);
        $packsql->bindParam(':version', $this->sanitize($packVersion,'float'), PDO::PARAM_INT);
        $exec = $packsql->execute();
        $packnum = $this->getTotalRow($packsql);
        $line = $this->getResultObject($packsql);
        
        $packId=$line->id;
        $firstName = $_SESSION['firstName'];
        $lastName  = $_SESSION['lastName'];
        $companyName = ucfirst($_SESSION['CompanyName']);
        $companyAddress = $_SESSION['companyAddress1']." ".$_SESSION['companyAddress2'];

        $this->field_values['packId']           = $packId;
        $this->field_values['firstName']        = $firstName;
        $this->field_values['lastName']         = $lastName;
        $this->field_values['packName']         = $packName;
        $this->field_values['packVersion']      = $line->version;    
        $this->field_values['minWfaVersion']      = $line->minWfaVersion;
        $this->field_values['certifiedBy']      = $line->certifiedBy;
        $this->field_values['author']      = $line->author;
        $this->field_values['packDate']      = $line->packDate;
        $this->field_values['companyName']      = ucfirst($_SESSION['CompanyName']);
        $this->field_values['companyAddress']   = $companyAddress;
        $this->field_values['packType']         = $typePack;
        $this->field_values['downloadDate']     = $now;
        $res = $this->insertQry();
        $res[0]->execute();

    }

    /**
     * getPackById
     * Function to fetch a particular pack using id.
     * 
     * @param type $packId
     * @return type
     */
    function getPackById($packId) {
        $query = "SELECT * from " . TBL_SNAPDETAILS . " where id=" . $packId . "";
        $sql = $this->executeQry($query);
        $numEntity = $this->getTotalRow($sql);
        if ($numEntity > 0) {
            return $this->getResultObject($sql);
        }
    }

    /* deleteValue
     * Function for deleting of many versions of a particular snap pack on the basis of their respective unique elements .
     * @param    $get    
     * author: hc
    */
    function deleteValue($get)
    {    
         if (!isset($get[idToDel]) || $get[idToDel]== null ){
         $_SESSION['SESS_MSG'] = msgSuccessFail("fail","No versions were selected to delete");
         header( 'Location:'.basename($_SERVER['HTTP_REFERER']) );
         exit;
         }
    
        foreach($get[idToDel] as $idToDel)
            { 
               $type="";
    
                if($get[page] == "snap-list" || $get[page] == "user_snapprofile" || $get[page] == "admin_profile")
                    {
                        $idToDel = $this->sanitize($idToDel,'string');
                        $packFilePath = $this->fetchValue(TBL_SNAPDETAILS,"packFilePath","id = '".$idToDel."'");
                        $query="SELECT * from ".TBL_SNAPDETAILS." WHERE id='".$idToDel."' ";
                        $queryDelete = " DELETE FROM ".TBL_SNAPDETAILS." WHERE id = '".$idToDel."' ";
                        $fieldName="pack";
                        $type="snapcenter";
                        
                    }
                    else 
                    {                       
                        header( 'Location: home.php' );
                    }
                            
                $sql = $this->executeQry($query);
                $exec = $sql->execute();
                $line = $this->getResultObject($sql);
    
                 if(!empty($packFilePath))
                    {
                        $path_parts = pathinfo(PATH.$packFilePath);
                        $unlinkDir = $path_parts['dirname'];
                        rrmdir($unlinkDir);                             
                        $packId=$idToDel;
                        
                        //deletetion query execute
                        $rst = $this->executeQry($queryDelete);
                        $rst->execute();
                
                
    if($rst)
    { 
       
      
     if($type == "snapcenter")
                $solrDelete="http://".SOLRSERVER.":".SOLRPORT."/solr/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:".$idToDel."%3C/query%3E%3C/delete%3E&commit=true";
            else if($type=="reports")
                $solrDelete="http://".SOLRSERVER.":".SOLRPORT."/solr/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:r_".$idToDel."%3C/query%3E%3C/delete%3E&commit=true";
            else
                $solrDelete="http://".SOLRSERVER.":".SOLRPORT."/solr/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:p_".$idToDel."%3C/query%3E%3C/delete%3E&commit=true";

    
                        $content = file_get_contents($solrDelete); 
                            
                        //xml deletion-for snapcenter   
                        if($type=="snapcenter"){  
                        $packVersion=$line->version;
                        $sqlinner = " DELETE FROM ".TBL_SNAPENTITIES." WHERE packId = '".$packId."' ";
                        $rst_entity = $this->executeQry($sqlinner);
                        $exec = $rst_entity->execute();
                        }
                        }
                    
                    }  
            }   
                $_SESSION['SESS_MSG'] = msgSuccessFail("success"," Selected versions of ".$packName." ".$fieldName." has been deleted successfully!!!");
                
                echo "<script language=javascript>window.location.href='".basename($_SERVER['HTTP_REFERER'])."';</script>";
                exit;
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
        $query = "update ".TBL_ADMINUSER." set hash = '$guid' where userName = '".$_SESSION['uid']."'";
        $this->executeQry($query);
        return $guid;
        
    }
    
    
    function getDownloadReports($type)
    {
        $query ="SELECT * FROM ".TBL_REPORTS." where packType = 'snapcenter' ORDER BY downloadDate DESC ";
        $sql = $this->executeQry($query);
        $exec = $sql->execute();
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
            $totalrecords = $num;
            $totalpage = $this->getNoOfPages();
            $pagenumbers = $this->getPageNo();      
            

            //-------------------------Paging------------------------------------------------
            $orderby = $_GET[orderby]? $_GET[orderby]:"packDate";
            $order = $_GET[order]? $_GET[order]:"DESC";   
            $query .=  "LIMIT ".$offset.", ". $recordsPerPage;

            $sql = $this->executeQry($query); 
            $exec = $sql->execute();
                
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
                     case $num:
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
    $sql_query = "select * from download_history where packType = :packType ORDER BY downloadDate DESC ";

    $rst = $this->executeQry($sql_query);
    $rst->bindParam(':packType', $this->sanitize($packType,'string'), PDO::PARAM_INT);
    $exec = $rst->execute();
    $cond = '';
    if(!empty($startLimit) || !empty($endLimit))
    {
        $cond = " limit $startLimit,$endLimit";
    }
    // Gets the data from the database
    $fields_cnt = $this->getTotalColumn($rst);
    $filename = "download_" . date('Ymd') . ".$type";
    $schema_insert = '';
    if($type=="xls")
    {
        $contentType="x-msexcel";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;
        while ($row = $this->fetch_assoc($rst))
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
                        stripslashes($rst->getColumnMeta($i)['name'])) . $csv_enclosed;
                $schema_insert .= $l;
                    $schema_insert .= $csv_separator;
                } // end for
 
            $out = trim(substr($schema_insert, 0, -1));
                $out .= $csv_terminated;
 
                // Format the data
                while ($row = $rst->fetch())
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
     
    function getCautionByPackId($uuid,$certi)
        {
            $query="SELECT * FROM ".TBL_SNAPDETAILS." where uuid = :uuid and certifiedBy = :certifiedBy"; 
            $sql = $this->executeQry($query);
            $sql->bindParam(':uuid', $this->sanitize($uuid,'string'), PDO::PARAM_INT);
            $sql->bindParam(':certifiedBy', $this->sanitize($certi,'string'), PDO::PARAM_INT);
            $exec = $sql->execute();
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
        $query="SELECT uuid FROM ".TBL_SNAPDETAILS." where id = '$id'";     
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

    function editCautionPage($post,$certi){ 
            $_SESSION['SESS_MSG'] = ""; 
            $this->tablename = TBL_SNAPDETAILS;     
            $this->field_values['cautionContent'] = base64_encode($post['cautionContent']) ;
            $this->condition  = "uuid = '".$post['packUuid']."' and certifiedBy = '$certi'";
            $res = $this->updateQry();
            $res->execute();
            $_SESSION['SESS_MSG'] = msgSuccessFail("success","Caution Notice has been updated successfully.");                  
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
        $certifiedBy=$post['type'];        
        $query = "update ". TBL_SNAPDETAILS . " set cautionStatus = :cautionStatus where uuid=:uuid and certifiedBy = :certifiedBy";
        $res = $this->executeQry($query);
        $params = array(
                'cautionStatus'=>$this->sanitize($checkValue,'string'),
                'uuid'=>$this->sanitize($post["packUuid"],'string'),
                'certifiedBy'=>$this->sanitize($certifiedBy,'string')
                   );
        $res->execute($params);
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
        $query = " SELECT * FROM " . TBL_SNAPDETAILS . " WHERE post_approved ='false'";
        $sql = $this->executeQry($query);
        $sql->execute();
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
        $query = " SELECT * FROM " . TBL_SNAPDETAILS . " WHERE $cond";
        $sql = $this->executeQry($query);
        $exec = $sql->execute();
        $num = $this->getTotalRow($sql);
        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;

        if ($num > 0) {
            
            $orderby = $_GET[orderby] ? $_GET[orderby] : "packDate";
            $order = $_GET[order] ? $_GET[order] : "DESC";
            $query .= " ORDER BY $orderby $order ";
            $sql = $this->executeQry($query);
            $exec = $sql->execute();
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

                    $userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $this->sanitize($_SESSION['uid'],'string') . "'");   
                    
                    $user = (array)$userType;                   

                    $genTable .= '               
                                            <td><a id="packHeader" href="snap-detail.shtml?packUuid='.$line->uuid.'&packVersion='.$line->version.'">'.$line->packName.'</a></td>';
                                                                    
                                            $genTable .= '<td>' .$line->version. '</td>';
                                           
                                            $genTable.='
                                            <td>' .$line->minWfaVersion.'</td>';
                                           
                                         
                                        
                                        $encodedPath = base64_encode($line->packFilePath);
                                        $genTable .= '          
                                            <td>' .$line->author . '</td>
                                            <td>' . date('jS F, Y', strtotime($line->packDate)) . '</td>';
                                            

                                            $genTable .="<td><a class='js-open-modal1'   href='javascript:void(NULL);' data-modal-id='popup2' id='".$line->id."'><img src='images/close_small.png' height='16' width='16' border='0' title='Reject' alt='Reject' /></a></td>";

                                            $genTable .="<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure you want to approve this Pack  ?')){window.location.href='pass.shtml?action=approvesnapPack&type=approve&id=".$line->id."'}else{}\" ><img src='images/success.png' height='16' width='16' border='0' title='Approve' alt='Approve' /></a></td>";
                                            $genTable .='<td><a href="eula.shtml?packUuid=' . $line->uuid .'&packVersion=' . $line->version.'&packType=snapcenter&certi='.$line->certifiedBy.'"><img src="images/downlaod-icon.png" border="0" title="Download"/></a></td>';

                    $cryptKey = 'sxcf10';
                    $encodedPath = base64_encode($line->packFilePath);
                    $i++;
                }
            
                $genTable .= '</tr>';
            }
        } else {
             $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
        return $genTable;
    }


    function approvePack($get)
    {
        $query = "UPDATE ".TBL_SNAPDETAILS." set post_approved = 'true' where id=:id";
        $res = $this->executeQry($query);
        $res->bindParam(':id', $this->sanitize($get[id],'string'), PDO::PARAM_INT);
        $res->execute();
        $nameQuery = "SELECT * FROM ".TBL_SNAPDETAILS." WHERE id=:id";
        $sql = $this->executeQry($nameQuery);
        $sql->bindParam(':id', $this->sanitize($get[id],'string'), PDO::PARAM_INT);
        $exec = $sql->execute();
        $line = $this->getResultObject($sql);
        $filePath = explode('.',$line->packFilePath);
        $xmlFile= PATH.$filePath[0].'/Plugin_descriptor.xml';
         

       if(empty($line->tags))
        { 
            $newTags = array();
        }
        else
        {
            $newTags = explode(",",$line->tags);
        }     

        if (file_exists($xmlFile))
        {        
        solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', '/var/www/html/workflowstore/'.$filePath[0]. '/Plugin_descriptor.xml', $newTags, "workflow",'zip','');

        }
        else
        {

            solrSearch(PATH.'SolrPhpClient/Apache/Solr/Service.php', $line, $newTags, "workflow",'dar','approvePack');
        }
      

        $userQry = "SELECT * FROM ".TBL_USER." WHERE receiveMail='true'";

                     $mailsql = $this->executeQry($userQry);  
                     $exec = $mailsql->execute();
                     $mailNum =$this->getTotalRow($mailsql);
                    

                     if ($mailNum > 0) {
                        
                        while ($userline = $this->getResultObject($mailsql)) {
                                

                            $subject = "New ".CONSTANT_UCWORDS." pack upload Notification: ". $line->packName;
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
                            

                            $this->sendEMail($message, $subject, 'afsar.khan@infobeans.com');

                           }

                            

                       }

               
        $_SESSION['SESS_MSG'] = msgSuccessFail("success","User Pack has been approved successfully!!!");
                
        echo "<script language=javascript>window.location.href='admin_profile.shtml';</script>";
        exit;
        
    }

    function rejectPack($post)
    {
        $post[id] = $this->sanitize($post[id],'string');
        $packFilePath = $this->fetchValue(TBL_SNAPDETAILS,"packFilePath","id = '$post[id]'");
        $query="SELECT * from ".TBL_SNAPDETAILS." WHERE id=:id ";
        $packQuery = "SELECT * from ".TBL_SNAPUSERPACKS." WHERE packId=:packId";
        $sql = $this->executeQry($query);
        $sql->bindParam(':id', $this->sanitize($post[id],'string'), PDO::PARAM_INT);
        $exec = $sql->execute();
        $line = $sql->fetch(PDO::FETCH_BOTH);
        $packSql = $this->executeQry($packQuery);
        $packSql->bindParam(':packId', $this->sanitize($post[id],'string'), PDO::PARAM_INT);
        $exec = $packSql->execute();
        $packLine = $packSql->fetch(PDO::FETCH_BOTH);
        $packName=$line[2];
        $packVersion= $line[6];
        $this->tablename = TBL_REJECTEDPACKS;
        $this->field_values['packName'] = $packName;
        $this->field_values['userEmail'] = $packLine[4];
        $this->field_values['firstName'] = $packLine[2];
        $this->field_values['lastName']= $packLine[3];
        $this->field_values['packType']= 'snapcenter';
        $this->field_values['adminComments']=  $post['comment'];
        $this->field_values['adminName'] = $_SESSION['firstName'].' '.$_SESSION['lastName'];
        $this->field_values['adminEmail'] = $_SESSION['mail'];
        $this->field_values['rejectDate'] = date('Y-m-d H:i:s');
        
        $res = $this->insertQry();
        $res[0]->execute();
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

                        $mailResult = $this->sendEMail($message, $subject, 'afsar.khan@infobeans.com');


       
        if(!empty($packFilePath)){
           
            $path_parts = pathinfo(PATH.$packFilePath);


            $unlinkDir = $path_parts['dirname'];
            rrmdir($unlinkDir);
            
            $packId=$post['id'];

            $sql = " DELETE FROM ".TBL_SNAPDETAILS." WHERE id = '$post[id]' ";

          
            $rst = $this->executeQry($sql);
            $exec = $rst->execute();
            if($rst)
            {
                $sql = " DELETE FROM ".TBL_SNAPENTITIES." WHERE packId = '$post[id]' ";
                $rst_entity = $this->executeQry($sql);
                $exec = $rst_entity->execute();
            }
            
        }
        $_SESSION['SESS_MSG'] = msgSuccessFail("success","User Pack has been rejected successfully!!!");
        
        echo "<script language=javascript>window.location.href='admin_profile.shtml';</script>";
    }
    
    function getRejectedPackData()
    {

        $query = " SELECT * FROM rejectedPacks WHERE userEmail = '".$this->sanitize($_SESSION['mail'],'email')."' and packType!='ocipack'";
        $sql = $this->executeQry($query);
        $exec = $sql->execute();
        $num = $this->getTotalRow($sql);
        
        if ($num > 0) {
            //-------------------------Paging------------------------------------------------           
            
            $orderby = $_GET[orderby] ? $_GET[orderby] : "rejectDate";
            $order = $_GET[order] ? $_GET[order] : "DESC";
            $query .= " ORDER BY $orderby $order";
            
            
            $sql = $this->executeQry($query);
            $exec = $sql->execute();
            
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
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }
        return $genTable;
    }

    function getUserPacks() {
        $cond = " 1=1 ";
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $this->sanitize($_REQUEST['searchtxt'],'string');
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }

        $query = " SELECT pd.* FROM " . TBL_SNAPDETAILS . " as pd inner join ".TBL_SNAPUSERPACKS." on pd.id= ".TBL_SNAPUSERPACKS.".packId and ".TBL_SNAPUSERPACKS.".userId='".$this->sanitize($_SESSION['uid'],'string')."' where pd.post_approved='false' GROUP BY pd.uuid";

        $sql = $this->executeQry($query);
        $exec = $sql->execute($params); 
        $num = $this->getTotalRow($sql);

        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
        if ($num > 0) {
            

            $orderby = $_GET[orderby] ? $_GET[orderby] : "packDate";
            $order = $_GET[order] ? $_GET[order] : "DESC";
            $sql = $this->executeQry($query);
            $exec = $sql->execute();
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
                                            <td><a id="packHeader" href="snap-detail.shtml?packUuid='.$line->uuid.'&packVersion='.$line->version.'">'.$line->packName.'</a></td> ';
                                            if ($userType== 1) {
                                            $genTable.='
                                            <td><a href="javascript:void(0)" class="triggerAdmin">' .$line->version.'</a>';
                                            }else {
                                            $genTable.='
                                            <td><a href="javascript:void(0)" class="triggersmall">' .$line->version.'</a>';
                                            }
                                            $genTable.='
                                            <div class="pop-up">
                                            <img src="images/popup-arrow.png" alt="" class="pop-arrow">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <thead>
                                                            <tr>                                                    
                                                            <td>Pack Name</td>
                                                            <td style="width:114px">Min Snap Center Version</td>
                                                            <td>Min Software Version</td>
                                                            </tr>
                                                    </thead>
                                                    ';
                                                    
                                        $query1 = "SELECT * FROM " . TBL_SNAPDETAILS . " where uuid = '".$line->uuid."'";
                                        $orderby = $_GET[orderby] ? $_GET[orderby] : "version";
                                        
                                        $query .= " ORDER BY $orderby ";

                                        $sql1 = $this->executeQry($query1);
                                        $exec = $sql1->execute();
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
                   

                        $genTable .= "<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure to delete this Record  ?')){window.location.href='pass.shtml?action=manageSnapPack&type=snapuserdelete&idToDel[]=" . $line->id . "&page=user_snapprofile'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Delete' alt='Delete' /></a></td>";

                   
                        $genTable .= '<td><a href="eula.shtml?packUuid=' . $line->uuid .'&packVersion=' . $line->version.'&packType=snapcenter&certi='.$line->certifiedBy.'"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td> ';
                    
                       
                    $i++;
                }
                
            }
        } else {
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }


        return $genTable;
    }

    function getUserDownloadedPacks()
    {
         $cond = " 1=1 ";
        if ($_REQUEST['searchtxt'] && $_REQUEST['searchtxt'] != SEARCHTEXT) {
            $searchtxt = $this->sanitize($_REQUEST['searchtxt'],'string');
            $cond .= " AND (packName LIKE '%$searchtxt%' OR uuid LIKE '%$searchtxt%')  ";
        }

        $query = " SELECT * FROM " . TBL_REPORTS . " where userId = :userId and packType='snapcenter'";

        $sql = $this->executeQry($query);
        $sql->bindParam(':userId', $this->sanitize($_SESSION['uid'],'string'), PDO::PARAM_STR);
        $exec = $sql->execute();
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
                                            
                                            $genTable.='
                    <td>' .$line->packType.'</td>';
                                            
                    
                                        $genTable .= '
                                            </td>

                                            <td style="width:140px;">' . date('jS F, Y', strtotime($line->downloadDate)) . '</td>
                                            <td>'.$line->author.'</td><td></td>';
                                            
                            
                    
                    $i++;
                }

        } else {
            $genTable = '<tr><td colspan="9" > no records found</td></tr>';
        }


        return $genTable;

    }

      // end new changes 

    /* 
     *  function to trim spaces and html special chars from given variable
     *  @params type string(char or int)
     *  @return type string(char or int)
     *  Dev ASG
     */
     function trimStrip_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }   
    /*
     * function to remove directory from root/wfs_data
     * @params type string filename
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
        if($post['packType'] == 'Snapcenter'){
        $this->field_values['flagPackUuid']         =   (isset($post['packUuid']) ? $post['packUuid'] : '');
        }else{
        $this->field_values['flagPackUuid']         =   '';
        }            
        $this->field_values['flagPackName']         =   $post['packName'];
        $this->field_values['flagPackVersion']      =   $post['packVersion'];        
        $this->field_values['trademark']            =   (($post['checkTrademark'] == 'true') ? 'true' : 'false');
        $this->field_values['infringement']         =   (($post['checkInfringement'] == 'true') ? 'true' : 'false');
        $this->field_values['flagComment']          =   $post['flagComment'];
        $this->field_values['flagPackType']         =   $post['packType'];
        $this->field_values['flagBy']               =   $_SESSION['uid'];
        if($post['packType'] == 'Snapcenter'){
        $this->field_values['flagPackOwner']        =   $post['packOwnerEmail'];
        }else{
        $this->field_values['flagPackOwner']        =   '';
        }
        $this->field_values['flagDate']             =   $now;
        $this->field_values['flagStatus']           =   '1';
        
                $res = $this->insertQry();
                $res[0]->execute();
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
                                                
                       $creatorEmail = "SELECT * FROM ".TBL_USER." WHERE email=:email AND receiveMail= :receiveMail";   
                        $mailsql = $this->executeQry($creatorEmail);
                        $params = array
                                (
                                    'email'=>$this->sanitize($post['packContactEmail'],'email'),
                                    'receiveMail'=>'true'  
                                );
                        $exec = $mailsql->execute($params);
                        $mailNum = $this->getTotalRow($mailsql);                     
                        $ownerEmail = "SELECT * FROM ".TBL_USER." WHERE email=:email AND receiveMail= :receiveMail";
                        $mailowner = $this->executeQry($ownerEmail);
                        $params = array
                                (
                                    'email'=>$this->sanitize($post['packOwnerEmail'],'email'),
                                    'receiveMail'=>'true'  
                                );
                        $exec = $mailowner->execute($params);
                        $mailNumOwner = $this->getTotalRow($mailowner);                        
                        if($post['packType'] != 'OPM'){
                            if($mailNum > 0){
                                $mailresponseUser = $this->sendEMail($message, $subject, 'afsar.khan@infobeans.com');
                            }                           
                        }
                        if($post['packType'] == 'Snapcenter'){
                                $mailresponseOwner = $this->sendEMail($message, $subject, 'afsar.khan@infobeans.com');
                        }
                      
                        $adminEmail = "ng-store-admins@netapp.com";
                        $mailresponseAdmin = $this->sendEMail($message, $subject, 'afsar.khan@infobeans.com');
                        
                        
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
    
        $clearQuery = "SELECT * FROM ".TBL_REPORTFLAG." WHERE id=:id";  
        $sql = $this->executeQry($clearQuery);
        $sql->bindParam(':id', $this->sanitize($getid[id],'string'), PDO::PARAM_INT);
        $exec = $sql->execute();
        $line = $sql->fetch(PDO::FETCH_OBJ);
        $userName = $line->flagBy;
        $getEmail = "SELECT * FROM ".TBL_USER." WHERE username=:username";
        $sqlEmail = $this->executeQry($getEmail);
        $sqlEmail->bindParam(':username', $this->sanitize($userName,'string'), PDO::PARAM_INT);
        $exec = $sqlEmail->execute();
        $lineEmail = $sqlEmail->fetch(PDO::FETCH_OBJ);
        $contactEmail = $lineEmail->email;
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
                                                
                        $mailGrievanceClear = $this->sendEMail($message, $subject, 'afsar.khan@infobeans.com');
                
            if($sql){
                $del = " UPDATE ".TBL_REPORTFLAG." SET flagStatus = '0' WHERE id = :id ";
                $rst = $this->executeQry($del);
                $rst->bindParam(':id', $this->sanitize($getid[id],'string'), PDO::PARAM_INT);
                $exec = $rst->execute();
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
        
        $query = "SELECT * FROM " . TBL_REPORTFLAG . " WHERE flagStatus =:flagStatus and flagPackType=:flagPackType order by flagDate desc";   
        $sql = $this->executeQry($query);
        $params = array(':flagStatus' => '1',':flagPackType'=>'Snapcenter');
        $exec = $sql->execute($params);
        $num = $this->getTotalRow($sql);           
        if ($num > 0) {         
                while ($line = $this->getResultObject($sql)) {   
                    $genTable .= '<tr>';
                    
                        $genTable .= '<td title="'.$line->flagPackName.'" alt="'.$line->flagPackName.'">'.wrapText($line->flagPackName,20).'</td>';
                        $genTable .= '<td title="'.$line->flagPackVersion.'" alt="'.$line->flagPackVersion .'">'. $line->flagPackVersion .'</td>';                          
                        $genTable .= '<td title="'.$line->flagBy.'" alt="'.$line->flagBy .'">'. wrapText($line->flagBy,20).'</td>';
                        $genTable .= '<td title="'.$line->trademark.'" alt="'.$line->trademark .'">'. wrapText(ucfirst($line->trademark),20).'</td>';
                        
                        $genTable .= '<td title="'.$line->infringement.'" alt="'.$line->infringement .'">'. wrapText(ucfirst($line->infringement),20).'</td>';
                        
                        $genTable .= '<td title="'.preg_replace("/[^a-zA-Z0-9\w ]+/", "", html_entity_decode($line->flagComment)).'" alt="'. preg_replace("/[^a-zA-Z0-9\w\ ]+/", "", html_entity_decode($line->flagComment)).'">'.wrapText(preg_replace("/[^a-zA-Z0-9\w\ ]+/", "", html_entity_decode($line->flagComment)),40) .'</td>';
                        
                        $genTable .= '<td title="'.$line->flagDate.'" alt="'.$line->flagDate .'">'. date('j F, Y', strtotime($line->flagDate)).'</td>';
                        
                        $genTable .= "<td><a class='packAction' href='javascript:void(NULL);' onClick=\"if(confirm('Are you sure you want to clear this Grievance?')){window.location.href='pass.php?action=snapclearFlag&type=clear&id=".$line->id."'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Clear Flag' alt='Clear Flag' /></a></td>";    
                                                                        
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
        
        $query = "SELECT * FROM " . TBL_REPORTFLAG . " WHERE flagStatus =:flagStatus AND flagPackOwner= :flagPackOwner and flagPackType=:flagPackType order by flagDate desc";   
        $sql = $this->executeQry($query);
        $params = array
                                    (
                                        'flagStatus'=>'1',
                                        'flagPackOwner'=>$this->sanitize($_SESSION['mail'],'email'),
                                        'flagPackType'=>'Snapcenter'
                                    );
        $exec = $sql->execute($params);
        $num = $this->getTotalRow($sql);    
            
        
        if ($num > 0) { 
    
                $flag= 'false';
                while ($line = $this->getResultObject($sql)) {
                    $queryUser = "SELECT * FROM " . TBL_SNAPDETAILS . " WHERE version=:version AND uuid =:uuid";
                    $sqlUser = $this->executeQry($queryUser);
                    $sqlUser->bindParam(':version', $this->sanitize($line->flagPackVersion,'float'), PDO::PARAM_INT);
                    $sqlUser->bindParam(':uuid', $this->sanitize($line->flagPackUuid,'string'), PDO::PARAM_INT);
                    $exec = $sqlUser->execute(); 
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
     * @params type string body, subject, packType, packName
     * @return type bool
     * @author ASG
     */
       
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

    
    /** hc
     * workflowHelp
     * Function for fetching full information of workflow help.
     *
     * @param    Int     
     * @return   String
     */
    function workflowHelp($packUuid,$packVersion) {
         $packQuery = "Select * from ".TBL_SNAPDETAILS." where uuid=:uuid and version=:version";
        
        $packsql = $this->executeQry($packQuery);
        $packsql->bindParam(':uuid', $this->sanitize($packUuid,'string'), PDO::PARAM_INT);
        $packsql->bindParam(':version', $this->sanitize($packVersion,'float'), PDO::PARAM_INT);
        $exec = $packsql->execute();
        $packnum = $this->getTotalRow($packsql);
        $line = $this->getResultObject($packsql);
        
        $help_text = $line-> readme_txt;
        
        if (!empty($help_text)) {          
            $genTable = '<div id="list4"><section class="comman-link wfhelp"><table><thead><tr><td>'.CONSTANT_UCWORDS.' Help</td></tr></thead>';        
            $genTable .='<tr><td>'.$help_text.'</td></tr>';
            
            $genTable .= '</table>
</section></div>';
        
         } else {
            $genTable = '<div>&nbsp;</div><div class="Error-Msg"> no help found for this pack</div>';
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
       
         $query = "Update ".TBL_SNAPDETAILS." set tags = :tags where uuid = :uuid  and version = :version ";
         $sql1 = $this->executeQry($query);
         $params = array
                (
                    'tags'=>$this->sanitize($post['tags'],'string'),
                    'uuid'=>$this->sanitize($post['uuid'],'string'),
                    'version'=>$this->sanitize($post['version'],'float')
                );
         $exec = $sql1->execute($params);
     }
     
     /* Function for star rating (Data Protection/Report/Performance)
         * @params type array $post
         * @author ASG
         */
        function addStarRating($post){
            $now = date('Y-m-d H:i:s');
            $this->tablename = TBL_RATINGSTAR;
            
            $userRatingEligibleqry = "SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId=:ratingPackId AND ratingPackVersion=:ratingPackVersion AND ratingPackType=:ratingPackType AND ratingBy=:ratingBy";$sql1 = $this->executeQry($userRatingEligibleqry);
                $params = array
                        (
                            'ratingPackId'=>$this->sanitize($post['starpackid'],'string'),
                            'ratingPackVersion'=>$this->sanitize($post['starpackversion'],'float'),
                            'ratingPackType'=>$this->sanitize($post['starpacktype'],'string'),
                            'ratingBy'=>$this->sanitize($_SESSION['uid'],'string')
                        );
                $exec = $sql1->execute($params);
                $userRatingEligible = $this->getTotalRow($sql1);
                if($userRatingEligible <= 0){
                     $this->field_values['ratingValue']         =  $post['starrating'];
                     $this->field_values['ratingPackVersion']   =  $post['starpackversion'];
                     $this->field_values['ratingPackId']        =  $post['starpackid'];
                     $this->field_values['ratingPackType']      =  $post['starpacktype'];
                     $this->field_values['ratingBy']            =  (isset($_SESSION['uid']) ? $_SESSION['uid'] : '');
                     $this->field_values['ratingDate']          =  $now;
                     
                        $res = $this->insertQry();
                        $res[0]->execute();
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
            
                     $this->field_values['depComment']          =  $this->sanitize($post['depComment'],'string');
                     $this->field_values['depPackVersion']      =  $post['depPackVersion'];
                     $this->field_values['depPackId']           =  $post['depPackid'];
                     $this->field_values['depPackType']         =  $post['depPackType'];
                     $this->field_values['depBy']               =  (isset($_SESSION['uid']) ? $_SESSION['uid'] : '');
                     $this->field_values['depDate']             =  $now;
                     $this->field_values['flag']                =  '1';
                     
                        $res = $this->insertQry();
                        $res[0]->execute();
                        if($res){
                            $_SESSION['SESS_MSG'] = msgSuccessFail("success","Deprecation has been posted successfully!");
                        }else{
                            $_SESSION['SESS_MSG'] = msgSuccessFail("error","Deprecation can not be posted!");
                        }
                header('Location:'.$detailPageUrl);
                exit;
        }

    function updateSolr($post)
    {
    
        $query = "SELECT id from packdetails where uuid=:uuid and version=:version";
        $rst = $this->executeQry($query);
        $rst->bindParam(':uuid', $this->sanitize($post['wfa_pack_uuid'],'string'), PDO::PARAM_INT);
        $rst->bindParam(':version', $this->sanitize($post['wfa_pack_version'],'float'), PDO::PARAM_INT);
        $numEntity = $this->getTotalRow($sql1);
        $line = $this->db_fetch_assoc_array($rst);
        $id=$line[0];
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
        
        
        $data_string = '[{"id":"'.$id.'","certifiedBy":{"set":"'.$this->sanitize($post['wfa_certificate'],'string').'"},"tags":{"set":['.$newString.']}}]';                           
        $ch = curl_init("http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/update?commit=true");                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  
        curl_setopt($ch, CURLOPT_USERPWD, "wfsuser:wfs@123#$");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string))                                                                       
        );                                                                                                       $result = curl_exec($ch);
    }

        /*
        * function for deprecation deletion
        * @author - Afsar khan
        */
        function setDeprecationFlagSnap($post){          
            $this->tablename = TBL_DEPRECATED;                          
                
            $updateQry = "DELETE FROM ".TBL_DEPRECATED." WHERE depPackVersion =:depPackVersion AND depPackId =:depPackId AND depPackType=:depPackType";
            $sql1 = $this->executeQry($updateQry);
            $params = array
                                    (
                                        'depPackVersion'=>$this->sanitize($post['packVesion'],'float'),
                                        'depPackId'=>$this->sanitize($post['packId'],'float'),
                                        'depPackType'=>$this->sanitize($post['packtype'],'string')
                                    );
            $exec = $sql1->execute($params);
            if($exec){ 
                $_SESSION['SESS_MSG'] = msgSuccessFail("success","Deprecation has been deleted successfully!");
                return true;
            }else{ 
                $_SESSION['SESS_MSG'] = msgSuccessFail("error","Deprecation can not be deleted!");
                return false;
            }
        }
}
?>
