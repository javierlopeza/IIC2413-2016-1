<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MaquiView - Top 5</title>
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
			<form action="./maquiviewquipasa_home.php" style="margin-top:20px">	
				<button type="submit" class="btn btn-danger btn-sm">Back to all movies</button>
			</form>
		</div>
	</div>
	<!-- Title -->
	<div class="row">
		<div class="col-md-12 text-center">
			<h1>MaquiView + Quipasa</h1>
			<?php
			$usuario_m = $_COOKIE['usuario_m'];
			$usuario_q = $_COOKIE['usuario_q'];
			echo "<h4>Usuario Conectado MaquiView: $usuario_m</h4>";
			echo "<h4>Usuario Conectado Quipasa: $usuario_q</h4>";
			?>
		</div>
	</div>
	<!-- Top 5 -->
	<div class="row">
		<div class="col-md-12 text-center">
			<hr>
			<h2>Top 5 Películas</h2>
			<hr>
		</div>
	</div>
	<div class="row">
		<!-- Top 5 Year -->
		<div class="col-md-5 col-md-offset-1 text-center">

			<?php 
			$year = date('Y');
			echo "<h3 style='margin-bottom:20px;'>Top 5 - Año $year</h3>";
			$year = $year . "-01-01";
			try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
			catch(PDOException $e){echo $e -> getMessage();}
			# Query
			
			$query_peliculas = "SELECT Programa.nombre, Programa.id, COUNT(Programa.id) FROM Programa, Pelicula, VistoPrograma WHERE Programa.id = Pelicula.id AND VistoPrograma.fechavisto >= '$year' AND Programa.id = VistoPrograma.id_programa GROUP BY Programa.id ORDER BY COUNT(Programa.id) DESC LIMIT 5;";
			$result = $db -> prepare($query_peliculas);
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

	<!-- Top 5 Month -->
		<div class="col-md-5 text-center">
			

			<?php 
			$months = (object) array('01' => 'Enero','02' => 'Febrero','03' => 'Marzo','04' => 'Abril','05' => 'Mayo','06' => 'Junio','07' => 'Julio','08' => 'Agosto','09' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre');
			$mes = date('m');
			$year = date('Y');
			$mes = $months->$mes;
			try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
			catch(PDOException $e){echo $e -> getMessage();}
			echo "<h3 style='margin-bottom:20px;'>Top 5 - $mes $year</h3>";
			# Query
			$date = date('Y-m-');
			$date = $date . '01';
			$query_peliculas = "SELECT Programa.nombre, Programa.id, COUNT(Programa.id) FROM Programa, Pelicula, VistoPrograma WHERE Programa.id = Pelicula.id AND VistoPrograma.fechavisto >= '$date' AND Programa.id = VistoPrograma.id_programa GROUP BY Programa.id ORDER BY COUNT(Programa.id) DESC LIMIT 5;";
			$result = $db -> prepare($query_peliculas);
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
	</div>

</body>
</html>