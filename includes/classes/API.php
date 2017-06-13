<?php 
@session_start();
class API extends MySqlDriver{

	protected $User;
	protected $method = '';
	protected $endpoint = '';
	protected $verb = '';
	protected $args = Array();
	protected $file = Null;
	
	public function __construct($request, $origin) 
	{	
		$this->obj = new MySqlDriver;     
		
		header("Access-Control-Allow-Orgin: *");
		header("Access-Control-Allow-Methods: *");
		//header("Content-Type: application/json");
		header('Content-Type: application/xml; charset=utf-8');

		$this->args = explode('/', rtrim($request, '/'));
		
		//print " -- 1 -- <br/>";

		//print_r($this->args);
		
		$this->endpoint = array_shift($this->args);
		
		//print " -- 2 -- <br/>";
		
		//print_r($this->endpoint);
		
		if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) 
		{
			$this->verb = array_shift($this->args);
		}

		$this->method = $_SERVER['REQUEST_METHOD'];
		
		if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) 
		{
			if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
				$this->method = 'DELETE';
			} else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
				$this->method = 'PUT';
			} else {
				throw new Exception("Unexpected Header");
			}
		}

		switch($this->method) 
		{
			case 'DELETE':
			case 'POST':
				$this->request = $this->_cleanInputs($_POST);
				break;
			case 'GET':
				$this->request = $this->_cleanInputs($_GET);
				break;
			case 'PUT':
				$this->request = $this->_cleanInputs($_GET);
				$this->file = file_get_contents("php://input");
				break;
			default:
				$this->_response('Invalid Method', 405);
				break;
		}	
          
		// Abstracted out for example
	//	$APIKey = new Models\APIKey();
	//	$User = new Models\User();
	//	$this->verifyKey($this->request['apiKey'], $origin);
		
		if (!array_key_exists('apiKey', $this->request)) 
		{
			throw new Exception('No API Key provided');
		} 	
		
		else if (!$this->verifyKey($this->request['apiKey'], $origin)) 
		{		
			throw new Exception('Invalid API Key');
		} 
 	
	}

/**
 * Example of an Endpoint
 */
	public function verifyKey($api,$origin){
		 try { 
			$apiData = $this->singleValue("apikeys","apiKey = '".$api."'");	  
			
            if($apiData) {
                return true;
            } else {
                // no key found, thus this key is invalid
              //  $this->app->status(400);
                $result = array("status" => "error", "message" => "You need a valid API key.");
                echo json_encode($result);
                return false;
            }
        } catch(Exception $e) {
         //   $this->app->status(500);
            $result = array("status" => "error", "message" => 'Exception: ' . $e->getMessage());
            echo json_encode($result);
            return false;
        } 
	}
 
	public function processAPI()
	{
		//echo "-- TEST ---- "; 
		if ((int)method_exists($this, $this->endpoint) > 0)
		{
			return $this->_response($this->{$this->endpoint}($this->args));
		}
	
		return $this->_response("No Endpoint: " . $this->endpoint, 404);
	}
	
	
	
	private function _response($data, $status = 200)
	{
		//$res = $this->_requestStatus($status);
	
		header('HTTP/1.1 ' . $status . '' . $this->_requestStatus($status));
	
		//return json_encode($data);
		return $data;		
	}
	
	
	private function _cleanInputs($data)
	{
		$clean_input = Array();
	
		if (is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$clean_input[$k] = $this->_cleanInputs($v);
			}
		}
		else
		{
			$clean_input = trim(strip_tags($data));
		}
	
		return $clean_input;
	}
	
	private function _requestStatus($code)
	{
		$status = array(
				200 => 'OK',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				500 => 'Internal Server Error',
		);
	
		return ($status[$code])?$status[$code]:$status[500];
	}
 
    protected function wfsmetadata() {
        //echo " -- test -- ";
	
		if (file_exists(EXPOSEDMETAXML)) {

			$xml_response = simplexml_load_file(EXPOSEDMETAXML);
		   
				if ($this->method == 'GET') {
					return $xml_response->asXML();
				} else {
					return "Only accepts GET requests";
				}  
		  }
		else{
			return "No wfsmetadata webservice exposed.";
		}
    }
	
	protected function timestamp() {

			if (file_exists(EXPOSEDTIMEXML)) {

				$xml_response = simplexml_load_file(EXPOSEDTIMEXML);       

					if ($this->method == 'GET') {
						return $xml_response->asXML();
					} else {
						return "Only accepts GET requests";
					}
			 }
			else{
				return "No timestamp webservice exposed.";
			}

		}

	protected function storeversion() {
		if (file_exists(EXPOSEDSTOREXML)) {

			$xml_response = simplexml_load_file(EXPOSEDSTOREXML);      

				if ($this->method == 'GET') {
					return $xml_response->asXML();
				} else {
					return "Only accepts GET requests";
				}
		 }
		else{
			return "No storeversion webservice exposed.";
		}

    }
	
}
?>