<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "";
$_SESSION["menu"] = "";

error_reporting(E_ALL & ~E_NOTICE);
require_once('config.php');

$inputClErr = $inputDpd3Err = $inputPhErr = $inputTempErr = $inputMaqErr = $inputLogTypeErr = "";
$msg = $inputCl = $inputDpd3 = $inputPh = $inputTemp = $inputMaq = $inputLogType = "";
$insert_count = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "logadd-form") {

  if (!empty($_POST["inputCl"])) {
    $inputCl = test_input($_POST["inputCl"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^(?:[0-9]{1})\.\d{2}$/", $inputCl) ) { // /^[0-2]+(\.[0-9]{1,2})?$/
      $inputClErr = "* Apenas números com formato: 1.55";
      $insert_count--;
    } elseif ( is_float($inputCl) && $inputCl <= 0.01 || $inputCl >= 2.00) {
      $inputClErr = "* Apenas valores compreendidos entre: 0.01 e 2.00";
      $insert_count--;
    } else $insert_count++;
  }

  if (!empty($_POST["inputDpd3"])) {
    $inputDpd3 = test_input($_POST["inputDpd3"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^(?:[0-9]{1})\.\d{2}$/", $inputDpd3)) { // /^[0-2]+(\.[0-9]{1,2})?$/
      $inputDpd3Err = "* Apenas números com formato: 0.55";
      $insert_count--;
    } elseif ( is_float($inputDpd3) && $inputDpd3 <= 0.01 || $inputDpd3 >= 2.00) {
      $inputDpd3Err = "* Apenas valores compreendidos entre: 0.01 e 2.00";
      $insert_count--;
    } else $insert_count++;
  }

  if (!empty($_POST["inputPh"])) {
    $inputPh = test_input($_POST["inputPh"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^(?:[0-9]{1})\.\d{2}$/", $inputPh)) { // /^[0-9]+(\.[0-9]{1,2})?$/
      $inputPhErr = "* Apenas números com formato: 7.21";
      $insert_count--;
    } elseif ( is_float($inputPh) && $inputPh <= 0.01 || $inputPh >= 14.00) {
      $inputPhErr = "* Apenas valores compreendidos entre: 0.01 e 14.00";
      $insert_count--;
    } else $insert_count++;
  }

  if (!empty($_POST["inputTemp"])) {
    $inputTemp = test_input($_POST["inputTemp"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^(?:[0-9]{1})\.\d{2}$/", $inputTemp)) { // /^[0-9]+(\.[0-9]{2,2})?$/
      $inputTempErr = "* Apenas números com formato: 25.19";
      $insert_count--;
    } elseif ( is_float($inputTemp) && $inputTemp <= 0.01 || $inputTemp >= 50.00) {
      $inputTempErr = "* Apenas valores compreendidos entre: 0.01 e 50.00";
      $insert_count--;
    } else $insert_count++;
  }

  if (!empty($_POST["inputMaq"])) {
    $inputMaq = test_input($_POST["inputMaq"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[0-9]{3}?$/", $inputMaq)) {
      $inputMaqErr = "* Apenas números com formato: 450";
      $insert_count--;
    } elseif ( is_int($inputMaq) && $inputMaq <= 1 || $inputMaq >= 999) {
      $inputMaqErr = "* Apenas valores compreendidos entre: 0 e 999";
      $insert_count--;
    } else $insert_count++;
  }

  if (!empty($_POST["inputLogType"])) {
    $inputLogType = test_input($_POST["inputLogType"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[0-9]{1}?$/", $inputLogType)) {
      $inputLogTypeErr = "* Apenas números com formato: 8";
      $insert_count--;
    } elseif ( is_int($inputLogType) && $inputLogType <= 1 || $inputLogType >= 9) {
      $inputLogTypeErr = "* Escolha uma opção..."; //"Apenas valores compreendidos entre: 1 e 9";
      $insert_count--;
    } else $insert_count++;
  }

  if ( $insert_count >= 2 && ( !empty($inputCl) || !empty($inputDpd3) || !empty($inputPh) || !empty($inputTemp) || !empty($inputMaq) || !empty($inputLogType) )) {
    $conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
    or die ('Could not connect to the database server' . mysqli_connect_error());

    $sql="INSERT INTO `logs` (`pid`, `cl`, `dpd3`, `ph`, `temp`, `maq`, `timedate`, `log_owner`, `log_type`)
    VALUES (NULL, '".$inputCl."', '".$inputDpd3."', '".$inputPh."', '".$inputTemp."', '".$inputMaq."', NOW(), '".$_SESSION['user']['fid']."', '".$inputLogType."')";

    // echo $sql;
    // echo "<br><br>";

    if( $conn->query($sql) == TRUE ) {
      // $msg = '<font class="text-success"> Novo registo adicionado com sucesso! </font>';
      // $msg = '<div class="alert alert-success">
      //   <strong>Sucesso!</strong> Novo registo adicionado.
      // </div>';
      $_SESSION["msg"] = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sucesso!</strong> Novo registo adicionado.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
      // $inputClErr = $inputDpd3Err = $inputPhErr = $inputTempErr = $inputMaqErr = $inputLogTypeErr = "";
      // $msg = $inputCl = $inputDpd3 = $inputPh = $inputTemp = $inputMaq = $inputLogType = "";
      // $insert_count = 0;
    } else {
      // $msg = '<font class="text-danger"> Ocorreu um erro: '.$sql.'<br>'. $conn->error.' </font>';
      // $msg = '<div class="alert alert-warning">
      //   <strong>Atenção!</strong> Ocorreu um erro: '.$sql.'<br>'. $conn->error.'
      // </div>';
      $_SESSION["msg"] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
	      <strong>Ups!</strong> Ocorreu um erro: '.$sql.'<br>'. $conn->error.'
	      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	        <span aria-hidden="true">&times;</span>
	      </button>
	    </div>';
    }
    $conn->close();
  } else {
    // $msg = '<font class="text-danger"> Nada a registar? (insert_count: '.$insert_count.')</font>';
    // $msg = '<div class="alert alert-danger">
    //   <strong>Erro!</strong> Nada a registar? (insert_count: '.$insert_count.')
    // </div>';
    $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Erro!</strong> Nada a registar? (insert_count: '.$insert_count.')
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>';
  }
	header( "Location: logs.php" );
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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
		<h1 class="h2"> Adicionar novo registo </h1>
  </div>

  <form class="form" id="logadd-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputCl">Cloro</label>
        <input type="number" min="0.01" max="2.00" step="0.01" class="form-control" id="inputCl" name="inputCl" placeholder="1.55" value="<?php echo $inputCl;?>">
          <span class="error"><?php echo $inputClErr;?></span>
      </div>
      <div class="form-group col-md-6">
        <label for="inputDpd3">DPD3</label>
        <input type="number" min="0.01" max="2.00" step="0.01" class="form-control" id="inputDpd3" name="inputDpd3" placeholder="0.76" value="<?php echo $inputDpd3;?>">
          <span class="error"><?php echo $inputDpd3Err;?></span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputPh">Ph</label>
        <input type="number" min="0.01" max="14.00" step="0.01" class="form-control" id="inputPh" name="inputPh" placeholder="7.21" value="<?php echo $inputPh;?>">
          <span class="error"><?php echo $inputPhErr;?></span>
      </div>
      <div class="form-group col-md-6">
        <label for="inputTemp">Temperatura</label>
        <input type="number" min="0.01" max="50.00" step="0.01" class="form-control" id="inputTemp" name="inputTemp" placeholder="25.15" value="<?php echo $inputTemp;?>">
          <span class="error"><?php echo $inputTempErr;?></span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputMaq">Maq</label>
        <input type="number" min="1" max="999" step="1" class="form-control" id="inputMaq" name="inputMaq" placeholder="450" value="<?php echo $inputMaq;?>">
          <span class="error"><?php echo $inputMaqErr;?></span>
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
        <span class="error"><?php echo $inputLogTypeErr;?></span>
			</div>

    </div>

    <button class="btn btn-primary" name="submit" value="logadd-form" type="submit">Adicionar</button>
    <br>
    <br>
    <?php
    // if( !empty($msg) ) {
    //   echo $msg;
    // }
    ?>
  </form>

</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
