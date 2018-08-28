<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "";


error_reporting(E_ALL & ~E_NOTICE);
require_once('config.php');

$insert_count = 0;
$inputFullnameErr = $inputUsernameErr = $inputPasswordErr = $inputEmailErr = $inputEmailErr = $inputAdminErr = "";
$msg = $inputFullname = $inputUsername = $inputPassword = $inputEmail = $inputEmail = $inputAdmin = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (empty($_POST["$inputFullname"])) {
		$inputFullnameErr = "* Nome completo é obrigatório.";
	}
  //   $inputCl = test_input($_POST["$inputFullname"]);
  //   if (!preg_match("/^[a-zA-Z ]*$/", $inputFullname) ) {
  //     $inputFullnameErr = "* Apenas letras e espaçoes permitidos.";
  //     $insert_count--;
  //   } else $insert_count++;
  // }

	if (empty($_POST["$inputUsername"])) {
		$inputUsernameErr = "* Username é obrigatório.";
	}
  //   $inputCl = test_input($_POST["$inputUsername"]);
  //   if (!preg_match("/^[a-zA-Z ]*$/", $inputUsername) ) {
  //     $inputUsernameErr = "* Apenas letras e espaçoes permitidos.";
  //     $insert_count--;
  //   } else $insert_count++;
  // }

	if (empty($_POST["$inputPassword"])) {
		$inputPasswordErr = "* Password é obrigatório.";
	}
  //   $inputCl = test_input($_POST["$inputPassword"]);
  //   if (!preg_match("/^[a-zA-Z ]*$/", $inputPassword) ) {
  //     $inputPasswordErr = "* Apenas letras e espaçoes permitidos.";
  //     $insert_count--;
  //   } else $insert_count++;
  // }

	if (empty($_POST["$inputEmail"])) {
		$inputEmailErr = "* Email é obrigatório.";
	}
	// } else {
	// 	$inputEmail = test_input($_POST["$inputEmail"]);
	// 	// check if e-mail address is well-formed
	// 	if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
	// 	 $inputEmailErr = "* Formato email invalido.";
	// 	 $insert_count--;
	// 	} else $insert_count++;
	// }

	if (empty($_POST["$inputAdmin"])) {
    $inputAdmin = "0";
		$insert_count++;
	} else {
    $inputAdmin = test_input($_POST["$inputAdmin"]);
		$insert_count++;
		$inputAdmin = "1";
	}

	if ( $insert_count == 4 && ( !empty($inputFullname) || !empty($inputUsername) || !empty($inputPassword) || !empty($inputEmail) || !empty($inputAdmin) )) {
    $conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
    or die ('Could not connect to the database server' . mysqli_connect_error());

		$sql="INSERT INTO `employers` (`fid`, `fullname`, `username`, `password`, `email`, `admin`, `timedate`)
		VALUES (NULL, '".$inputFullname."', '".$inputUsername."', SHA1('".$inputPassword."'), '".$inputEmail."', '".$inputAdmin."', NOW() )";

    $msg = $sql;

    if( $conn->query($sql) == TRUE ) {
      $msg = '<div class="alert alert-success">
        <strong>Sucesso!</strong> Novo registo adicionado.
      </div>';
			$inputFullnameErr = $inputUsernameErr = $inputPasswordErr = $inputEmailErr = $inputEmailErr = $inputAdminErr = "";
			$inputFullname = $inputUsername = $inputPassword = $inputEmail = $inputEmail = $inputAdmin = "";
    } else {
      $msg = '<div class="alert alert-warning">
        <strong>Atenção!</strong> Ocorreu um erro: '.$sql.'<br>'. $conn->error.'
      </div>';
    }
    $conn->close();
  } else {
    $msg = '<div class="alert alert-danger">
      <strong>Erro!</strong> Nada a registar? (insert_count: '.$insert_count.') <br> SQL: '.$sql.'
    </div>';
  }
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
		<h1 class="h2"> Registar novo funcionário </h1>
  </div>

	<form id="employeradd-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">

		<div class="form-group row">
	    <label for="nome_completo" class="col-sm-2 col-form-label">Nome completo</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="inputFullname" name="inputFullname" placeholder="Zé Manel" required value="<?php echo $inputUsername;?>">
          <span class="error"><?php echo $inputFullnameErr?></span>
				<!-- <div class="valid-tooltip">
				    Parece bem!
			    </div> -->
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="username" class="col-sm-2 col-form-label">Username</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="inputUsername" name="inputUsername" placeholder="ze123" required value="<?php echo $inputUsername;?>">
          <span class="error"><?php echo $inputUsernameErr?></span>
				<!-- <div class="valid-tooltip">
				    Parece bem!
			    </div> -->
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password" required value="<?php echo $inputPassword;?>">
          <span class="error"><?php echo $inputPasswordErr?></span>
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
	    <div class="col-sm-10">
	      <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email" required value="<?php echo $inputEmail;?>">
          <span class="error"><?php echo $inputEmailErr?></span>
	    </div>
	  </div>

	  <div class="form-group row">
	    <div class="col-sm-2">Checkbox</div>
	    <div class="col-sm-10">
	      <div class="form-check">
	        <input class="form-check-input" type="checkbox" id="inputAdmin" name="inputAdmin">
	        <label class="form-check-label" for="admin">
	          Admin
	        </label>
	      </div>
	    </div>
	  </div>
	  <div class="form-group row">
	    <div class="col-sm-10">
	      <button type="submit" class="btn btn-primary"> <ion-icon name="add-circle-outline"></ion-icon>&nbsp;Adicionar </button>
	    </div>
	  </div>
		<br>
    <br>
    <?php
    if( !empty($msg) ) {
      echo $msg;
    }
    ?>
	</form>

</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
