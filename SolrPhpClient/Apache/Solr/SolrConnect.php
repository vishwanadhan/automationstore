<?php

	require_once( 'Service.php' );
  
  // http://localhost:8983/solr/collection1/select?q=TG-Name&wt=json&indent=true
  // TG-Name- search parameter
  // Try to connect to the named server, port, and url
  // 
  $solr = new Apache_Solr_Service( 'localhost', '8983', '/solr' );
  
	$client = new Solarium\Client($config);
	$ping = $client->createPing();
	$result = $client->ping($ping);
	$result->getStatus();
	print_r($result);
	exit;
  
  if ( ! $solr->ping() ) {
    echo 'Solr service not responding.';
   
  }
  else
  {
	echo "Connected to Solr Server";
	
	//
  //
  // Create two documents
  //
	$xml = simplexml_load_file('wfapack.xml');
		 $deJson = json_encode($xml);
		 
		 //print_r($deJson); exit;
		$xml_array = json_decode($deJson,1);
		
		$document = new Apache_Solr_Document();
		$document->id = $xml_array['uuid'];
		$document->packName = $xml_array['pack-name'];
		$document->packDescription = $xml_array['pack-description'];
		$document->packAuthor = $xml_array['author'];
		$document->certifiedBy = $xml_array['certified-by'];
		$document->minVersion = $xml_array['min-wfa-version'];
		$document->packKeywords = $xml_array['keywords'];
		
  $part = new Apache_Solr_Document();
 
	$docs = array(
      'id' => 9,
	  'uuid' => $xml_array['uuid'],
      'packName' => $xml_array['pack-name'],
	  'packDescription' => $xml_array['pack-description'],
	  'packAuthor' => $xml_array['author'],
	  'certifiedBy' => $xml_array['certified-by'],
	  'minVersion' => $xml_array['min-wfa-version'],
	  'packKeywords' => $xml_array['keywords'],	  
    );
	
	foreach ( $docs as $key => $value ) {
      if ( is_array( $value ) ) {
        foreach ( $value as $data ) {
          $part->setMultiValue( $key, $data );
        }
      }
      else {
       // echo "key is: ".$key;
		 $part->$key = $value;
      }
    }
	
  // Load the documents into the index
  // 
try {
		$solr->addDocument($part);
	
		$solr->commit();
		
  }
  catch ( Exception $e ) {
    echo $e->getMessage();
  }
  
  }


?>