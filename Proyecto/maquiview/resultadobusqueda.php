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
			<form action="../index.html" style="margin-top:20px; right:100px;">	
				<button type="submit" class="btn btn-danger btn-sm">Exit</button>
			</form>
		</div>
		<div class="col-md-6 text-left">
			<form action="./maquiview_home.php" style="margin-top:20px">	
				<button type="submit" class="btn btn-danger btn-sm">Back to all movies</button>
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

	<!-- Title Resultado Busqueda -->
	<div class="row">
		<div class="col-md-12 text-center">
			<h3>Resultado Búsqueda</h3>
			<?php 
			# Form Values
			$nombre_programa = $_POST['nombre-programa'];
			$genero = $_POST['genero'];
			$year = $_POST['year'];

			# DB Connection
			try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
			catch(PDOException $e){echo $e -> getMessage();}

			# (1) Busqueda solo por nombre.
			if ($nombre_programa != "" and $genero == "" and $year == "0") {
				$query = "(SELECT Programa.nombre, Programa.id, null, Programa.fechaestreno AS fecha FROM Programa, Pelicula WHERE Programa.id = Pelicula.id AND Programa.nombre LIKE '%$nombre_programa%' UNION SELECT Programa.nombre, Programa.id, Serie.nombre, Programa.fechaestreno AS fecha FROM Programa, EnTemporada, Temporada, EnSerie, Serie WHERE Programa.id = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie AND Serie.nombre LIKE '%$nombre_programa%') ORDER BY fecha DESC;";
				$result = $db -> prepare($query);
				$result -> execute();
				$programas = $result -> fetchAll();
				if (count($programas) == 0) {
					echo "<h4>Ningún resultado.</h4>";
				}
				foreach ($programas as $p) {
					$nombre = $p[0];
					$idprograma = $p[1];
					$serie = $p[2];
					if ($serie != "") {
						$nombre = $serie.": ".$nombre;
					} 
					echo "<form class='form-horizontal' style='width:35%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$idprograma' class='col-md-8 control-label'>$nombre</label>
						    <div class='col-md-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$idprograma' name='idprograma' value='$idprograma'>Info</button>
						    </div>
						</div>
					</form>";
				}
			}

			# (2) Busqueda solo por genero.
			if ($nombre_programa == "" and $genero != "" and $year == "0") {
				$query = "(SELECT Programa.nombre, Programa.id, null, Programa.fechaestreno AS fecha FROM Programa, Pelicula, PeliculaPoseeGenero WHERE Programa.id = Pelicula.id AND Pelicula.id = PeliculaPoseeGenero.id_pelicula AND PeliculaPoseeGenero.nombre_genero = '$genero' UNION SELECT Programa.nombre, Programa.id, Serie.nombre, Programa.fechaestreno AS fecha FROM Programa, EnTemporada, Temporada, EnSerie, Serie, SeriePoseeGenero WHERE Programa.id = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie AND Serie.id_serie = SeriePoseeGenero.id_serie AND SeriePoseeGenero.nombre_genero = '$genero') ORDER BY fecha DESC;";
				$result = $db -> prepare($query);
				$result -> execute();
				$programas = $result -> fetchAll();
				if (count($programas) == 0) {
					echo "<h4>Ningún resultado.</h4>";
				}
				foreach ($programas as $p) {
					$nombre = $p[0];
					$idprograma = $p[1];
					$serie = $p[2];
					if ($serie != "") {
						$nombre = $serie.": ".$nombre;
					} 
					echo "<form class='form-horizontal' style='width:35%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$idprograma' class='col-md-8 control-label'>$nombre</label>
						    <div class='col-md-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$idprograma' name='idprograma' value='$idprograma'>Info</button>
						    </div>
						</div>
					</form>";
				}
			}

			# (3) Busqueda solo por año.
			if ($nombre_programa == "" and $genero == "" and $year != "0") {
				$query = "(SELECT Programa.nombre, Programa.id, null, Programa.fechaestreno AS fecha FROM Programa, Pelicula WHERE Programa.id = Pelicula.id AND Programa.fechaestreno >= '$year-01-01' AND Programa.fechaestreno <= '$year-12-31' UNION SELECT Programa.nombre, Programa.id, Serie.nombre, Programa.fechaestreno AS fecha FROM Programa, EnTemporada, Temporada, EnSerie, Serie WHERE Programa.id = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie AND Programa.fechaestreno >= '$year-01-01' AND Programa.fechaestreno <= '$year-12-31') ORDER BY fecha DESC;";
				$result = $db -> prepare($query);
				$result -> execute();
				$programas = $result -> fetchAll();
				if (count($programas) == 0) {
					echo "<h4>Ningún resultado.</h4>";
				}
				foreach ($programas as $p) {
					$nombre = $p[0];
					$idprograma = $p[1];
					$serie = $p[2];
					if ($serie != "") {
						$nombre = $serie.": ".$nombre;
					} 
					echo "<form class='form-horizontal' style='width:35%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$idprograma' class='col-md-8 control-label'>$nombre</label>
						    <div class='col-md-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$idprograma' name='idprograma' value='$idprograma'>Info</button>
						    </div>
						</div>
					</form>";
				}
			}

			# (4) Busqueda por nombre y fecha de estreno.
			if ($nombre_programa != "" and $genero == "" and $year != "0") {
				$query = "(SELECT Programa.nombre, Programa.id, null, Programa.fechaestreno AS fecha FROM Programa, Pelicula WHERE Programa.id = Pelicula.id AND Programa.nombre LIKE '%$nombre_programa%' AND Programa.fechaestreno >= '$year-01-01' AND Programa.fechaestreno <= '$year-12-31' UNION SELECT Programa.nombre, Programa.id, Serie.nombre, Programa.fechaestreno AS fecha FROM Programa, EnTemporada, Temporada, EnSerie, Serie WHERE Programa.id = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie AND Serie.nombre LIKE '%$nombre_programa%' AND Programa.fechaestreno >= '$year-01-01' AND Programa.fechaestreno <= '$year-12-31') ORDER BY fecha DESC;";
				$result = $db -> prepare($query);
				$result -> execute();
				$programas = $result -> fetchAll();
				if (count($programas) == 0) {
					echo "<h4>Ningún resultado.</h4>";
				}
				foreach ($programas as $p) {
					$nombre = $p[0];
					$idprograma = $p[1];
					$serie = $p[2];
					if ($serie != "") {
						$nombre = $serie.": ".$nombre;
					} 
					echo "<form class='form-horizontal' style='width:35%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$idprograma' class='col-md-8 control-label'>$nombre</label>
						    <div class='col-md-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$idprograma' name='idprograma' value='$idprograma'>Info</button>
						    </div>
						</div>
					</form>";
				}
			}

			# (5) Busqueda por genero y fecha de estreno.
			if ($nombre_programa == "" and $genero != "" and $year != "0") {
				$query = "(SELECT Programa.nombre, Programa.id, null, Programa.fechaestreno AS fecha FROM Programa, Pelicula, PeliculaPoseeGenero WHERE Programa.id = Pelicula.id AND Pelicula.id = PeliculaPoseeGenero.id_pelicula AND PeliculaPoseeGenero.nombre_genero = '$genero' AND Programa.fechaestreno >= '$year-01-01' AND Programa.fechaestreno <= '$year-12-31' UNION SELECT Programa.nombre, Programa.id, Serie.nombre, Programa.fechaestreno AS fecha FROM Programa, EnTemporada, Temporada, EnSerie, Serie, SeriePoseeGenero WHERE Programa.id = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie AND Serie.id_serie = SeriePoseeGenero.id_serie AND SeriePoseeGenero.nombre_genero = '$genero' AND Programa.fechaestreno >= '$year-01-01' AND Programa.fechaestreno <= '$year-12-31') ORDER BY fecha DESC;";
				$result = $db -> prepare($query);
				$result -> execute();
				$programas = $result -> fetchAll();
				if (count($programas) == 0) {
					echo "<h4>Ningún resultado.</h4>";
				}
				foreach ($programas as $p) {
					$nombre = $p[0];
					$idprograma = $p[1];
					$serie = $p[2];
					if ($serie != "") {
						$nombre = $serie.": ".$nombre;
					} 
					echo "<form class='form-horizontal' style='width:35%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$idprograma' class='col-md-7 control-label'>$nombre</label>
						    <div class='col-md-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$idprograma' name='idprograma' value='$idprograma'>Info</button>
						    </div>
						</div>
					</form>";
				}
			}

			# (6) Busqueda por nombre y genero.
			if ($nombre_programa != "" and $genero != "" and $year == "0") {
				$query = "(SELECT Programa.nombre, Programa.id, null, Programa.fechaestreno AS fecha FROM Programa, Pelicula, PeliculaPoseeGenero WHERE Programa.id = Pelicula.id AND Pelicula.id = PeliculaPoseeGenero.id_pelicula AND PeliculaPoseeGenero.nombre_genero = '$genero' AND Programa.nombre LIKE '%$nombre_programa%' UNION SELECT Programa.nombre, Programa.id, Serie.nombre, Programa.fechaestreno AS fecha FROM Programa, EnTemporada, Temporada, EnSerie, Serie, SeriePoseeGenero WHERE Programa.id = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie AND Serie.id_serie = SeriePoseeGenero.id_serie AND SeriePoseeGenero.nombre_genero = '$genero' AND Serie.nombre LIKE '%$nombre_programa%') ORDER BY fecha DESC;";
				$result = $db -> prepare($query);
				$result -> execute();
				$programas = $result -> fetchAll();
				if (count($programas) == 0) {
					echo "<h4>Ningún resultado.</h4>";
				}
				foreach ($programas as $p) {
					$nombre = $p[0];
					$idprograma = $p[1];
					$serie = $p[2];
					if ($serie != "") {
						$nombre = $serie.": ".$nombre;
					} 
					echo "<form class='form-horizontal' style='width:35%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$idprograma' class='col-md-7 control-label'>$nombre</label>
						    <div class='col-md-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$idprograma' name='idprograma' value='$idprograma'>Info</button>
						    </div>
						</div>
					</form>";
				}
			}

			# (7) Busqueda por nombre y genero y año estreno.
			if ($nombre_programa != "" and $genero != "" and $year != "0") {
				$query = "(SELECT Programa.nombre, Programa.id, null, Programa.fechaestreno AS fecha FROM Programa, Pelicula, PeliculaPoseeGenero WHERE Programa.id = Pelicula.id AND Pelicula.id = PeliculaPoseeGenero.id_pelicula AND PeliculaPoseeGenero.nombre_genero = '$genero' AND Programa.nombre LIKE '%$nombre_programa%' AND Programa.fechaestreno >= '$year-01-01' AND Programa.fechaestreno <= '$year-12-31' UNION SELECT Programa.nombre, Programa.id, Serie.nombre, Programa.fechaestreno AS fecha FROM Programa, EnTemporada, Temporada, EnSerie, Serie, SeriePoseeGenero WHERE Programa.id = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie AND Serie.id_serie = SeriePoseeGenero.id_serie AND SeriePoseeGenero.nombre_genero = '$genero' AND Serie.nombre LIKE '%$nombre_programa%' AND Programa.fechaestreno >= '$year-01-01' AND Programa.fechaestreno <= '$year-12-31') ORDER BY fecha DESC;";
				$result = $db -> prepare($query);
				$result -> execute();
				$programas = $result -> fetchAll();
				if (count($programas) == 0) {
					echo "<h4>Ningún resultado.</h4>";
				}
				foreach ($programas as $p) {
					$nombre = $p[0];
					$idprograma = $p[1];
					$serie = $p[2];
					if ($serie != "") {
						$nombre = $serie.": ".$nombre;
					} 
					echo "<form class='form-horizontal' style='width:35%; margin:0 auto;' method='post' action='infoprograma.php'>
						<hr>
						<div class='form-group'>
						    <label for='$idprograma' class='col-md-7 control-label'>$nombre</label>
						    <div class='col-md-3'>
						        <button type='submit' class='btn btn-success btn-md' id='$idprograma' name='idprograma' value='$idprograma'>Info</button>
						    </div>
						</div>
					</form>";
				}
			}



			?>











		</div>
	</div>

</body>
</html>