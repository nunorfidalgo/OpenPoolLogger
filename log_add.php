<?php
// session_start();
// error_reporting(E_ALL & ~E_NOTICE);
// require_once('config.php');

// // SELECT date_format(`timedate`,'%d-%b-%Y') FROM `employers`
// // SELECT date_format(`timedate`, '%T') FROM `employers`

// if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
// 	// print_r($_POST);
// 	// echo "<br><br>";
// 	// var_dump($_POST);
// 	// echo "<br><br>";
// 	// print_r($_SESSION);
//   // echo "<br><br>user id: ".$_SESSION['user']['fid'];
// 	// echo "<br><br>";

// 	$inputCl = trim(addslashes($_POST['inputCl']));
// 	$inputDpd3 = trim(addslashes($_POST['inputDpd3']));
// 	$inputPh = trim(addslashes($_POST['inputPh']));
// 	$inputTemp = trim(addslashes($_POST['inputTemp']));
// 	$inputMaq = trim(addslashes($_POST['inputMaq']));
// 	$inputLogType = trim(addslashes($_POST['inputLogType']));

// 	$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
// 		or die ('Could not connect to the database server' . mysqli_connect_error());

// 	$sql="INSERT INTO `logs` (`pid`, `cl`, `dpd3`, `ph`, `temp`, `maq`, `timedate`, `log_owner`, `log_type`)
// 	VALUES (NULL, '".$inputCl."', '".$inputDpd3."', '".$inputPh."', '".$inputTemp."', '".$inputMaq."', NOW(), '".$_SESSION['user']['fid']."', '".$inputLogType."')";

// 	// echo $sql;
// 	// echo "<br><br>";

//   if( $conn->query($sql) == TRUE ) {
// 		// echo "New record created successfully";
// 		  header( "Location: logs.php" );
// 	} else {
// 		echo "Error: " . $sql . "<br>" . $conn->error;
// 		sleep(5);
//     header( "Location: logs_form.php" );
// 	}
// 	$conn->close();
// }
?>
