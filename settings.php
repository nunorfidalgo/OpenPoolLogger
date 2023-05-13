<?php
require_once('config.php');
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
      <div class="col-md-4 mb-3">
        <label for="inputCity"> Entidade </label>
        <input type="text" class="form-control" id="inputEntity"  placeholder="Entidade" value="<?php echo $_SESSION['settings']['entity']; ?>">
      </div>
			<div class="col-md-4 mb-3">
        <label for="inputCity"> Website </label>
        <input type="text" class="form-control" id="inputEntityUrl"  placeholder="Website" value="<?php echo $_SESSION['settings']['entity_url']; ?>">
      </div>
			<div class="col-md-4 mb-3">
				<label for="inputState">Língua</label>
				<select id="inputState" class="form-control">
					<option value="1" selected>Português</option>
					<option value="2">Inglês</option>
				</select>
			</div>
		</div>
	<button type="submit" class="btn btn-primary">Salvar</button>
	</form>

<br>
<hr/>
<br>

	<form>
		<div class="form-row">

			<div class="col-md-4 mb-3">
				<label for="inputCity"> Novo tipo </label>
				<input type="text" class="form-control" id="inputCity"  placeholder="Novo tipo" value="<?php echo "Adicionar novo tipo..."; ?>">
			</div>

			<div class="col-md-4 mb-3">
				<label for="inputLogType">Tipo</label>
				<select id="inputLogType" name="inputLogType" class="form-control" required>

					<?php
					$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
						or die ('Could not connect to the database server' . mysqli_connect_error());
					$sql="SELECT `log_type`.`tid`, `log_type`.`name` FROM `log_type`";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						//echo "<option selected>Escolha...</option>";
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

	<button type="submit" class="btn btn-primary">Salvar</button>
	</form>

<br>
<hr/>
<br>

	<form>

		<div class="form-row">

			<?php
			$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
				or die ('Could not connect to the database server' . mysqli_connect_error());
			$sql="SELECT `settings_param`.`param`, `settings_param`.`param_min`, `settings_param`.`param_max` FROM `settings_param`";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($settings_param = $result->fetch_assoc()){

					echo '


					<div class="col-md-4 mb-3">
						<label for="validationDefault01">Parametro</label>
						<input type="text" class="form-control" id="validationDefault01" value="'.$settings_param['param'].'" required>
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationDefault02">Min</label>
						<input type="text" class="form-control" id="validationDefault02" value="'.$settings_param['param_min'].'" required>
					</div>
					<div class="col-md-4 mb-3">
						<label for="validationDefault02">Max</label>
						<input type="text" class="form-control" id="validationDefault02" value="'.$settings_param['param_max'].'" required>
					</div>


					';

					// echo '<div class="col-md-4 mb-3>
					// 	<label for="inputEmail3" class="col-sm-2 col-form-label"> '.$settings_param['param'].' </label>
					// 	<div class="col-sm-2">
					// 		<input type="email" class="form-control" id="inputEmail3" placeholder="Min" value="'.$settings_param['param_min'].'">
					// 	</div>
					// 	<div class="col-sm-2">
					// 		<input type="email" class="form-control" id="inputEmail3" placeholder="Max" value="'.$settings_param['param_max'].'">
					// 	</div>
					// </div>';
				}
			}
			$conn->close();
			?>
		</div>

	<button type="submit" class="btn btn-primary">Salvar</button>
  </form>

<?php
?>

<br>
<hr/>
<br>


</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
