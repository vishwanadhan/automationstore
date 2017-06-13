<?php

//session_start();
//***********************************************
//** Entity Class Extend MySqlDriver Class
//***********************************************
class Login extends MySqlDriver {

    function __construct() {
        $this->obj = new MySqlDriver;
    }

    /**
     * Function to validate user entered password
     * @param type $plain
     * @param type $encrypted
     * @return boolean
     */
    function validate_password($plain, $encrypted) {
        if (!is_null($plain) && !is_null($encrypted)) {

            if (md5($plain) == $encrypted) {
                return true;
            }
        }
        return false;
    }

    function checkSession() {
        if (!$_SESSION['uid']) {
        //    $_SESSION['SESS_MSG'] = 'OOPS! your session has been expired!'; 
            redirect('pack-list.shtml');
            exit;
        }
    }

    /**
     *
     * Function to check if a valid user has logged in or not.
     * check the session for user
     * @param    none
     * @return   none
     *
     */
   function checkHeader() {
        $uid = null;
        $userName = null;
        $headerArray = array();
        $headerArray = getallheaders();
    
    $type = array();

    if(!empty($headerArray['userDN']))
    {
        $userDn = explode(',',$headerArray['userDN']);     
            $type = explode('=',$userDn[1]);  
        }

    $isEnabled = $headerArray['nowenabled'];
    
        
        foreach ($headerArray as $name => $value) {
        if(!empty($type) && strtolower($value) != 'oblixanonymous')
        {
            $cond = strtolower($type[1]) == strtolower('External') && $isEnabled == 2;
        }
          else{
            $cond = strtolower($value) == 'oblixanonymous' && strtolower($name) == 'uid';
        }

    
            if ($cond) {
     
                unset($_SESSION[$name]);
                session_destroy();
        setcookie("PHPSESSID","", 1, "/","netapp.com");
        setcookie("ObSSOCookie","", 1, "/","netapp.com");

        
        session_unset();
        session_destroy();
        $_SESSION = array();        
          
            //    $_SESSION['SESS_MSG'] = 'OOPS! your session has been expired!';
          
            } else {    
                //$_SESSION['mail'] = $_SESSION['userEmail'];

                if($name == 'userEmail')
                    $_SESSION['mail'] = $value;
                else
                   $_SESSION[$name] = strtolower($value);
                $_SESSION['PHPSESSIONID'] = session_id();
            }

        }

        
       
    }

    /**
     *
     * Logout
     * Destroy session for user
     * 
     * @param    none
     * @return   none
     *
     */
    function Logout() {
      //  $this->executeQry("update " . TBL_SESSIONDETAIL . " set signOutDateTime = '" . date('Y-m-d H:i:s') . "' where sessionId = '" . $_SESSION['PHPSESSIONID'] . "' and adminId = '" . $_SESSION['ADMIN_ID'] . "'");
        setcookie("PHPSESSID","", 1, "/","netapp.com");
	 setcookie("ObSSOCookie","", 1, "/","netapp.com");
        unset($_SESSION['ADMIN_ID']);
        unset($_SESSION['ADMINNNAME']);
        unset($_SESSION['USERLEVELID']);
        unset($_SESSION['DEFAULTLANGUAGE']);
        unset($_SESSION['PHPSESSIONID']);
        unset($_SESSION['SESSIONID']);
        session_destroy();
    }

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
     *
     * fetchUserType
     * Fetch the type of user
     * 
     * @param    none
     * @return   string userType
     *
     */
    function fetchUserType() {
        $rst = $this->fetchValue(TBL_ADMINUSER, "userType", " userName = '" . $_SESSION['uid'] . "'");
        return $rst;
    }


    function fetchUserName()
	{
		$rst = $this->fetchValue(TBL_ADMINUSER,"userName"," userName = '" . $_SESSION['uid'] . "'");
		
	     return $rst;	
	}	

		
		
	/**
	*
	* lastLogin
	*updates the last login details of the user
	*
	* @author Arun Verma
	**/
	function lastLogin($uid)
		{
			$now = date('Y-m-d H:i:s');
			$this->tablename = TBL_USER;     
			$this->field_values['lastLogin'] = $now ;
			$this->condition  = "userName = '".$uid."' ";
			$this->updateQry();
		}	

}

// End Class
?>	
 