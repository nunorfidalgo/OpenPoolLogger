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
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

		<h1 class="h1"> Funcionários </h1>

    <?php
      if( isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ){
        echo $_SESSION["msg"];
      }
      echo $_SESSION["msg"] = "";

      if( $_SESSION['user']['admin'] == "1")
      echo '<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <a class="btn btn-primary" role="button" href="employers_form.php"><ion-icon name="add-circle-outline"></ion-icon>&nbsp;	Criar </a>
      </div>';
    ?>

	</div>

  <div class="table-responsive">
    <table class="table table-striped table-sm table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome completo</th>
          <th>Nome</th>
          <th>Email</th>
  				<th>Criado em</th>
          <th>Administrador</th>
          <?php
          if( $_SESSION['user']['admin'] == "1")
          echo '
            <th>
              <ion-icon name="build"></ion-icon>
            </form>
            </th>
            <th>
              <ion-icon name="trash"></ion-icon>
            </th>';
          ?>
        </tr>
      </thead>

<?php
require_once('config.php');
$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
  or die ('Could not connect to the database server' . mysqli_connect_error());

$query = "SELECT `employers`.`eid`, `employers`.`fullname`, `employers`.`username`, `employers`.`email`, `employers`.`admin`, `employers`.`record_datetime` FROM `employers`";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    // print_r($stmt);
    // die();
    $stmt->bind_result($eid, $fullname, $username, $email, $admin, $record_datetime);
    while ($stmt->fetch()) {
  		printf("<tbody><tr>");
      printf('<td class="text-muted"> %s </td>', $eid);
  		printf("<td>%s</td> <td>%s</td> <td>%s</td>", $fullname, $username, $email);
      printf('<td class="text-muted"> %s </td>', $record_datetime);

  		if($admin == "1")
  			printf('<td class="text-dark font-weight-bold"> Sim </td>');
  		else
  			printf('<td class="text-muted"> Não </td>');

      if( $_SESSION['user']['admin'] == "1")
      echo '
      <td class="text-muted">
        <form class="form" id="edit-employer-form" method="post" action="employers_form.php" role="form">
          <button type="submit" name="submit" value="edit-employer-form">
          <input hidden name="eid" value="'.$eid.'"/>
            <ion-icon name="build"></ion-icon>
          </button>
        </form>
      </td>
      <td class="text-muted">
        <ion-icon name="trash"></ion-icon>
      </td>
      ';
  		printf("</tr></tbody>");
    }
    $stmt->close();
}
$con->close();
?>

		</table>
	</div>

  <?php
  echo '<br>';
  echo $query;
  echo '<br><br>';
  print_r($_SESSION);
  ?>
</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
