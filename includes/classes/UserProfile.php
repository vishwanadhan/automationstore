<?php
@session_start();
ob_start();
/**
 * Pack.php
 * This class implements various functionalities related to information regarding Packs.
 * 
 */
class UserProfile extends MySqlDriver {

    //constructor started

    function __construct() {
        $obj = new MySqlDriver;
        $this->log = new KLogger(LOGFILEPATH, KLogger::DEBUG);
    }
    //constructor ended

   function updateUserInfo($post)
    {
       $query = "Update ".TBL_USER." set ";
       $userType =$this->fetchUserType(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");

     
       $parameters = '';
        if(isset($post['phone']))
        {
            $param .= "phone ='".$post['phone']."'";
        }

        if($userType == 1)
        {
            $param.=",receiveMail = 'true'";
        }
        else
        {
            if(isset($post['emailNotify']))
            {
            
                $param.=",receiveMail = 'true'";
            }
            else 
            {
                $param .=",receiveMail = 'false'";
            }
        }

        $query .=$param."  where email = '".$post['email']."'";

        $result = $this->executeQry($query);
        return $result;
        
    }
	
	
    function updateMailInfo()
    {

        $query = "Update ".TBL_USER." set receiveMail='true' where email = '".$_SESSION['mail']."'";
        $this->executeQry($query);
    }

  function GetAllSubscribedUsersEmail()
    {
        $userEmailData = array();
        $query = "Select firstName, lastName, email, id From ".TBL_USER." where receiveMail='true' ";
        
        $result = $this->executeQry($query);
            
      $num = $this->getTotalRow($result);
        if($num > 0){
          while ($line = $this->getResultObject($result)) {
                $userEmailData[] = $line;
            }
        }
         return $userEmailData; 
    }

}
?>