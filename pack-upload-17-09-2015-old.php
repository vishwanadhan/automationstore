<?php
session_start();

/**
 * pack-upload.php
 * file for uploading a pack with different extensions( zip or dar)
 * uploads the pack, extracts the pack and index the pack;
 * 
 */
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');
require_once('includes/classes/KLogger.php');

//creating a logger object
$log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
$loginObj = new Login();
$pageName = getPageName();



$packObj = new Pack();
$userType = $loginObj->fetchUserType();  
    if(empty($_SESSION['uid']))
    {
        echo"<script>window.location.href='pack-list.shtml'</script>";
        exit;
    }
    
if (isset($_POST['submit'])) {
    //echo "hello";exit;
    $filename = $_FILES["zip_file"]["name"];
    $source     = $_FILES["zip_file"]["tmp_name"];
    $type   = $_FILES["zip_file"]["type"];
    $size   = $_FILES["zip_file"]["size"]/1024;    // In KB

   $tags = $_POST['packTags'];
   $type = "workflow";



    if(empty($filename))
    {
        $message = "Please select a file to upload.";
    }
    else
    {

   // $name = explode(".", $filename);

        $pos = strrpos($filename, ".");
    $begin = substr($filename, 0, $pos);
    $end = substr($filename, $pos+1);
    
    $name[0] = $begin;
    $name[1] = $end;    

    $name[0] = isset($name[0]) ? $name[0] : null;
    $name[1] = isset($name[1]) ? $name[1] : null;

    //check to see if the file being uploaded is a zip or dar file.
    $continue = (strtolower($name[1]) == 'zip' || strtolower($name[1]) == 'dar') ? true : false;
    if (!$continue) {
        $log->LogError("Failed to upload the file - not a .zip or .dar file.");
        $message = "The file you are trying to upload is not a .zip or .dar file. Please try again.";
    } else {

    if($size >= MAXUPLOADSIZE)
        {
            $_SESSION['SESS_MSG'] = msgSuccessFail("fail","Pack Size Issue Hint: Pack size exceeds the allowed pack size (10 MB)"); 
            echo"<script>window.location.href='pack-upload.shtml'</script>";                       
            exit;   
        }

        $iMicrotime = microtime(true);
        $iTime = explode('.', $iMicrotime);
        $iMill = $iTime[1];
        $dDate = date('Y-m-d-H-i-s', $iTime[0]) . '-' . $iMill;

        $path = $docRoot . 'wfs_data/build_' . $dDate;
        $export_path = $path . '/';

        $_SESSION['exportPath'] = $export_path.$name[0];

        if (!is_dir($path)) {
            @mkdir($path, 0777);
        }
        if (is_dir($path)) {
            @mkdir($export_path, 0777);
        }

        $target_path = $path . '/' . basename($filename);

       

        // echo $export_path."||hello ".is_dir($path); exit;                    

        if (move_uploaded_file($source, $target_path)) {

            $zip = new ZipArchive();
            $x = $zip->open($target_path);
            
            
            if (strtolower($name[1]) == 'zip') {
                $zip->extractTo($export_path . $name[0]); // change this to the correct site path   

                $zip->close();
            } else {
                if (!is_dir($export_path . $name[0])) {
                    @mkdir($export_path . $name[0], 0777);
                }

                chdir($export_path . $name[0]);
        //  exec('chown -R root /');
        //  exec('chmod -R 777 ' . $export_path . $name[0], $opt);
                exec('jar -xvf "' . $target_path.'"', $output);
            }
        
            /*echo $target_path;    
            echo "<pre>";
            print_r($output);
            exit; */    
       
            if ($x === true || !empty($output)) {
                $packFilePath = 'wfs_data/build_' . $dDate . '/' . basename($filename);
               


                $packName = explode(".", $packFilePath);
               
                // xml read start

                $xml_array = array();
                $xmlFile = $export_path . $name[0] . '/pack-info.xml';

               

                if (!file_exists($xmlFile)) {            
                    rrmdir($path);
                    $_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Pack Format Issues Hint: pack-info.xml Missing");
                    $log->LogError("Failed to upload the file -  pack-info.xml is missing.");
                    echo"<script>window.location.href='pack-upload.shtml'</script>";
                    exit;
                }
               else{
            $checkXml = isXmlStructureValid($xmlFile);
            if(!$checkXml)
                {
                    $_SESSION['SESS_MSG'] = msgSuccessFail("fail","Pack Format Issues Hint: pack-info.xml is not well formed.");    
                    echo"<script>window.location.href='pack-upload.shtml'</script>";                       
                    exit;
                }
          }    
                
                $xml = simplexml_load_file($xmlFile, 'SimpleXMLElement',LIBXML_NOCDATA);
                $deJson = json_encode($xml);

               
                $xml_array = json_decode($deJson, 1);

          $countEntity = 0;


        foreach($xml_array['entities']['entity'] as $entityArray)
            {
                if(is_array($entityArray))
                    {   
                        $countEntity++;
                    }
                    else{
                        $countEntity = 1;
                    }
            }   
            
         // $packObj->hc();
            //$packObj->addPackData($xml_array,$packFilePath,$countEntity, $tags, $type);  
              $packId = $packObj->addPackData($xml_array,$packFilePath,$countEntity, $tags, $type);
				
				
			//	echo $packId;exit;
                if (!$packId) {
                    if (is_dir($path)) { 
                        rrmdir($path);
                    }

                    $_SESSION['SESS_MSG'] = msgSuccessFail("fail", "Pack Record already exists!!!");
                    $log->LogError("Failed to upload the file - Pack record already exists.");
                    echo"<script>window.location.href='pack-upload.shtml'</script>";
                    exit;
                } 
				
                if (strtolower($name[1]) == 'dar') {
                    $message = "Your .dar file was uploaded and unpacked.";
                } else {
                    $message = "Your .zip file was uploaded and unpacked.";
                }
             //   $log->logInfo("Pack has been uploaded and unpacked successfuly");
            }
        } else {
            $message = "There was a problem with the upload. Please try again.";
            $log->LogDebug("Failed to upload the file - file needs to be uploaded again.");
        }



     //   $_SESSION['SESS_MSG'] = msgSuccessFail("success", "Pack has been added successfully!!!");
        echo"<script>window.location.href='pack-list.shtml'</script>";
        exit;
    }
}

}

// site head js include here 
include('includes/head.php');
?>   

<script type="text/javascript">
    $(document).ready(function() { 

        var filename="";
    $('#zip_upload').change(function() {            
            fileName = $(this).val().split('/').pop().split('\\').pop();           
            $('#zipFile').val(fileName);
            $(this).css('width','77px');
            $(this).css('color','fff'); 
      });

          $('#tags input').on('focusout',function(){ 
            var valueTag = '';
            var txt= this.value.replace(/[^a-zA-Z0-9\+\-\.\#]/g,''); // allowed characters
            if(txt.charAt(0) == '#')
            {
                txt= txt.substring(1);
            }
            if(txt) {
              $(this).before('<span class="tag">'+ txt.toLowerCase() +'<span class="delete-tag" title="remove this tag"></span></span>');

              
            }

            $('span.tag').each(function(){
                valueTag = valueTag + $(this).text()+",";
            });
            $('#packsTag').val(valueTag);
            this.value="";
          }).on('keyup',function( e ){
    // if: comma,enter (delimit more keyCodes with | pipe)
            if(/(32)/.test(e.which)) $(this).focusout(); 

          });
  
    $('#tags').on('click','.tag',function(){
          $(this).remove(); 
          var valueTag = '';
          $('span.tag').each(function(){
                valueTag = valueTag + $(this).text()+",";
            });
            $('#packsTag').val(valueTag);
      });
      
    });

</script>   

<style>

#tags {
    border: 1px solid #ccc;
    cursor: text;
    background-color: #fff;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    height: 35px;
}

#packTag {
    
    border: none;
    outline: none;
}

#tags { box-shadow: 0 1px 2px rgba(0,0,0,0.1) inset; }
.tag:hover {
    color: #3e6d8e;
    background-color: #dae6ef;
    border: 1px solid #dae6ef;
}
#tags .post-tag {
    margin: 6px 3px 0 3px;
}
.tag:hover {
    text-decoration: none;
}


.tags
{
    
}
.tag{
    color: #566e76;
    background: #e4edf4;
    border: 1px solid #c0d4db;
    padding: .4em .5em;
   
   margin: 6px 3px 0 3px;
    text-decoration: none;
    text-align: center;
    font-size: 11px;
    line-height: 1;
    white-space: nowrap;
    display: inline-block;
}

.delete-tag
{
    background-image: url("images/sprites.png?v=c4222387135a");
    
}

.delete-tag {
    width: 14px;
    height: 14px;
    vertical-align: middle;
    display: inline-block;
    background-position: -40px -319px;
    cursor: pointer;
    margin-left: 3px;
    margin-top: 1px;
    margin-bottom: -1px;
}

.delete-tag:hover, .delete-tag-active {
    background-position: -40px -340px;
}

</style>
    <!--[if lte IE 7]> <html class="ie7"> <![endif]-->  
    <!--[if IE 8]>     <html class="ie8"> <![endif]-->  
    <!--[if !IE]><!--> <html><!--<![endif]--> 



</head>
<body>
<?php
//site header include here 
include('includes/header.php');
?>
    <div id="nav-under-bg">
        <!-- -->
    </div>



    <div id="body_content">
        <div id="outerDiv1">
            <!--form name="ecartFrm" method="post" action="pass.php?action=importExport&type=deleteall" enctype="multipart/form-data"-->
            <form enctype="multipart/form-data" method="post" action="" name="upload_form" id="upload_form"> 

                <div style="margin-top:20px;">
                    <? 
                    echo $_SESSION['SESS_MSG']; 
                    unset($_SESSION['SESS_MSG']); 
                    ?>
                </div>



                <h2 style="margin-top:70px;margin-left:20px;"> Upload Pack </h2>
                <h4 style="margin-top:20px;margin-left:20px;font-weight:normal;">You can upload a pack to the publicly accessed Workflow Store Portal. Make sure the selected pack contains workflows and all its elements that are valid and tested successfully. To learn more about packs or how to create a pack, <a href="https://library.netapp.com/ecm/ecm_download_file/ECMP1644818">click here</a>." </h4>

                <div id="uploadForm" class="uploadForm">
                    <label class="label">  
              <div id="uploadFile" style="clear:both;">
                       <div style="display:inline-block;float:left;">Select a pack: <input type="text" name="zipfile" id="zipFile" readOnly="true" /> </div>
                        
                       <div style="margin-top:0;float:left;"><input type="file" name="zip_file" id="zip_upload" class="wel" /></div>
                  </div>
            </label>    

                       
                        
                    <br />
            <div id="subText">Valid 'zip' or 'dar' file.</div>

            <br>
                Enter tags:
                <div style="width: 657.777777671814px; height: 34.777777671814px;" id="tags"><span></span></span></span>
                <input type="text" name="tags" style="width: 649.777777671814px;height: 25px;" id="packTag" placeholder="" tabindex="103" autocomplete="off">
                <span></span></div> 
                    
                <br>
                <input type="hidden" id="packsTag" name="packTags" value="">

                    <div style="position: relative;left: 88px;">        

                        <span class="message">
<?php
if (!empty($message)) {
    echo $message;
    unset($message);
}
?>
                        </span>

                    </div>

                    <input type="submit" name="submit" value="Upload" id="uploadButton" style="cursor:pointer;margin-top:40px;z-index:99999;"/>


                </div>
            </form>


        </div>
    </div>
                            <?php
                            // site head js include here 
                            include('includes/footer.php');
                            ?> 

</body>
</html>
