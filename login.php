<?php
require_once('config.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	// print_r($_POST);

	$inputEmail = trim(addslashes($_POST['inputEmail']));
	$inputPassword = sha1(trim(addslashes($_POST['inputPassword'])));

	$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
		or die ('Could not connect to the database server' . mysqli_connect_error());

	$sql="SELECT * FROM `employers` WHERE `employers`.`email`='$inputEmail' AND `employers`.`password`='$inputPassword'";

	// echo "<br><br>";
	// echo $sql;
	// echo "<br><br>";

	$result = $conn->query($sql);
	// print_r($db_res);
	// echo "<br><br>";

	if ($result->num_rows > 0) {
		session_start();
		$db_res = $result->fetch_assoc();
		$_SESSION['user'] = $db_res;
		$_SESSION['sidebar'] = 'charts';

		// print_r($_SESSION);
		// echo "<br><br>";
		// print_r($_SESSION['user']['fid']);
		// echo "<br><br>";

		$sql_settings = "SELECT * FROM `settings`";
		$result_settings = $conn->query($sql_settings);
		if ($result_settings->num_rows > 0) {
			$db_res_settings = $result_settings->fetch_assoc();
			$_SESSION['settings'] = $db_res_settings;
		}

		header( "Location: logs.php" );
	} else {
		echo "No user...";
    // header( "Location: index.php" );
	}
$conn->close();
} else {
	header( "Location: index.php" );
}
?>
