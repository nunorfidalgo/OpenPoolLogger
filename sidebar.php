<nav class="col-md-2 d-none d-md-block bg-light sidebar">
	<div class="sidebar-sticky">
		<ul class="nav flex-column">

		<li class="nav-item">
			<a class="nav-link <?php if ($_SESSION['sidebar'] == "logs" ) echo "active"; ?>" href="logs.php">
			<span data-feather="file"></span>
				Registos
			</a>
		</li>

		<li class="nav-item">
			<a class="nav-link  <?php if ($_SESSION['sidebar'] == "charts" ) echo "active"; ?>" href="charts.php">
			<span data-feather="bar-chart-2"></span>
				Gráficos
			</a>
		</li>

		<li class="nav-item">
			<a class="nav-link <?php if ($_SESSION['sidebar'] == "employers" ) echo "active"; ?>" href="employers.php">
			<span data-feather="users"></span>
				Funcionários
			</a>
		</li>

	</div>
</nav>
