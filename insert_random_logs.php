<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once('config.php');

function frand($min, $max, $decimals = 0) {
  $scale = pow(10, $decimals);
  return mt_rand($min * $scale, $max * $scale) / $scale;
}
// echo "frand(0, 10, 2) = " . frand(0, 10, 2) . "\n";

$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

$stmt = $conn->prepare("INSERT INTO `logs` (`cl`, `dpd3`, `ph`, `temp`, `maq`, `timedate`, `log_owner`, `log_type`) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?) ");
$stmt->bind_param("ddddddd", $cl, $dpd3, $ph, $temp, $maq, $owner, $type);

$max_logs = 50;
for ($i = 1; $i <= $max_logs; $i++) {
  $cl = frand(0, 2, 2);
  $dpd3 = frand(0, 2, 2);
  $ph = frand(0, 14, 2);
  $temp = frand(0, 50, 2);
  $maq = rand(1,999);
  $owner = rand(1,2); // alterar para 6 quando colocar os novos users
  $type = rand(1,2);
  printf("%.2f, %.2f, %.2f, %.2f, %d, %d, %d;<br>", $cl, $dpd3, $ph, $temp, $maq, $owner, $type);
  $stmt->execute();
}

$stmt->close();
$conn->close();
?>
