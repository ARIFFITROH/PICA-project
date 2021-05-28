<?php
  include 'spreadsheetconfig.php';
  
  $rowCount = 0;
  $totalHandle = fopen($totalSpreadsheetUrl, "r");
  
  while (($row = fgetcsv($totalHandle, 0, ",")) !== FALSE)
  {
    $rowCount++;
    if ($rowCount == 1) { continue; } // skip the first/header row of the CSV

    $i = 0;
    $totalexcellent = $row[$i++];
    $totalgood = $row[$i++];
    $totalintermediet = $row[$i++];
    $totalweak = $row[$i++];
    $totallowless = $row[$i++];
    $totalempty = $row[$i++];
    $tanggalupdatedata = $row[$i++];
    $totalareal = $row[$i++];
  } // end while, loop through CSV data
  fclose($totalHandle);
?>