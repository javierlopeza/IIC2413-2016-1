<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Enlazar Quipasa</title>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>

<?php 
# Form Values
$usuario_m = $_COOKIE['usuario_m_registrado'];
$usuario_q = $_POST['usuario_q_enlazar'];
$password_q = $_POST['password_q_enlazar'];
# DB Connection 28
try{$db28 = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
catch(PDOException $e){echo $e -> getMessage();}

# DB Connection 21
try{$db21 = new PDO("pgsql:dbname=grupo21;host=localhost;user=grupo21;password=grupo21");}
catch(PDOException $e){echo $e -> getMessage();}

# Query para verificar existencia del usuario de Quipasa.
$query = "SELECT * FROM Usuarios WHERE username = '$usuario_q';";
$result = $db21 -> prepare($query);
$result -> execute();
$usuarios = $result -> fetchAll();
$existe = count($usuarios);

#Query para verificar que la cuenta quipasa no este enlazada
$query = "SELECT * FROM cuentaenlazada WHERE userquipasa = '$usuario_q';";
$result = $db28 -> prepare($query);
$result -> execute();
$usuarios = $result -> fetchAll();
$existe_enlace = count($usuarios);



if ($existe == 1 AND $existe_enlace == 0) {
	#	Query para verificar password
	$query = "SELECT password FROM Usuarios WHERE username = '$usuario_q';";
	$result = $db21 -> prepare($query);
	$result -> execute();
	$password = $result -> fetch(PDO::FETCH_OBJ);

	if ($password_q == $password -> password){
	# Query para enlazar los usuarios recibidos.
	$query = "INSERT INTO CuentaEnlazada VALUES('$usuario_m', '$usuario_q');";
	$result = $db28 -> prepare($query);
	$result -> execute();

	# Exito al enlazar.
	echo "<div class='col-md-12 text-center'>
		<h3>¡Se han enlazado exitosamente las cuentas!</h3>
	</div>
	<div class='row'>
		<div class='col-md-12 text-center'>
			<form action='login.html' style='width:40%; margin:0 auto; margin-top:20px;'>	
				<button type='submit' class='btn btn-primary btn-sm'>Continuar</button>
			</form>
		</div>
	</div>";
	}
	else{
	# Error al enlazar.
	echo "<div class='col-md-12 text-center'>
		<h3>La cuenta de Quipasa ingresada no existe o la clave es incorrecta.</h3>
	</div>
	<div class='row'>
		<div class='col-md-12 text-center'>
			<form action='opcionesregistro.php' style='width:40%; margin:0 auto; margin-top:20px;'>	
				<button type='submit' class='btn btn-primary btn-sm'>Reintentar</button>
			</form>
		</div>
	</div>";	
	}
}
else {
	# Error al enlazar.
	echo "<div class='col-md-12 text-center'>
		<h3>La cuenta de Quipasa ingresada no existe o ya esta enlazada.</h3>
	</div>
	<div class='row'>
		<div class='col-md-12 text-center'>
			<form action='opcionesregistro.php' style='width:40%; margin:0 auto; margin-top:20px;'>	
				<button type='submit' class='btn btn-primary btn-sm'>Reintentar</button>
			</form>
		</div>
	</div>";
}




?>
</body>
</html>