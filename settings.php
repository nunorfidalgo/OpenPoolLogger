<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "";
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
		<h1 class="h2"> Configurações </h1>
  </div>

  <form>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputCity"> Entidade </label>
        <input type="text" class="form-control" id="inputCity"  placeholder="Entidade" value="<?php echo $_SESSION['settings']['entity']; ?>">
      </div>
      <div class="form-group col-md-6">
        <label for="inputState">Língua</label>
        <select id="inputState" class="form-control">
          <option value="1" selected>Português</option>
          <option value="2">Inglês</option>
        </select>
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
  </form>

<?php
// require_once('config.php');
// $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
// 	or die ('Could not connect to the database server' . mysqli_connect_error());
//
// // $query = "SELECT * from parametros";
// $query = "SELECT `parametros`.`pid`, `parametros`.`cloro`, `parametros`.`dpd3`, `parametros`.`ph`, `parametros`.`temperatura`, `parametros`.`maq`, `parametros`.`datahora`, `funcionarios`.`fullname`
// FROM `parametros`, `funcionarios`
// WHERE `parametros`.`responsavel` = `funcionarios`.`fid`";
//
// if ($stmt = $con->prepare($query)) {
//     $stmt->execute();
// 	//print_r($stmt);
//     $stmt->bind_result($id, $cloro, $dpd3, $ph, $temp, $maq, $datahora, $responsavel);
//     while ($stmt->fetch()) {
//         //printf("%s, %s\n", $field1, $field2);
// 		printf("<tbody><tr>");
//
// 		printf("<td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td>", $id, $cloro, $dpd3, $ph, $temp, $maq, $datahora, $responsavel);
//
// 		printf("</tr></tbody>");
//     }
//     $stmt->close();
// }
// $con->close();
?>


</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
