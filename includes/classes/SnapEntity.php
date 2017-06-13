<?php
@session_start();

/**
 * SnapEntity.php
 * This class implements various functionalities related to entities of snap packs.
 * 
 */
class SnapEntity extends MySqlDriverPDO {

    function __construct() {
        $obj = new MySqlDriverPDO;
        parent::__construct();
        $this->log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
    }

    /**
     * function to encrypt a password
     * @param type $plain - simple user defined text
     * @return string - encrypted string
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

    /**
     * 
     * @staticvar boolean $seeded
     * @param type $min
     * @param type $max
     */
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
        $output = preg_replace("/[^a-zA-Z0-9-_]/","", $input); 
    }
        return $output;
    }
    /**
     * Function to fetch full information of entities of a particular pack based on packId, uuid, and version, changing the content if funtion is used for download
     * @param type $packId
     * @param type $pkUuid
     * @param type $pkVersion
	 * @param type $downloadPg
     * @return string
     */
     //hc-funtion_changed
    function entityVersionInformation( $pkUuid=null, $pkVersion=null, $downloadPg = null) {
		$cond = " 1=1 ";
		$query = "select * from " . TBL_SNAPDETAILS . " where uuid=:uuid && version =:version";
		$rst = $this->executeQry($query);
        $rst->bindParam(':uuid', $this->sanitize($pkUuid,'string'), PDO::PARAM_INT);
        $rst->bindParam(':version', $this->sanitize($pkVersion,'float'), PDO::PARAM_INT);
        $exec = $rst->execute();
        $numPack = $this->getTotalRow($rst);
        $linePack = $this->getResultObject($rst);
		$certi=$linePack->certifiedBy;
        $qry = "select distinct version,packDate from " . TBL_SNAPDETAILS . " where uuid = :uuid  and post_approved = 'true' and certifiedBy = :certifiedBy ORDER BY version DESC";
        $rst = $this->executeQry($qry);
        $rst->bindParam(':uuid', $this->sanitize($linePack->uuid,'string'), PDO::PARAM_INT);
        $rst->bindParam(':certifiedBy', $this->sanitize($linePack->certifiedBy,'string'), PDO::PARAM_INT);
        $exec = $rst->execute();
        $packVersion = $this->db_fetch_assoc_array($rst);
        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
		$backUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/snap-list.shtml";
		
        if(isset($_SESSION['backPage']))
        {
        	if($_SESSION['backPage'] == 'search-packs.shtml')
        	{
        		$genTable .= '	<section class="back-link">
						<a href="'.$backUrl.'"> < Back</a>
						</section>';
        	}
        	else{
        		$genTable .= '	<section class="back-link">
						<a href="'.$backUrl.'"> < Back</a>
						</section>';	
        		 
        		
					}
        }
        else{
		$genTable .= '	<section class="back-link">
						<a href="'.$backUrl.'"> < Back</a>
						</section>';
					}
        
		$encodedPath = base64_encode(PATH . $linePack->packFilePath);
        if ($numPack > 0) {
            //Added dynamic divs and dynamic data
            $genTable .= '<input type="hidden" name="packUuid" id="packUuid" value="' . $linePack->uuid . '">';
            $genTable .= '<input type="hidden" name="packId" id="packId" value="' . $linePack->id . '">';
			$genTable .= '<section class="details-pack">
						  <h2> ' . $linePack->packName .' </h2>
						  <div class="version">Version :
						  <span>'.$pkVersion.' </span>
						  ';
						  
						 if($linePack->post_approved=='true'){
						foreach ($packVersion as $ver) {
													if ($ver['version'] == $pkVersion) {} else 
													{
														$genTable .= '<a href="snap-detail.shtml?packUuid='.$pkUuid.'&packVersion='.$ver['version'].'"> ' . $ver['version'] . '</a>';
													}
												}}
												
						$genTable .= '	</div>';
						
						
						$genTable .= '<div class="download-div">';
						$checkValue = (($linePack->cautionStatus=='true') ? 'checked' : '');
						$genTable .= '<div class="download-left">';
							if($checkValue)
							{               
								
								if ($downloadPg !='1'){
								
				
								$genTable .='<a href="cautionPage.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=snapcenter&certi='.$certi.'" name="download" class="myButton1 downlaod_btn">Download</a> ';
							
														}
							}
							else
							{
								
								if ($downloadPg !='1')
								{
									$genTable .='<a href="eula.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=snapcenter&certi='.$certi.'" name="download" class="myButton1 downlaod_btn">Download</a> ';
								}
							}   
							$genTable .= '<a href="snapcenterHelp.shtml?packUuid='.$linePack->uuid.'&packVersion='.$linePack->version.'"> <img src="images/documentation-icon.png" width="20" height="21" border="0" align="absmiddle"  /> Documentation</a>';
							$genTable .='</div>';
							$genTable .='	<div class="downlaod-right">';
									
							if (strtolower($linePack->certifiedBy) == 'netapp' || $linePack->certifiedBy == "NETAPP") 
											{
												$genTable .= '<img src="images/netapp-certified-icon.png" width="20" height="20"  align="absmiddle"> This pack is NetApp-generated .'; 
											}
											else 
											{
												$genTable .= '<img src="images/non-netapp-certified.png" width="20" height="20"  align="absmiddle">This pack is Community-generated .';  
											}
										
								
						$genTable .= '		</div>
										</div>
									</section>';
									
						$genTable .= '
											<section class="de-left-content">';
							
									$genTable .='		<article>
											<pre>
											' .html_entity_decode($linePack->packDescription) . '
											</pre></article>
											<article><span>Whats changed</span>
											<pre>'.$linePack->whatsChanged.'</pre>
											<br />
											<span>Pre-requisites</span>
											<pre>'.$linePack->preRequisites.'</pre>
											<ul><li> Snap Center Version: '.$linePack->minWfaVersion.'</li>
											<li> Windows Compatibility: '.$linePack->minsoftwareversion.',   Linux Compatibility: '.$linePack->maxsoftwareversion.'</li></ul>
											</article>';
						
						
						$genTable .= '<article><span>Community Link</span>
										<pre>'.$linePack->communityLink.'</pre></article>';
						$genTable .= '<div class="download-left">';
						if($checkValue)
							{               
								
								if ($downloadPg !='1'){
								$genTable .='<a href="cautionPage.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=snapcenter&certi='.$certi.'" name="download" class="myButton1 downlaod_btn">Download</a> ';
							
														}
							}
							else
							{
								
								if ($downloadPg !='1'){
								
								$genTable .='<a href="eula.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=snapcenter&certi='.$certi.'" name="download" class="myButton1 downlaod_btn">Download</a> ';
														}
							}   
				
							$genTable .= '<a href="snapcenterHelp.shtml?packUuid='.$linePack->uuid.'&packVersion='.$linePack->version.'"> <img src="images/documentation-icon.png" width="20" height="21" border="0" align="absmiddle"  /> Documentation</a>';
				if($downloadPg == '1'){
				$genTable .='<article> ';
					
						$flag = false;
								if (!empty($_REQUEST['captcha'])) {

								if (!empty($_SESSION['captcha']) and trim(strtolower($_REQUEST['captcha'])) == $_SESSION['captcha']) {					
									  
									  $genTable .= '<div class="download-left"><a href="download.shtml?packPath=' . $encodedPath. '&packUuid=' . $linePack->uuid .'&packName=' . $linePack->packName .'&packVersion=' . $linePack->version.'&packType=snapcenter" name="download" id="packAction" class="myButton1 downlaod_btn">Download</a></div>';
									  
									$flag = true;	  
								}					
							}
							if(!$flag)	
							{								
							
								$genTable .= '<img src="captcha.shtml" id="captcha" /><br/>';

								$genTable .= '<a href="javascript:void(0);" onclick="
								document.getElementById(\'captcha\').src=\'captcha.shtml?\'+Math.random();
								document.getElementById(\'captcha-form\').focus();"
								id="change-image">Change text </a><br/> <br/>';

								$genTable .= '<div id="captchaButtons"><input type="text" name="captcha" id="captcha-form" autocomplete="off" autofocus/><br/><br/>';  
								$genTable .= '<input type="submit" name="submitCaptcha" value="Submit Captcha" class="myButton"/></div>';
	
										if(!empty($_SESSION['captcha']) && !empty($_REQUEST['captcha'])){
											$genTable .='<div class="captcha_error"><img src="images/error.png" width="16" border="0" height="16">&nbsp;&nbsp;&nbspInvalid captcha</div>';}
										
									if(empty($_REQUEST['captcha']) && isset($_REQUEST['submitCaptcha']))
									   {
										  $genTable .='<div class="captcha_error"><img src="images/error.png" width="16" border="0" height="16">&nbsp;&nbsp;&nbspPlease enter a valid CAPTCHA value</div>';
									   }
									
							}
						$genTable .='</article> ';	
						
						}
						$genTable .= '</div></section>';  		
					
						
						$genTable .= '<div class="de-right-content">
							<section class="de-right-div bt-btm">
							<article>
							<span>Version History</span>
							';
							foreach ($packVersion as $ver)
								{
								
								$time = new DateTime($ver['packDate']);
								$relaeseDate = $time->format('jS M,Y');					
								$genTable .= '
												<div>Version:'.$ver['version'].' </div>
												<div>Released on:'.$relaeseDate.'</div>
												<div>&nbsp;</div>';
								}


							$genTable .= '
							</article>	
							<article><span>Tags</span>';

							$packTags = explode(",",$linePack->tags);

							$numTags = count($packTags);
							$countTag = 0;
							$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $this->sanitize($_SESSION['uid'],'string') . "'");
							
							if(($userType==1) && $linePack->post_approved=='false'){

								$genTable .='<input type="hidden" id="uuid" name="uuid" value="'.$linePack->uuid.'" />';
								$genTable .='<input type="hidden" name="version" id="version" value="'.$linePack->version.'" />';

								$genTable .='<div>';
								

								$genTable .='<div id="tags" style=" max-width: 240.777777671814px; min-height: 34.777777671814px;height:auto;clear:both;">';

								
								foreach($packTags as $key => $value)
								{
									if(!empty($value))
									{
										$genTable .= '<span class="tag">'.(strtolower($value)).'<span class="delete-tag" title="remove this tag"></span></span>';
									}

									
								} 
								$genTable .='<input type="text" tabindex="103" placeholder="" id="packTag" style="width: 200.777777671814px;height: 25px;" name="tags" placeholder="Add a tag"/><span></span></div> <br />
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

							if(($userType==1) && $linePack->post_approved=='false'){

								$genTable .='<input type="hidden" id="uuid" name="uuid" value="'.$linePack->uuid.'" />';
								$genTable .='<input type="hidden" name="version" id="version" value="'.$linePack->version.'" />';
								$genTable .='<br/><br/><input type="button" class="btn" id="uploadpc-btn" value="Update Tags"/><div class="form-error" id="wfaContactTagError" style="margin-left:0px;"></div>';
							}
							$genTable .='</article><div>&nbsp;</div><span>Author</span>';
							if (strtolower($linePack->certifiedBy) == 'netapp' || $linePack->certifiedBy == "NETAPP") {
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
							$select_rating = "SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId=:ratingPackId AND ratingPackVersion=:ratingPackVersion AND ratingPackType=:ratingPackType";
							$sql1 = $this->executeQry($select_rating);
		                    $params = array
									(
									    'ratingPackId'=>$this->sanitize($linePack->id,'string'),
									    'ratingPackVersion'=>$this->sanitize($linePack->version,'float'),
									    'ratingPackType'=>'snapcenter'
									);
		                    $exec = $sql1->execute($params);
		                    
		                    $total = $this->getTotalRow($sql1);
								while($row = $sql1->fetch(PDO::FETCH_BOTH))	{
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
							<article><span>Rating</span><span>
							 '.$imgPrint.' </span><span class="display-star">('.$total.')</span>
							</article><br />';
							/* 5 star displayed end - ASG */
							
						$genTable .= '<article>
								<span>Contact</span>
								<div>'.$linePack->contactName.'<br />'.$linePack->contactEmail.'<br />'.$linePack->contactPhone.'</div>
							</article>
							</section>
							<div class="de-right-div">
								<p></p>';
						if((isset($_SESSION['uid'])) && ($_SESSION['uid'] != '') && ($downloadPg != '1')){	
						// TBL_RATINGSTAR = rating, TBL_REPORTS = download_history
								/* 
								 * 5 Star rating 
								 * code start ASG
								 */								
							$selcetUserRating = "SELECT * FROM " .TBL_REPORTS." WHERE packId=:packId AND packVersion=:packVersion AND userId=:userId";
							$sql1 = $this->executeQry($selcetUserRating);
		                    $params = array
									(
									    'packId'=>$this->sanitize($linePack->id,'string'),
									    'packVersion'=>$this->sanitize($linePack->version,'float'),
									    'userId'=>$this->sanitize($_SESSION['uid'],'string')
									);
		                    $exec = $sql1->execute($params);
		                    $userRatedValue = $this->getTotalRow($sql1);
							
							$userRatingEligibleqry = "SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId=:ratingPackId AND ratingPackVersion=:ratingPackVersion AND ratingPackType=:ratingPackType AND ratingBy=:ratingBy";
							
							$sql1 = $this->executeQry($userRatingEligibleqry);
		                    $params = array
									(
									    'ratingPackId'=>$this->sanitize($linePack->id,'string'),
									    'ratingPackVersion'=>$this->sanitize($linePack->version,'float'),
									    'ratingPackType'=>'snapcenter',
									    'ratingBy'=>$this->sanitize($_SESSION['uid'],'string')
									);
		                    $exec = $sql1->execute($params);
		                    $userRatingEligible = $this->getTotalRow($sql1);
							
							if($userRatedValue > 0){
								if($userRatingEligible <= 0){
								$genTable .= '<article><span>Rate this Pack</span>
										<form name="ratingForm" id="ratingForm" method="post" action="pack-detail.php">
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
										<input type="hidden" name="starpackid" id="starpackid" value='.$linePack->id.' />
										<input type="hidden" name="starpacktype" id="starpacktype" value="snapcenter" />
										<input type="hidden" name="starpackversion" id="starpackversion" value='.$linePack->version.' />
										<input type="submit" value="Submit" name="submit_rating" id="submit_rating" style="display:none;">
									</form></article><br />';
								}else{
								$genTable .= '<article><span>Rate this pack</span><div class="div">
								<img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><br />';
								$genTable .= "You already rated this pack!</div></article><br />";
								}
							}else{
								$genTable .= '<article><span>Rate this pack</span><div class="div">
								<img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><br />';
								$genTable .= "Download this pack to rate!</div></article><br />";
							}
						$genTable .= "<article><span>Feedback</span><a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup1' id='".$linePack->id."'>Flag as inappropriate</a></article><br />";
						}
						/* Deprecation link Start - ASG */
						
							$getDepData = $this->getDepData($linePack->id, $linePack->version, 'snapcenter');	
							if($userType == 1 && $downloadPg != '1'){
								$genTable .= "<article><span>Deprecate</span>";	
							}					
														
							if($getDepData <= 0 ){								
								if($userType == 1 && $downloadPg != '1'){
									$genTable .= "<a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup2' id='".$linePack->id."'>Post Deprecation</a>";
								}
							}else if($getDepData > 0){								
								$records = $this->getDepDataDetailed($linePack->id, $linePack->version, 'snapcenter');
																	
								if($userType == 1 && $downloadPg != '1'){
									$checkValue = (($records['flag'] == '1') ? 'Checked' : '');	
									$genTable .= 'Uncheck to Delete Comment: <input type="checkbox" name="deprecationFlag" class="deprecationFlag" onclick="setDeprecationFlag('. $linePack->id .',\'' . $linePack->version . '\')"; ' .$checkValue. ' alt="Delete Comment"/> <span id="cautionLoader' . $linePack->id . '"></span>';
								}
								if($records['depComment'] != ''){
									$genTable .= "<pre><font color='red'>".$records['depComment']."</font></pre></article>";
								}																  							
							}
													
						$genTable .= '	</div>
											
						</div>';
						//download 
						
						
           						
        } else {
            $genTable = '
			
			<section class="back-link">
						<a href="snap-list.shtml"> < Back</a><div>No Records Found</div>
						
						</section>';
        }

        return $genTable;    
    }
	/* Function to get any pack id based on uuid and version
	* @param type string
    * @return string	
	* (Start block -> @Author ASG)
	*/
   function getPackId($packUuid, $packVersion){
            $idqry = "SELECT id FROM ".TBL_SNAPDETAILS." WHERE uuid = :uuid AND version=:version"; 
            $sql = $this->executeQry($idqry);
		    $sql->bindParam(':uuid', $this->sanitize($packUuid,'string'), PDO::PARAM_INT);
		    $sql->bindParam(':version', $this->sanitize($packVersion,'float'), PDO::PARAM_INT);
		    $exec = $sql->execute(); 
            $packIdsres =$sql->fetch(PDO::FETCH_ASSOC);
            $packId = $packIdsres['id']; 
            return $packId;
   }  
	/* Function to get any pack name based on uuid and version 
	* @param type string
    * @return string
	* @Author ASG
	*/
   function getPackName($packUuid, $packVersion){
            $nameQry = "SELECT packName FROM ".TBL_SNAPDETAILS." WHERE uuid = :uuid AND version=:version"; 
			$sql = $this->executeQry($nameQry);
		    $sql->bindParam(':uuid', $this->sanitize($packUuid,'string'), PDO::PARAM_INT);
		    $sql->bindParam(':version', $this->sanitize($packVersion,'float'), PDO::PARAM_INT);
		    $exec = $sql->execute(); 
            $packNameRes =$sql->fetch(PDO::FETCH_ASSOC);
            $packName = $packNameRes['packName']; 
            return $packName;
   }
   /* Function to get any pack contact email based on uuid and version
	* @param type string
    * @return string   
	* @Author ASG
	*/
   function getPackContactEmail($packUuid, $packVersion){
            $emailQry = "SELECT contactEmail FROM ".TBL_SNAPDETAILS." WHERE uuid =:uuid AND version=:version ";
		    $sql = $this->executeQry($emailQry);
		    $sql->bindParam(':uuid', $this->sanitize($packUuid,'string'), PDO::PARAM_INT);
		    $sql->bindParam(':version', $this->sanitize($packVersion,'float'), PDO::PARAM_INT);
		    $exec = $sql->execute(); 
            $packContactEmail =$sql->fetch(PDO::FETCH_ASSOC);
            $email = $packContactEmail['contactEmail']; 
            return $email;
   }
   /* Function to get any pack owner email based on uuid and version
	* @param type string
    * @return string   
	* @Author ASG
	*/
   function getPackOwnerEmail($packUuid, $packVersion){
			$packid = $this->getPackId($packUuid, $packVersion);			
            $emailQry ="SELECT userEmail FROM ". TBL_SNAPUSERPACKS ." WHERE packId = :packId"; 
            $sql = $this->executeQry($emailQry);
		    $sql->bindParam(':packId', $this->sanitize($packid,'string'), PDO::PARAM_INT);
		    $exec = $sql->execute(); 
            $packContactEmail =$sql->fetch(PDO::FETCH_ASSOC);
            $email = $packContactEmail['userEmail']; 
            return $email;
   }
   /* End block -> Dev ASG */ 

   /**   
     * Function to fetch full information of entities of a particular pack based on packId, filter
     * @param type $packId 
     * @param type $entityType
     * @return string
     */
    function setContentFilter($packId = null, $entityType) {
			if($entityType == 'all')
			{
				$query = "SELECT * FROM " . TBL_SNAPENTITIES . " where packId = :packId and entityType IN('Workflow','Finder','Filter','Command','Dictionary Entry','Script Data Source Type','Scheme','Remote System Type', 'Category','Workflows','Finders','Filters','Commands','Dictionary Entrys','Script Data Source Types','Schemes','Remote System Types','Categories')";    
			}
			else{
				$query = "SELECT * FROM " . TBL_SNAPENTITIES . " where packId = :packId and entityType like '".$this->sanitize(ucwords($entityType),'string')."%'";
			}

			$orderby = $_GET[orderby] ? $_GET[orderby] : "entityDate";
			$order = $_GET[order] ? $_GET[order] : "DESC";
			$query .= " ORDER BY $orderby $order";

			$sql = $this->executeQry($query);
			$sql->bindParam(':packId', $this->sanitize($packId,'string'), PDO::PARAM_INT);
			$exec = $sql->execute();
			$numEntity = $this->getTotalRow($sql);
			$genTable = '';
			if ($numEntity > 0) {

					$i = $offset + 1;

					while ($line = $this->getResultObject($sql)) {

						$highlight = $i % 2 == 0 ? "main-body-bynic" : "main-body-bynic2";
						$div_id = "status" . $line->entityId;
						
						$genTable .= '<tr>
											<td><span>' . htmlspecialchars($line->name, ENT_QUOTES, 'UTF-8') . '</span>'.htmlspecialchars($line->description, ENT_QUOTES, 'ISO-8859-1').'</td>
											<td>' . $line->version . '</td>
									  </tr>';

						$i++;
					} 
			}
			else{
				$genTable = '<tr><td colSpan="2">No Entities Found </td></tr>';
			}
			
			return $genTable;
			exit;
	}
			
		/*
	*	function to get number of Deprecation
	*	@param type string, id, version, type
	* 	@return int
	*	@Author - ASG
	*/
	function getDepData($id, $version, $type){	
		$depqry = "SELECT * FROM ".TBL_DEPRECATED." WHERE depPackId=:depPackId AND depPackVersion=:depPackVersion AND depPackType=:depPackType AND flag=:flag";
		$sql1 = $this->executeQry($depqry);
        $params = array
									(
									    'depPackId'=>$this->sanitize($id,'string'),
									    'depPackVersion'=>$this->sanitize($version,'float'),
									    'depPackType'=>$this->sanitize($type,'string'),
									    'flag'=>'1'
									);
        $exec = $sql1->execute($params);
        $total = $this->getTotalRow($sql1);

		return $total;			
	}
	
	/*
	*	function to get detail of Deprecation
	*	@param type string, id, version, type
	* 	@return array
	*	@Author - ASG
	*/
	function getDepDataDetailed($id, $version, $type){				
		$depRecordqry = "SELECT * FROM ".TBL_DEPRECATED." WHERE depPackId=:depPackId AND depPackVersion=:depPackVersion AND depPackType=:depPackType AND flag=:flag";
		$sql1 = $this->executeQry($depRecordqry);
        $params = array
									(
									    'depPackId'=>$this->sanitize($id,'string'),
									    'depPackVersion'=>$this->sanitize($version,'float'),
									    'depPackType'=>$this->sanitize($type,'string'),
									    'flag'=>'1'
									);
        $exec = $sql1->execute($params);
		$totalRecord = $sql1->fetch(PDO::FETCH_ASSOC);			
		return $totalRecord;			
	}
}
?>