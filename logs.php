<?php
session_start();
if( !isset($_SESSION["user"]) ) header( "Location: index.php" );

require_once('config.php');
$page_start = microtime(true);

$_SESSION["sidebar"] = "logs";
$_SESSION["menu"] = "";

if (!isset($_SESSION['logs']['orderby'])) $_SESSION['logs']['orderby'] = "asc";
if (!isset($_SESSION['logs']['limit'])) $_SESSION['logs']['limit'] = 10;
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
	$_SESSION["logs"]['total_pages'] = round($_SESSION["logs"]['num_rows'] / $_SESSION["logs"]['limit']);
}

if( !isset($_SESSION["logs"]['total_pages'])) $_SESSION["logs"]['total_pages'] = round($_SESSION["logs"]['num_rows'] / $_SESSION["logs"]['limit']);
if( !isset($_SESSION["logs"]['upper_pages'])) $_SESSION["logs"]['upper_pages'] = $_SESSION["logs"]['total_pages'];
if( !isset($_SESSION["logs"]['lower_pages'])) $_SESSION["logs"]['lower_pages'] = $_SESSION["logs"]['upper_pages'] - $_SESSION["logs"]['limit'];

if( $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" && $_POST['inputLogType'] == "all" ) {
	// print_r($_POST); die();
	$sql = "SELECT * FROM `logs`;";
	$result = $conn->query($sql);
	$_SESSION["logs"]['num_rows'] = $result->num_rows;
	$_SESSION["logs"]['total_pages'] = round($_SESSION["logs"]['num_rows'] / $_SESSION["logs"]['limit']);
	$_SESSION["logs"]['upper_pages'] = $_SESSION["logs"]['total_pages'];
	$_SESSION["logs"]['lower_pages'] = $_SESSION["logs"]['upper_pages'] - $_SESSION["logs"]['limit'];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" && $_POST['inputLogType'] == "1" ) {
	// print_r($_POST); die();
	// $sql = "SELECT * FROM `logs` WHERE `logs`.`log_type` = ".$_SESSION['logs']['logtype'].";";
	$sql = "SELECT * FROM `logs` WHERE `logs`.`log_type` = ".$_POST['inputLogType'].";";
	$result = $conn->query($sql);
	$_SESSION["logs"]['num_rows'] = $result->num_rows;
	$_SESSION["logs"]['total_pages'] = round($_SESSION["logs"]['num_rows'] / $_SESSION["logs"]['limit']);
	$_SESSION["logs"]['upper_pages'] = $_SESSION["logs"]['total_pages'];
	$_SESSION["logs"]['lower_pages'] = $_SESSION["logs"]['upper_pages'] - $_SESSION["logs"]['limit'];
}


if( $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" && $_POST['inputLogType'] == "2" ) {
	// print_r($_POST); die();
	// $sql = "SELECT * FROM `logs` WHERE `logs`.`log_type` = ".$_SESSION['logs']['logtype'].";";
	$sql = "SELECT * FROM `logs` WHERE `logs`.`log_type` = ".$_POST['inputLogType'].";";
	$result = $conn->query($sql);
	$_SESSION["logs"]['num_rows'] = $result->num_rows;
	$_SESSION["logs"]['total_pages'] = round($_SESSION["logs"]['num_rows'] / $_SESSION["logs"]['limit']);
	$_SESSION["logs"]['upper_pages'] = $_SESSION["logs"]['total_pages'];
	$_SESSION["logs"]['lower_pages'] = $_SESSION["logs"]['upper_pages'] - $_SESSION["logs"]['limit'];
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-export" ) {
	echo "export... TODO!";
	die();
	// sleep(10);
	// header( "Location: logs.php" );
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logs-form-filter" ) {
	$_SESSION['logs']['logtype'] = $_POST['inputLogType'];
	$_SESSION['logs']['date'] = $_POST['inputDate'];
	$_SESSION['logs']['orderby'] = $_POST['inputOrderBy'];
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-next" ) {
	if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] < $_SESSION['logs']['num_rows'] ){
		$_SESSION['logs']['offset'] += $_SESSION['logs']['limit'];
		$_POST["submit"] = "";
	}
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "pages-logs-form-prev" ) {
	if ($_SESSION['logs']['offset'] > 0 ) {
		$_SESSION['logs']['offset'] -= $_SESSION['logs']['limit'];
		$_POST["submit"] = "";
		if( $_SESSION["logs"]['actual_page'] >= $_SESSION["logs"]['upper_pages'] ){
			$_SESSION["logs"]['upper_pages'] += $_SESSION["logs"]['limit'];
			$_SESSION["logs"]['lower_pages'] += $_SESSION["logs"]['limit'];
		}
	}
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "jump_to_page_number" ) {
	echo "test";
	print_r($_POST);
	die();
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
					<option value="desc" <?php if($_SESSION['logs']['orderby'] == "desc" ) echo "selected"; ?>> Data descendente </option>
					<option value="asc" <?php if($_SESSION['logs']['orderby'] == "asc" ) echo "selected"; ?>> Data ascendente </option>
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

  <div class="table-responsive needs-validation ">
    <table class="table table-condensed table-sm table-hover"><!-- table-striped -->
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
			SELECT `logs`.`lid`, `logs`.`cl`, `logs`.`dpd3`, `logs`.`ph`, `logs`.`temp`, `logs`.`maq`, `logs`.`record_time`, `employers`.`fullname`, `log_type`.`name`
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
// ORDER BY `logs`.`pid` ".$_SESSION['logs']['orderby']."
			$query .= "
			ORDER BY `logs`.`record_time` ".$_SESSION['logs']['orderby']."
			LIMIT ".$_SESSION['logs']['offset'].", ".$_SESSION['logs']['limit'];

			if ($stmt = $conn->prepare($query)) {

			$start = microtime(true);

	    $stmt->execute();

			$end = microtime(true);

			    $stmt->bind_result($id, $cl, $dpd3, $ph, $temp, $maq, $record_time, $log_owner, $type);

			    while ($stmt->fetch()) {
						printf("<tbody><tr>");

						printf('<td class="text-muted align-middle">%s</td>', $id);

						if( $cl < 1.0 ) printf('<td class="text-danger align-middle" >%s</td>', $cl);
						elseif( $cl > 1.5 ) printf('<td class="text-warning align-middle" >%s</td>', $cl);
						else printf('<td class="text-success align-middle" >%s</td>', $cl);

						if( $cl+$dpd3 < $cl-0.5 ) printf('<td class="text-warning align-middle" >%s</td>', $dpd3);
						elseif( $cl+$dpd3 > $cl+0.5 ) printf('<td class="text-danger align-middle" >%s</td>', $dpd3);
						else printf('<td class="text-success align-middle">%s</td>', $dpd3);

						if( $ph < 6.3 ) printf('<td class="text-danger align-middle" >%s</td>', $ph);
						elseif( $ph > 7.1 ) printf('<td class="text-success align-middle" >%s</td>', $ph);
						else printf('<td class="text-warning align-middle" >%s</td>', $ph);

						if( $temp < 20 ) printf('<td class="text-danger align-middle" >%s</td>', $temp);
						elseif( $temp > 25 ) printf('<td class="text-success align-middle" >%s</td>', $temp);
						else printf('<td class="text-warning align-middle" >%s</td>', $temp);

						if( $maq == '' ) {
							printf('<td class="text-muted align-middle">-</td>');
						} else {
							if( $maq < 450 ) printf('<td class="text-success align-middle" >%s</td>', $maq);
							elseif( $maq > 500 ) printf('<td class="text-danger align-middle" >%s</td>', $maq);
							else printf('<td class="text-warning align-middle" >%s</td>', $maq);
						}

						// printf('<td class="text-muted">%s</td>', "");
printf('<td class="text-muted align-middle">
    <!--<input type="number" class="form-control col-sm-6" id="inputClCorreted" aria-describedby="inputClCorreted" placeholder="Valor" readonly>-->
  	<select id="inputClCorreted" name="inputClCorreted" class="form-control">
		<option value="">1.0 kg</option>
		<option value="">1.5 kg</option>
		</select>
</td>');
// echo '
// <form>
//   <div class="form-group">
//
//     <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
//     <!-- <small id="emailHelp" class="form-text text-muted">We will never share your email with anyone else.</small> -->
//   </div>
//
//   <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
// </form>';

						printf('<td class="text-muted align-middle">%s</td>', $type);

						printf('<td class="text-muted align-middle">%s</td>', $record_time);

						if( $_SESSION['user']['fullname'] == $log_owner)
							printf('<td class="align-middle">%s</td>', $log_owner);
						else
							printf('<td class="text-muted align-middle">%s</td>', $log_owner);

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
				<label for="query-time">
				<?php
				// $difference = $end - $start;
				// printf("Tempo pesquisa: %.6f seconds.", $difference);
				// ?>
				</label>
			</div>
		</div>

		<form id="pages-logs-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">
		<div class="btn-group" role="group" aria-label="Button group with nested dropdown justify-content-end">
			<div class="btn-group" role="group">
				<ul class="pagination pagination-sm">

					<li class="page-item <?php if ($_SESSION['logs']['offset'] <= 0 ) echo 'disabled'; else echo 'active'; ?>">
						<button class="page-link" type="submit" name="submit" value="pages-logs-form-prev" aria-label="Anterior">
							<span aria-hidden="false">&laquo;</span>
							<span>Anterior</span>
						</button>
					</li>

					<?php
					$_SESSION["logs"]['actual_page'] = round(($_SESSION["logs"]['num_rows'] - $_SESSION['logs']['offset']) / $_SESSION["logs"]['limit']);


					if( $_SESSION["logs"]['actual_page'] <= $_SESSION["logs"]['lower_pages'] ){
						$_SESSION["logs"]['upper_pages'] -= $_SESSION["logs"]['limit'];
						$_SESSION["logs"]['lower_pages'] -= $_SESSION["logs"]['limit'];
					}

					for( $i = $_SESSION["logs"]['upper_pages'] ; $i > $_SESSION["logs"]['lower_pages'] && $i > 0 ; $i-- ){
					?>

					<li class="page-item <?php if ($_SESSION["logs"]['actual_page'] == $i) echo "active"; ?>">
						<input id="page_num" name="page_num" type="hidden" value="<?php echo $i; ?>"> </input>
						<button class="page-link" type="submit" name="submit" value="jump_to_page_number" aria-label="jump_to_page_number">
							<?php //echo $i; ?>
							<?php printf("%d", $i); ?>
						</button>
					</li>

					<?php
					}
					?>

					<li class="page-item <?php if ($_SESSION['logs']['offset'] + $_SESSION['logs']['limit'] >= $_SESSION['logs']['num_rows'] ) echo 'disabled'; else echo 'active'; ?>">
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
	echo 'num_rows: '.$_SESSION["logs"]['num_rows'].', total_pages: '.$_SESSION["logs"]['total_pages'].', upper_pages: '.$_SESSION["logs"]['upper_pages'].', lower_pages: '.$_SESSION["logs"]['lower_pages'].', page: '.$_SESSION["logs"]['actual_page'];
	echo '<br>';
	echo 'offset: '.$_SESSION['logs']['offset'].', limit: '.$_SESSION['logs']['limit'];
	echo '<br>';
	echo $query;
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
