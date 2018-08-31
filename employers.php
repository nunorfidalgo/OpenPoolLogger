<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "employers";
$_SESSION["menu"] = "";
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
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">

		<h1 class="h2"> Funcionários </h1>

    <?php
        if( isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ){
          echo $_SESSION["msg"];
        }
        echo $_SESSION["msg"] = "";
    ?>

    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
      <a class="btn btn-outline-secondary active" role="button" href="employers_form.php"><ion-icon name="add-circle-outline"></ion-icon>&nbsp;	Adicionar</a>
    </div>

	</div>

  <div class="table-responsive">
    <table class="table table-striped table-sm table-hover">
      <thead>
        <tr>
          <!-- <th>#</th> -->
          <th>Nome completo</th>
          <th>Nome</th>
          <th>Email</th>
  				<th>Criado em</th>
          <th>Administrador</th>
          <?php
          if( $_SESSION['user']['admin'] == "1")
          echo '<th><ion-icon name="build"></ion-icon></th>
                <th><ion-icon name="trash"></ion-icon></th>';
          ?>
        </tr>
      </thead>

<?php
require_once('config.php');
$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());
/*
SELECT `parametros`.`pid`, `parametros`.`cloro`, `parametros`.`dpd3`, `parametros`.`ph`, `parametros`.`temperatura`, `parametros`.`maq`, `parametros`.`datahora`, `funcionarios`.`fullname`
FROM `parametros`, `funcionarios`
WHERE `parametros`.`responsavel` = `funcionarios`.`fid`
*/

// $query = "SELECT `funcionarios`.`fid`, `funcionarios`.`fullname`, `funcionarios`.`username`, `funcionarios`.`email`, `funcionarios`.`admin`, `funcionarios`.`datahora` FROM `funcionarios`";
$query = "SELECT `employers`.`fid`, `employers`.`fullname`, `employers`.`username`, `employers`.`email`, `employers`.`admin`, `employers`.`timedate` FROM `employers`";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
	//print_r($stmt);
    $stmt->bind_result($fid, $fullname, $username, $email, $admin, $timedate);
    while ($stmt->fetch()) {
        //printf("%s, %s\n", $field1, $field2);
		printf("<tbody><tr>");

    // printf('<td class="text-muted"> %s </td>', $fid);
		printf("<td>%s</td> <td>%s</td> <td>%s</td>", $fullname, $username, $email);
    printf('<td class="text-muted"> %s </td>', $timedate);

		if($admin == "1")
			printf('<td class="text-muted"> Sim </td>');
		else
			printf('<td class="text-muted"> Não </td>');

    if( $_SESSION['user']['admin'] == "1")
    echo '<td class="text-muted"> <ion-icon name="build"></ion-icon> </td>
          <td class="text-muted"> <ion-icon name="trash"></ion-icon> </td>';

		printf("</tr></tbody>");
    }
    $stmt->close();
}
$con->close();
?>

		</table>
	</div>
</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
