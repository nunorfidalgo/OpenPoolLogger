<?php
require_once('config.php');
$page_start = microtime(true);
session_start();
if( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "logs";
$_SESSION["menu"] = "";
// if( !isset($_SESSION["logs"]["page"])) $_SESSION["logs"]["page"] = "1";
if( !isset($_SESSION['logs']['logtype'])) $_SESSION['logs']['logtype'] = "all";
if( !isset($_SESSION['logs']['orderby'])) $_SESSION['logs']['orderby'] = "desc";

$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

// Get total rows from logs table
if( !isset($_SESSION["logs"]['num_rows']) ) {
	$sql = "select * from logs;";
	$result = $conn->query($sql);
	$_SESSION["logs"]['num_rows'] = $result->num_rows;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" ) {
	// echo "filter";
	// print_r($_POST);
	// die();
	$_SESSION['logs']['orderby'] = $_POST['inputOrderBy'];
	$_SESSION['logs']['logtype'] = $_POST['inputLogType'];
	// inputDate
}




if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-next" ) {
	if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] <= $_SESSION['logs']['num_rows'] ){
		$_SESSION['logs']['offset'] += $_SESSION['logs']['limit'];
		$_POST["submit"] = "";
		// $_SESSION["logs"]["page"] += 1;
	}
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-prev" ) {
	if ($_SESSION['logs']['offset'] > 0 ) {
		$_SESSION['logs']['offset'] -= $_SESSION['logs']['limit'];
		$_POST["submit"] = "";
		// $_SESSION["logs"]["page"] -= 1;
	}
}



//
//
// if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form" ) {
// 	// if( $_SESSION["logs"]["page"] == "1" ) {
// 		if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] <= $_SESSION['logs']['num_rows'] ){
// 			$_SESSION['logs']['offset'] += $_SESSION['logs']['limit'];
// 			$_POST["submit"] = "";
// 			$_SESSION["logs"]["page"] = "1";
// 		}
// 	// }
// 	// if( $_SESSION["logs"]["page"] == "2" ) {
// 		if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] <= $_SESSION['logs']['num_rows'] ){
// 			$_SESSION['logs']['offset'] += $_SESSION['logs']['limit'];
// 			$_POST["submit"] = "";
// 			$_SESSION["logs"]["page"] = "2";
// 		}
// 	// }
// 	// if( $_SESSION["logs"]["page"] == "3" ) {
// 		// echo 	$_SESSION['logs']['offset'];
// 		// die();
// 		if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] <= $_SESSION['logs']['num_rows'] ){
// 			$_SESSION['logs']['offset'] += $_SESSION['logs']['limit'];
// 			$_POST["submit"] = "";
// 			$_SESSION["logs"]["page"] = "3";
// 		}
// 	// }
// }
/*
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-2" ) {
	if( $_SESSION["logs"]["page"] == "1" ) {
		if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] <= $_SESSION['logs']['num_rows'] ){
			$_SESSION['logs']['offset'] -= $_SESSION['logs']['limit'];
			$_POST["submit"] = "";
			$_SESSION["logs"]["page"] = "1";
		}
	}
	if( $_SESSION["logs"]["page"] == "2" ) {
		if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] <= $_SESSION['logs']['num_rows'] ){
			$_SESSION['logs']['offset'] += $_SESSION['logs']['limit'];
			$_POST["submit"] = "";
			$_SESSION["logs"]["page"] = "2";
		}
	}
	if( $_SESSION["logs"]["page"] == "3" ) {
		if ($_SESSION['logs']['offset'] + 2*$_SESSION['logs']['limit'] <= $_SESSION['logs']['num_rows'] ){
			$_SESSION['logs']['offset'] += 2*$_SESSION['logs']['limit'];
			$_POST["submit"] = "";
			$_SESSION["logs"]["page"] = "3";
		}
	}

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-3" ) {

}
*/
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

		<?php
		    if( isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ){
		      echo $_SESSION["msg"];
		    }
		    echo $_SESSION["msg"] = "";
		?>

		<div class="btn-group" role="group">
		  <a class="btn btn-outline-secondary" role="button" href="#"><ion-icon name="paper"></ion-icon> &nbsp; Exportar </a>
		  <a class="btn btn-outline-secondary active" role="button" href="logs_form.php"> <ion-icon name="add-circle-outline"></ion-icon>&nbsp;	Adicionar </a>
		</div>

	</div>

	<form id="logs-form-filter" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">

		<!-- <h1 class="h2"> Piscina </h1> -->

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
			<div class="btn-group" role="group">
				<!-- <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Todos
				</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					<a class="dropdown-item" href="#">Todos</a>
					<a class="dropdown-item" href="#">Piscina</a>
					<a class="dropdown-item" href="#">Jacuzzi</a>
				</div> -->
				<select id="inputLogType" name="inputLogType" class="form-control">
					<?php
					$sql="SELECT `log_type`.`tid`, `log_type`.`name` FROM `log_type`";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						$str_type = '<option value="all"';
						if($_SESSION['logs']['logtype'] == "all" ) 	$str_type .= " selected";
						$str_type .= '>Todos</option>';
						while(	$row = $result->fetch_assoc() ) {
							$str_type .= '<option value="'.$row["tid"].'"';
							if($_SESSION['logs']['logtype'] == $row["tid"]) $str_type .= " selected";
							// if($_SESSION['logs']['logtype'] == "2") $str_type .= " selected";
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
				<!-- <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Descendente
				</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					<a class="dropdown-item" href="#">Ascendente</a>
					<a class="dropdown-item" href="#">Descendente</a>
				</div> -->
				<select id="inputOrderBy" name="inputOrderBy" class="form-control">
					<option value="desc" <?php if($_SESSION['logs']['orderby'] == "desc" ) echo "selected"; ?> >Descendente</option>
					<option value="asc" <?php if($_SESSION['logs']['orderby'] == "asc" ) echo "selected"; ?>>Ascendente</option>
				</select>
			</div>
		</div>

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-end">
			<div class="btn-group" role="group">
				<!-- <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  Todos
				</button>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
					<a class="dropdown-item" href="#">Todos</a>
					<a class="dropdown-item" href="#">Última semana</a>
					<a class="dropdown-item" href="#">Últimas duas semanas</a>
					<a class="dropdown-item" href="#">Último mês</a>
					<a class="dropdown-item" href="#">Últimos dois meses</a>
					<a class="dropdown-item" href="#">Último Ano</a>
				</div> -->
				<select id="inputDate" name="inputDate" class="form-control">
					<option value="all" selected>Todos</option>
					<option value="last-week">Última semana</option>
					<option value="last-two-weeks">Últimas duas semanas</option>
					<option value="last-two-month">Último mês</option>
					<option value="last-two-months">Últimos dois meses</option>
					<option value="last-year">Último Ano</option>
				</select>
		  </div>
		  <!-- <a class="btn btn-outline-secondary active" role="button" href="#"> <ion-icon name="pulse"></ion-icon>&nbsp; Filtrar </a> -->
			<button class="btn btn-outline-secondary active" type="submit" name="submit" value="logs-form-filter" aria-label="Filtrar"> Filtrar </button>
		</div>

	</div>
	</form>

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
					<th data-toggle="tooltip" data-placement="top" title="Tipo">Tipo</th>
				  <th data-toggle="tooltip" data-placement="top" title="Data e hora">Data/Hora</th>
				  <th data-toggle="tooltip" data-placement="top" title="Funcionário">Responsável</th>

        </tr>
      </thead>

<?php

// $query = "SELECT * from parametros";
// $query = "SELECT `parametros`.`pid`, `parametros`.`cloro`, `parametros`.`dpd3`, `parametros`.`ph`, `parametros`.`temperatura`, `parametros`.`maq`, `parametros`.`datahora`, `funcionarios`.`fullname`
// FROM `parametros`, `funcionarios`
// WHERE `parametros`.`responsavel` = `funcionarios`.`fid`";

// $query = "SELECT `logs`.`pid`, `logs`.`cl`, `logs`.`dpd3`, `logs`.`ph`, `logs`.`temp`, `logs`.`maq`, `logs`.`timedate`, `employers`.`fullname`
// FROM `logs`, `employers`
// WHERE `logs`.`log_owner` = `employers`.`fid`
// ORDER BY `logs`.`pid` desc
// LIMIT 0, 12";

if (!isset($_SESSION['logs']['orderby'])) $_SESSION['logs']['orderby'] = "asc";
// if (!isset($_SESSION['logs']['limit_low'])) $_SESSION['logs']['limit_low'] = 0;
if (!isset($_SESSION['logs']['limit'])) $_SESSION['logs']['limit'] = 10;
if (!isset($_SESSION['logs']['offset'])) $_SESSION['logs']['offset'] = 0; //else $_SESSION['logs']['offset'] = 0;

// $query = "
// SELECT `logs`.`pid`, `logs`.`cl`, `logs`.`dpd3`, `logs`.`ph`, `logs`.`temp`, `logs`.`maq`, `logs`.`timedate`, `employers`.`fullname`
// FROM `logs`, `employers`
// WHERE `logs`.`log_owner` = `employers`.`fid`
// ORDER BY `logs`.`pid` ".$_SESSION['logs']['orderby']."
// LIMIT ".$_SESSION['logs']['offset'].", ".$_SESSION['logs']['limit'];

$query = "
SELECT `logs`.`pid`, `logs`.`cl`, `logs`.`dpd3`, `logs`.`ph`, `logs`.`temp`, `logs`.`maq`, `logs`.`timedate`, `employers`.`fullname`, `log_type`.`name`
FROM `logs`, `employers`, `log_type`
WHERE `logs`.`log_owner` = `employers`.`fid`
AND `logs`.`log_type` = `log_type`.`tid`";
if( $_SESSION['logs']['logtype'] != "all" )
	$query .= " AND `logs`.`log_type` = ". $_SESSION['logs']['logtype'];
$query .= "
ORDER BY `logs`.`pid` ".$_SESSION['logs']['orderby']."
LIMIT ".$_SESSION['logs']['offset'].", ".$_SESSION['logs']['limit'];




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

    $stmt->bind_result($id, $cl, $dpd3, $ph, $temp, $maq, $timedate, $log_owner, $type);

    while ($stmt->fetch()) {
			printf("<tbody><tr>");
			// printf("<td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td>", $id, $cl, $dpd3, $ph, $temp, $maq, $timedate, $log_owner);
			printf('<td class="text-muted">%s</td>', $id);

			// printf("<td>%s</td>", $cl);
			if( $cl < 1.0 ) printf('<td class="text-danger" >%s</td>', $cl);
			elseif( $cl > 1.5 ) printf('<td class="text-warning" >%s</td>', $cl);
			else printf('<td class="text-success" >%s</td>', $cl);

			// printf("<td>%s</td>", $dpd3);
			if( $cl+$dpd3 < $cl-0.5 ) printf('<td class="text-warning" >%s</td>', $dpd3);
			elseif( $cl+$dpd3 > $cl+0.5 ) printf('<td class="text-danger" >%s</td>', $dpd3);
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

			printf('<td class="text-muted">%s</td>', $type);

			printf('<td class="text-muted">%s</td>', $timedate);

			if( $_SESSION['user']['fullname'] == $log_owner)
				printf('<td>%s</td>', $log_owner);
			else
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
	printf("Tempo pesquisa: %.6f seconds.", $difference);
	// $queryTime = number_format($difference, 10);
	// echo "<br>Query time: $queryTime seconds.";
	?>

<form id="pages-logs-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">
<!-- <nav aria-label="Page navigation example"> -->
  <ul class="pagination pagination-sm justify-content-end">

    <!-- <li class="page-item <?php //if ($_SESSION['logs']['offset'] <= 0 ) echo 'disabled'; ?>">
			<button class="page-link" type="submit" name="submit" value="pages-logs-form-prev" aria-label="Anterior">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only disable"> Anterior</span>
      </button>
    </li> -->

		<li class="page-item <?php if ($_SESSION['logs']['offset'] <= 0 ) echo 'disabled'; ?>">
			<button class="page-link" type="submit" name="submit" value="pages-logs-form-prev" aria-label="Anterior">
				<span aria-hidden="false">&laquo;</span>
				<span>Anterior</span>
			</button>
		</li>

    <li class="page-item active">
			<!-- <button class="page-link" type="submit" name="submit" value="pages-logs-form" aria-label="Anterior"> -->
			<div class="page-link" aria-label="Anterior">
				<?php printf("Página: %d", ($_SESSION["logs"]['num_rows'] - $_SESSION['logs']['offset']) / $_SESSION["logs"]['limit']); ?>
			</div>
			<!-- </button> -->
		</li>
<!--
		<li class="page-item <?php //if ($_SESSION['logs']['page'] == '2' ) echo 'active'; ?>">
			<button class="page-link" type="submit" name="submit" value="pages-logs-form" aria-label="Anterior">
				<?php //printf("%d", (($_SESSION["logs"]['num_rows'] - $_SESSION['logs']['offset']) / $_SESSION["logs"]['limit']) - 1); ?>
			</button>
		</li>

		<li class="page-item <?php //if ($_SESSION['logs']['page'] == '3' ) echo 'active'; ?>">
			<button class="page-link" type="submit" name="submit" value="pages-logs-form" aria-label="Anterior">
				<?php //printf("%d", (($_SESSION["logs"]['num_rows'] - $_SESSION['logs']['offset']) / $_SESSION["logs"]['limit']) - 2); ?>
			</button>
		</li> -->

    <li class="page-item <?php if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] >= $_SESSION['logs']['num_rows'] ) echo 'disabled'; ?>">
			<button class="page-link" type="submit" name="submit" value="pages-logs-form-next" aria-label="Seguinte">
			 	<span >Seguinte</span>
	      <span aria-hidden="false">&raquo;</span>
	    </button>
    </li>

  </ul>
<!-- </nav> -->
</form>

<?php
// echo $query;
// echo '<br>';
// echo 'page: '.$_SESSION["logs"]["page"];
?>

</main>

</div>
</div>

<?php include_once('footer.php');
// $page_end = microtime(true);
// $page_render_diff = $page_end - $page_start;
// printf("Tempo renderização página: %.6f seconds.", $page_render_diff);
?>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

</body>
</html>
