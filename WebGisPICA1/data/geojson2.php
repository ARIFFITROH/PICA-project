<?php
	include 'spreadsheetconfig.php';

  $rowCount = 0;
  $features = array();
  $error = FALSE;

  $output = array();
  /* asupkeun */
  $filter = FALSE;

  if(isset($_GET['filter'])){
    $filter = true;
  }
  /* end asupkeun */

  // attempt to set the socket timeout, if it fails then echo an error
  if ( ! ini_set('default_socket_timeout', 15))
  {
    $output = array('error' => 'Unable to Change PHP Socket Timeout');
    $error = TRUE;
  } // end if, set socket timeout

  // if the opening the CSV file handler does not fail
  if ( !$error && (($dataHandle = fopen($dataSpreadsheetUrl, "r")) !== FALSE) )
  {
    // while CSV has data, read up to 10000 rows
    while (($csvRow = fgetcsv($dataHandle, 10000, ",")) !== FALSE)
    {
      $rowCount++;
      if ($rowCount == 1) { continue; } // skip the first/header row of the CSV
      /* asupkeun */
      $af = str_replace(' ','_', $csvRow[10]);
      
      if( $filter==true ){
        if( isset($_GET[$af]) ){
          $output[] = array(
            'features' => array(
              'KODE' => $csvRow[0],
              'BLOK' => $csvRow[1],
              'LUAS' => $csvRow[2],
              'PROTAS' => $csvRow[3],
              'EXCELLENT' => $csvRow[4],
              'GOOD' => $csvRow[5],
              'INTERMEDIATE' => $csvRow[6],
              'WEAK' => $csvRow[7],
              'LOWLESS' => $csvRow[8],
              'EMPTY' => $csvRow[9],
              'AFDELING' => $csvRow[10],
              'NIMUT' => $csvRow[11],
              'TT' => $csvRow[12],
              'KEBUN' => $csvRow[13],

            )
          );
        }

      }else{
        $output[] = array(
          'features' => array(
            'KODE' => $csvRow[0],
            'BLOK' => $csvRow[1],
            'LUAS' => $csvRow[2],
            'PROTAS' => $csvRow[3],
            'EXCELLENT' => $csvRow[4],
            'GOOD' => $csvRow[5],
            'INTERMEDIATE' => $csvRow[6],
            'WEAK' => $csvRow[7],
            'LOWLESS' => $csvRow[8],
            'EMPTY' => $csvRow[9],
            'AFDELING' => $csvRow[10],
            'NIMUT' => $csvRow[11],
            'TT' => $csvRow[12],
            'KEBUN' => $csvRow[13],

          )
        );
      }

      /* end asupkeun */
    
    } // end while, loop through CSV data
    fclose($dataHandle); // close the CSV file handler
    // var_dump($output);exit;
  }  // end if , read file handler opened

  // else, file didn't open for reading
  else
  {
    $output = array('error' => 'Problem Reading Google CSV');
  }  // end else, file open fail

  //Read geojson file
  $geojsonAdmin = file_get_contents("./DEKAN1/D12.geojson");
  $polygonAdmin = json_decode($geojsonAdmin, TRUE);

  
	foreach ($polygonAdmin['features'] as $key => $first_value) {
      foreach ($output as $second_value) {
        // var_dump($second_value);
        if( $first_value['properties']['BLOK'] == $second_value['features']['BLOK'] ){
          $polygonAdmin['features'][$key]['properties']['name'] = 'BLOK-'.$second_value['features']['BLOK'];
        	$polygonAdmin['features'][$key]['properties']['LUAS'] = $second_value['features']['LUAS'];
          $polygonAdmin['features'][$key]['properties']['PROTAS'] = $second_value['features']['PROTAS'];
          $polygonAdmin['features'][$key]['properties']['EXCELLENT'] = $second_value['features']['LUAS'];
          $polygonAdmin['features'][$key]['properties']['GOOD'] = $second_value['features']['GOOD'];
          $polygonAdmin['features'][$key]['properties']['INTERMEDIATE'] = $second_value['features']['LUAS'];
          $polygonAdmin['features'][$key]['properties']['WEAK'] = $second_value['features']['LUAS'];
          $polygonAdmin['features'][$key]['properties']['LOWLESS'] = $second_value['features']['LUAS'];
          $polygonAdmin['features'][$key]['properties']['EMPTY'] = $second_value['features']['LUAS'];
          $polygonAdmin['features'][$key]['properties']['AFDELING'] = $second_value['features']['AFDELING'];
          $polygonAdmin['features'][$key]['properties']['AFD'] = str_replace(" ","",$second_value['features']['AFDELING']);
          $polygonAdmin['features'][$key]['properties']['NIMUT'] =  $second_value['features']['NIMUT'];
          $polygonAdmin['features'][$key]['properties']['NIM'] =  $second_value['features']['NIMUT'];
          $polygonAdmin['features'][$key]['properties']['TT'] = $second_value['features']['TT'];
          $polygonAdmin['features'][$key]['properties']['KEBUN'] = $second_value['features']['KEBUN'];

        } else {}
      } 
  }

	$combined_output = json_encode($polygonAdmin, JSON_NUMERIC_CHECK); 

	header("Access-Control-Allow-Origin: *");
  // header('Cache-Control: no-cache, must-revalidate');
	header('Content-Type: application/json');
	echo $combined_output;
?>