<?php

@session_start();

/**
 * SolrResponse.php
 * This class processes the response to be sent as a result of search performed by the user.
 * 
 * 
 */
class SolrResponse extends MySqlDriver {

    function __construct() {
        $obj = new MySqlDriver;
        $this->log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
    }
    
    /**
     * getWorkflowResponse
     * Function to fetch data from response based on user entered text.
     * 
     * @param type $searchText
     * @return string
     */
    function getWorkflowResponse($searchText,$certi) {
	
		if(empty($searchText))
		{
			return  "<tbody><tr><td colspan='9' >Please provide a valid search text</td></tr></tbody>";
		}

          if (isset($searchText)) {
            $searchValue = $searchText;
            }
          else {
            $searchValue = $_REQUEST["search"];
             }

         
            
          $search = trim($searchValue);
         
          $search = str_replace(' ', '+', $search);
          if (!empty($search) && $searchValue != 'Enter Search Text...') {
                 $search = str_replace(' ', '+', $search);
       

            if($certi!="NONE" && $certi!="NETAPPG"){
          if($search[0] == '#')
          {
			       
            $url="http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/select/?q=".substr($search,1)."&fq=-certifiedBy:NONE%20&fq=type%3Aworkflow&wt=json&indent=true&defType=edismax&qf=tags&stopwords=true&lowercaseOperators=true"; 
          }
          else
          {	
              $url="http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/select/?q=*".$search."*&fq=-certifiedBy:NONE&fq=type:workflow&wt=json&indent=true&defType=edismax&qf=packName+packDescription+entityName+entityDescription+tags&stopwords=true&lowercaseOperators=true"; 
          }
		}
		else
		{
			if($search[0] == '#')
          {
			       
            $url="http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/select/?q=".substr($search,1)."&fq=certifiedBy:".$certi."&fq=type%3Aworkflow&wt=json&indent=true&defType=edismax&qf=tags&stopwords=true&lowercaseOperators=true"; 
          }
          else
          {	
              $url="http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/select/?q=*".$search."*&fq=certifiedBy:".$certi."&fq=type:workflow&wt=json&indent=true&defType=edismax&qf=packName+packDescription+entityName+entityDescription+tags&stopwords=true&lowercaseOperators=true"; 
          }
		}


          $content = file_get_contents($url);
            $obj = json_decode($content, true);


           
            $response = $obj['response'];
            
            $docs = $response['docs'];

           
            $len = count($docs);
              
            if ($len > 0) {
                $genTable = '';
                $flag = 'false';
                for ($i = 0; $i < $len; $i++) {

                    $countSearch = 0;
                    $workCount = 0;
                    $commandCount = 0;
                    $genTable .= '<div id="mainDiv">';
                     $packObj = new Pack();
                     $packData = $packObj->getPackById($docs[$i]['id']);

                     $userType = $packObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");

                   
                     $user= (array)$userType;

                     if($docs[$i]['type'] == "workflow" || !empty($docs[$i]['uuid']))
                     {
                         $flag = 'true';
                         $encodedPath = base64_encode($packData->packFilePath);

                         $genTable .= '<tr>';

					
					if(isset($docs[$i]['certifiedBy']))
                        { if ($docs[$i]['certifiedBy'] == 'NETAPP') {
                              $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                            } 
                        else {
                              $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                              }
							
					}
					else
					{
						if ($docs[$i]['packAuthor'] == 'NetApp') {
                              $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                            } 
                        else {
                              $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                              }
					}

                          $genTable .= '<td><a id="packHeader"  title="'.$docs[$i]['packName'].'" href="pack-detail.shtml?packUuid='.$docs[$i]['uuid'].'&packVersion='.$docs[$i]['packVersion'].'">'.$docs[$i]['packName'].'</a></td>';

                          $genTable .= '<td>' .$docs[$i]['packVersion']. '</td>';

                          if ($userType== 1) {
                        $genTable.='
                        <td>' .$packData->minWfaVersion.'';
                        }else {
                        $genTable.='
                        <td>' .$packData->minWfaVersion.'';
                        }
            
                        $genTable .= '
                                    </td><td>' .$docs[$i]['packAuthor'] . '</td>';  
                        $genTable .= '<td>' .date('d M, Y', strtotime($docs[$i]['packDate'])). '</td><td><a href="workflowHelp.shtml?packId='. base64_encode($idmaxVersion).'"><img src="images/documentation-icon.png" border="0" /></a></td>';



                        $genTable .= '<td><a href="eula.shtml?packUuid=' . $docs[$i]['uuid'] .'&packVersion=' . $docs[$i]['packVersion'].'&packType=workflow&certi='.$docs[$i]['certifiedBy'].'"><img src="images/downlaod-icon.png" border="0" /></a></td> '; 

                        if($userType == 1)
                         {
                           $genTable .= "<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure to delete this Record  ?')){window.location.href='pass.php?action=managePack&type=solrdelete&idToDel[]=" . $packData->id . "&page=pack-list'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Delete' alt='Delete' /></a></td>";
                         }



                     }
                   
                     
                   
                    
                $this->log->LogInfo("Solr performed the searched successfully.");
               
            }

            return $genTable;
          }
          else
          {
             
              if($searchValue[0] == '#')
              {
                $searchValue = substr($searchValue,1);
              }

                $this->log->LogInfo("Solr returned an empty result set.");
                $this->log->LogDebug("Query parameter searched is: " . $searchValue . " inside " . $_SERVER['PHP_SELF']);

                $genTable ="<tbody><tr><td colspan='9' >Sorry no records found in Workflow Packs.</td></tr></tbody>";

                return $genTable;
          }



        }
         
    }
  
   /**
     * getSolrResponse
     * Function to fetch data from response based on user entered text.
     * 
     * @param type $searchText
     * @return string
     */
    function getReportResponse($searchText,$certi) {
	
		if(empty($searchText))
		{
			return  "<tbody><tr><td colspan='9' >Please provide a valid search text</td></tr></tbody>";
		}


      if (isset($searchText)) {
            $searchValue = $searchText;
            }
          else {
            $searchValue = $_REQUEST["search"];
             }

          $search =$searchValue;
          $search = str_replace(' ', '+', $search);
          if (!empty($search) && $searchValue != 'Enter Search Text...') {
                 $search = str_replace(' ', '%20', $search);
       
          if($search[0] == '#')
                {
                  return '<tbody><tr><td colspan="9" >Sorry,no records found in Reports.</td></tr></tbody>';
                }
                else
                {
				
               $url="http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/select/?q=*".$search."*&wt=json&indent=true&defType=edismax&qf=reportName+reportDescription&stopwords=true&lowercaseOperators=true"; 
           }

         
		   
          $content = file_get_contents($url);
          $obj = json_decode($content, true);

          $response = $obj['response'];
          $docs = $response['docs'];
          $len = count($docs);

       
           if ($len > 0) {
                $genTable = '';

                for ($i = 0; $i < $len; $i++) {

                  $countSearch = 0;
                  $workCount = 0;
                  $commandCount = 0;
                  $genTable .= '<div id="mainDiv">';
                  $packObj = new Pack();
                  $packData = $packObj->getPackByTypeId($docs[$i]['id'],"report");

                  $userType = $packObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");

                 $flag = 'false'; 
                 $user= (array)$userType;

                  if($docs[$i]['type'] == "reports")
                  {

                    $flag = 'true';
                    $encodedPath = base64_encode($packData->packFilePath);

                    $genTable .= '<tr>';
                    if ($docs[$i]['certifiedBy'] == 'NETAPP') {
                              $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                            } 
                        else {
                              $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                              }
                     
                     $genTable .= '<td><a id="packHeader" title="'.$docs[$i]['reportName'].'" href="reports-detail.shtml?reportName='.implode("!",explode(" ",$docs[$i]['reportName'])).'&reportVersion='.$docs[$i]['reportVersion'].'">'. $docs[$i]['reportName'].'</a></td>';

                     $genTable .= '<td>' .$docs[$i]['reportVersion']. '</td>';
             
              
                    $genTable .= '
                          </td><td>' .$docs[$i]['ocumVersion'] . '</td>';  
                    $genTable .= '<td>' .date('d M, Y', strtotime($docs[$i]['ocumReleaseDate'])) . '</td> ';

                   $genTable .= '<td><a href="eula.shtml?reportName=' . implode("!",explode(" ",$docs[$i]['reportName'])) .'&reportVersion=' . $docs[$i]['reportVersion'].'&packType=report"><img src="images/downlaod-icon.png" border="0" /></a></td> ';

                   if($userType == 1)
                   {
                     $genTable .= "<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure to delete this Record  ?')){window.location.href='pass.php?action=managePack&type=solrdelete&idToDel[]=" . $packData->id . "&page=reports'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Delete' alt='Delete' /></a></td>";
                   }
                  }

                 if($flag == 'false')
                    {
                      $genTable = '<tbody><tr><td colspan="9" >Sorry no records found in Reports.</td></tr></tbody>';
                    }
                   
                   
                $this->log->LogInfo("Solr performed the searched successfully.");
               

                }
                 return $genTable;

 
          }
          else
          {
            if($searchValue[0] == '#')
            {
              $searchValue = substr($searchValue,1);
            }
             $this->log->LogInfo("Solr returned an empty result set.");
                $this->log->LogDebug("Query parameter searched is: " . $searchValue . " inside " . $_SERVER['PHP_SELF']);

                $genTable ="<tbody><tr><td colspan='9' >Sorry no records found in Reports.</td></tr></tbody>";

                return $genTable;
          }



      }
    
    }
  
   /**
     * getSolrResponse
     * Function to fetch data from response based on user entered text.
     * 
     * @param type $searchText
     * @return string
     */
    function getPerformanceResponse($searchText,$certi) {
	
		if(empty($searchText))
		{
			return  "<tbody><tr><td colspan='9' >Please provide a valid search text</td></tr></tbody>";
		}


       if (isset($searchText)) {
            $searchValue = $searchText;
            }
          else {
            $searchValue = $_REQUEST["search"];
             }

          $search = $searchValue;
          $search = str_replace(' ', '+', $search);
          if (!empty($search) && $searchValue != 'Enter Search Text...') {
                 $search = str_replace(' ', '%20', $search);
       
         if($search[0] == '#')
                {
                  return '<tbody><tr><td colspan="9" >Sorry,no records found in Performance.</td></tr></tbody>';
                }
                else
                {
				
               $url="http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/select/?q=*".$search."*&wt=json&indent=true&defType=edismax&qf=performanceName+performanceDescription&stopwords=true&lowercaseOperators=true"; 
           }
          $content = file_get_contents($url);
          $obj = json_decode($content, true);

          $response = $obj['response'];
          $docs = $response['docs'];
          $len = count($docs);

           if ($len > 0) {
                $genTable = '';

                for ($i = 0; $i < $len; $i++) {

                  $countSearch = 0;
                  $workCount = 0;
                  $commandCount = 0;
                  $genTable .= '<div id="mainDiv">';
                  $packObj = new Pack();
                  $packData = $packObj->getPackByTypeId($docs[$i]['id'],"performance");

                  $userType = $packObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");

                 $flag = 'false'; 
                 $user= (array)$userType;

                  if($docs[$i]['type'] == "performance")
                  {

                    $flag = 'true';
                    $encodedPath = base64_encode($packData->packFilePath);

                    $genTable .= '<tr>';
                    if ($docs[$i]['certifiedBy'] == 'NETAPP') {
                              $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';                    
                            } 
                        else {
                              $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
                              }
             
                     $genTable .= '<td><a id="packHeader" title="'.$docs[$i]['performanceName'].'"  href="performance-detail.shtml?packName='.implode("!",explode(" ",$docs[$i]['performanceName'])).'&packVersion='.$docs[$i]['performanceVersion'].'">'.$docs[$i]['performanceName'].'</a></td>';

                     $genTable .= '<td>' .$docs[$i]['performanceVersion']. '</td>';
             
              
                    $genTable .= '
                          </td><td>' .$docs[$i]['opmVersion'] . '</td>';  
                    $genTable .= '<td>' .date('d M, Y', strtotime($docs[$i]['opmReleaseDate'])) . '</td> ';

                   $genTable .= '<td><a href="eula.shtml?packName=' . implode("!",explode(" ",$docs[$i]['performanceName'])).'&packVersion=' . $docs[$i]['performanceVersion'].'&packType=performance"><img src="images/downlaod-icon.png" border="0" /></a></td> ';

                   if($userType == 1)
                   {

                       $perId = explode('_',$docs[$i]['id']);
                     

                   $genTable .= "<td><a class='packAction' href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure to delete this Record  ?')){window.location.href='pass.php?action=managePack&type=delete&idToDel[]=" . $perId[1] . "&page=performance'}else{}\" ><img src='images/drop.png' height='16' width='16' border='0' title='Delete' alt='Delete' /></a></td>";
                   }
                  }

                 if($flag == 'false')
                    {
                      $genTable = '<tbody><tr><td colspan="9" >Sorry no records found in Performance.</td></tr></tbody>';
                    }
                   
                   
                $this->log->LogInfo("Solr performed the searched successfully.");
                

                }return $genTable;

 
          }
          else
          {
            if($searchValue[1] == '#')
            {
              $searchValue = substr($searchValue,1);
            }
             $this->log->LogInfo("Solr returned an empty result set.");
                $this->log->LogDebug("Query parameter searched is: " . $searchValue . " inside " . $_SERVER['PHP_SELF']);

                $genTable ="<tbody><tr><td colspan='9' >Sorry no records found in Performance.</td></tr></tbody>";

                return $genTable;
          }



      }
    }

	
	
	function getOCIResponse($searchText,$certi) {

      if (isset($searchText)) {
            $searchValue = $searchText;
            }
          else {
            $searchValue = $_REQUEST["search"];
             }

          $search = trim($searchValue);
          
          $search = str_replace(' ', '+', $search);
          if (!empty($search) && $searchValue != 'Enter Search Text...') {
                 $search = str_replace(' ', '%20', $search);
       
	   
		
			if($search[0] == '#')
			{
			  $url="http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/select/?q=".substr($search,1)."&fq=certifiedBy:".$certi."&fq=type%3Aoci&wt=json&indent=true&defType=edismax&qf=tags&stopwords=true&lowercaseOperators=true";
			}
			else
			{
			
				$url="http://".SOLRSERVER.":".SOLRPORT."/solr/collection1/select/?q=*".$search."*&fq=certifiedBy:".$certi."&fq=type:oci&wt=json&indent=true&defType=edismax&qf=ociName+ociDescription+tags&stopwords=true&lowercaseOperators=true"; 
			}
			
			//echo $url;exit;

			//echo $url;exit;
          $content = file_get_contents($url);
          $obj = json_decode($content, true);

          $response = $obj['response'];
          $docs = $response['docs'];
          $len = count($docs);

		

           if ($len > 0) { 
                $genTable = '';

                for ($i = 0; $i < $len; $i++) {
					
                  $countSearch = 0;
                  $workCount = 0;
                  $commandCount = 0;
                  $genTable .= '';
                  $ociObj = new OciPack();
                  $packData = $ociObj->getPackByTypeId($docs[$i]['ociName'],$docs[$i]['ociVersion']);
				  
                
                  $userType = $ociObj->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
                  
                 $flag = 'false'; 
                 $user= (array)$userType;

                
	
                  if($docs[$i]['type'] == "oci")
                  {
                    
                    $flag = 'true';
                    $encodedPath = base64_encode(@$packData->packFilePath);

                    $genTable .= '<tr>';

				  if (strtolower($docs[$i]['certifiedBy'])=='netapp'  || strtolower($docs[$i]['certifiedBy']) == '') 
                          {
                            $genTable .= '<td ><img src="images/netapp-certified-icon.png"></td>';
                          }
						  else
						  {
							 $genTable .= '<td ><img src="images/non-netapp-certified.png"></td>';
						  }

             
                     $genTable .= '<td><a id="packHeader"  title="'.$docs[$i]['ociName'].'" href="ocipack-detail.shtml?packName='.implode("!",explode(" ",$docs[$i]['ociName'])).'&packVersion='.$docs[$i]['ociPackVersion'].'">'.$docs[$i]['ociName'].'</a></td>';

                     $genTable .= '<td>' .$docs[$i]['ociPackVersion']. '</td>';
             
              
                    $genTable .= '
                          </td><td>' .$docs[$i]['ociVersion'] . '</td>';  
                         // date('jS F, Y', strtotime($docs[$i]['ocumReleaseDate']))
                    $genTable .= '<td>' .date('d M, Y', strtotime($docs[$i]['ociReleaseDate'])) . '</td> ';
					 $genTable .= '<td>' .$docs[$i]['ociType'] . '</td> ';
					 $genTable .=  '<td><a href="eula.shtml?packName='.implode("!",explode(" ",$docs[$i]['ociName'])).'&packVersion='.$docs[$i]['ociPackVersion'].'&packType=ocipack" name="download" class="packAction"><img src="images/downlaod-icon.png" border="0" title="Download" /></a></td>';

                   $genTable .= '<td><a href="eula.shtml?packName=' . implode("!",explode(" ",$docs[$i]['ociName'])) .'&packVersion=' . $docs[$i]['ociPackVersion'].'><img src="images/downlaod-icon.png" border="0" /></a></td> ';

              
                  }

                 if($flag == 'false')
                    {
                      $genTable = '<tbody><tr><td colspan="9" >Sorry no records found in OnCommand Insight.</td></tr></tbody>';
                    }
                   
                   
                $this->log->LogInfo("Solr performed the searched successfully.");
             

                }
			   return $genTable;
 
          }
          else
          {
            if($searchValue[1] == '#')
            {
              $searchValue = substr($searchValue,1);
            }
             $this->log->LogInfo("Solr returned an empty result set.");
                $this->log->LogDebug("Query parameter searched is: " . $searchValue . " inside " . $_SERVER['PHP_SELF']);

                $genTable ="<tbody><tr><td colspan='9' >Sorry no records found in OnCommand Insight.</td></tr></tbody>";

                return $genTable;
          }



      }
    
    }


}
?>
