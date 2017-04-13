<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Quipasa - Comentario Enviado</title>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>

	<?php
		#Usuario
		$usuario_m = $_COOKIE['usuario_m'];
		$usuario_q = $_COOKIE['usuario_q'];

		#Form Values
		$comentario = $_POST['mensaje'];
		$destinatario = $_POST['destinatario'];

		# DB Connection
		try{$db21 = new PDO("pgsql:dbname=grupo21;host=localhost;user=grupo21;password=grupo21");}
		catch(PDOException $e){echo $e -> getMessage();}

		# Query id number
		$nueva_id = 1;
		$query = "SELECT count(*) FROM Mensajes;";
		$result = $db21 -> prepare($query);
		$result -> execute();
		$mensajes = $result -> fetchAll();
		foreach($mensajes as $mensaje){
			$nueva_id = $nueva_id + $mensaje[0];
		}
	
		# DB Connection
		try{$db28 = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
		catch(PDOException $e){echo $e -> getMessage();}

		#Query nombre programa
		$idprograma = $_COOKIE['idprogramaactual'];
		$query = "SELECT Programa.nombre FROM Programa WHERE Programa.id = '$idprograma';";
		$result = $db28 -> prepare($query);
		$result -> execute();
		$infobasica = $result -> fetchAll();
		$nombreprograma = $infobasica[0][0];

		#Query insertar Mensaje
		$date = date('Y/m/d');
		$comentario = "#" . $nombreprograma . ": " . $comentario ;

		$query = "INSERT INTO Mensajes VALUES($nueva_id,'$usuario_q','$destinatario','$date','$comentario', bool 'f');";
		$result = $db21 -> prepare($query);
		$result -> execute();
	?>

	<div class="row">
		<div class="col-md-12 text-center" style="margin-bottom: 100px;">
			<h2> Tu comentario ha sido enviado con éxito </h2>
			<form action="./infoprograma.php" method="POST" style="margin-top:20px; right:100px">
			<?php
				echo"<button type='submit' class='btn btn-success btn-md' name='idprograma' value='$idprograma'>Volver</button>"
			?>
			</form>
		</div>
	</div>

</body>