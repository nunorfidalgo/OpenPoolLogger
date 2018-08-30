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
      <h1 class="display-4">Olá e bem-vindo ao OpenPoolLogger.</h1>
      <br>
      <p class="lead"> O âmbito desta aplicação Web será de guardar registos dos valores dos parametros da água de que temos nas piscinas, Jacuzzi's, entre outros.
        De momento os parametros guardados são o cloro, o DPD3, o pH, a temperatura, os watts, hora do registo e quem efectou o registo, sendo que de seguida podemos
        introduzir o valor de correção do cloro (em kg). Estes valores têm uma relação entre si, mostrados em cores, assim como os valores favoraveis que fazem o
        sistema da piscina funcionar da melhor forma.
        Após alguns registos temos acesso a gráficos que mostram de forma rápida a evolução destes parametros e como consequencia o estado geral da última semana, mês, etc...
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
