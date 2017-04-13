<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MaquiView - Info Programa</title>
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
		
			<?php 
			# Form Values: idprograma
			$idprograma = $_POST['idprograma'];

			# Se guarda en cookie el id del programa actual
			setcookie("idprogramaactual", $idprograma, time()+30*24*60*60);

			# DB Connection
			try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
			catch(PDOException $e){echo $e -> getMessage();}

			# Query Informacion Basica: retorna (nombre programa, duracion, estreno)
			$query = "SELECT Programa.nombre, Programa.duracion, Programa.fechaestreno FROM Programa WHERE Programa.id = '$idprograma';";
			$result = $db -> prepare($query);
			$result -> execute();
			$infobasica = $result -> fetchAll();
			$nombreprograma = $infobasica[0][0];
			$duracionprograma = $infobasica[0][1];
			$estrenoprograma = $infobasica[0][2];

			# Query Tipo Programa: retorna (nombre de la serie, numero de temporada) o vacio.
			$query = "SELECT Serie.nombre, Temporada.numero FROM EnTemporada, Temporada, EnSerie, Serie WHERE '$idprograma' = EnTemporada.id_capitulo AND EnTemporada.id_temporada = Temporada.id_temporada AND Temporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = Serie.id_serie;";
			$result = $db -> prepare($query);
			$result -> execute();
			$serietemporada = $result -> fetchAll();
			if (count($serietemporada) == 1) {
				# Es un capitulo de serie.
				$nombreserie = $serietemporada[0][0];
				$numerotemporada = $serietemporada[0][1];
				echo "<hr>";
				echo "<h3>Capítulo</h3>";
				echo "<h2>$nombreserie: $nombreprograma</h2>";
				echo "<h3>Temporada: $numerotemporada</h3>";
				echo "<hr>";

				# Se indica en una cookie que es un capitulo.
				setcookie("tipoprograma", "capitulo", time()+30*24*60*60);
			}
			else {
				# Es una pelicula.
				echo "<hr>";
				echo "<h3>Película</h3>";
				echo "<h1>$nombreprograma</h1>";
				echo "<hr>";

				# Se indica en una cookie que es un capitulo.
				setcookie("tipoprograma", "pelicula", time()+30*24*60*60);
			}

			echo "<h4>Duración: $duracionprograma</h4>
				<h4>Fecha de Estreno: $estrenoprograma</h4>";

			# Query Generos
			$query = "SELECT nombre_genero FROM PeliculaPoseeGenero WHERE '$idprograma' = PeliculaPoseeGenero.id_pelicula UNION SELECT nombre_genero FROM EnTemporada, EnSerie, SeriePoseeGenero WHERE '$idprograma' = EnTemporada.id_capitulo AND EnTemporada.id_temporada = EnSerie.id_temporada AND EnSerie.id_serie = SeriePoseeGenero.id_serie;";
			$result = $db -> prepare($query);
			$result -> execute();
			$generos = $result -> fetchAll();
			if (count($generos) > 0) {
				echo "<h3>Géneros:</h3>";
				foreach ($generos as $g) {
					echo "<h4>- $g[0]</h4>";
				};
			}

			# Query Actores
			$query = "SELECT PoseeActor.nombre_actor FROM PoseeActor WHERE '$idprograma'  = PoseeActor.id_programa;";
			$result = $db -> prepare($query);
			$result -> execute();
			$actores = $result -> fetchAll();
				if (count($actores) > 0) {
				echo "<h3>Actores:</h3>";
				foreach ($actores as $a) {
					echo "<h4>- $a[0]</h4>";
				};
			}

			# Query Directores
			$query = "SELECT PoseeDirector.nombre_director FROM PoseeDirector WHERE '$idprograma' = PoseeDirector.id_programa;";
			$result = $db -> prepare($query);
			$result -> execute();
			$directores = $result -> fetchAll();
			if (count($directores) > 0) {
				echo "<h3>Directores:</h3>";
				foreach ($directores as $d) {
					echo "<h4>- $d[0]</h4>";
				};
			}
			?>
		</div>
	</div>
	<!-- Ver Película Button -->
	<div class="row">
		<div class="col-md-12 text-center">
			<form action="verprograma.php" style="width:40%; margin:0 auto; margin-top:20px; margin-bottom:50px;">	
				<button type="submit" class="btn btn-success btn-lg">Ver Programa</button>
			</form>
		</div>
	</div>

	<hr>

	<!-- Comentar Película -->
	<div class="row">
		<div class="col-md-12 text-center">
			<h3>Comentar</h3>
			<form action="enviar_mensaje.php" , method="POST", style="width:40%; margin:0 auto; margin-top:20px; margin-bottom:50px;">
				<input type="text" class="form-control" placeholder="Mensaje" name="mensaje" style="margin-bottom: 20px;" required="true">
				<div class="row">
					<div class="col-md-3 text-center">
						<h4>Destinatario: </h4>
					</div>
					<div class="col-md-5 text-left">
						<select class="form-control" id="Destino" name="destinatario", required="true">		    
							<?php 
								# DB Connection
								try{$db = new PDO("pgsql:dbname=grupo21;host=localhost;user=grupo21;password=grupo21");}
								catch(PDOException $e){echo $e -> getMessage();}

								#Query Amigos
								$query = "SELECT u2.username AS username
									FROM Usuarios  AS u1,Amistades , Usuarios AS u2
									WHERE u1.username = '$usuario_q'
									AND u1.email = Amistades.email1
									AND Amistades.email2 = u2.email
									UNION
									SELECT u2.username AS username
									FROM Usuarios  AS u1,Amistades , Usuarios AS u2
									WHERE u1.username = '$usuario_q'
									AND u1.email = Amistades.email2
									AND Amistades.email1 = u2.email;";
								$result = $db -> prepare($query);
								$result -> execute();
								$amigos = $result -> fetchAll();

								$query = "SELECT Grupos.nombre
									FROM Usuarios,Grupos,Miembros
									WHERE Usuarios.username = '$usuario_q'
									AND Usuarios.email = Miembros.usuario
									AND Grupos.id = Miembros.grupo;";
								$result = $db -> prepare($query);
								$result -> execute();
								$grupos = $result -> fetchAll();

								foreach ($amigos as $amigo){
									echo "<option value='$amigo[0]'>$amigo[0] (amigo)</option>";
								}
								foreach ($grupos as $grupo){
									echo "<option value='$grupo[0]'>$grupo[0] (grupo)</option>";
								}
							?>
						</select>
					</div>
					<div class="col-md-2 text-center">
						<button type="submit" class="btn btn-success btn-lg">Comentar</button>
					</div>
				</div>				
			</form>
		</div>
	</div>
	<!-- Comentarios Película -->
	<div class="row">
		<div class="col-md-12 text-center" style="margin-bottom: 100px;">		
			<h3 style="margin-bottom:20px;">Comentarios</h3>
			<?php
			$query_personas = "SELECT Mensajes.emisor, Mensajes.fecha, Mensajes.texto
								FROM Mensajes
								WHERE (Mensajes.destinatario ='$usuario_q' OR Mensajes.emisor = '$usuario_q')
								AND (";
			$query_grupos = "SELECT Mensajes.emisor, Mensajes.fecha, Mensajes.texto
							FROM Mensajes
							WHERE Mensajes.destinatario IN (SELECT Grupos.nombre
															FROM Grupos,Miembros,Usuarios
															WHERE Miembros.grupo = Grupos.id 
															AND((Usuarios.username = '$usuario_q'
															AND Usuarios.email = miembros.usuario)
															OR Usuarios.email =' $usuario_q'))
							AND (";
			$n_actores = 0;
			foreach ($actores as $a) {
				if ($n_actores==0){
					$query_personas = $query_personas . " Mensajes.texto LIKE '%" . $a[0] . "%'";
					$query_grupos = $query_grupos . " Mensajes.texto LIKE '%" . $a[0] . "%'";
				}
				else{
					$query_personas = $query_personas . " OR Mensajes.texto LIKE '%" . $a[0] . "%'";
					$query_grupos = $query_grupos . " OR Mensajes.texto LIKE '%" . $a[0] . "%'";
				}
				$n_actores = $n_actores+1;
			};

			$n_directores = 0;
			foreach ($directores as $d) {
				if ($n_directores==0 and $n_actores==0){
					$query_personas = $query_personas . " Mensajes.texto LIKE '%" . $d[0] . "%'";
					$query_grupos = $query_grupos . " Mensajes.texto LIKE '%" . $d[0] . "%'";
				}
				else{
					$query_personas = $query_personas . " OR Mensajes.texto LIKE '%" . $d[0] . "%'";
					$query_grupos = $query_grupos . " OR Mensajes.texto LIKE '%" . $d[0] . "%'";
				}
				$n_directores = $n_directores+1;
			}

			if ($n_directores==0 and $n_actores==0){
				$query_personas = $query_personas . "Mensajes.texto LIKE '%". $nombreprograma . "%' )";
				$query_grupos = $query_grupos . "Mensajes.texto LIKE '%". $nombreprograma . "%' )";
			}
			else{
				$query_personas = $query_personas . " OR Mensajes.texto LIKE '%". $nombreprograma . "%' )";
			$query_grupos = $query_grupos . " OR Mensajes.texto LIKE '%". $nombreprograma . "%' )";
			}

			$query = $query_personas . " UNION " . $query_grupos . ";";

			$result = $db -> prepare($query);
			$result -> execute();
			$comentarios = $result -> fetchAll();	
			foreach ($comentarios as $c) {
							echo "<h4>($c[1]) $c[0] : $c[2] </h4>";
						};
			?>
		</div>
	</div>
</body>
</html>


