<?php
  
  require_once( 'Service.php' );
  
  // 
  // 
  // Try to connect to the named server, port, and url
  // 
  $solr = new Apache_Solr_Service( 'localhost', '8983', '/solr' );
  
  if ( ! $solr->ping() ) {
    echo 'Solr service not responding.';
    exit;
  }
  
  //
  //
  // Create two documents
  //
  $docs = array(
    'doc_no1' => array(
      'id' => 1,
      'title' => 'Alphabet',
      'text' => 'Franz jagt im komplett verwahrlosten Taxi quer durch Bayern',
      'category' => array( 'Orange', 'Birne' ),
    ),
    'doc_no2' => array(
      'id' => 2,
      'title' => 'Buchstaben',
      'text' => 'Polyfon zwitschernd assen Mäxchens Vögel Rüben, Joghurt und Quark.',
      'category' => array( 'Apfel', 'Birne' ),
    ),
  );
    
  $documents = array();
  
  foreach ( $docs as $item => $fields ) {
    
    $part = new Apache_Solr_Document();
    
    foreach ( $fields as $key => $value ) {
      if ( is_array( $value ) ) {
        foreach ( $value as $data ) {
          $part->setMultiValue( $key, $data );
        }
      }
      else {
        $part->$key = $value;
      }
    }
    
    $documents[] = $part;
  }
    
  //
  //
  // Load the documents into the index
  // 
  try {
    $solr->addDocuments( $documents );
    $solr->commit();
    
  }
  catch ( Exception $e ) {
    echo $e->getMessage();
  }
  
  ?>