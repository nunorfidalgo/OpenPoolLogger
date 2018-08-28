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
		<h1 class="h2"> Registar novo funcionário </h1>
  </div>

	<form id="employeradd-form" method="post" action="employer_add.php" role="form">

		<div class="form-group row">
	    <label for="nome_completo" class="col-sm-2 col-form-label">Nome completo</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="inputFullname" name="inputFullname" placeholder="Zé Manel" required>
				<div class="valid-tooltip">
				    Parece bem!
			    </div>
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="username" class="col-sm-2 col-form-label">Username</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="inputUsername" name="inputUsername" placeholder="ze123" required>
				<div class="valid-tooltip">
				    Parece bem!
			    </div>
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password" required>
	    </div>
	  </div>

		<div class="form-group row">
	    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
	    <div class="col-sm-10">
	      <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email" required>
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
	      <button type="submit" class="btn btn-primary"> Adicionar </button>
	    </div>
	  </div>
	</form>

</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
