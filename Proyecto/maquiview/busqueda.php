<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MaquiView - Búsqueda</title>
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
	<!-- Busqueda -->
	<div class="row">
		<div class="col-md-12 text-center">
			<hr>
			<h2>Búsqueda</h2>
			<h4 style="margin-bottom: 20px;">Nota: Rellena solo los campos que quieras filtrar.</h4>
			<hr>
		</div>
	</div>
	<div class="row" style="margin-top: 0px;">
		<div class="col-md-12 text-center">
			<form action="resultadobusqueda.php" method="POST" class="form-horizontal" style="width:40%; margin:0 auto;">
			  <div class="form-group">
			    <label for="inputNombrePrograma" class="col-sm-5 control-label">Nombre Programa</label>
			    <div class="col-sm-7">
			      <input type="text" class="form-control" id="inputNombrePrograma" placeholder="Buscando a Nemo" name="nombre-programa">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputGenero" class="col-sm-5 control-label">Género</label>
			    <div class="col-sm-7">
			      <input type="text" class="form-control" id="inputGenero" placeholder="Animada" name="genero">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputYear" class="col-sm-5 control-label">Año Estreno</label>
			      <div class="col-sm-7">
			      	<select class="form-control" id="inputYear" name="year">
			      		<option value="0"> --- </option>
			      		<?php 
			      		for ($i=1900; $i<=2016; $i++) { $year = $i;?>
						<option value="<?php echo $year;?>"><?php echo $year;?></option>
						 <?php } ?>
				    </select>
			      </div>
			  </div>
			  <div class="form-group">
			    <div class="col-sm-12 text-center">
			      <button type="submit" class="btn btn-success">Buscar</button>
			    </div>
			  </div>
			</form>

		</div>
	</div>
</body>
</html>