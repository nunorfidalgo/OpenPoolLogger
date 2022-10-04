<?php
session_start();
if( !isset($_SESSION["user"]) ) header( "Location: index.php" );

require_once('config.php');

$_SESSION["sidebar"] = "logs";
$_SESSION["menu"] = "";

if (!isset($_SESSION["logs"]['limit'])) $_SESSION["logs"]['limit'] = 10;
if (!isset($_SESSION['logs']['offset'])) $_SESSION['logs']['offset'] = 0;

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

// pagination

// next page
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-next" ) {
	if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] < $_SESSION['logs']['num_rows'] ){
		$_SESSION['logs']['offset'] += $_SESSION['logs']['limit'];
	}
}
// Jump to page
if ($_SERVER["REQUEST_METHOD"] == "POST" && strpos($_POST["submit"], "jump_to_page_number-") !== false) {
	//print_r($_POST);
	//var_dump($_POST);
	//die();
	$_SESSION['logs']['actual_page'] = substr($_POST["submit"], strpos($_POST["submit"], "-") + 1);
	$_SESSION['logs']['offset'] = ($_SESSION['logs']['total_pages'] - $_SESSION['logs']['actual_page'] ) * $_SESSION['logs']['limit'];
	$_SESSION["logs"]['upper_pages'] += $_SESSION["logs"]['limit'];
	$_SESSION["logs"]['lower_pages'] += $_SESSION["logs"]['limit'];
}
// previous page
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-prev" ) {
	if ($_SESSION['logs']['offset'] > 0 ) {
		$_SESSION['logs']['offset'] -= $_SESSION['logs']['limit'];
		if( $_SESSION["logs"]['actual_page'] >= $_SESSION["logs"]['upper_pages'] ){
			$_SESSION["logs"]['upper_pages'] += $_SESSION["logs"]['limit'];
			$_SESSION["logs"]['lower_pages'] += $_SESSION["logs"]['limit'];
		}
	}
}

// // export
// if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-export" ) {
// 	echo $_SESSION['logs']['export_query'];
// 	die();
// }

// // import
// if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-import" ) {
// 	echo "import... TODO!"; die();
// }
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
		<h1 class="h1"> Registos </h1>
		<?php
    if( isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ){
      echo $_SESSION["msg"];
    }
    echo $_SESSION["msg"] = "";
		?>
		<div class="btn-group" role="group">
			<a class="btn btn-outline-primary" role="button" href="#"><ion-icon name="copy"></ion-icon>&nbsp; Importar </a>
		  <a class="btn btn-primary" role="button" href="logs_form.php"> <ion-icon name="add-circle-outline"></ion-icon>&nbsp; Adicionar </a>
		</div>
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

	</form>
	</div>

  <div class="table-responsive">
    <table class="table table-condensed table-sm table-hover">
      <thead>
        <tr>
          <!-- <th data-toggle="tooltip" data-placement="top" title="id"><ion-icon name="key"></ion-icon></th> -->
          <th data-toggle="tooltip" data-placement="top" title="Cloro, valores entre 0.00-2.00 ppm ou mg/l">Cl</th>
          <th data-toggle="tooltip" data-placement="top" title="Dpd3, valores entre 0.00-2.00 ppm ou mg/l">DPD3</th>
          <th data-toggle="tooltip" data-placement="top" title="pH, valores entre 0(ácido)->7(neutro)<-14(alcalino)">pH</th>
          <th data-toggle="tooltip" data-placement="top" title="Temperatura em ºC">Temp</th>
				  <th data-toggle="tooltip" data-placement="top" title="Watts gastos pelas máquinas">Máquina</th>
					<th data-toggle="tooltip" data-placement="top" title="Correção dos valores de Cl/DPD3 em kg">Correção</th>
					<th data-toggle="tooltip" data-placement="top" title="Tipo">Tipo</th>
				  <th data-toggle="tooltip" data-placement="top" title="Data e hora">Data/Hora</th>
				  <th data-toggle="tooltip" data-placement="top" title="Funcionário">Responsável</th>
        </tr>
      </thead>

			<?php
			// main query

			// update pagination
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
				// $_SESSION['logs']['offset'] = 0;
			}

			$query .= "
			ORDER BY UNIX_TIMESTAMP(`logs`.`record_time`) ".$_SESSION['logs']['orderby'];

			// for export query in logs_export.php
			$_SESSION['logs']['export_query'] = $query;

			$query_start = microtime(true);
			$query_result = $conn->query($query);
			// error_log(print_r($query_result,true));

			$_SESSION["logs"]['num_rows'] = $query_result->num_rows;
			$_SESSION["logs"]['total_pages'] = round($_SESSION["logs"]['num_rows'] / $_SESSION["logs"]['limit']);
			$_SESSION["logs"]['upper_pages'] = $_SESSION["logs"]['total_pages'];
			$_SESSION["logs"]['lower_pages'] = $_SESSION["logs"]['upper_pages'] - $_SESSION["logs"]['limit'];
			// end update pagination

			$query .= "
			LIMIT ".$_SESSION['logs']['offset'].", ".$_SESSION['logs']['limit'];

			//error_log("query: ");
			//error_log(print_r($query,true));

			$query_result = $conn->query($query);
			error_log(print_r($query_result,true));
			$query_end = microtime(true);

			foreach ($query_result as $row) {
				// error_log(print_r($row,true));

				printf("<tbody><tr>");
				// printf('<td class="text-muted align-middle">%s</td>', $id);

				if( $row['cl'] < 1.0 ) printf('<td class="text-danger align-middle" >%s</td>', $row['cl']);
				elseif( $row['cl'] > 1.5 ) printf('<td class="text-warning align-middle" >%s</td>', $row['cl']);
				else printf('<td class="text-success align-middle" >%s</td>', $row['cl']);

				if( $row['cl']+$row['dpd3'] < $row['cl']-0.5 ) printf('<td class="text-warning align-middle" >%s</td>', $row['dpd3']);
				elseif( $row['cl']+$row['dpd3'] > $row['cl']+0.5 ) printf('<td class="text-danger align-middle" >%s</td>', $row['dpd3']);
				else printf('<td class="text-success align-middle">%s</td>', $row['dpd3']);

				if( $row['ph'] < 6.3 ) printf('<td class="text-danger align-middle" >%s</td>', $row['ph']);
				elseif( $row['ph'] > 7.1 ) printf('<td class="text-success align-middle" >%s</td>', $row['ph']);
				else printf('<td class="text-warning align-middle" >%s</td>', $row['ph']);

				if( $row['temp'] < 20 ) printf('<td class="text-danger align-middle" >%s</td>', $row['temp']);
				elseif( $row['temp'] > 25 ) printf('<td class="text-success align-middle" >%s</td>', $row['temp']);
				else printf('<td class="text-warning align-middle" >%s</td>', $row['temp']);

				if( $row['maq'] == '' ) {
					printf('<td class="text-muted align-middle">-</td>');
				} else {
					if( $row['maq'] < 450 ) printf('<td class="text-success align-middle" >%s</td>', $row['maq']);
					elseif( $row['maq'] > 500 ) printf('<td class="text-danger align-middle" >%s</td>', $row['maq']);
					else printf('<td class="text-warning align-middle" >%s</td>', $row['maq']);
				}

				printf('<td class="text-primary align-middle">%s</td>', $row['correction']);

				printf('<td class="text-muted align-middle">%s</td>', $row['name']);

				printf('<td class="text-muted align-middle">%s</td>', $row['record_time']);

				if( $_SESSION['user']['fullname'] == $row['fullname'])
					printf('<td class="font-weight-bold align-middle">%s</td>', $row['fullname']);
				else
					printf('<td class="text-muted align-middle">%s</td>', $row['fullname']);

				printf("</tr></tbody>");

	    } // end while

			$query_result->close();
			$conn->close();
			?>

    </table>
  </div>

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
		<div class="p-1 text-secondary">Tempo pesquisa: <b> <?php printf("%.6f", $query_end - $query_start); ?> </b> segundos, <b class="text-info"> <?php echo $_SESSION["logs"]['num_rows']; ?> </b> registos encontrados.</div>
		<form id="pages-logs-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">
				<ul class="pagination pagination-sm">

					<li class="page-item<?php if ($_SESSION['logs']['offset'] <= 0 ) echo ' disabled'; else echo ' active'; ?>">
						<button class="page-link" type="submit" name="submit" value="pages-logs-form-prev" aria-label="Anterior">
							<span aria-hidden="false">&laquo;</span>
							<span>Anterior</span>
						</button>
					</li>

						<?php
						$_SESSION["logs"]['actual_page'] = round(($_SESSION["logs"]['num_rows'] - $_SESSION['logs']['offset']) / $_SESSION["logs"]['limit']);
						for( $page_num = $_SESSION["logs"]['upper_pages'] ; $page_num > $_SESSION["logs"]['lower_pages'] && $page_num > 0 ; $page_num-- ){
						?>

						<li class="page-item<?php if ($_SESSION["logs"]['actual_page'] == $page_num) echo " active"; ?>">
						  <button class="page-link<?php if ($_SESSION["logs"]['actual_page'] == $page_num) echo " disabled"; ?>" type="submit" name="submit" value="jump_to_page_number-<?php echo $page_num; ?>" aria-label="jump_to_page_number-<?php echo $page_num; ?>">
						    <?php echo $page_num; ?>
						  </button>
						</li>

						<?php
						}
						?>

					<li class="page-item<?php if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] >= $_SESSION['logs']['num_rows'] ) echo ' disabled'; else echo ' active'; ?>">
						<button class="page-link" type="submit" name="submit" value="pages-logs-form-next" aria-label="Seguinte">
							<span >Seguinte</span>
							<span aria-hidden="false">&raquo;</span>
						</button>
					</li>

				</ul>
		</form>
	</div>

	<?php
	echo "<br><hr/><br>";
	echo $query;
	echo "<br><hr/><br>";
	var_dump($_SESSION); ?>

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
