<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Registro MaquiView</title>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
	<div class="row">
		<div class="col-md-12 text-center">
			<form action="index.html" style="width:40%; margin:0 auto; margin-top:20px;">	
				<button type="submit" class="btn btn-primary btn-sm">Go Home</button>
			</form>
		</div>

	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<h1>Registro a MaquiView</h1>
		</div>
	</div>
	<div class="row" style="margin-top: 30px;">
		<div class="col-md-12 text-center">
			<form action="verificarregistro.php" method='POST' class="form-horizontal" style="width:30%; margin:0 auto;">
			  <div class="form-group">
			    <label for="inputNombre" class="col-sm-2 control-label">Nombre</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="inputNombre" placeholder="Juan Reutter" name="nombre-completo" required="true">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputEdad" class="col-sm-2 control-label">Edad</label>
			      <div class="col-sm-10">
			      	<select class="form-control" id="inputEdad" name="edad">
			      		<?php for ($i=14; $i<=99; $i++) { ?>
						<option value="<?php echo $i;?>"><?php echo $i;?></option>
						 <?php } ?>
				    </select>
			      </div>
			  </div>
			  <div class="form-group">
			    <label for="inputSexo" class="col-sm-2 control-label">Sexo</label>
			      <div class="col-sm-10">
			      	<select class="form-control" id="inputSexo" name="sexo">
				      <option>masculino</option>
				      <option>femenino</option>
				    </select>
			      </div>
			  </div>
			  <div class="form-group">
			    <label for="inputEmail" class="col-sm-2 control-label">Email</label>
			    <div class="col-sm-10">
			      <input type="email" class="form-control" id="inputEmail" placeholder="jlreutte@uc.cl" name="email" required="true">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputUsuario" class="col-sm-2 control-label">Usuario</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="inputUsuario" placeholder="juanreutter" name="usuario" required="true">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputClave" class="col-sm-2 control-label">Clave</label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" id="inputClave" placeholder="miclave" name="clave" required="true">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputPlan" class="col-sm-2 control-label">Plan</label>
			      <div class="col-sm-10">
			      	<select class="form-control" id="inputPlan" name="plan">

					<?php 
					# DB Connection
					try{$db = new PDO("pgsql:dbname=grupo28;host=localhost;user=grupo28;password=grupo28");}
					catch(PDOException $e){echo $e -> getMessage();}

					# Query Planes
					$query = "SELECT Plan.nombre FROM Plan;";

					$result = $db -> prepare($query);
					$result -> execute();
					$planes = $result -> fetchAll();
					foreach ($planes as $p) {
						echo "<option>$p[0]</option>";
					}
					?>

				    </select>
			      </div>
			  </div>
			  <div class="form-group">
			    <div class="col-sm-12 text-center">
			      <button type="submit" class="btn btn-success">Registrarse</button>
			    </div>
			  </div>
			</form>
		</div>
	</div>
</body>
</html>