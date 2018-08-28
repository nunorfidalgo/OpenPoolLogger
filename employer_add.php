<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
require_once('config.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	var_dump($_POST);
	echo "<br><br>";

	$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
		or die ('Could not connect to the database server' . mysqli_connect_error());



	$inputFullname = trim(addslashes($_POST['inputFullname']));
	$inputUsername = trim(addslashes($_POST['inputUsername']));
	$inputPassword = trim(addslashes($_POST['inputPassword']));
	// $inputEmail = mysqli_real_escape_string($conn, $_POST['inputEmail']);
	// $inputEmail = $conn->real_escape_string($_POST['inputEmail']);
	$inputEmail = trim(addslashes($_POST['inputEmail']));
	$inputEmail = htmlentities($_POST['inputEmail']);
	$inputAdmin = trim(addslashes($_POST['inputAdmin']));

	if( $inputAdmin == "on" )
		$inputAdmin = "1";
	else
		$inputAdmin = "0";

	echo "<br><br>";
	echo "inputEmail: ".$inputEmail;
	echo "<br><br>";

	$sql="INSERT INTO `employers` (`fid`, `fullname`, `username`, `password`, `email`, `admin`, `timedate`)
	VALUES (NULL, '".$inputFullname."', '".$inputUsername."', SHA1('".$inputPassword."'), '".$inputEmail."', '".$inputAdmin."', NOW() )";

	echo $sql;
	echo "<br><br>";

// die();
  if( $conn->query($sql) == TRUE ) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
    // header( "Location: index.php" );
	}
	$conn->close();
}
?>
