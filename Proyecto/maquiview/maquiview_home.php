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
		<div class="col-md-12 text-center">
			<form action="../index.html" style="width:40%; margin:0 auto; margin-top:20px;">	
				<button type="submit" class="btn btn-danger btn-sm">Exit</button>
			</form>
		</div>
	</div>
	<!-- Title -->
	<div class="row">
		<div class="col-md-12 text-center">
			<h1>MaquiView</h1>
			<?php
			$usuario = $_COOKIE['usuario'];
			echo "<h4>Usuario Conectado: $usuario</h4>";
			?>
		</div>
	</div>
	<hr>
	<div class="row">
		<!-- 5 Top Button -->
		<div class="col-md-6 text-center" style="left:100px;">
			<form action="5masvistos.php" style="width:40%; margin:0 auto; margin-top:20px;">	
				<button type="submit" class="btn btn-success btn-lg">Ver Top 5</button>
			</form>
		</div>
		<!-- Filter Button -->
		<div class="col-md-6 text-center" style="right:100px;">
			<form action="busqueda.php" style="width:40%; margin:0 auto; margin-top:20px;">	
				<button type="submit" class="btn btn-success btn-lg">Búsqueda</button>
			</form>
		</div>
	</div>
	<hr>

	<div class="row">
		<!-- Peliculas Iniciales -->
		<div class="col-md-5 col-md-offset-1 text-center">
			<h3 style="margin-bottom:20px;">Películas</h3>
			<?php 
			# DB Connection
			try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
			catch(PDOException $e){echo $e -> getMessage();}

			# Query Todas Las Peliculas
			$query = "SELECT Programa.nombre, Programa.id FROM Programa, Pelicula WHERE Programa.id = Pelicula.id;";

			$result = $db -> prepare($query);
			$result -> execute();
			$peliculas = $result -> fetchAll();
			foreach ($peliculas as $p) {
				echo "<form class='form-horizontal' style='width:50%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$p[1]' class='col-sm-8 control-label'>$p[0]</label>
						    <div class='col-sm-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$p[1]' name='idprograma' value='$p[1]'>Info</button>
						    </div>
						</div>
					</form>";
			}
			?>
		</div>

		<!-- Capítulos Iniciales -->
		<div class="col-md-5 text-center">
			<h3 style="margin-bottom:20px;">Capítulos</h3>
			<?php 
			# DB Connection
			try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
			catch(PDOException $e){echo $e -> getMessage();}

			# Query
			$query_capitulos = "SELECT Programa.nombre, Programa.id, Serie.nombre FROM Programa, EnTemporada, Temporada, EnSerie, Serie WHERE Programa.id = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie;";
			$result = $db -> prepare($query_capitulos);
			$result -> execute();
			$capitulos = $result -> fetchAll();
			foreach ($capitulos as $p) {
				echo "<form class='form-horizontal' style='width:50%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$p[1]' class='col-sm-8 control-label'>$p[2]: $p[0]</label>
						    <div class='col-sm-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$p[1]' name='idprograma' value='$p[1]'>Info</button>
						    </div>
						</div>
					</form>";
			}
			?>			
		</div>
	</div>

</body>
</html>