<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="author" content="unsorry">

	<?php
    	include 'data/dashboard.php';
    	include 'data/total.php';
    ?>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.css">

	<!-- Datatable CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">

	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css">

	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

	<!-- Theme style -->
	<link rel="stylesheet" href="assets/css/AdminLTE.min.css">

	<link rel="stylesheet" href="assets/css/app.css">

	<link href="assets/pic/ptp.png" rel="shortcut icon" type="image/png">
	<title><?php echo $judul_tab; ?></title>
</head>

<body>
	<!-- Modal Info -->
	<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header alert-dark">
					<h5 class="modal-title" id="exampleModalCenterTitle"><b><?php echo $instansi;?></b></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php
            	echo '<table class="table table-borderless">'.
            	'<tr><td><i class="fas fa-home"></i></td><td>'. $alamat .'</td></tr>'.
            	'<tr><td><i class="fas fa-envelope"></i></td><td>'. $email .'</td></tr>'.
            	'<tr><td><i class="fas fa-phone-square-alt"></i></td><td>'. $telepon .'</td></tr>'.
            	'<tr><td><i class="fas fa-file-csv"></i></td><td><a href="'. $spreadsheet_url .'" target="_blank">Data</a></td></tr>'.
            	'</table>';
            ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End of Modal Info -->

	<!-- Modal Feature -->
	<div class="modal fade" id="featureModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header alert-info">
					<h5 class="modal-title text-primary" id="feature-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="feature-info"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End of Modal Feature -->

	<div class="container">
		<!-- Topbar -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
			<a class="navbar-brand" href="#">
				<b><?php echo $navbar_icon; ?> <?php echo $judul_navbar; ?></b>
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
				aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="#" data-toggle="modal" data-target="#infoModal"><i
								class="fas fa-info-circle"></i> Info</a>
					</li>
				</ul>
			</div>
		</nav>
		<!-- End of Topbar -->

		<!-- asupkeun -->
		<!-- Filter Afdeling -->
		<div class="card card-info shadow m-2">
			<div class="card-header">
				<strong class="card-title">Filter</strong>
			</div>
			<div class="card-body">
				<div class="tab-content p-0">
					<form class="form" method="get" action="">
						<div class="form-group">
							<!--select  name="afdeling" class="form-control">
								<option value="all">All</option>
							</select-->
							<?php
							$dataHandle = fopen($dataSpreadsheetUrl, "r");
						    // while CSV has data
						    $arr = array();
						    while (($row = fgetcsv($dataHandle, 0, ",")) !== FALSE)
						    {
						    	$arr[$row[10]]=$row[10];
							}
							fclose($dataHandle);
							if( count($arr) > 0  ){
								foreach($arr as $k => $val ){
									$check ='checked';
									if($k != 'AFDELING'){ 
										if(isset($_GET['filter'])){
											$check = (isset($_GET[str_replace(' ', '_', $val)]))?'checked':'';
										}

										echo '
											<div  class="form-check icheck-primary d-inline ml-2">
		                      					<input type="checkbox" value="'.str_replace(' ', '_', $val).'" name="'.str_replace(' ', '_', $val).'" class="ch-afde" id="'.str_replace(' ', '', $val).'" '.$check.'>
		                      					<label for="'.str_replace(' ', '', $val).'">'.$val.'</label>
		                    				</div>';
	                    			}
								}
							}
							?>
							<input type="hidden" name="filter" value="true" />						    	
						</div>
					</form>
				</div>
			</div>
		</div>			
		<!-- End of Filter -->
		<!-- End asupkeun -->

		<!-- Map Content -->
		<div id="map" class="card border-primary shadow m-2" style="margin-top:24px;" ></div>
		<!-- Total Data Content -->
		<div class="card border-primary shadow text-white bg-White m-2 p-4">
			<div class="row">
				<div class="col-sm m-2">
					<div class="small-box bg-orange-active">
						<div class="inner">
							<h3><?php echo $totalexcellent; ?> Ha</h3>
							<p><?php echo $judul_total_Excelent; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-heart"></i>
						</div>
					</div>
				</div>

				<div class="col-sm m-2">
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?php echo $totalgood; ?> Ha</h3>
							<p><?php echo $judul_total_Good; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-thumbsup"></i>
						</div>
					</div>
				</div>

				<div class="col-sm  m-2">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?php echo $totalintermediet;?>  Ha</h3>
							<p><?php echo $judul_total_Intermediet; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-android-sad"></i>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm  m-2">
					<div class="small-box bg-danger">
						<div class="inner">
							<h3><?php echo $totalweak;?> Ha</h3>
							<p><?php echo $judul_total_Weak; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-bonfire"></i>
						</div>
					</div>
				</div>
				<div class="col-sm  m-2">
					<div class="small-box bg-dark">
						<div class="inner">
							<h3><?php echo $totallowless; ?> Ha</h3>
							<p><?php echo $judul_total_Lowless; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-thumbsdown" style="color:white"></i>
							
						</div>
					</div>
				</div>
				<div class="col-sm  m-2">
					<div class="small-box bg-secondary text-white">
						<div class="inner">
							<h3><?php echo number_format($totalempty); ?> Ha</h3>
							<p><?php echo $judul_total_Empty; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-android-remove-circle" style="color:White"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Table Content -->
		<div class="card border-primary shadow m-2 p-3">
			<div class="alert alert-dark text-center">
				<h4><strong><?php echo $judul_tabel;?></strong></h4>
			</div>
			<div class="table-responsive">
				<table id="dataTable" class="table table-striped table-bordered">
					<thead>
						<tr class="alert-primary text-dark text-center">
							<th>No</th>
							<th class="">Afdeling</th>
							<th>Blok</th>
							<th class="">Luas_Ha</th>
							
							<th class="bg-orange-active">Excellent</th>
							<th class="alert-success">Good</th>
							<th class="alert-warning">Intermediet</th>
							<th class="alert-danger">Weak</th>
							<th class="bg-dark text-gray">Lowless</th>
							<th class="bg-dark text-gray">Empty</th>
							
							
						</tr>
					</thead>
					<tbody>
						<?php
			              	$rowCount = 0;
	    		            $dataHandle = fopen($dataSpreadsheetUrl, "r");
							$arr_data =array();
						    // while CSV has data
						    while (($row = fgetcsv($dataHandle, 0, ",")) !== FALSE)
						    {
							    $rowCount++;
							    if ($rowCount == 1) { continue; } // skip the first/header row of the CSV
							    $afde = str_replace(" ", "_", $row[10]);
							    $i = 0;
								      
							    if( isset($_GET['filter']) ){
									if(isset($_GET[$afde]) == $afde ){ 
									   //    $no = $row[$i++];
									   //    $Blok = $row[$i++] ;
									   //    $Luas_Ha = $row[$i++] ; 
									   //    $Protas = $row[$i++];
									   //    $Excelent = $row[$i++];
									   //    $Good = $row[$i++];
									   //    $Intermediet = $row[$i++];
									   //    $Weak = $row[$i++];
										  // $Lowless = $row[$i++];
										  // $Empty = $row[$i++];
										  // $Afdeling = $row[$i++];
										  // $NIMUT = $row[$i++];
										  // $TT = $row[$i++];
										  // $KEBUN = $row[$i++];
										$arr_data[$row[1]]=array(
											'luas'=>$row[2],
											'protas'=>$row[3],
											'excelent'=>$row[4],
											'good'=>$row[5],
											'intermediet'=>$row[6],
											'weak'=>$row[7],
											'lowless'=>$row[8],
											'empty'=>$row[9],
											'afdeling'=>$row[10],
											'nimut'=>$row[11],
											'tt'=>$row[12],
											'kebun'=>$row[13],
										);
									}
							      
							      }else{
								      $arr_data[$row[1]]=array(
											'luas'=>$row[2],
											'protas'=>$row[3],
											'excelent'=>$row[4],
											'good'=>$row[5],
											'intermediet'=>$row[6],
											'weak'=>$row[7],
											'lowless'=>$row[8],
											'empty'=>$row[9],
											'afdeling'=>$row[10],
											'nimut'=>$row[11],
											'tt'=>$row[12],
											'kebun'=>$row[13],
										);
								   //    $no = $row[$i++];
								   //    $Blok = $row[$i++] ;
								   //    $Luas_Ha = $row[$i++] ; 
								   //    $Protas = $row[$i++];
								   //    $Excelent = $row[$i++];
								   //    $Good = $row[$i++];
								   //    $Intermediet = $row[$i++];
								   //    $Weak = $row[$i++];
									  // $Lowless = $row[$i++];
									  // $Empty = $row[$i++];
									  // $Afdeling = $row[$i++];
									  // $NIMUT = $row[$i++];
									  // $TT = $row[$i++];
									  // $KEBUN = $row[$i++];							      	
							      }
							}      

							if(count($arr_data) > 0){
								$i = 1;
								foreach($arr_data as $key => $val ){
									echo '<tr>'.
					                  '<td class="text-center">'. $i .'</td>'.
									  '<td class="text-center">'. $key.'</td>'.
					                  '<td class="text-center">'. $val['luas'] .'</td>'.
					                  '<td class="text-center">'. $val['protas'] .'</td>'.
					                  
					                  '<td class="text-center">'. $val['excelent'] .'</td>'.
					                  '<td class="text-center">'. $val['good'] .'</td>'.
					                  '<td class="text-center">'. $val['intermediet'] .'</td>'.
					                  '<td class="text-center">'. $val['weak'] .'</td>'.
									  '<td class="text-center">'. $val['lowless'] .'</td>'.
									  '<td class="text-center">'. $val['empty'] .'</td>'.
								  
				                  	'</tr>';	
				                  	$i++;
								}
							}
							      


					//   $nilai=$odp;
					//   $color;
					//   if ($odp > 3 ){
					//   $color = "style='background-color: red;'";
					// }else{
					// 	$color = "style='background-color: blue;'";
					
							      
							    // } // end while, loop through CSV data

							    fclose($dataHandle); // close the CSV file handler
	              ?>
					</tbody>
				</table>
			</div>
		</div>
		<footer class="bg-dark text-white mx-2">
			<div class="row mx-auto">
				<div class="col text-left px-2">
					<small>Update data: <?php echo $tanggalupdatedata;?></small>
				</div>
				<div class="col text-right px-2">
					<small><?php echo $attribution;?></small>
				</div>
			</div>
		</footer>
	</div>

	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
	<script src="https://unpkg.com/rbush@2.0.2/rbush.min.js"></script>
	<script src="https://unpkg.com/labelgun@6.1.0/lib/labelgun.min.js"></script>
	<script src="assets/plugins/leaflet-label/labels.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
	<script src="assets/js/app.js"></script>
	
</body>

</html>