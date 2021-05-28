<?php
	include 'spreadsheetconfig.php';

	$rowCount = 0;
	$dashboardHandle = fopen($dashboardSpreadsheetUrl, "r");

	//Loop through the CSV rows.
	while (($row = fgetcsv($dashboardHandle, 0, ",")) !== FALSE) {
		$rowCount++;
		if ($rowCount == 1) { continue; } // skip the first/header row of the CSV
	    //Print out my column data.
	  
	  $i = 0;
	  $judul_tab = $row[$i++];
	  $navbar_icon = $row[$i++];
		$judul_navbar = $row[$i++];
		$attribution = $row[$i++];
		$instansi = $row[$i++];
		$email = $row[$i++];
		$telepon = $row[$i++];
		$alamat = $row[$i++];
		$spreadsheet_url = $row[$i++];
		$judul_total_Excelent = $row[$i++];
		$judul_total_Good = $row[$i++];
		$judul_total_Intermediet = $row[$i++];
		$judul_total_Weak = $row[$i++];
		$judul_total_Lowless = $row[$i++];
		$judul_total_Empty = $row[$i++];
		$judul_tabel = $row[$i++];
		$judul_total_Areal = $row[$i++];
	}
	fclose($dashboardHandle); // close the CSV file handler
?>