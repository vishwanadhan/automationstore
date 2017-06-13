<?php

@session_start();

/**
 * Entity.php
 * This class implements various functionalities related to entities of packs.
 * 
 */
class Entity extends MySqlDriver {

    function __construct() {
        $obj = new MySqlDriver;
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
		//$query = "select * from " . TBL_PACKDETAILS . " where id=".base64_decode($packId);
		//if($packId == null){
		
		$query = "select * from " . TBL_PACKDETAILS . " where uuid='".$pkUuid."' && version ='".$pkVersion."';";
		//} 
			
			$rst = $this->executeQry($query);
            $numPack = $this->getTotalRow($rst);
            $linePack = $this->getResultObject($rst);
			$certi=$linePack->certifiedBy;

            $qry = "select distinct version,packDate from " . TBL_PACKDETAILS . " where uuid = '" . $linePack->uuid . "'  and post_approved = 'true' and certifiedBy = '".$linePack->certifiedBy."' ORDER BY version DESC";
            $packVersion = $this->db_fetch_assoc_array_my($qry);

            
		
        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;

        if(isset($_SESSION['backPage']))
        {
        	if($_SESSION['backPage'] == 'search-packs.shtml')
        	{
        		$genTable .= '	<section class="back-link">
						<a href="pack-list.shtml"> < Back</a>
						</section>';
        	}
        	else{
        		$genTable .= '	<section class="back-link">
						<a href="'.$_SESSION['backPage'].'"> < Back</a>
						</section>';
					}
        }
        else{
		$genTable .= '	<section class="back-link">
						<a href="pack-list.shtml"> < Back</a>
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
														$genTable .= '<a href="pack-detail.shtml?packUuid='.$pkUuid.'&packVersion='.$ver['version'].'"> ' . $ver['version'] . '</a>';
													}
												}}
												
						$genTable .= '	</div>';
						
						
						$genTable .= '<div class="download-div">';
						$checkValue = (($linePack->cautionStatus=='true') ? 'checked' : '');
						$genTable .= '<div class="download-left">';
							if($checkValue)
							{               
								
								if ($downloadPg !='1'){
								
				
								$genTable .='<a href="cautionPage.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=workflow&certi='.$certi.'" name="download" class="myButton1 downlaod_btn">Download</a> ';
							
														}
							}
							else
							{
								
								if ($downloadPg !='1'){
								/*$genTable .='<a href="eula.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=workflow&certi='.$certi.'" name="download" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a> ';*/
								
								$genTable .='<a href="eula.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=workflow&certi='.$certi.'" name="download" class="myButton1 downlaod_btn">Download</a> ';
														}
							}   
/* 				echo $linePack->version. " ". $linePack->uuid;
				echo $pkVersion." ".$pkUuid; */
							$genTable .= '<a href="workflowHelp.shtml?packUuid='.$linePack->uuid.'&packVersion='.$linePack->version.'"> <img src="images/documentation-icon.png" width="20" height="21" border="0" align="absmiddle"  /> Documentation</a>';
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
											<ul><li> WFA Version: '.$linePack->minWfaVersion.'</li>
											<li> Windows Compatibility: '.$linePack->minsoftwareversion.',   Linux Compatibility: '.$linePack->maxsoftwareversion.'</li></ul>
											</article>';
						
						
						$genTable .= '<article><span>Community Link</span>
										<pre>'.$linePack->communityLink.'</pre></article>';
									// contents start
						$genTable .= '<article><span>Contents</span>';
						$genTable .= '<div class="in-textlinks">
										  <ul id="content-ul">
												<li><a class="current" href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'workflow\');">Workflows </a></li>
												<li>|</li>
												<li> <a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'command\');">Commands</a></li> 
												<li>| </li>
												<li><a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'filter\');">Filters</a></li>
												<li> |</li>
												<li> <a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'finder\');">Finders</a> </li>
												<li>|</li>
												<li> <a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'dictionary entry\');">Dictionary Entry</a></li>
												<li> | </li>
												<li><a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'scheme\');">Schemes </a></li>
												<li>| </li>
												<li><a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'cache queries\');">Cache Queries </a></li>
												<li>| </li>
												<li><a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'script data source type\');">Data Source Types</a></li> 
												<li>| </li>
												<li><a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'remote system\');">Remote System Types</a></li>  
												<li>| </li>
												<li><a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'category\');">Categories</a></li> 
												<li>| </li>
												<li><a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'all\');">All</a></li>
											</ul>
									</div>';
									
						//<li>| </li><li><a href="javascript:void(0);" onclick="contentFilter(this,'.$linePack->id .',\'all\');">All</a></li>

						$genTable .= '<div class="in-table">      
										<table cellpadding="0" cellspacing="0" border="0">
											<thead>
											<tr>
												<td>Name</td>
												<td>Entity version</td>
											</tr>
											</thead>';
							

								$qryType = " ";
												
						$genTable .= '<tbody id="contentData"></tbody>';			

						$genTable .= '</table></div></article>';
						// contents end
						$genTable .= '<div class="download-left">';
						if($checkValue)
							{               
								
								if ($downloadPg !='1'){
								$genTable .='<a href="cautionPage.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=workflow&certi='.$certi.'" name="download" class="myButton1 downlaod_btn">Download</a> ';
							
														}
							}
							else
							{
								
								if ($downloadPg !='1'){
								/*$genTable .='<a href="eula.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=workflow&certi='.$certi.'" name="download" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a> ';*/
								
								$genTable .='<a href="eula.shtml?packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=workflow&certi='.$certi.'" name="download" class="myButton1 downlaod_btn">Download</a> ';
														}
							}   
				
							$genTable .= '<a href="workflowHelp.shtml?packUuid='.$linePack->uuid.'&packVersion='.$linePack->version.'"> <img src="images/documentation-icon.png" width="20" height="21" border="0" align="absmiddle"  /> Documentation</a>';
				if($downloadPg == '1'){
				$genTable .='<article> ';
					
						$flag = false;
								if (!empty($_REQUEST['captcha'])) {

								if (!empty($_SESSION['captcha']) and trim(strtolower($_REQUEST['captcha'])) == $_SESSION['captcha']) {					
									
									/* $genTable .= '<div class="download-left"><a href="download.shtml?packPath=' . $encodedPath. '&packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=workflow" name="download" id="packAction" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a></div>';	 */
									  
									  $genTable .= '<div class="download-left"><a href="download.shtml?packPath=' . $encodedPath. '&packUuid=' . $linePack->uuid .'&packVersion=' . $linePack->version.'&packType=workflow" name="download" id="packAction" class="myButton1 downlaod_btn">Download</a></div>';
									  
									$flag = true;	  
								}					
							}
							if(!$flag)	
							{								
							
								$genTable .= '<img src="captcha.shtml" id="captcha" /><br/>';

								$genTable .= '<a href="javascript:void(0);" onclick="
								document.getElementById(\'captcha\').src=\'captcha.shtml?\'+Math.random();
								document.getElementById(\'captcha-form\').focus();"
								id="change-image">Change text </a> ( Note : Captcha is Case Sensitive )<br/> <br/>';

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

							$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");

							
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
							$select_rating = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$linePack->id."' AND ratingPackVersion='".$linePack->version."' AND ratingPackType='Workflow'");
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
							$selcetUserRating = mysql_query("SELECT * FROM " .TBL_REPORTS." WHERE packId='".$linePack->id."' AND packVersion='".$linePack->version."' AND userId='".$_SESSION['uid']."'");
							
							$userRatedValue = mysql_num_rows($selcetUserRating);
							
							$userRatingEligibleqry = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$linePack->id."' AND ratingPackVersion='".$linePack->version."' AND ratingPackType='Workflow' AND ratingBy='".$_SESSION['uid']."'");
							
							$userRatingEligible = mysql_num_rows($userRatingEligibleqry);
							//echo $userRatingEligible; exit;
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
										<input type="hidden" name="starpacktype" id="starpacktype" value="Workflow" />
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
						
							$getDepData = $this->getDepData($linePack->id, $linePack->version, 'Workflow');	
							if($userType == 1 && $downloadPg != '1'){
								$genTable .= "<article><span>Deprecate</span>";	
							}					
														
							if($getDepData <= 0 ){								
								if($userType == 1 && $downloadPg != '1'){
									$genTable .= "<a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup2' id='".$linePack->id."'>Post Deprecation</a>";
								}
							}else if($getDepData > 0){								
								$records = $this->getDepDataDetailed($linePack->id, $linePack->version, 'Workflow');
																	
								if($userType == 1 && $downloadPg != '1'){
									$checkValue = (($records['flag'] == '1') ? 'Checked' : '');	
									$genTable .= 'Uncheck to Delete Comment: <input type="checkbox" name="deprecationFlag" class="deprecationFlag" onclick="setDeprecationFlag('. $linePack->id .',\'' . $linePack->version . '\')"; ' .$checkValue. ' alt="Delete Comment"/> <span id="cautionLoader' . $linePack->id . '"></span>';
								}
								if($records['depComment'] != ''){
									$genTable .= "<pre><font color='red'>".$records['depComment']."</font></pre></article>";
								}																  							
							}
														
							/*<a class="js-open-modal"  href="javascript:void(0)" data-modal-id="popup1">Flag as inappropriate</a>*/ 
						$genTable .= '	</div>
											
									   
						</div>';
						//download 
						
						
           						
        } else {
            //$this->log->LogError("No records found to be displayed - inside " . __FUNCTION__);
            $genTable = '
			
			<section class="back-link">
						<a href="pack-list.shtml"> < Back</a><div>No Records Found</div>
						
						</section>';
        }

        return $genTable;    
    }

    /**
     * Function to fetch full information of entities of a particular pack based on packId, uuid, and version
     * @param type $packId
     * @param type $pkUuid
     * @param type $pkVersion
     * @return string
     */
    function entityVersionInformationDownload($packId = null, $pkUuid, $pkVersion) {

        $cond = " 1=1 ";
        if (empty($pkUuid) && empty($pkVersion)) {
            $rst = $this->selectQry(TBL_PACKDETAILS, "id=$packId ", "", "");
            $numPack = $this->getTotalRow($rst);
            $linePack = $this->getResultObject($rst);

            $qry = "select distinct version from " . TBL_PACKDETAILS . " where uuid = '" . $linePack->uuid . "'";
            $packVersion = $this->db_fetch_assoc_array_my($qry);
        } else {
            $rst = $this->selectQry(TBL_PACKDETAILS, "uuid = '" . $pkUuid . "' and version='" . $pkVersion . "'", "", "");
            $numPack = $this->getTotalRow($rst);
            $linePack = $this->getResultObject($rst);

            $qry = "select distinct version from " . TBL_PACKDETAILS . " where uuid = '" . $pkUuid . "'";
            $packVersion = $this->db_fetch_assoc_array_my($qry);
        }

        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;

        $genTable = '<div id="outerDiv1">';
        if ($numPack > 0) {
            $genTable .= '<input type="hidden" name="packUuid" id="packUuid" value="' . $linePack->uuid . '">';
            $genTable .= '<input type="hidden" name="packId" id="packId" value="' . html_entity_decode(base64_encode($linePack->id)). '">';


            if (strtolower($linePack->certifiedBy) == 'netapp') {
                $class = 'certifiedImage';
            } else {
                $class = '';
            }
            $genTable .= '<div id="insideContent1">
                            <div id="contentHeader1">
                            <div style="position:relative;display:inline;">' . $linePack->packName . '</div>
                            <div class="' . $class . '"></div>
                            
                            </div>
                                <div id="contentData1">' . $linePack->packDescription . '</div>
                                <div id="metaData1">
                                <div id="cVersion1">
                                Current Pack Version: ' . $linePack->version . '
                                </div>
                                <div id="cAuthor1">
                                Author: ' . $linePack->author . '
                                </div>
                                <div id="cDate1">
                                    Released on: '.$linePack->packDate.'                                </div>
                                <div id="minVersion1">
                                    Min WFA Version: '.$linePack->minWfaVersion.'
                                </div>';
                                $minsofV= $linePack->minSoftVersion;
								$minwfaV = intval($linePack->minWfaVersion);	
								
								if(($minwfaV) <= "3") 
								{  
									if( !empty($minsofV )) 
									{ 
										$genTable .= '
										<div id="minSoftVersion1">
											Min Software Version: '.$linePack->minSoftVersion.'
										</div>';
									}
								}
								else if($minwfaV >= "4"){
								$genTable .= '
										<div id="minSoftVersion1">
											Min Software Version: '.$linePack->minSoftVersion.'
										</div>';							
								}
								
			$genTable .= '					
							</div>
							</div> 
							<div id="dataTable">
								<div id="entityHeading">Contents of this pack</div>
								<table id="myTable">
								<tr>
									<th>Name</th>
									<th>Type</th>
									<th>Entity Version</th>
								</tr>
								';


            $query = "SELECT * FROM " . TBL_PACKENTITIES . " where packId = '" . $linePack->id . "'";

            $orderby = $_GET[orderby] ? $_GET[orderby] : "entityDate";
            $order = $_GET[order] ? $_GET[order] : "DESC";
            $query .= " ORDER BY $orderby $order";

            $sql = $this->executeQry($query);
            $numEntity = $this->getTotalRow($sql);

            if ($numEntity > 0) {

                $i = $offset + 1;

                while ($line = $this->getResultObject($sql)) {

                    $highlight = $i % 2 == 0 ? "main-body-bynic" : "main-body-bynic2";
                    $div_id = "status" . $line->entityId;

                    $genTable .= '
                                    <tr>
                                        <td>' . $line->name . '</td>
                                        <td>' . $line->entityType . '</td>
                                        <td>' . $line->version . '</td>
                                    </tr>';

                    $i++;
                }
               
                $encodedPath = base64_encode(PATH.$linePack->packFilePath);
                
                $genTable .= '</table>'; 
                $flag = false;
                if (!empty($_REQUEST['captcha'])) {
                
                    if (!empty($_SESSION['captcha']) and trim(strtolower($_REQUEST['captcha'])) == $_SESSION['captcha']) {                  
                              
                         $genTable .= '<div id="downloadEula">Click here To
                                      <a href="download.shtml?packPath=' . $encodedPath. '" name="download" id="packAction">download</a>                                
                              </div></div>';      
                              
                        $flag = true;     
                    }                   
                }
                
                if(!$flag)  
                {               
                    $genTable .= '<img src="captcha.shtml" id="captcha" /><br/>';
                    
                    $genTable .= '<a href="javascript:void(0);" onclick="
        document.getElementById(\'captcha\').src=\'captcha.shtml?\'+Math.random();
        document.getElementById(\'captcha-form\');"
        id="change-image">Change text </a> ( Note : Captcha is Case Sensitive )<br/> <br/>';
        
                    $genTable .= '<div id="captchaButtons"><input type="text" name="captcha" id="captcha-form" autocomplete="off" autofocus/><br/><br/>';
                    $genTable .= '<input type="submit" value="Submit Captcha" class="myButton"/></div>';
                }
               
            }
            $genTable .= '</div>';

        } else {
            $this->log->LogError("No records found to be displayed -  inside " . __FUNCTION__);
            $genTable = '<div>&nbsp;</div><div class="Error-Msg">No Records Found</div></div>';
        }

        return $genTable;
    }

    /**
     * Function to fetch full information of a pack, including entity details based on packId.
     * @param type $packId
     * @return string
     */
    function entityFullInformation($packId) {

        $cond = " 1=1 ";

        $rst = $this->selectQry(TBL_PACKDETAILS, "id=$packId ", "", "");
        $numPack = $this->getTotalRow($rst);
        $linePack = $this->getResultObject($rst);

        $qry = "select distinct version from " . TBL_PACKDETAILS . " where uuid = '" . $linePack->uuid . "'";
        $packVersion = $this->db_fetch_assoc_array_my($qry);

        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;

        $genTable = '<div id="outerDiv1">';
        if ($numPack > 0) {
            $genTable .= '<input type="hidden" name="packUuid" id="packUuid" value="' . $linePack->uuid . '">';
            $genTable .= '<div id="insideContent1">
                                <div id="contentHeader1">
                            <div sytle="float:left">' . $linePack->packName . '</div>
                            <div id="versionMenu1" >
                                <label>Viewing Pack Version</label><select name="packVersion" id="packVersion" class="packVersion" onchange="entityForm.form.submit();">';
            foreach ($packVersion as $ver) {
                $genTable .= '<option val="' . $ver['version'] . '">' . $ver['version'] . '</option>';
            }

            $genTable .= '</select>
                            </div>
                            </div>
                                <div id="contentData1">' . $linePack->packDescription . '</div>
                                <div id="metaData1">
                                <div id="cVersion1">
                                Current Pack Version: ' . $linePack->version . '
                                </div>
                                <div id="cAuthor1">
                                Author: ' . $linePack->author . '
                                </div>
                                <div id="cDate1">
                                    Released on: '.$linePack->packDate.'                                </div>
                                <div id="minVersion1">
                                    Min WFA Version: '.$linePack->minWfaVersion.'
                                </div>';
                            $minsofV= $linePack->minSoftVersion;
								$minwfaV = intval($linePack->minWfaVersion);	
								
								if(($minwfaV) <= "3") 
								{  
									if( !empty($minsofV )) 
									{ 
										$genTable .= '
										<div id="minSoftVersion1">
											Min Software Version: '.$linePack->minSoftVersion.'
										</div>';
									}
								}
								else if($minwfaV >= "4"){
								$genTable .= '
										<div id="minSoftVersion1">
											Min Software Version: '.$linePack->minSoftVersion.'
										</div>';							
								}
								
			$genTable .= '					
							</div>
							</div> 
							<div id="dataTable">
								<div id="entityHeading">Contents of this pack</div>
								<table id="myTable">
								<tr>
									<th>Name</th>
									<th>Type</th>
									<th>Entity Version</th>
								</tr>
								';

            $query = "SELECT * FROM " . TBL_PACKENTITIES . " where packId = $packId";

            //-------------------------Paging------------------------------------------------           
            $paging = $this->paging($query);
            @$this->setLimit($_GET["limit"]);
            $recordsPerPage = $this->getLimit();
            @$offset = $this->getOffset($_GET["page"]);
            $this->setStyle("redheading");
            $this->setActiveStyle("smallheading");
            $this->setButtonStyle("boldcolor");
            $currQueryString = $this->getQueryString();
            $this->setParameter($currQueryString);
            $totalrecords = $this->numrows;
            $currpage = $this->getPage();
            $totalpage = $this->getNoOfPages();
            $pagenumbers = $this->getPageNo();
            //-------------------------Paging------------------------------------------------

            $orderby = $_GET[orderby] ? $_GET[orderby] : "entityDate";
            $order = $_GET[order] ? $_GET[order] : "DESC";
            $query .= " ORDER BY $orderby $order LIMIT " . $offset . ", " . $recordsPerPage; 


            $sql = $this->executeQry($query);
            $numEntity = $this->getTotalRow($sql);

            if ($numEntity > 0) {

                $i = $offset + 1;


                while ($line = $this->getResultObject($sql)) {

                    $highlight = $i % 2 == 0 ? "main-body-bynic" : "main-body-bynic2";
                    $div_id = "status" . $line->entityId;

                    $genTable .= '
                                    <tr>
                                        <td>' . $line->name . '</td>
                                        <td>' . $line->entityType . '</td>
                                        <td>' . $line->version . '</td>
                                    </tr>
                                    ';

                    $i++;
                }

                $encodedPath = base64_encode(SITEPATH . $linePack->packFilePath);

                $genTable .= '</table><div id="downloadButton1">
                            <a href="eula.shtml?packPath=' . $encodedPath . '&packId=' . $linePack->id . '" name="download" id="packD" class="packAction" ">Download Pack</a>                                               
                        </div></div>';

                switch ($recordsPerPage) {
                    case 10:
                        $sel1 = "selected='selected'";
                        break;
                    case 20:
                        $sel2 = "selected='selected'";
                        break;
                    case 30:
                        $sel3 = "selected='selected'";
                        break;
                    case $this->numrows:
                        $sel4 = "selected='selected'";
                        break;
                }
                $currQueryString = $this->getQueryString();
                $limit = basename($_SERVER['PHP_SELF']) . "?" . $currQueryString;
            }
            $genTable.="<div style='overflow:hidden; margin:0px 0px 0px 50px;'><table border='0' width='88%' height='50'>
                     <tr><td align='left' width='300' class='page_info' 'style=margin-left=20px;'>
                     Display <select name='limit' id='limit' onchange='pagelimit(\"$limit\");' class='page_info'>
                     <option value='10' $sel1>10</option>
                     <option value='20' $sel2>20</option>
                     <option value='30' $sel3>30</option> 
                     <option value='" . $totalrecords . "' $sel4>All</option>  
                       </select> Records Per Page
                    </td><td align='center' class='page_info'><inputtype='hidden' name='page' value='" . $currpage . "'></td><td 
                    class='page_info' align='center' width='200'>Total " . $totalrecords . " records found</td><td width='0' 
                    align='right'>" . $pagenumbers . "</td></tr></table></div>";
        } else {
           // $this->log->LogError("No records found to be displayed");
            $genTable = '<div>&nbsp;</div><div class="Error-Msg">No Records Found</div></div>';
        }

        return $genTable;
    }

    function getUserById($userId) {
        $rst = $this->selectQry(TBL_ADMINUSER, "id='$userId'", "", "");
        if ($this->getTotalRow($rst)) {
            return $this->getResultRow($rst);
        } else {
            header("Location:manageUser.php");
            exit;
        }
    }

    function editRecord($post) {
        /* echo"<pre>"; print_r($_POST);exit;    */
        $this->tablename = TBL_ADMINUSER;
        $uid = base64_encode($post['userId']);
        $this->field_values['userName'] = $post['userName'];
        $this->field_values['email'] = $post['email'];

        if (!empty($_FILES['advertiserImage'])) {
            $created = time();

            $image_name = $_FILES['advertiserImage']['name'];
            $filename = strtolower(basename($image_name));
            $tmp_name = $_FILES['advertiserImage']['tmp_name'];

            $basepath = PATH;
            $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
            if (!empty($tmp_name)) {
                $time_constant = time();
                $image_name = str_replace(" ", "_", $image_name);
                //image details for database
                $image = 'images/user/main/' . $time_constant . '.' . $ext;
                $imageThumb = 'images/user/thumb/' . $time_constant . '.' . $ext;

                $main_path = $basepath . $image;
                $thumb_path = $basepath . $imageThumb;

                list($width, $height, $type, $attr) = @getimagesize($main_path);

                $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

                if ((($ext == "jpg") || ($ext == "png") || ($ext == "gif") || ($ext == "jpeg")) && ($_FILES["advertiserImage"]["size"] < 400000)) {
                    if (@move_uploaded_file($tmp_name, $main_path)) {
                        create_thumb_imagemagic($main_path, 320, 460, $thumb_path);
                        $this->field_values['image'] = $image;
                        $this->field_values['imageSmall'] = $thumb_path;
                    }
                } else {
                    $_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Error: Only .jpg or .png files is allowed");
                    echo"<script>window.location.href='addUser.php'</script>";
                    exit;
                }
            }
        }


        $this->field_values['modby'] = $_SESSION['ADMIN_ID'];
        $this->condition = "id='" . $post['userId'] . "'";
        $res = $this->updateQry();
        if ($res) {
            $_SESSION['SESS_MSG'] = msgSuccessFail("success", "User has been updated successfully!!!");
        }
        redirect('manageUser.php');
        exit;
    }
    
	/* Function to get any pack id based on uuid and version
	* @param type string
    * @return string	
	* (Start block -> @Author ASG)
	*/
   function getPackId($packUuid, $packVersion){
            $idqry = mysql_query("SELECT id FROM ".TBL_PACKDETAILS." WHERE uuid = '".$packUuid."' AND version='".$packVersion."'"); 
            $packIdsres = mysql_fetch_array($idqry);
            $packId = $packIdsres[0]; 
            return $packId;
   }  
	/* Function to get any pack name based on uuid and version 
	* @param type string
    * @return string
	* @Author ASG
	*/
   function getPackName($packUuid, $packVersion){
            $nameQry = mysql_query("SELECT packName FROM ".TBL_PACKDETAILS." WHERE uuid = '".$packUuid."' AND version='".$packVersion."'"); 
            $packNameRes = mysql_fetch_array($nameQry);
            $packName = $packNameRes['packName']; 
            return $packName;
   }
   /* Function to get any pack contact email based on uuid and version
	* @param type string
    * @return string   
	* @Author ASG
	*/
   function getPackContactEmail($packUuid, $packVersion){
            $emailQry = mysql_query("SELECT contactEmail FROM ".TBL_PACKDETAILS." WHERE uuid = '".$packUuid."' AND version='".$packVersion."'"); 
            $packContactEmail = mysql_fetch_array($emailQry);
            $email = $packContactEmail['contactEmail']; 
            return $email;
   }
   /* Function to get any report owner email based on name and version
	* @param type string
    * @return string   
	* @Author ASG
	*/
   function getReportContactEmail($reportName, $reportVersion){
            $emailQry = mysql_query("SELECT authorEmail FROM ".TBL_OCUMREPORTS." WHERE reportName = '".$reportName."' AND reportVersion='".$reportVersion."'"); 
            $reportContactEmail = mysql_fetch_array($emailQry);
            $email = $reportContactEmail['authorEmail']; 
            return $email;
   }
   /* Function to get any pack owner email based on uuid and version
	* @param type string
    * @return string   
	* @Author ASG
	*/
   function getPackOwnerEmail($packUuid, $packVersion){
			$packid = $this->getPackId($packUuid, $packVersion);			
            $emailQry = mysql_query("SELECT userEmail FROM ". TBL_USERPACKS ." WHERE packId = '".$packid."'"); 
            $packContactEmail = mysql_fetch_array($emailQry);
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
			/*if($entityType == 'all')
			{
				$query = "SELECT * FROM " . TBL_PACKENTITIES . " where packId = '" . $packId . "' and entityType IN('Workflow','Finder','Filter','Command','Dictionary Entry','Data Source','Scheme','Remote System Type','Workflows','Finders','Filters','Commands','Dictionary Entrys','Data Sources','Schemes','Remote System Types')";    
			}
			else{
				$query = "SELECT * FROM " . TBL_PACKENTITIES . " where packId = '" . $packId . "' and entityType like '".ucwords($entityType)."%'";
			}*/

			if($entityType == 'all')
			{
				$query = "SELECT * FROM " . TBL_PACKENTITIES . " where packId = '" . $packId . "' and entityType IN('Workflow','Finder','Filter','Command','Dictionary Entry','Script Data Source Type','Scheme','Remote System Type', 'Category','Workflows','Finders','Filters','Commands','Dictionary Entrys','Script Data Source Types','Schemes','Remote System Types','Categories')";    
			}
			else{
				$query = "SELECT * FROM " . TBL_PACKENTITIES . " where packId = '" . $packId . "' and entityType like '".ucwords($entityType)."%'";
			}
			/* if($entityType == 'all')
			{
				$query = "SELECT * FROM " . TBL_PACKENTITIES . " where packId = '" . $packId . "' and entityType IN('Workflow','Finder','Filter','Command','Dictionary Entry','Data Source','Scheme')";   
			}
			else{
				$query = "SELECT * FROM " . TBL_PACKENTITIES . " where packId = '" . $packId . "' and entityType = '".ucwords($entityType)."'";
			} */
			
			$orderby = $_GET[orderby] ? $_GET[orderby] : "entityDate";
			$order = $_GET[order] ? $_GET[order] : "DESC";
			$query .= " ORDER BY $orderby $order";

			$sql = $this->executeQry($query);
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
	
	/**
     * Function to fetch full information of entities of a report pack based on reportName and reportVersion, changing the content if funtion is used for download
     * @param type $reportName
     * @param type $reportVersion
	 * @param type $downloadPg
     * @return string
     */
     //hc-funtion
	function reportInformation($reportName,$reportVersion ,$downloadPg = null) {
		
		$query = " SELECT * FROM ". TBL_OCUMREPORTS." where reportName='".implode(" ",explode("!",$reportName))."' && reportVersion='".$reportVersion."'";
		
		$rst = $this->executeQry($query);
        $num = $this->getTotalRow($rst);
		$lineReport = $this->getResultObject($rst); 
		
		
        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
		$encodedPath = base64_encode( PATH. $lineReport->reportFilePath);	
		
		$qry = "select distinct reportVersion,reportDate from " . TBL_OCUMREPORTS. " where reportName = '" . implode(" ",explode("!",$reportName)) . "' ORDER BY reportVersion desc";
        $reportVERSIONS = $this->db_fetch_assoc_array_my($qry);
		$genTable .= '<section class="back-link">
    	<a href="reports.shtml"> < Back</a>
		</section>	';
        if ($num > 0) {
            //Added dynamic divs and dynamic data
            $genTable .= ' <section class="details-pack">
							<h2>'.$lineReport->reportName.'</h2>
							<div class="version">Version :
							<span>'.$lineReport->reportVersion.' </span>';
						  
						foreach ($reportVERSIONS as $ver) {
													if ($ver['reportVersion'] == $lineReport->reportVersion) {} else 
													{
														$genTable .= '<a href="reports-detail.shtml?reportName='.implode("!",explode(" ",$lineReport->reportName)).'&reportVersion='.$ver['reportVersion'] .'"> ' . $ver['reportVersion'] . '</a>';
													}
												}
																	
			$genTable .= '</div>	<div class="download-div">
			
			<div class="download-left">';
			if($downloadPg != 1){
			/*$genTable .= '<a href="eula.shtml?reportName=' .implode("!",explode(" ",$reportName)).'&reportVersion=' .$reportVersion.'&packType=report" name="download" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a>';*/
			
			$genTable .= '<a href="eula.shtml?reportName=' .implode("!",explode(" ",$reportName)).'&reportVersion=' .$reportVersion.'&packType=report" name="download" class="myButton1 downlaod_btn">Download</a>';
			}
			$genTable .= '</div>
			<div class="downlaod-right"> ';
			if ($lineReport->certifiedBy == "NETAPP") 
											{
												$genTable .= '<img src="images/netapp-certified-icon.png" width="20" height="20"  align="absmiddle"> This template is NetApp-generated .'; 
											}
											else 
											{
												$genTable .= '<img src="images/non-netapp-certified.png" width="20" height="20"  align="absmiddle">This template is Community-generated .';  
											}
			$genTable .= ' </div></div>
							</section>
						<section class="de-left-content"><article>
						<pre>
						' .html_entity_decode($lineReport->reportDescription) . '
						</pre></article>
						<article><span>Whats changed</span>
						<pre>'.$lineReport->versionChanges.'</pre>
						<br />
						<span>Pre-requisites</span>
						<pre>'.$lineReport->otherPrerequisite.'</pre>
						<ul><li> OCUM Version: '.$lineReport->OCUMVersion.'</li>
						<li> Software Version Minimum: '.$lineReport->minONTAPVersion.',  Maximum: '.$lineReport->maxONTAPVersion.'</li></ul>
						</article>
						<div class="download-left">';
						if($downloadPg != '1'){
						/*$genTable .='<a href="eula.shtml?reportName=' .implode("!",explode(" ",$reportName)).'&reportVersion=' .$reportVersion.'&packType=report" name="download" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a>';*/
						
						$genTable .='<a href="eula.shtml?reportName=' .implode("!",explode(" ",$reportName)).'&reportVersion=' .$reportVersion.'&packType=report" name="download" class="myButton1 downlaod_btn">Download</a>';
						}
						$genTable .='</div>';
						if($downloadPg == '1'){
						$genTable .='<article> ';
					/* echo "request".$_REQUEST['captcha']."<br>";
					echo "session".$_SESSION['captcha']; */
					
						$flag = false;
								if (!empty($_REQUEST['captcha'])) {

								if (!empty($_SESSION['captcha']) and trim(strtolower($_REQUEST['captcha'])) == $_SESSION['captcha']) {					
									
									$genTable .= '<div class="download-left"><a href="download.shtml?packPath=' . $encodedPath. '&reportName=' .implode("!",explode(" ",$reportName)).'&reportVersion=' .$reportVersion.'&packType=report" name="download" id="packAction" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a></div>';	
									  
									$flag = true;	  
								}					
							}
							if(!$flag)	
							{		  						
							
								$genTable .= '<img src="captcha.shtml" id="captcha" /><br/>';

								$genTable .= '<a href="javascript:void(0);" onclick="
								document.getElementById(\'captcha\').src=\'captcha.shtml?\'+Math.random();
								document.getElementById(\'captcha-form\');"
								id="change-image">Change text </a> ( Note : Captcha is Case Sensitive )<br/> <br/>';

								$genTable .= '<div id="captchaButtons"><input type="text" name="captcha" id="captcha-form" autocomplete="off" autofocus/><br/><br/>';
								$genTable .= '<input type="submit" value="Submit Captcha" class="myButton" /></div>';
	
								if(!empty($_SESSION['captcha']) && !empty($_REQUEST['captcha'])){
											$genTable .='<div class="captcha_error"><img src="images/error.png" width="16" border="0" height="16">&nbsp;&nbsp;&nbspInvalid captcha</div>';}
								
								if (isset($_REQUEST['captcha'])){			
								if (empty($_REQUEST['captcha'])){$genTable .='<div class="captcha_error"><img src="images/error.png" width="16" border="0" height="16">&nbsp;&nbsp;&nbspInvalid captcha</div>';}}
								
									
							}
						$genTable .='</article> ';	
						
						}	
						
						
						
						$genTable .='</section>';
			$genTable .= '	<div class="de-right-content">
							<section class="de-right-div bt-btm">
							<article>
							<span>Version History</span>
							';
			foreach ($reportVERSIONS as $ver)
							{
							$time = new DateTime($ver['reportDate']);
							$relaeseDate = $time->format('jS M,Y');					
							$genTable .= '
											<div>Version:'.$ver['reportVersion'].' </div>
											<div>Released on:'.$relaeseDate.'</div>
											<div>&nbsp;</div>';
							}
			$genTable .= '
							</article>				 
							<span>Author</span>';
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
							 * for that pack/report -ASG
							 */
							$select_rating = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$lineReport->id."' AND ratingPackVersion='".$lineReport->reportVersion."' AND ratingPackType='Report'");
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
								<div>'.$lineReport->authorName.'<br />'.$lineReport->authorEmail.'<br />'.$lineReport->authorContact.'</div>
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
								
						$selcetUserRating = mysql_query("SELECT * FROM " .TBL_REPORTS." WHERE packId='".$lineReport->id."' AND packVersion='".$lineReport->reportVersion."' AND userId='".$_SESSION['uid']."' AND packType='report'");													
						$userRatedValue = mysql_num_rows($selcetUserRating);
														
							$userRatingEligibleqry = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$lineReport->id."' AND ratingPackVersion='".$lineReport->reportVersion."' AND ratingPackType='Report' AND ratingBy='".$_SESSION['uid']."'");
							
							$userRatingEligible = mysql_num_rows($userRatingEligibleqry);
							//echo $userRatingEligible; exit;
							if($userRatedValue > 0){
								if($userRatingEligible <= 0){
								$genTable .= '<article><span>Rate this Report</span>
										<form name="ratingForm" id="ratingForm" method="post" action="reports-detail.php">
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
										<input type="hidden" name="starpacktype" id="starpacktype" value="Report" />
										<input type="hidden" name="starpackversion" id="starpackversion" value='.$lineReport->reportVersion.' />
										<input type="submit" value="Submit" name="submit_rating" id="submit_rating" style="display:none;">
									</form></article><br />';
								}else{
								$genTable .= '<article><span>Rate this Report</span><div class="div">
								<img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><br />';
								$genTable .= "You already rated this Report!</div></article><br />";
								}
							}else{
								$genTable .= '<article><span>Rate this Report</span><div class="div">
								<img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><br />';
								$genTable .= "Download this Report to rate!</div></article><br />";
							}							
							$genTable .= "<article><span>Feedback</span><a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup1' id='".$lineReport->id."'>Flag as inappropriate</a></article><br />";
						}
						/* Deprecation link Start - ASG */
							$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
						
							$getDepData = $this->getDepData($lineReport->id, $lineReport->reportVersion, 'Report');		
							if($userType == 1 && $downloadPg != '1'){
								$genTable .= "<article><span>Deprecate</span>";	
							}																	
							if($getDepData <= 0 ){								
								if($userType == 1 && $downloadPg != '1'){
									$genTable .= "<a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup2' id='".$lineReport->id."'>Post Deprecation</a>";
								}
							}else if($getDepData > 0){								
								$records = $this->getDepDataDetailed($lineReport->id, $lineReport->reportVersion, 'Report');
																		
								if($userType == 1 && $downloadPg != '1'){
									$checkValue = (($records['flag'] == '1') ? 'Checked' : '');	
									$genTable .= 'Uncheck to Delete Comment: <input type="checkbox" name="deprecationFlag" class="deprecationFlag" onclick="setDeprecationFlag('. $lineReport->id .',\'' . $lineReport->reportVersion . '\')"; ' .$checkValue. ' alt="Delete Comment"/> <span id="cautionLoader' . $lineReport->id . '"></span>';
								}
								if($records['depComment'] != ''){
									$genTable .= "<pre><font color='red'>".$records['depComment']."</font></pre></article>";
								}																  							
							}
						
							/* Deprecation link End - ASG */
							
							/*<a class="js-open-modal"  href="javascript:void(0)" data-modal-id="popup1">Flag as inappropriate</a>*/ 
						$genTable .= '	</div>									   
						</div>';
										
        } else {
            $genTable = '<tr><td colspan="5" >No Records Found</td></tr>';
        }
		
        return $genTable;    }
		
		/**
     * Function to fetch full information of entities of a performance pack based on packName and packVersion, changing the content if funtion is used for download
     * @param type $packName
     * @param type $packVersion
	 * @param type $downloadPg
     * @return string
     */
     //hc-funtion	 
	function performanceInformation($packName ,$packVersion ,$downloadPg = null) {

		$query = " SELECT * FROM ". TBL_OPMPACKS." where packName='".implode(" ",explode("!",$packName))."' && packVersion='".$packVersion."'";
		$rst = $this->executeQry($query);
        $num = $this->getTotalRow($rst);
		$lineReport = $this->getResultObject($rst); 
        $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
		$encodedPath = base64_encode(PATH . $lineReport->packFilePath);
		$qry = "select distinct packVersion,packDate as reportDate from " . TBL_OPMPACKS. " where packName = '" . implode(" ",explode("!",$packName)) . "'ORDER BY packVersion desc";
        $packVersionS = $this->db_fetch_assoc_array_my($qry);
		$genTable .= '<section class="back-link">
    	<a href="performance.shtml"> < Back</a>
		</section>	';
			
        if ($num > 0) {
            //Added dynamic divs and dynamic data
            $genTable .= ' <section class="details-pack">
							<h2>'.$lineReport->packName.'</h2>
							<div class="version">Version :
							<span>'.$lineReport->packVersion.' </span>';
						  
						foreach ($packVersionS as $ver) {
													if ($ver['packVersion'] == $lineReport->packVersion) {} else 
													{
														$genTable .= '<a href="performance-detail.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$ver['packVersion'] .'"> ' . $ver['packVersion'] . '</a>';
													}
												}
																	
			$genTable .= '</div>	<div class="download-div">
			<div class="download-left">';
			
			if($downloadPg != '1'){
			/*$genTable .= '<a href="eula.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=performance" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a>';*/
			
			$genTable .= '<a href="eula.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=performance" class="myButton1 downlaod_btn">Download</a>';
			}
			$genTable .='</div>
			<div class="downlaod-right"> ';
			if ($lineReport->certifiedBy == "NETAPP") 
											{
												$genTable .= '<img src="images/netapp-certified-icon.png" width="20" height="20"  align="absmiddle"> This pack is NetApp-generated .'; 
											}
											else 
											{
												$genTable .= '<img src="images/non-netapp-certified.png" width="20" height="20"  align="absmiddle">This pack is Community-generated .';  
											}
			$genTable .= ' </div>
							</section>
						<section class="de-left-content"><article>
						<pre>
						' .html_entity_decode($lineReport->packDescription) . '
						</pre></article>
						<article><span>Whats changed</span>
						<pre>'.$lineReport->versionChanges.'</pre>
						<br />
						<span>Pre-requisites</span>
						<pre>'.$lineReport->otherPrerequisite.'</pre></br>
						<ul><li> OPM Version: '.$lineReport->OPMVersion.'</li>
						<li> Software Version Minimum: '.$lineReport->minONTAPVersion.',   Maximum: '.$lineReport->maxONTAPVersion.'</li></ul>
						</article>
						<div class="download-left">';
						if($downloadPg != '1'){
						/*$genTable .= '<a href="eula.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=performance" name="download" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a>';*/
						
						$genTable .= '<a href="eula.shtml?packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=performance" name="download" class="myButton1 downlaod_btn">Download</a>';
						}
						$genTable .='</div>';
						if($downloadPg == '1'){
						$genTable .='<article> ';
					
						$flag = false;
								if (!empty($_REQUEST['captcha'])) {

								if (!empty($_SESSION['captcha']) and trim(strtolower($_REQUEST['captcha'])) == $_SESSION['captcha']) {					
									
									$genTable .= '<div class="download-left"><a href="download.shtml?packPath='.base64_encode($lineReport->packFilePath).' &packName='.implode("!",explode(" ",$lineReport->packName)).'&packVersion='.$lineReport->packVersion.'&packType=performance" name="download" id="packAction" class="packAction"><img src="images/download-btn.png" border="0"  align="absmiddle"/></a></div>';	
									  
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
							$relaeseDate = $time->format('jS M,Y');					
							$genTable .= '
											<div>Version:'.$ver['packVersion'].' </div>
											<div>Released on:'.$relaeseDate.'</div>
											<div>&nbsp;</div>';
							}
			$genTable .= '
							</article>				 
							<span>Author</span>';
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
							$select_rating = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$lineReport->id."' AND ratingPackVersion='".$lineReport->packVersion."' AND ratingPackType='Performance'");
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
								<div>'.$lineReport->authorName.'<br /><a target="_blank" href="https://www.netapp.com">www.netapp.com</a><br /></div>
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
								 
						$selcetUserRating = mysql_query("SELECT * FROM " .TBL_REPORTS." WHERE packId='".$lineReport->id."' AND packVersion='".$lineReport->packVersion."' AND userId='".$_SESSION['uid']."' AND packType='performance'");												
							$userRatedValue = mysql_num_rows($selcetUserRating);
														
							$userRatingEligibleqry = mysql_query("SELECT * FROM ".TBL_RATINGSTAR." WHERE ratingPackId='".$lineReport->id."' AND ratingPackVersion='".$lineReport->packVersion."' AND ratingPackType='Performance' AND ratingBy='".$_SESSION['uid']."'");
							
							$userRatingEligible = mysql_num_rows($userRatingEligibleqry);
							
							if($userRatedValue > 0){
								if($userRatingEligible <= 0){
								$genTable .= '<article><span>Rate this Pack</span>
										<form name="ratingForm" id="ratingForm" method="post" action="performance-detail.php">
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
										<input type="hidden" name="starpacktype" id="starpacktype" value="Performance" />
										<input type="hidden" name="starpackversion" id="starpackversion" value='.$lineReport->packVersion.' />
										<input type="submit" value="Submit" name="submit_rating" id="submit_rating" style="display:none;">
									</form></article><br />';
								}else{
								$genTable .= '<article><span>Rate this pack</span><div class="div">
								<img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><br />';
								$genTable .= "You already rated this Pack!</div></article><br />";
								}
							}else{
								$genTable .= '<article><span>Rate this pack</span><div class="div">
								<img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><img src="images/star-blank-icon.png"><br />';
								$genTable .= "Download this Pack to rate!</div></article><br />";
							}	
						$genTable .= "<article><span>Feedback</span><a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup1' id='".$lineReport->id."'>Flag as inappropriate</a></article><br />";
						}
						
						/* Deprecation link Start - ASG */
							$userType = $this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
						
							if($userType == 1 && $downloadPg != '1'){
								$genTable .= "<article><span>Deprecate</span>";	
							}
							$getDepData = $this->getDepData($lineReport->id, $lineReport->packVersion, 'Performance');
							if($getDepData <= 0 ){
									
								if($userType == 1 && $downloadPg != '1'){
									$genTable .= "<a class='js-open-modal'  href='javascript:void(NULL);' data-modal-id='popup2' id='".$lineReport->id."'>Post Deprecation</a>";									
								}
							}else if($getDepData > 0){													
							$records = $this->getDepDataDetailed($lineReport->id, $lineReport->packVersion, 'Performance');
																		
								if($userType == 1 && $downloadPg != '1'){
									$checkValue = (($records['flag'] == '1') ? 'Checked' : '');	
									$genTable .= 'Uncheck to Delete Comment: <input type="checkbox" name="deprecationFlag" class="deprecationFlag" onclick="setDeprecationFlag('. $lineReport->id .',\'' . $lineReport->packVersion . '\')"; ' .$checkValue. ' alt="Delete Comment"/> <span id="cautionLoader' . $lineReport->id . '"></span>';
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
		
        return $genTable;    }
		
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
	
	/* Function to get any report id based on name and version
	* @param type string, string
    * @return string	
	* (Start block -> @Author ASG)
	*/
   function getReportId($name, $version){
            $idqry = mysql_query("SELECT id FROM ".TBL_OCUMREPORTS." WHERE reportName = '".$name."' AND reportVersion='".$version."'"); 
            $Idsres = mysql_fetch_array($idqry);
            $Id = $Idsres['id']; 
            return $Id;
   }
   /* Function to get any OPM pack id based on name and version
	* @param type string, string
    * @return string	
	* (Start block -> @Author ASG)
	*/
   function getPackIdOPM($name, $version){	  
            $idqry = mysql_query("SELECT id FROM ".TBL_OPMPACKS." WHERE packName = '".$name."' AND packVersion='".$version."'"); 
            $Idsres = mysql_fetch_array($idqry);
            $Id = $Idsres['id']; 
            return $Id;
   }
}
?>
