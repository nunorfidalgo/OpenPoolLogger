<?php
require_once('config.php');

function frand($min, $max, $decimals = 0) {
  $scale = pow(10, $decimals);
  return mt_rand($min * $scale, $max * $scale) / $scale;
}
// echo "frand(0, 10, 2) = " . frand(0, 10, 2) . "\n";

function randomDate($start_date, $end_date){
    // Convert to timetamps
    $min = strtotime($start_date);
    $max = strtotime($end_date);

    // Generate random number using above bounds
    $val = rand($min, $max);

    // Convert back to desired date format
    return date('Y-m-d H:i:s', $val);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

$stmt = $conn->prepare("INSERT INTO `logs` (`cl`, `dpd3`, `ph`, `temp`, `maq`, `correction`, `record_time`, `log_owner`, `log_type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ");

$stmt->bind_param("ddddidsii", $cl, $dpd3, $ph, $temp, $maq, $correction, $record_time, $log_owner, $log_type);


$max_logs = 40; //2*364;
for ($i = 1; $i <= $max_logs; $i++) {
  $cl = frand(0, 2, 2);
  $dpd3 = frand(0, 2, 2);
  $ph = frand(0, 14, 2);
  $temp = frand(0, 50, 2);
  $maq = rand(1,999);
  $correction = frand(1, 3, 2);
  $record_time = randomDate( "now", "-2 months");
  //$record_time = randomDate( "now", "-2 weeks");
  // $record_time = randomDate( "now", "-2 years");
  $log_owner = rand(1,6); // alterar para 6 quando colocar os novos users
  $log_type = rand(1,2);

  $str = printf("%.2f, %.2f, %.2f, %.2f, %d, %.2f, %s, %d, %d", $cl, $dpd3, $ph, $temp, $maq, $correction, $record_time, $log_owner, $log_type);
  if( $stmt->execute() == TRUE ) { $str .= ", OK!!!<br>"; echo $str; }
}

$stmt->close();
$conn->close();
?>
