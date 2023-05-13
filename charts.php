<?php
session_start();
if( !isset($_SESSION["user"]) ) header( "Location: index.php" );

require_once('config.php');

$_SESSION["sidebar"] = "charts";
$_SESSION["menu"] = "";

if( !isset($_SESSION['logs']['logtype'])) $_SESSION['logs']['logtype'] = "all";
if( !isset($_SESSION['logs']['date'])) $_SESSION['logs']['date'] = "all";
if (!isset($_SESSION['logs']['orderby'])) $_SESSION['logs']['orderby'] = "DESC";
if( !isset($_SESSION['logs']['inputEmployer'])) $_SESSION['logs']['inputEmployer'] = "all";

// Filters

if( $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" ) {
	//print_r($_POST); die();
	$_SESSION['logs']['logtype'] = $_POST['inputLogType'];
	$_SESSION['logs']['date'] = $_POST['inputDate'];
	$_SESSION['logs']['orderby'] = $_POST['inputOrderBy'];
	$_SESSION['logs']['inputEmployer'] = $_POST['inputEmployer'];
}

?>

<!doctype html>
<html lang="en">

<?php include_once('header.php'); ?>

<body>

<?php include_once('menu.php'); ?>

<div class="container-fluid">
<div class="row">

<?php include_once('sidebar.php'); ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    	<h1 class="h1"> Gráficos </h1>
	</div>
		
	<form id="logs-form-filter" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-start">

			<div class="btn-group" role="group">
				<select id="inputLogType" name="inputLogType" class="form-control">
					<?php

					$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
						or die ('Could not connect to the database server' . mysqli_connect_error());

					$logtype="SELECT `log_type`.`tid`, `log_type`.`name` FROM `log_type`";
					$result = $conn->query($logtype);
					if ($result->num_rows > 0) {
						$str_type = '<option value="all"';
						if($_SESSION['logs']['logtype'] == "all" ) 	$str_type .= " selected";
						$str_type .= '>Todos</option>';
						while(	$row = $result->fetch_assoc() ) {
							$str_type .= '<option value="'.$row["tid"].'"';
							if($_SESSION['logs']['logtype'] == $row["tid"]) $str_type .= " selected";
							$str_type .= '>'.$row["name"].'</option>';
						}
					} else {
							$str_type .= '<option>Sem dados...</option>';
					}
					echo $str_type;

					?>
				</select>
			</div>

			<div class="btn-group" role="group">
				<select id="inputDate" name="inputDate" class="form-control">
					<option value="all" <?php if( $_SESSION['logs']['date'] == 'all' ) echo ' selected'; ?>>Todos</option>
					<option value="last-week"<?php if( $_SESSION['logs']['date'] == 'last-week' ) echo ' selected'; ?>>Última semana</option>
					<option value="last-two-weeks" <?php if( $_SESSION['logs']['date'] == 'last-two-weeks' ) echo ' selected'; ?>>Últimas duas semanas</option>
					<option value="last-month" <?php if( $_SESSION['logs']['date'] == 'last-month' ) echo ' selected'; ?>>Último mês</option>
					<option value="last-two-months" <?php if( $_SESSION['logs']['date'] == 'last-two-months' ) echo ' selected'; ?>>Últimos dois meses</option>
					<option value="last-year" <?php if( $_SESSION['logs']['date'] == 'last-year' ) echo ' selected'; ?>>Último Ano</option>
				</select>
		  	</div>

			<div class="btn-group" role="group">
				<select id="inputOrderBy" name="inputOrderBy" class="form-control">
					<option value="DESC" <?php if($_SESSION['logs']['orderby'] == "DESC" ) echo "selected"; ?>> Data descendente </option>
					<option value="ASC" <?php if($_SESSION['logs']['orderby'] == "ASC" ) echo "selected"; ?>> Data ascendente </option>
				</select>
			</div>

			<div class="btn-group" role="group">
				<select id="inputEmployer" name="inputEmployer" class="form-control">
					<?php

					$employers="SELECT `employers`.`eid`, `employers`.`fullname` FROM `employers`";

					$result = $conn->query($employers);
					if ($result->num_rows > 0) {
						$str_type = '<option value="all"';
						if($_SESSION['logs']['inputEmployer'] == "all" ) 	$str_type .= " selected";
						$str_type .= '>Todos</option>';
						while(	$row = $result->fetch_assoc() ) {
							$str_type .= '<option value="'.$row["eid"].'"';
							if($_SESSION['logs']['inputEmployer'] == $row["eid"]) $str_type .= " selected";
							$str_type .= '>'.$row["fullname"].'</option>';
						}
					} else {
							$str_type .= '<option>Sem dados...</option>';
					}
					echo $str_type;

					?>
				</select>
			</div>

		</div>

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-center">
			<div class="btn-group" role="group">
				<a class="btn btn-outline-secondary" role="button" href="logs_export.php"><ion-icon name="paper"></ion-icon>&nbsp; Exportar </a>
				<button class="btn btn-outline-secondary active" type="submit" name="submit" value="logs-form-filter" aria-label="Filtrar"> <ion-icon name="search"></ion-icon>&nbsp; Filtrar </button>
			</div>
		</div>
	</div>
	</form>

	<?php
	// main query

	$query = "
	SELECT `logs`.`lid`, `logs`.`cl`, `logs`.`dpd3`, `logs`.`ph`, `logs`.`temp`, `logs`.`maq`, `logs`.`correction`, `logs`.`record_time`, `employers`.`fullname`, `log_type`.`name`
	FROM `logs`, `employers`, `log_type`
	WHERE `logs`.`log_owner` = `employers`.`eid`
	AND `logs`.`log_type` = `log_type`.`tid`";

	if( $_SESSION['logs']['logtype'] != "all" )
		$query .= " AND `logs`.`log_type` = ".$_SESSION['logs']['logtype'];

	if( $_SESSION['logs']['date'] == 'last-week' ) $query .= " AND `logs`.`record_time` >= SUBDATE(now(), INTERVAL 1 week) ";
	if( $_SESSION['logs']['date'] == 'last-two-weeks' ) $query .= " AND `logs`.`record_time` >= SUBDATE(now(), INTERVAL 2 week) ";
	if( $_SESSION['logs']['date'] == 'last-month' ) $query .= " AND `logs`.`record_time` >= SUBDATE(now(), INTERVAL 1 month) ";
	if( $_SESSION['logs']['date'] == 'last-two-months' ) $query .= " AND `logs`.`record_time` >= SUBDATE(now(), INTERVAL 2 month) ";
	if( $_SESSION['logs']['date'] == 'last-year' ) $query .= " AND `logs`.`record_time` >= SUBDATE(now(), INTERVAL 1 year) ";

	if( $_SESSION['logs']['inputEmployer'] != "all" ) {
		$query .= " AND `logs`.`log_owner` = ".$_SESSION['logs']['inputEmployer'];
	}

	$query .= "
	ORDER BY UNIX_TIMESTAMP(`logs`.`record_time`) ".$_SESSION['logs']['orderby'];

	$query_start = microtime(true);
	$query_result = $conn->query($query);
	// error_log("query: ");
	// error_log(print_r($query,true));
	$query_result = $conn->query($query);	
	$query_end = microtime(true);

	// foreach ($query_result as $row) {
		//error_log(print_r($row,true));
		//printf('lid: %s | cl: %s | dpd3: %s | ph: %s | temp: %s | maq: %s | correction: %s | name: %s | record_time: %s | fullname: %s ', 
		//$row['lid'], $row['cl'], $row['dpd3'], $row['ph'], $row['temp'], $row['maq'], $row['correction'], $row['name'], $row['record_time'], $row['fullname']);	
	// } // end foreach
	
	
	//echo json_encode($query_result);
	//$teste = "javascript test";

	$data_result = array();
	foreach ($query_result as $row) {
		array_push($data_result, $row);
	} // end foreach

	$query_result->close();
	$conn->close();
	?>

	<canvas class="my-4 w-100" id="myChart" width="900" height="350"></canvas>

</main>
</div>
</div>

<?php
include_once('footer.php');
?>

<!-- Graphs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script type="text/javascript">

	var query_result = <?php echo json_encode($data_result); ?>;
	//console.log(JSON.stringify(query_result));

	var cl = [];
	var dpd3 = [];
	var ph = [];
	var temp = [];
	var maq = [];
	var correction = [];
	var record_time = [];


	for (let i = 0; i < query_result.length; i++) {
		cl.push(query_result[i]['cl']);
		dpd3.push(query_result[i]['dpd3']);
		ph.push(query_result[i]['ph']);
		temp.push(query_result[i]['temp']);
		maq.push(query_result[i]['maq']);
		correction.push(query_result[i]['correction']);
		record_time.push(query_result[i]['record_time']);
		//console.log(query_result[i]['record_time']);
    }

	var ctx = document.getElementById("myChart");
	
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: record_time,
			datasets: [{
				label: 'cl',
				data: cl,
				backgroundColor: 'rgba(255, 99, 132, 0.2)',
				borderColor: 'rgb(255, 99, 132)',
			},
			{
				label: 'dpd3',
				data: dpd3,
				backgroundColor: 'rgba(255, 159, 64, 0.2)',
				borderColor: 'rgb(255, 159, 64)',
			},
			{
				label: 'pH',
				data: ph,
				backgroundColor: 'rgba(255, 205, 86, 0.2)',
				borderColor: 'rgb(255, 205, 86)',
			},
			{
				label: 'Temp',
				data: temp,
				backgroundColor: 'rgba(75, 192, 192, 0.2)',
				borderColor: 'rgb(75, 192, 192)',
			},
			{
				label: 'Maq',
				data: maq,
				backgroundColor: 'rgba(54, 162, 235, 0.2)',
				borderColor: 'rgb(54, 162, 235)',
			},
			{
				label: 'Correção',
				data: correction,
				backgroundColor: 'rgba(153, 102, 255, 0.2)',
				borderColor: 'rgb(153, 102, 255)',
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
</script>


</body>
</html>
