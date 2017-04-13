<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Enlazar Quipasa</title>
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
			<h1>Enlazar Quipasa</h1>
		</div>
	</div>
	<div class="row" style="margin-top: 30px;">
		<div class="col-md-12 text-center">
			<form action="verificarenlace.php" method='POST' class="form-horizontal" style="width:30%; margin:0 auto;">
			  <div class="form-group">
			    <label for="inputUser" class="col-sm-6 control-label">Usuario Quipasa</label>
			    <div class="col-sm-6">
			      <input type="text" class="form-control" id="inputUser" placeholder="juanreutter" name="usuario_q_enlazar" required="true">
			    </div>
			  </div>
  			  <div class="form-group">
			    <label for="inputUser" class="col-sm-6 control-label">Password Quipasa</label>
			    <div class="col-sm-6">
			      <input type="password" class="form-control" id="inputUser" placeholder="Password" name="password_q_enlazar" required="true">
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="col-sm-12 text-center">
			      <button type="submit" class="btn btn-success">Enlazar Quipasa</button>
			    </div>
			  </div>
			</form>
		</div>
	</div>
</body>
</html>