<?php
@session_start();
ob_start();
/**
 * SendMailClass.php
 * This class implements various functionalities related to Mails.
 * 
 */

class SendMailClass extends MySqlDriver {

    //constructor started

    function __construct() {
        $obj = new MySqlDriver;
        $this->log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
    }
    //constructor ended

//comman mail function 
    function sendEMail($message, $subject, $to){
	
    $mail = new PHPMailer();    
    $mail->SMTPAuth   = true;
    $mail->Host       = "smtp.netapp.com";
    $mail->Port       = 25; 

    $mail->SetFrom('no-reply@netapp.com', 'No Reply');
    // $mail->AddAddress('ng-acn-utility-services-request@netapp.com', 'NetApp Group platform');
    $mail->AddAddress($to, str_replace('.',' ',strtok($to,'@'))); 
	
    //$mail->AddAddress('asmita.sharma@infogain.com', 'asmita'); 
    
    $mail->Subject = $subject ; 

    $mail->MsgHTML($message); 

        if(!$mail->Send()){
		    /* echo "Message could not be sent. <p>";
            echo "Mailer Error: " . $mail->ErrorInfo; */
            return 0;      
        }else{
            return 1;
        }
    }
   /*
	 * function to report for new workflow pack to Each user who has checked notify me
	 * @params type	text $pask as urls
	 * @return type bool
	 * Dev ASG
	 */	 
	
	
  function mailUpoadedPackNotificationTOallUser($Packs_Url){

      $userObj =  new UserProfile();
      $userData=  $userObj->GetAllSubscribedUsersEmail();
      $now = date('Y-m-d H:i:s');
      $subject = "Notification: New OCI packs uploaded ";
      $baseurl = SITEPATH;
	  	  if($Packs_Url != ''){
	  
      foreach ($userData as $userInfo) {

            $message = '<html><body>'; 
            $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">'; 
            $message .= "<tr><td><strong>Hello ".ucfirst($userInfo->firstName)."  ".ucfirst($userInfo->lastName).",</strong> </td></tr>";
            $message .= "<tr style='background: #eee;'><td>" . $subject. "</td></tr>";
            //$message .= "<tr style='background: #eee;'><td>Few new packs have been uploaded below are the URL's: </td></tr>"; 
            $message .= "<tr style='background: #eee;'><td> ".nl2br($Packs_Url)." </td></tr>"; 
            $message .= "</table>";
            $message .= '<br>Thank you <br></br>*** This is an automatically generated email, please do not reply ***';
            $message .= "</body></html>";   

            $mailresponse = $this->sendEMail($message, $subject, $userInfo->email);

          
            if($mailresponse){
		
                continue;
            }
            
          } 
 $_SESSION['SESS_MSG'] = msgSuccessFail("success", "Packs Notification has been send successfully."); 
      return true; 
		}else{
			$_SESSION['SESS_MSG'] = msgSuccessFail("Fail", "Please enter a valid message."); 
			return false; 
	  
  }
  }
}

  
?>