<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MaquiView</title>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>

<?php 
# Form Values
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

# DB Connection
try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
catch(PDOException $e){echo $e -> getMessage();}

# Query
$query = "SELECT * FROM Usuario WHERE Usuario.username = '$usuario';";
$result = $db -> prepare($query);
$result -> execute();
$usuarios = $result -> fetchAll();
$existe = count($usuarios);

if ($existe == 1 and $usuario == $usuarios[0][0] and $clave == $usuarios[0][1]) {
	# Se guarda el usuario en una cookie.
	setcookie("usuario", $usuario, time()+30*24*60*60);
	# Se redirige a la pagina de MaquiView	
	header('Location: maquiview_home.php');
} else {
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
?>
</body>
</html>
