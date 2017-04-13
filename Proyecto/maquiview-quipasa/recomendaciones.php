<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MaquiView + Quipasa</title>
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
	<hr>

	<!-- Title Resultado Recomendaciones -->
	<div class="row">
		<div class="col-md-12 text-center">
			<h3>Resultado Recomendaciones</h3>
			<?php

			# Funcion para permutar elementos de lista
			function permutations(array $elements)
			{
			    if (count($elements) <= 1) {
			        yield $elements;
			    } else {
			        foreach (permutations(array_slice($elements, 1)) as $permutation) {
			            foreach (range(0, count($elements) - 1) as $i) {
			                yield array_merge(
			                    array_slice($permutation, 0, $i),
			                    [$elements[0]],
			                    array_slice($permutation, $i)
			                );
			            }
			        }
			    }
			}

			# DB Connection 21
			try{$db21 = new PDO("pgsql:dbname=grupo21;host=localhost;user=grupo21;password=grupo21");}
			catch(PDOException $e){echo $e -> getMessage();}


			# Query para obtener personas, count(mensajes intercambiados).
			$query = "SELECT p, SUM(C) AS CT
						FROM
						((SELECT destinatario AS p, COUNT(destinatario) as C
						FROM Mensajes
						WHERE emisor = '$usuario_q'
						GROUP BY destinatario)
						UNION ALL
						(SELECT emisor as p, COUNT(emisor) AS C
						FROM Mensajes
						WHERE destinatario = '$usuario_q'
						GROUP BY emisor)) AS Q
						GROUP BY p
						ORDER BY CT DESC;";
			$result = $db21 -> prepare($query);
			$result -> execute();
			$personascont = $result -> fetchAll();

			# DB Connection 28
			try{$db28 = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
			catch(PDOException $e){echo $e -> getMessage();}

			# Query para obtener personas, count(mensajes intercambiados).
			$query = "SELECT * FROM cuentaenlazada;";
			$result = $db28 -> prepare($query);
			$result -> execute();
			$personasmvq = $result -> fetchAll();

			# Juntamos los 5 primeros usuarios que estan en MV
			$cincopersonas = array();
			foreach ($personascont as $p) {
				$usuarioq = $p[0];
				foreach ($personasmvq as $pmvq) {
					if ($usuarioq == $pmvq[1]) {
						array_push($cincopersonas, $pmvq[0]);
						break;
					}
				}
				if (count($cincopersonas) == 5) {
					break;
				}
			}

			$peliculasrecomendadas = array();

			# Query para peliculas que han visto todos
			$query = "SELECT Programa.id, Programa.nombre
					FROM Programa, Pelicula, VistoPrograma
					WHERE Programa.id = Pelicula.id
						AND Programa.id = VistoPrograma.id_programa
						AND VistoPrograma.username = '$cincopersonas[0]'";
			$length = count($cincopersonas);
			for ($i = 1; $i < $length; $i++) {
				$query = $query."INTERSECT
								SELECT Programa.id, Programa.nombre
								FROM Programa, Pelicula, VistoPrograma
								WHERE Programa.id = Pelicula.id
									AND Programa.id = VistoPrograma.id_programa
									AND VistoPrograma.username = '$cincopersonas[$i]'";
			}
			$query = $query.";";
			$result = $db28 -> prepare($query);
			$result -> execute();
			$peliculas5 = $result -> fetchAll();

			foreach ($peliculas5 as $p5) {
				if (!(in_array($p5, $peliculasrecomendadas))) {
					array_push($peliculasrecomendadas, $p5);
				}
			}

			if (count($cincopersonas) == 5) {
				foreach (permutations($cincopersonas) as $permutationcincopersonas) {
					# Query para peliculas que han visto unicamente 4
					$query = "SELECT Programa.id, Programa.nombre
						FROM Programa, Pelicula, VistoPrograma
						WHERE Programa.id = Pelicula.id
							AND Programa.id = VistoPrograma.id_programa
							AND VistoPrograma.username = '$permutationcincopersonas[0]'";
					for ($i = 1; $i < 4; $i++) {
						$query = $query."INTERSECT
										SELECT Programa.id, Programa.nombre
										FROM Programa, Pelicula, VistoPrograma
										WHERE Programa.id = Pelicula.id
											AND Programa.id = VistoPrograma.id_programa
											AND VistoPrograma.username = '$permutationcincopersonas[$i]'";
					}
					$query = $query."INTERSECT
									SELECT Programa.id, Programa.nombre
									FROM Programa, Pelicula
									WHERE Programa.id = Pelicula.id
									EXCEPT
									SELECT Programa.id, Programa.nombre
									FROM Programa, Pelicula, VistoPrograma
									WHERE Programa.id = Pelicula.id
										AND Programa.id = VistoPrograma.id_programa
										AND VistoPrograma.username = '$permutationcincopersonas[4]'";
					$query = $query.";";
					$result = $db28 -> prepare($query);
					$result -> execute();
					$peliculas4 = $result -> fetchAll();

					foreach ($peliculas4 as $p4) {
						if (!(in_array($p4, $peliculasrecomendadas))) {
							array_push($peliculasrecomendadas, $p4);
						}
					}

				}




				foreach (permutations($cincopersonas) as $permutationcincopersonas) {
					# Query para peliculas que han visto unicamente 3
					$query = "SELECT Programa.id, Programa.nombre
						FROM Programa, Pelicula, VistoPrograma
						WHERE Programa.id = Pelicula.id
							AND Programa.id = VistoPrograma.id_programa
							AND VistoPrograma.username = '$permutationcincopersonas[0]'";
					for ($i = 1; $i < 3; $i++) {
						$query = $query."INTERSECT
										SELECT Programa.id, Programa.nombre
										FROM Programa, Pelicula, VistoPrograma
										WHERE Programa.id = Pelicula.id
											AND Programa.id = VistoPrograma.id_programa
											AND VistoPrograma.username = '$permutationcincopersonas[$i]'";
					}
					for ($j = 3; $j < 5; $j++) {
						$query = $query."INTERSECT
											SELECT Programa.id, Programa.nombre
											FROM Programa, Pelicula
											WHERE Programa.id = Pelicula.id
											EXCEPT
											SELECT Programa.id, Programa.nombre
											FROM Programa, Pelicula, VistoPrograma
											WHERE Programa.id = Pelicula.id
												AND Programa.id = VistoPrograma.id_programa
												AND VistoPrograma.username = '$permutationcincopersonas[$j]'";
					}
					$query = $query.";";
					$result = $db28 -> prepare($query);
					$result -> execute();
					$peliculas3 = $result -> fetchAll();

					foreach ($peliculas3 as $p3) {
						if (!(in_array($p3, $peliculasrecomendadas))) {
							array_push($peliculasrecomendadas, $p3);
						}
					}

				}


				foreach (permutations($cincopersonas) as $permutationcincopersonas) {
					# Query para peliculas que han visto unicamente 2
					$query = "SELECT Programa.id, Programa.nombre
						FROM Programa, Pelicula, VistoPrograma
						WHERE Programa.id = Pelicula.id
							AND Programa.id = VistoPrograma.id_programa
							AND VistoPrograma.username = '$permutationcincopersonas[0]'";
					for ($i = 1; $i < 2; $i++) {
						$query = $query."INTERSECT
										SELECT Programa.id, Programa.nombre
										FROM Programa, Pelicula, VistoPrograma
										WHERE Programa.id = Pelicula.id
											AND Programa.id = VistoPrograma.id_programa
											AND VistoPrograma.username = '$permutationcincopersonas[$i]'";
					}
					for ($j = 2; $j < 5; $j++) {
						$query = $query."INTERSECT
										SELECT Programa.id, Programa.nombre
										FROM Programa, Pelicula
										WHERE Programa.id = Pelicula.id
										EXCEPT
										SELECT Programa.id, Programa.nombre
										FROM Programa, Pelicula, VistoPrograma
										WHERE Programa.id = Pelicula.id
											AND Programa.id = VistoPrograma.id_programa
											AND VistoPrograma.username = '$permutationcincopersonas[$j]'";
					}
					$query = $query.";";
					$result = $db28 -> prepare($query);
					$result -> execute();
					$peliculas2 = $result -> fetchAll();

					foreach ($peliculas2 as $p2) {
						if (!(in_array($p2, $peliculasrecomendadas))) {
							array_push($peliculasrecomendadas, $p2);
						}
					}

				}

				foreach (permutations($cincopersonas) as $permutationcincopersonas) {
					# Query para peliculas que han visto unicamente 1
					$query = "SELECT Programa.id, Programa.nombre
						FROM Programa, Pelicula, VistoPrograma
						WHERE Programa.id = Pelicula.id
							AND Programa.id = VistoPrograma.id_programa
							AND VistoPrograma.username = '$permutationcincopersonas[0]'";
					for ($j = 1; $j < 5; $j++) {
						$query = $query."INTERSECT
										SELECT Programa.id, Programa.nombre
										FROM Programa, Pelicula
										WHERE Programa.id = Pelicula.id
										EXCEPT
										SELECT Programa.id, Programa.nombre
										FROM Programa, Pelicula, VistoPrograma
										WHERE Programa.id = Pelicula.id
											AND Programa.id = VistoPrograma.id_programa
											AND VistoPrograma.username = '$permutationcincopersonas[$j]'";
					}
					$query = $query.";";
					$result = $db28 -> prepare($query);
					$result -> execute();
					$peliculas1 = $result -> fetchAll();

					foreach ($peliculas1 as $p1) {
						if (!(in_array($p1, $peliculasrecomendadas))) {
							array_push($peliculasrecomendadas, $p1);
						}
					}

				}
			}

			$length = count($peliculasrecomendadas);
			for ($i = 0; $i < $length; $i++) {
			  	$pr = $peliculasrecomendadas[$i];
			  	$ranking = $i + 1;
				echo "<form class='form-horizontal' style='width:50%; margin:0 auto;' method='post' action='infoprograma.php'>
					<hr>
					<div class='form-group'>
					    <label for='$pr[0]' class='col-sm-6 control-label'>[$ranking]  $pr[1]</label>
					    <div class='col-sm-3'>
					        <button type='submit' class='btn btn-success btn-md' id='$pr[0]' name='idprograma' value='$pr[0]'>Info</button>
					    </div>
					</div>
				</form>";
			}

			?>
		</div>
	</div>
</body>
</html>