<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">

	<a class="navbar-brand" href="<?php echo $_SERVER["HTTP_REFERER"]; ?>"> OpenPoolLogger </a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
		<ul class="navbar-nav">
			<li class="nav-item">
        <a class="nav-link <?php if ($_SESSION['menu'] != "about" ) echo "active"; ?>" href="https://www.goodfit.pt"> <?php echo $_SESSION['settings']['entity']; ?> </a> <!-- <span class="sr-only">(current)</span> -->
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($_SESSION['menu'] == "about" ) echo "active"; ?>" href="about.php"> Sobre </a>
      </li>

		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
 	 		<?php echo $_SESSION['user']['fullname']; ?>
			</a>
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
				<a class="dropdown-item <?php if ($_SESSION['menu'] == "settings" ) echo "active"; ?>" href="settings.php"> Configurações </a>

				<form class="form" id="edit-employer-form" method="post" action="employers_form.php" role="form">
					<button class="dropdown-item <?php if ( $_SESSION['user']['eid'] == $inputEid) echo "active"; ?>" type="submit" name="submit" value="edit-employer-form"> Perfil </button>
					<input hidden name="eid" value="<?php echo $_SESSION['user']['eid']; ?>"/>
				</form>

				<form class="form" id="logout-form" method="post" action="logout.php" role="form">
			  	<button class="dropdown-item btn btn-primary btn-block" type="submit" href="#"> Sair </button>
				</form>

			</div>
		  </li>
		</ul>
	</div>

</nav>
