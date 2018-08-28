<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
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

		<h1 class="h2"> Painel de Controle </h1>

		<div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2">
        <button class="btn btn-outline-secondary"> Exportar </button>
      </div>
    </div>
  </div>

  <h2> ? </h2>

  <div class="table-responsive">

    <!-- <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Cloro</th>
          <th>DPD3</th>
          <th>Ph</th>
          <th>Temperatura</th>
				  <th>Maq</th>
				  <th>data/hora</th>
				  <th>Responsável</th>
        </tr>
      </thead> -->

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

		<!-- </table> -->

	</div>
</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
