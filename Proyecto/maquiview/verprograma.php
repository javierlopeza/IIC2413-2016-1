<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MaquiView</title>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
	<!-- Salir Button -->
	<div class="row">
		<div class="col-md-6 text-right">
			<form action="../index.html" style="margin-top:20px; right:100px">	
				<button type="submit" class="btn btn-danger btn-sm">Exit</button>
			</form>
		</div>
		<div class="col-md-6 text-left">
			<form action="./maquiview_home.php" style="margin-top:20px">	
				<button type="submit" class="btn btn-danger btn-sm">Back to all movies</button>
			</form>
		</div>
	</div>
	
	<?php
	# Datos
	$usuarioactual = $_COOKIE['usuario'];
	$tipoprograma = $_COOKIE['tipoprograma'];
	$idprograma = $_COOKIE['idprogramaactual'];
	
	# DB Connection
	try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
	catch(PDOException $e){echo $e -> getMessage();}

	# Query saldo usuario actual.
	$query = "SELECT saldo FROM Usuario WHERE Usuario.username = '$usuarioactual';";
	$result = $db -> prepare($query);
	$result -> execute();
	$saldos = $result -> fetchAll();
	$saldo = (float)$saldos[0][0];

	# Se revisa si es una pelicula o un capitulo.
	if ($tipoprograma == 'pelicula') {
		# Se verifica que tiene el saldo disponible.
		if ($saldo >= 1) {
			# Tiene creditos disponibles, pagina de exito.
			echo '
				<!-- Title -->
				<div class="row">
					<div class="col-md-12 text-center">
						<h1>¡Disfruta tu programa!</h1>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12 text-center">
						<h3>Tu saldo actual es de ';
			echo ($saldo - 1.0);
			echo ' créditos
						</h3>
					</div>
				</div>
				';
			# Disminución del saldo por programa visto.
			$query = "UPDATE Usuario SET saldo = saldo - 1 WHERE username = '$usuarioactual';";
			$result = $db -> prepare($query);
			$result -> execute();

			# Agregar a VistoPrograma.
			$query = "INSERT INTO VistoPrograma VALUES('$usuarioactual', '$idprograma', (SELECT date('now')));";
			$result = $db -> prepare($query);
			$result -> execute();
		}
		else {
			# No tiene creditos disponibles, pagina de error.
			echo '
				<!-- Title -->
				<div class="row">
					<div class="col-md-12 text-center">
						<h1>¡No tienes saldo suficiente!</h1>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12 text-center">
						<h3>Tu saldo actual es de ';
			echo ($saldo);
			echo ' créditos
						</h3>
					</div>
				</div>
				';
		}
	}
	if ($tipoprograma == 'capitulo') {
		# Se verifica que tiene el saldo disponible.
		if ($saldo >= 0.25) {
			# Tiene creditos disponibles, pagina de exito.
			echo '
				<!-- Title -->
				<div class="row">
					<div class="col-md-12 text-center">
						<h1>¡Disfruta tu programa!</h1>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12 text-center">
						<h3>Tu saldo actual es de ';
			echo ($saldo - 0.25);
			echo ' créditos
						</h3>
					</div>
				</div>
				';

			# Disminución del saldo por programa visto.
			$query = "UPDATE Usuario SET saldo = saldo - 0.25 WHERE username = '$usuarioactual';";
			$result = $db -> prepare($query);
			$result -> execute();

			# Agregar a VistoPrograma.
			$query = "INSERT INTO VistoPrograma VALUES('$usuarioactual', '$idprograma', (SELECT date('now')));";
			$result = $db -> prepare($query);
			$result -> execute();

		}
		else {
			# No tiene creditos disponibles, pagina de error.
			echo '
				<!-- Title -->
				<div class="row">
					<div class="col-md-12 text-center">
						<h1>¡No tienes saldo suficiente!</h1>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12 text-center">
						<h3>Tu saldo actual es de ';
			echo ($saldo);
			echo ' créditos
						</h3>
					</div>
				</div>
				';
			
		}
	}	
	?>
</body>
</html>