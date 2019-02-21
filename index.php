<!DOCTYPE html>
<html lang="es">
	<meta charset="utf-8">	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
	<head>
		<title>Planificar</title>
	</head>
	<link href="public/css/style.css" rel="stylesheet">
	<link href="libs/css/bootstrap.css" rel="stylesheet">
	<script src="libs/js/jquery-3.3.1.js"></script>
	<script src="libs/js/bootstrap.js"></script>
<body>
	<nav class="navbar navbar-dark bg-dark menu">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="#">Home</a>
			</li>
		</ul> 
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div id="left" class="col-2 p-1 fill-heigth">
				<?php include("public/php/panel_left.php"); ?>
			</div>
			<div id="right" class="col-10 p-1 bg-primary fill-heigth">
				<?php include("public/php/panel_right.php"); ?>
			</div>
		</div>
	</div>
</body>
</html>