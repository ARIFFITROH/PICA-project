<?php

  $rowCount = 0;
  $features = array();
  $error = FALSE;
  $output = array();

  // attempt to set the socket timeout, if it fails then echo an error
  if ( ! ini_set('default_socket_timeout', 15))
  {
    $output = array('error' => 'Unable to Change PHP Socket Timeout');
    $error = TRUE;
  } // end if, set socket timeout

  //Read geojson file
  $geojsonAdmin = file_get_contents("./DEKAN1/perafdeling.geojson");
  $polygonAdmin = json_decode($geojsonAdmin, TRUE);

	$combined_output = json_encode($polygonAdmin, JSON_NUMERIC_CHECK); 

	header("Access-Control-Allow-Origin: *");
  // header('Cache-Control: no-cache, must-revalidate');
	header('Content-Type: application/json');
	echo $combined_output;
?>