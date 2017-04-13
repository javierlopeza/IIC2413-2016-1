<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MaquiView + Quipasa</title>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>

<?php 
# Form Values
$usuario_m = $_POST['usuario_m'];
$clave_m = $_POST['clave_m'];
$usuario_q = $_POST['usuario_q'];
$clave_q = $_POST['clave_q'];

# DB Connection Grupo 28
try{$db28 = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
catch(PDOException $e){echo $e -> getMessage();}

# DB Connection Grupo 21
try{$db21 = new PDO("pgsql:dbname=grupo21;host=localhost;user=grupo21;password=grupo21");}
catch(PDOException $e){echo $e -> getMessage();}


# Query cuentas enlazadas.
$query = "SELECT * FROM CuentaEnlazada WHERE usermaquiview = '$usuario_m' AND userquipasa = '$usuario_q';";
$result = $db28 -> prepare($query);
$result -> execute();
$usuarios = $result -> fetchAll();
$existe_enlace = count($usuarios);


# Query Usuario Maquiview
$query = "SELECT * FROM Usuario WHERE Usuario.username = '$usuario_m';";
$result = $db28 -> prepare($query);
$result -> execute();
$usuarios_m = $result -> fetchAll();
$existe_m = count($usuarios_m);

# Query Usuario Quipasa
$query = "SELECT username, password FROM Usuarios WHERE Usuarios.username = '$usuario_q';";
$result = $db21 -> prepare($query);
$result -> execute();
$usuarios_q = $result -> fetchAll();
$existe_q = count($usuarios_q);

if ($existe_enlace == 1) {
	if ($existe_m == 1 and $usuario_m == $usuarios_m[0][0] and $clave_m == $usuarios_m[0][1]) {
		if ($existe_q == 1 and $usuario_q == $usuarios_q[0][0] and $clave_q == $usuarios_q[0][1]) {
			# Se guarda el usuario en una cookie.
			setcookie("usuario_m", $usuario_m, time()+30*24*60*60);
			setcookie("usuario_q", $usuario_q, time()+30*24*60*60);
			# Se redirige a la pagina de MaquiView+ Quipasa
			header('Location: maquiviewquipasa_home.php');
		}
		else {
		# Error: usuario no existe, boton para volver a intentar
		echo "<div class='col-md-12 text-center'>
				<h3>El usuario no existe o la clave no es correcta.</h3>
			</div>
			<div class='row'>
				<div class='col-md-12 text-center'>
					<form action='../login.html' style='width:40%; margin:0 auto; margin-top:20px;'>	
						<button type='submit' class='btn btn-primary btn-sm'>Reintentar</button>
					</form>
				</div>
			</div>";
		} 
	}
	else {
			# Error: usuario no existe, boton para volver a intentar
			echo "<div class='col-md-12 text-center'>
					<h3>El usuario no existe o la clave no es correcta.</h3>
				</div>
				<div class='row'>
					<div class='col-md-12 text-center'>
						<form action='../login.html' style='width:40%; margin:0 auto; margin-top:20px;'>	
							<button type='submit' class='btn btn-primary btn-sm'>Reintentar</button>
						</form>
					</div>
				</div>";
	}
}
else {
	# Error: usuario no existe, boton para volver a intentar
	echo "<div class='col-md-12 text-center'>
			<h3>Las cuentas no están enlazadas.</h3>
		</div>
		<div class='row'>
			<div class='col-md-12 text-center'>
				<form action='../login.html' style='width:40%; margin:0 auto; margin-top:20px;'>	
					<button type='submit' class='btn btn-primary btn-sm'>Reintentar</button>
				</form>
			</div>
		</div>";
}


?>
</body>
</html>
