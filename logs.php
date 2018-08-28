<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "logs";
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

		<h1 class="h2"> Registos </h1>

		<div class="btn-group" role="group">
		  <a class="btn btn-outline-secondary" role="button" href="#"><ion-icon name="paper"></ion-icon> &nbsp; Exportar </a>
		  <a class="btn btn-outline-secondary active" role="button" href="logs_form.php"> <ion-icon name="add-circle-outline"></ion-icon>&nbsp;	Adicionar </a>
		</div>

  	</div>

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">

		<!-- <h1 class="h2"> Piscina </h1> -->

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
			<div class="btn-group" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Piscina
				</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					<a class="dropdown-item" href="#">Piscina</a>
					<a class="dropdown-item" href="#">Jacuzzi</a>
				</div>
			</div>
			<div class="btn-group" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Descendente
				</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					<a class="dropdown-item" href="#">Ascendente</a>
					<a class="dropdown-item" href="#">Descendente</a>
				</div>
			</div>
		</div>

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-end">

			<div class="btn-group" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  Última Semana
				</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					<a class="dropdown-item" href="#">Última Semana</a>
					<a class="dropdown-item" href="#">Últimas duas semanas</a>
					<a class="dropdown-item" href="#">Último Mês</a>
					<a class="dropdown-item" href="#">Último Ano</a>
				</div>
		  </div>
		  <a class="btn btn-outline-secondary active" role="button" href="#"> <ion-icon name="pulse"></ion-icon>&nbsp; Filtrar </a>
		</div>

  </div>

  <div class="table-responsive needs-validation">
    <table class="table table-striped table-sm table-hover">
      <thead>
        <tr>
          <th data-toggle="tooltip" data-placement="top" title="id"><ion-icon name="key"></ion-icon></th>
          <th data-toggle="tooltip" data-placement="top" title="0-2 ppm ou mg/l">Cloro</th>
          <th data-toggle="tooltip" data-placement="top" title="0-2 ppm ou mg/l">DPD3</th>
          <th data-toggle="tooltip" data-placement="top" title="0-14">Ph</th>
          <th data-toggle="tooltip" data-placement="top" title="Celsius (ºC)">Temperatura(ºC)</th>
				  <th data-toggle="tooltip" data-placement="top" title="Watts">Maq</th>
					<th data-toggle="tooltip" data-placement="top" title="Correção valores de Cl/DPD3">Correção</th>
				  <th data-toggle="tooltip" data-placement="top" title="Data e hora">Data/Hora</th>
				  <th data-toggle="tooltip" data-placement="top" title="Funcionário">Responsável</th>
        </tr>
      </thead>

<?php
require_once('config.php');
$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

// $query = "SELECT * from parametros";
// $query = "SELECT `parametros`.`pid`, `parametros`.`cloro`, `parametros`.`dpd3`, `parametros`.`ph`, `parametros`.`temperatura`, `parametros`.`maq`, `parametros`.`datahora`, `funcionarios`.`fullname`
// FROM `parametros`, `funcionarios`
// WHERE `parametros`.`responsavel` = `funcionarios`.`fid`";
$query = "SELECT `logs`.`pid`, `logs`.`cl`, `logs`.`dpd3`, `logs`.`ph`, `logs`.`temp`, `logs`.`maq`, `logs`.`timedate`, `employers`.`fullname`
FROM `logs`, `employers`
WHERE `logs`.`log_owner` = `employers`.`fid`
ORDER BY `logs`.`pid` desc
LIMIT 0, 12";

if ($stmt = $conn->prepare($query)) {

// $conn->query('set profiling=1'); //optional if profiling is already enabled
// $conn->query($_POST['query']);
// $res = $conn->query('show profiles');
// print_r($res);
// $records = $res->fetch_assoc();
// $duration = $records[0]['Duration'];

$start = microtime(true);

    $stmt->execute();

$end = microtime(true);

    $stmt->bind_result($id, $cl, $dpd3, $ph, $temp, $maq, $timedate, $log_owner);

    while ($stmt->fetch()) {
			printf("<tbody><tr>");
			// printf("<td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td>", $id, $cl, $dpd3, $ph, $temp, $maq, $timedate, $log_owner);
			printf('<td class="text-muted">%s</td>', $id);

			// printf("<td>%s</td>", $cl);
			if( $cl < 1.0 ) printf('<td class="text-warning" >%s</td>', $cl);
			elseif( $cl > 1.5 ) printf('<td class="text-danger" >%s</td>', $cl);
			else printf('<td class="text-success" >%s</td>', $cl);

			// printf("<td>%s</td>", $dpd3);
			if( $dpd3 < $cl-0.5 ) printf('<td class="text-warning" >%s</td>', $dpd3);
			elseif( $dpd3 > $cl+0.5 ) printf('<td class="text-danger" >%s</td>', $dpd3);
			else printf('<td class="text-success" >%s</td>', $dpd3);

			// printf("<td>%s</td>", $ph);
			if( $ph < 6.3 ) printf('<td class="text-danger" >%s</td>', $ph);
			elseif( $ph > 7.1 ) printf('<td class="text-success" >%s</td>', $ph);
			else printf('<td class="text-warning" >%s</td>', $ph);

			// printf("<td>%s</td>", $temp);
			if( $temp < 20 ) printf('<td class="text-danger" >%s</td>', $temp);
			elseif( $temp > 25 ) printf('<td class="text-success" >%s</td>', $temp);
			else printf('<td class="text-warning" >%s</td>', $temp);

			// if( strcasecmp( $maq, '' ) == 0 ) {
			if( $maq == '' ) {
				printf("<td>-</td>");
			} else {
				if( $maq < 450 ) printf('<td class="text-success" >%s</td>', $maq);
				// if( $maq > 450 && $maq > 500 ) printf('<td class="text-warning" >%s</td>', $maq);
				elseif( $maq > 500 ) printf('<td class="text-danger" >%s</td>', $maq);
				else printf('<td class="text-warning" >%s</td>', $maq);
			}

			printf('<td class="text-muted">%s</td>', "");
			printf('<td class="text-muted">%s</td>', $timedate);
			printf('<td class="text-muted">%s</td>', $log_owner);
			printf("</tr></tbody>");
    }
    $stmt->close();
}
$conn->close();
?>

    </table>
  </div>
	<?php
	// echo "This query took " . ($end - $start) . " seconds.";
	$difference = $end - $start;
	printf("Query time: %.6f seconds.", $difference);
	// $queryTime = number_format($difference, 10);
	// echo "<br>Query time: $queryTime seconds.";
	?>

<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-end">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
</nav>

</main>

</div>
</div>

<?php include_once('footer.php'); ?>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

</body>
</html>
