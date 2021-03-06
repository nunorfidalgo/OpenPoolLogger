<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["menu"] = "settings";
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
        <input type="text" class="form-control" id="inputEntity"  placeholder="Entidade" value="<?php echo $_SESSION['settings']['entity']; ?>">
      </div>
			<div class="form-group col-md-6">
        <label for="inputCity"> Website </label>
        <input type="text" class="form-control" id="inputEntityUrl"  placeholder="Website" value="<?php echo "em falta..."; //$_SESSION['settings']['inputEntityUrl']; ?>">
      </div>
    </div>

		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="inputState">Língua</label>
				<select id="inputState" class="form-control">
					<option value="1" selected>Português</option>
					<option value="2">Inglês</option>
				</select>
			</div>
		</div>

		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="inputCity"> Adicionar Tipo </label>
				<input type="text" class="form-control" id="inputCity"  placeholder="Novo tipo" value="<?php echo "em falta...";//echo $_SESSION['settings']['entity']; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="inputLogType">Tipo</label>
				<select id="inputLogType" name="inputLogType" class="form-control" required>
					<?php
					require_once('config.php');
					$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
						or die ('Could not connect to the database server' . mysqli_connect_error());
					$sql="SELECT `log_type`.`tid`, `log_type`.`name` FROM `log_type`";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						echo "<option selected>Escolha...</option>";
						while(	$row = $result->fetch_assoc() ) {
							echo "<option value=".$row["tid"].">".$row["name"]."</option>";
						}
					} else {
							echo "<option>Sem dados...</option>";
					}
					$conn->close();
				?>
				</select>
			</div>
		</div>

		<div class="form-group row">
			<label for="inputEmail3" class="col-sm-2 col-form-label"> Cloro </label>
			<div class="col-sm-2">
			  <input type="email" class="form-control" id="inputEmail3" placeholder="Min">
			</div>
			<div class="col-sm-2">
			 <input type="email" class="form-control" id="inputEmail3" placeholder="Max">
			</div>
		</div>
	 <div class="form-group row">
		 <label for="inputEmail3" class="col-sm-2 col-form-label"> DPD3 </label>
		 <div class="col-sm-2">
			 <input type="email" class="form-control" id="inputEmail3" placeholder="Min">
		 </div>
		 	<div class="col-sm-2">
				<input type="email" class="form-control" id="inputEmail3" placeholder="Max">
			</div>
	  </div>

		<div class="form-group row">
			<label for="inputEmail3" class="col-sm-2 col-form-label"> pH </label>
			<div class="col-sm-2">
				<input type="email" class="form-control" id="inputEmail3" placeholder="Min">
			</div>
		 <div class="col-sm-2">
			 <input type="email" class="form-control" id="inputEmail3" placeholder="Max">
		 </div>
	 </div>

	 <div class="form-group row">
		 <label for="inputEmail3" class="col-sm-2 col-form-label"> Temperatura </label>
		 <div class="col-sm-2">
			 <input type="email" class="form-control" id="inputEmail3" placeholder="Min">
		 </div>
		<div class="col-sm-2">
			<input type="email" class="form-control" id="inputEmail3" placeholder="Max">
		</div>
	</div>

	<div class="form-group row">
		<label for="inputEmail3" class="col-sm-2 col-form-label"> Maq </label>
		<div class="col-sm-2">
			<input type="email" class="form-control" id="inputEmail3" placeholder="Min">
		</div>
		<div class="col-sm-2">
			<input type="email" class="form-control" id="inputEmail3" placeholder="Max">
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
