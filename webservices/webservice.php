<?php

require_once 'API.php';
@session_start();
include_once('../config/configure.php');
require_once('../includes/function/autoload.php');

/**
 * webservice.php
 * This class exposes the main xml containing the list of all packs uploaded as as web service.
 */
class webservice extends API {

    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);
    }

    /**
     * Example of an Endpoint
     */
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

if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new webservice($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}