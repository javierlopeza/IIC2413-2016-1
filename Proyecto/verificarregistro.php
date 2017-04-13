<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Registro</title>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>

<?php 
# Form Values
$nombre = $_POST['nombre-completo'];
$edad = (int)$_POST['edad'];
$sexo = $_POST['sexo'];
$email = $_POST['email'];
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];
$plan = $_POST['plan'];


# DB Connection
try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
catch(PDOException $e){echo $e -> getMessage();}

# Query para verificar la no existencia previa del nombre de usuario.
$query = "SELECT * FROM Usuario WHERE Usuario.username = '$usuario';";
$result = $db -> prepare($query);
$result -> execute();
$usuarios = $result -> fetchAll();
$existe = count($usuarios);

if ($existe == 0) {
	# Query para registrar al nuevo usuario.
	$query = "INSERT INTO Usuario VALUES('$usuario', '$clave', '$nombre', '$sexo', $edad, '$email', 0);";
	$result = $db -> prepare($query);
	$result -> execute();

	# Query para agregar contrato del plan.
	$query = "INSERT INTO Contrato VALUES('$usuario', '$plan');";
	$result = $db -> prepare($query);
	$result -> execute();

	# Query para cargar nuevo saldo.
	$query = "UPDATE Usuario SET saldo = (SELECT creditos FROM Plan WHERE Plan.nombre = '$plan') WHERE Usuario.username = '$usuario';";
	$result = $db -> prepare($query);
	$result -> execute();

	# Exito, boton para continuar registro.
	echo "<div class='col-md-12 text-center'>
		<h3>¡Registro exitoso a MaquiView!</h3>
	</div>
	<div class='row'>
		<div class='col-md-12 text-center'>
			<form action='opcionesregistro.php' style='width:40%; margin:0 auto; margin-top:20px;'>	
				<button type='submit' class='btn btn-primary btn-sm'>Continuar</button>
			</form>
		</div>
	</div>";

	# Guardamos en una cookie el usuario creado.
	setcookie("usuario_m_registrado", $usuario, time()+30*24*60*60);
	setcookie("nombre_m_registrado", $nombre, time()+30*24*60*60);
	setcookie("edad", $edad, time()+30*24*60*60);
	setcookie("sexo_m_registrado", $sexo, time()+30*24*60*60);
	setcookie("email", $email, time()+30*24*60*60);

} else {
	# Error: usuario ya existe, boton para volver a intentar
	echo "<div class='col-md-12 text-center'>
			<h3>El usuario ya existe.</h3>
		</div>
		<div class='row'>
			<div class='col-md-12 text-center'>
				<form action='login.html' style='width:40%; margin:0 auto; margin-top:20px;'>	
					<button type='submit' class='btn btn-primary btn-sm'>Reintentar</button>
				</form>
			</div>
		</div>";
}
?>
</body>
</html>