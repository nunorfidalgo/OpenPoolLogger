<?php
session_start();
if ( !isset($_SESSION["user"]) ) header( "Location: index.php" );
$_SESSION["sidebar"] = "";
$_SESSION["menu"] = "about";
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

		<h1 class="h2"> Sobre </h1>

	</div>

  <div class="table-responsive">

    <div class="jumbotron">
      <h1 class="display-4">Obrigado por usar o OpenPoolLogger.</h1>
      <br>
      <p class="lead"> 
        Esta aplicação foi desenvolvida para guardar registos de valores dos parametros da água de que temos em piscinas, Jacuzzi's e outros.
        De momento os parametros que podemos registar são o Cloro, o DPD3, o pH, a temperatura, os watts gastos pela máquina, o valor de correção 
        do cloro (em kg), hora do registo, o respónsável que efecta o registo destes valores e o tipo a que os valores fazem referencia.
      </p>
      <br>
      <hr class="my-4">
      <p> Visite a página do projeto no GitHub:</p>
      <a class="btn btn-primary btn-lg" href="https://github.com/nunorfidalgo/OpenPoolLogger" role="button"> <ion-icon name="logo-github"></ion-icon> GitHub: OpenPoolLogger</a>
  	</div>

  </div>

</main>

</div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
