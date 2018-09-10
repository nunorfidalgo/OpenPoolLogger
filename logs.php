<?php
session_start();
if( !isset($_SESSION["user"]) ) header( "Location: index.php" );

require_once('config.php');
$page_start = microtime(true);

$_SESSION["sidebar"] = "logs";
$_SESSION["menu"] = "";

if (!isset($_SESSION['logs']['orderby'])) $_SESSION['logs']['orderby'] = "asc";
if (!isset($_SESSION['logs']['limit'])) $_SESSION['logs']['limit'] = 4;
if (!isset($_SESSION['logs']['offset'])) $_SESSION['logs']['offset'] = 0;

if( !isset($_SESSION['logs']['logtype'])) $_SESSION['logs']['logtype'] = "all";
if( !isset($_SESSION['logs']['orderby'])) $_SESSION['logs']['orderby'] = "desc";
if( !isset($_SESSION['logs']['date'])) $_SESSION['logs']['date'] = "all";

$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());


if( !isset($_SESSION["logs"]['num_rows']) ) {
	$sql = "SELECT * FROM `logs`;";
	$result = $conn->query($sql);
	$_SESSION["logs"]['num_rows'] = $result->num_rows;
}

if( $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" && $_POST['inputLogType'] == "all" ) {
	// print_r($_POST); die();
	$sql = "SELECT * FROM `logs`;";
	$result = $conn->query($sql);
	$_SESSION["logs"]['num_rows'] = $result->num_rows;
}

if( $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" && $_POST['inputLogType'] == "1" ) {
	// print_r($_POST); die();
	// $sql = "SELECT * FROM `logs` WHERE `logs`.`log_type` = ".$_SESSION['logs']['logtype'].";";
	$sql = "SELECT * FROM `logs` WHERE `logs`.`log_type` = ".$_POST['inputLogType'].";";
	$result = $conn->query($sql);
	$_SESSION["logs"]['num_rows'] = $result->num_rows;
}

if( $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" && $_POST['inputLogType'] == "2" ) {
	// print_r($_POST); die();
	// $sql = "SELECT * FROM `logs` WHERE `logs`.`log_type` = ".$_SESSION['logs']['logtype'].";";
	$sql = "SELECT * FROM `logs` WHERE `logs`.`log_type` = ".$_POST['inputLogType'].";";
	$result = $conn->query($sql);
	$_SESSION["logs"]['num_rows'] = $result->num_rows;
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-export" ) {
	echo "export... TODO!";
	die();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" ) {
	$_SESSION['logs']['logtype'] = $_POST['inputLogType'];
	$_SESSION['logs']['date'] = $_POST['inputDate'];
	$_SESSION['logs']['orderby'] = $_POST['inputOrderBy'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-next" ) {
	if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] <= $_SESSION['logs']['num_rows'] ){
		$_SESSION['logs']['offset'] += $_SESSION['logs']['limit'];
		$_POST["submit"] = "";
	}
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-prev" ) {
	if ($_SESSION['logs']['offset'] > 0 ) {
		$_SESSION['logs']['offset'] -= $_SESSION['logs']['limit'];
		$_POST["submit"] = "";
	}
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
		<h1 class="h2"> Registos </h1>
		<?php
    if( isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ){
      echo $_SESSION["msg"];
    }
    echo $_SESSION["msg"] = "";
		?>
		<div class="btn-group" role="group">
		  <a class="btn btn-outline-secondary active" role="button" href="logs_form.php"> <ion-icon name="add-circle-outline"></ion-icon>&nbsp;	Adicionar </a>
		</div>
	</div>

	<form id="logs-form-filter" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-start">
			<div class="btn-group" role="group">
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
							$str_type .= '>'.$row["name"].'</option>';
						}
					} else {
							$str_type .= '<option>Sem dados...</option>';
					}
					echo $str_type;
				?>
				</select>
			</div>
		<!-- </div>

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-center"> -->

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
					<option value="desc" <?php if($_SESSION['logs']['orderby'] == "desc" ) echo "selected"; ?> >Descendente</option>
					<option value="asc" <?php if($_SESSION['logs']['orderby'] == "asc" ) echo "selected"; ?>>Ascendente</option>
				</select>
			</div>
		</div>

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-center">
			<div class="btn-group" role="group">
				<button class="btn btn-outline-secondary" type="submit" name="submit" value="logs-form-export" aria-label="Filtrar"><ion-icon name="paper"></ion-icon> &nbsp; Exportar </button>
				<button class="btn btn-outline-secondary active" type="submit" name="submit" value="logs-form-filter" aria-label="Filtrar"> <ion-icon name="search"></ion-icon>&nbsp; Filtrar </button>
			</div>
		</div>

	</form>
	</div>

  <div class="table-responsive needs-validation">
    <table class="table table-striped table-sm table-hover">
      <thead>
        <tr>
          <th data-toggle="tooltip" data-placement="top" title="id"><ion-icon name="key"></ion-icon></th>
          <th data-toggle="tooltip" data-placement="top" title="Cloro, valores entre 0.00-2.00 ppm ou mg/l">Cl</th>
          <th data-toggle="tooltip" data-placement="top" title="Dpd3, valores entre 0.00-2.00 ppm ou mg/l">DPD3</th>
          <th data-toggle="tooltip" data-placement="top" title="pH, valores entre 0(ácido)->7(neutro)<-14(alcalino)">pH</th>
          <th data-toggle="tooltip" data-placement="top" title="Temperatura em ºC">Temp</th>
				  <th data-toggle="tooltip" data-placement="top" title="Watts gastos pelas máquinas">Máquina</th>
					<th data-toggle="tooltip" data-placement="top" title="Correção valores de Cl/DPD3 em kg">Correção</th>
					<th data-toggle="tooltip" data-placement="top" title="Tipo">Tipo</th>
				  <th data-toggle="tooltip" data-placement="top" title="Data e hora">Data/Hora</th>
				  <th data-toggle="tooltip" data-placement="top" title="Funcionário">Responsável</th>
        </tr>
      </thead>

			<?php

			$query = "
			SELECT `logs`.`pid`, `logs`.`cl`, `logs`.`dpd3`, `logs`.`ph`, `logs`.`temp`, `logs`.`maq`, `logs`.`timedate`, `employers`.`fullname`, `log_type`.`name`
			FROM `logs`, `employers`, `log_type`
			WHERE `logs`.`log_owner` = `employers`.`fid`
			AND `logs`.`log_type` = `log_type`.`tid`";

			if( $_SESSION['logs']['logtype'] != "all" )
				$query .= " AND `logs`.`log_type` = ".$_SESSION['logs']['logtype'];

			if( $_SESSION['logs']['date'] == 'last-week' ) $query .= " AND `logs`.`timedate` >= SUBDATE(now(), INTERVAL 1 week) ";
			if( $_SESSION['logs']['date'] == 'last-two-weeks' ) $query .= " AND `logs`.`timedate` >= SUBDATE(now(), INTERVAL 2 week) ";
			if( $_SESSION['logs']['date'] == 'last-month' ) $query .= " AND `logs`.`timedate` >= SUBDATE(now(), INTERVAL 1 month) ";
			if( $_SESSION['logs']['date'] == 'last-two-months' ) $query .= " AND `logs`.`timedate` >= SUBDATE(now(), INTERVAL 2 month) ";
			if( $_SESSION['logs']['date'] == 'last-year' ) $query .= " AND `logs`.`timedate` >= SUBDATE(now(), INTERVAL 1 year) ";

			$query .= "
			ORDER BY `logs`.`timedate` ".$_SESSION['logs']['orderby']."
			LIMIT ".$_SESSION['logs']['offset'].", ".$_SESSION['logs']['limit'];

			if ($stmt = $conn->prepare($query)) {

			$start = microtime(true);

			    $stmt->execute();

			$end = microtime(true);

			    $stmt->bind_result($id, $cl, $dpd3, $ph, $temp, $maq, $timedate, $log_owner, $type);

			    while ($stmt->fetch()) {
						printf("<tbody><tr>");

						printf('<td class="text-muted">%s</td>', $id);

						if( $cl < 1.0 ) printf('<td class="text-danger" >%s</td>', $cl);
						elseif( $cl > 1.5 ) printf('<td class="text-warning" >%s</td>', $cl);
						else printf('<td class="text-success" >%s</td>', $cl);

						if( $cl+$dpd3 < $cl-0.5 ) printf('<td class="text-warning" >%s</td>', $dpd3);
						elseif( $cl+$dpd3 > $cl+0.5 ) printf('<td class="text-danger" >%s</td>', $dpd3);
						else printf('<td class="text-success">%s</td>', $dpd3);

						if( $ph < 6.3 ) printf('<td class="text-danger" >%s</td>', $ph);
						elseif( $ph > 7.1 ) printf('<td class="text-success" >%s</td>', $ph);
						else printf('<td class="text-warning" >%s</td>', $ph);

						if( $temp < 20 ) printf('<td class="text-danger" >%s</td>', $temp);
						elseif( $temp > 25 ) printf('<td class="text-success" >%s</td>', $temp);
						else printf('<td class="text-warning" >%s</td>', $temp);

						if( $maq == '' ) {
							printf("<td>-</td>");
						} else {
							if( $maq < 450 ) printf('<td class="text-success" >%s</td>', $maq);
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

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">

		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-start">
			<div class="btn-group" role="group">
				<?php
				$difference = $end - $start;
				printf("Tempo pesquisa: %.6f seconds.", $difference);
				?>
			</div>
		</div>

		<form id="pages-logs-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">
		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-end">
			<div class="btn-group" role="group">
				<ul class="pagination pagination-sm">

					<li class="page-item <?php if ($_SESSION['logs']['offset'] <= 0 ) echo 'disabled'; ?>">
						<button class="page-link" type="submit" name="submit" value="pages-logs-form-prev" aria-label="Anterior">
							<span aria-hidden="false">&laquo;</span>
							<span>Anterior</span>
						</button>
					</li>

					<?php
					for( $i = 0 ; $i <= 10 ; $i++ ){
					?>

					<li class="page-item active">
						<button class="page-link" type="submit" name="submit" value="pages-logs-form" aria-label="page_number">
						<!-- <div class="page-link" aria-label="page_number"> -->
							<?php printf("%d", (($_SESSION["logs"]['num_rows'] - $_SESSION['logs']['offset']) / $_SESSION["logs"]['limit'])); ?>
						<!-- </div> -->
						</button>
					</li>

					<?php } ?>

					<li class="page-item <?php if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] >= $_SESSION['logs']['num_rows'] ) echo 'disabled'; ?>">
						<button class="page-link" type="submit" name="submit" value="pages-logs-form-next" aria-label="Seguinte">
							<span >Seguinte</span>
							<span aria-hidden="false">&raquo;</span>
						</button>
					</li>

				</ul>
			</div>

		</div>
		</form>
	</div>

	<?php
	// echo '<br><br>';
	echo $query;
	echo '<br>';
	echo 'num_rows: '.$_SESSION["logs"]['num_rows'];
	?>

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
