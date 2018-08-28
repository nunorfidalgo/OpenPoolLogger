<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "charts";
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
    <h1 class="h2"> Gráficos </h1>
	</div>

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">

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
				  Todos
				</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					<a class="dropdown-item" href="#">Cloro</a>
					<a class="dropdown-item" href="#">DPD3</a>
					<a class="dropdown-item" href="#">Ph</a>
					<a class="dropdown-item" href="#">Temperatura</a>
					<a class="dropdown-item" href="#">Maq</a>
				</div>
			</div>
		  
		</div>

    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

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

	  	<button type="button" class="btn btn-outline-secondary active"> Mostrar </button>
	</div>

</div>

<canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>

<?php
/*
// $query = "SELECT * from parametros";
$query = "SELECT `parametros`.`pid`, `parametros`.`cloro`, `parametros`.`dpd3`, `parametros`.`ph`, `parametros`.`temperatura`, `parametros`.`maq`, `parametros`.`datahora`, `funcionarios`.`fullname`
FROM `parametros`, `funcionarios`
WHERE `parametros`.`responsavel` = `funcionarios`.`fid`";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
	//print_r($stmt);
    $stmt->bind_result($id, $cloro, $dpd3, $ph, $temp, $maq, $datahora, $responsavel);
    while ($stmt->fetch()) {
        //printf("%s, %s\n", $field1, $field2);
		printf("<tbody><tr>");

		printf("<td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td>", $id, $cloro, $dpd3, $ph, $temp, $maq, $datahora, $responsavel);

		printf("</tr></tbody>");
    }
    $stmt->close();
}
$con->close();
*/
?>

    </table>
  </div>
</main>

</div>
</div>

<?php
include_once('footer.php');
include_once('footer_charts.php');
?>

</body>
</html>
