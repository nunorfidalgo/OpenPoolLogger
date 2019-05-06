<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "";
$_SESSION["menu"] = ""; // see on edit-employer-form

error_reporting(E_ALL & ~E_NOTICE);
require_once('config.php');

// print_r($_POST);
// die();

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$insert_count = 0;
$inputEid = $inputFullnameErr = $inputUsernameErr = $inputPasswordErr = $inputEmailErr = $inputAdminErr = "";
$msg = $inputFullname = $inputUsername = $inputPassword = $inputEmail = $inputAdmin = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "add-employer-form" ) {
	$_SESSION["menu"] = "add-employer-form";
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "add-employer-form-submit" ) {

	if (empty($_POST["inputFullname"])) {
		$inputFullnameErr = "* Nome completo é obrigatório.";
	} else {
    $inputFullname = test_input($_POST["inputFullname"]);
    if (!preg_match("/^[a-zA-Z ]*$/", $inputFullname) ) {
      $inputFullnameErr = "* Apenas letras e espaçoes permitidos.";
      $insert_count--;
    } else $insert_count++;
  }

	if (empty($_POST["inputUsername"])) {
		$inputUsernameErr = "* Username é obrigatório.";
	} else {
    $inputUsername = test_input($_POST["inputUsername"]);
    if (!preg_match("/^[a-zA-Z ]*$/", $inputUsername) ) {
      $inputUsernameErr = "* Apenas letras e espaçoes permitidos.";
      $insert_count--;
    } else $insert_count++;
  }

	if (empty($_POST["inputPassword"])) {
		$inputPasswordErr = "* Password é obrigatório.";
	} else {
    $inputPassword = test_input($_POST["inputPassword"]);
    if (!preg_match("/^[a-zA-Z ]*$/", $inputPassword) ) {
      $inputPasswordErr = "* Apenas letras e espaçoes permitidos.";
      $insert_count--;
    } else $insert_count++;
  }

	if (empty($_POST["inputEmail"])) {
		$inputEmailErr = "* Email é obrigatório.";
	} else {
		$inputEmail = test_input($_POST["inputEmail"]);
		// check if e-mail address is well-formed
		if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
		 $inputEmailErr = "* Formato email invalido.";
		 $insert_count--;
		} else $insert_count++;
	}

	if (!isset($_POST["inputAdmin"])) {
    $inputAdmin = "0";
		$insert_count++;
	} else {
    // $inputAdmin = test_input($_POST["$inputAdmin"]);
		$inputAdmin = "1";
		$insert_count++;
	}

	print_r($_POST);
	//
	// echo '<br>'.$inputFullname;
	// echo '<br>'.$inputUsername;
	// echo '<br>'.$inputPassword;
	// echo '<br>'.$inputEmail;
	// echo '<br>'.$inputAdmin;

	if ( $insert_count == 5  ) { // && (!empty($inputFullname) && !empty($inputUsername) && !empty($inputPassword) && !empty($inputEmail) && !empty($inputAdmin))
    $conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
    or die ('Could not connect to the database server' . mysqli_connect_error());

		$sql="INSERT INTO `employers` (`fid`, `fullname`, `username`, `password`, `email`, `admin`, `timedate`)
		VALUES (NULL, '".$inputFullname."', '".$inputUsername."', SHA1('".$inputPassword."'), '".$inputEmail."', '".$inputAdmin."', NOW() )";

echo '<br>'.$sql;
die();

    // $msg = $sql;

    if( $conn->query($sql) == TRUE ) {
      // $msg = '<div class="alert alert-success">
      //   <strong>Sucesso!</strong> Novo registo adicionado.
      // </div>';
			$_SESSION["msg"] = '<div class="alert alert-success alert-dismissible fade show" role="alert">
	      <strong>Sucesso!</strong> Novo funcionário adicionado.
	      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	        <span aria-hidden="true">&times;</span>
	      </button>
	    </div>';
// 			$inputFullnameErr = $inputUsernameErr = $inputPasswordErr = $inputEmailErr = $inputEmailErr = $inputAdminErr = "";
// 			$inputFullname = $inputUsername = $inputPassword = $inputEmail = $inputEmail = $inputAdmin = "";
// 			$insert_count = 0;
// 			$_POST["inputFullname"] = $_POST["inputUsername"] = $_POST["inputPassword"] = $_POST["inputEmail"] = $_POST["inputEmail"] = $_POST["inputAdmin"] = "";
// // filter_var_array($_POST, FILTER_SANITIZE_STRING);
    } else {
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
    // $msg = '<div class="alert alert-danger">
    //   <strong>Erro!</strong> Nada a registar? (insert_count: '.$insert_count.') <br> SQL: '.$sql.'
    // </div>';
		$_SESSION["msg"] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Erro!</strong> Nada a registar? (insert_count: '.$insert_count.')
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>';
  }
	header( "Location: employers.php" );
} // end add-employer-form-submit









if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "edit-employer-form" ) {
	$_SESSION["menu"] = "edit-employer-form";
	// echo "edit-employer-form";
	print_r($_POST);

	$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
		or die ('Could not connect to the database server' . mysqli_connect_error());

	$query="SELECT `employers`.`eid`, `employers`.`fullname`, `employers`.`username`, `employers`.`email`, `employers`.`admin` FROM `employers` WHERE `employers`.`eid`='$_POST[eid]'";

	$result = $conn->prepare($query);
	$result->execute();
	$result->bind_result($eid, $fullname, $username, $email, $admin);
  while ($result->fetch()) {
		$inputEid = $eid;
		$inputFullname = $fullname;
		$inputUsername = $username;
		$inputEmail = $email;
		$inputAdmin = $admin;
	}

// $result = $conn->query($query);
// $user = $result->fetch_assoc();; //$stmt->fetch();

	// header('Content-Type: application/json');
	// echo json_encode($user, JSON_PRETTY_PRINT);

	// echo "<br><br>";
	// echo $query;
	// echo "<br>";
	//
	// echo "<br>";
  // echo print_r($user);
	// // echo json_encode($user, JSON_PRETTY_PRINT);
 	// echo "<br><br>";
	// die();

} // end edit-employer-form




if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "edit-employer-form-submit" ) {

} // end edit-employer-form-submit
?>










<!doctype html>
<html lang="en">

<?php include_once('header.php'); ?>

<body>

<?php include_once('menu.php'); ?>

<div class="container-fluid">
<div class="row">

<?php include_once('sidebar.php'); ?>

<?php echo print_r( $_POST ); ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

		<h1 class="h2">
			<?php
			if( $_POST["submit"] == "edit-employer-form" ){
				echo "Alterar funcionário";
				// print_r($_SESSION["user"]);
			} else {
				echo "Novo funcionário";
			}
			?>
		</h1>
  </div>

	<form id="add-employer-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form">

		<div class="form-group row">
	    <label for="nome_completo" class="col-sm-2 col-form-label">Nome completo</label>
	    <div class="col-sm-10">
	       <input type="text" class="form-control" id="inputFullname" name="inputFullname" placeholder="Zé Manel" required value="<?php	echo $inputFullname; ?>" required>
          <span class="error"><?php echo $inputFullnameErr?></span>
				<!-- <div class="valid-tooltip">
				    Parece bem!
			    </div> -->
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="username" class="col-sm-2 col-form-label">Username</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="inputUsername" name="inputUsername" placeholder="ze123" required value="<?php	echo $inputUsername; ?>" required>
          <span class="error"><?php echo $inputUsernameErr?></span>
				<!-- <div class="valid-tooltip">
				    Parece bem!
			    </div> -->
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password" required value="<?php echo $inputPassword; ?>" required>
          <span class="error"><?php echo $inputPasswordErr?></span>
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
	    <div class="col-sm-10">
	      <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email" value="<?php echo $inputEmail; ?>" required>
				<!-- value="<?php	//if( $_POST['submit'] == 'edit-employer-form' ) echo $_SESSION['user']['email']; else echo $inputEmail; ?>"> -->
          <span class="error"><?php echo $inputEmailErr?></span>
	    </div>
	  </div>

	  <div class="form-group row">
	    <div class="col-sm-2">Checkbox</div>
	    <div class="col-sm-10">
	      <div class="form-check">

	        <input class="form-check-input" type="checkbox" id="inputAdmin" name="inputAdmin" <?php	if( $inputAdmin == 1 ) echo 'checked'; ?> >
						<!-- <?php	//if( $_POST['submit'] == 'edit-employer-form' && $_SESSION['user']['admin'] == 1 ) echo 'checked'; ?>> -->
	        <label class="form-check-label" for="inputAdmin">
	          Admin
	        </label>
	      </div>
	    </div>
	  </div>
	  <div class="form-group row">
	    <div class="col-sm-10">
	      <button name="submit" value="add-employer-form" type="submit" name="?" class="btn btn-primary">
					<?php
						if( $_POST['submit'] == 'edit-employer-form' )
							echo '<ion-icon name="save"></ion-icon>&nbsp; Guardar';
						else
							echo '<ion-icon name="add-circle-outline"></ion-icon>&nbsp;Adicionar';
					?>
				</button>
	    </div>
	  </div>
		<br>
    <?php
    // if( !empty($msg) ) {
    //   echo $msg;
		// 	$msg = "";
    // }
    ?>
	</form>

</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
