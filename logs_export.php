<?php
session_start();
if( !isset($_SESSION["user"]) ) header( "Location: index.php" );

require_once('config.php');

$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
  or die ('Could not connect to the database server' . mysqli_connect_error());

$query_result = $conn->query($_SESSION['logs']['export_query']);

// Export to CSV
if($query_result->num_rows > 0){
  $delimiter = ",";
  $filename = "export-data_" . date('Y-m-d') . ".csv";

  // Create a file pointer
  $f = fopen('php://memory', 'w');

  // Set column headers
  $fields = array('Cl', 'DPD3', 'pH',	'Temp', 'Máquina', 'Correção', 'Tipo', 'Data/Hora', 'Responsável');
  fputcsv($f, $fields, $delimiter);

  foreach ($query_result as $row) {
    error_log(print_r($row,true));
    // data to CSV
    $lineData = array($row['cl'], $row['dpd3'], $row['ph'], $row['temp'], $row['maq'], $row['correction'], $row['name'], $row['record_time'], $row['fullname']);
    fputcsv($f, $lineData, $delimiter);

  } // end while

	// Move back to beginning of file
	fseek($f, 0);

	// Set headers to download file rather than displayed
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');

	//output all remaining data on a file pointer
	fpassthru($f);
}

$query_result->close();
$conn->close();
?>
