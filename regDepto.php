<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="iso-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CEFA</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/sweetalert2.min.css">
    
    <style>
        body {
            background-color: #eef5f9;
        }
    </style>
</head>
<body onload="registrar.clave.focus()">
	<div class="contenido">
		<div class="row justify-content-center">
		<form name="registrar" class="form" method="POST">
			<table class="table table-sm col-12">
				<tr><th>ALTA DE DEPARTAMENTOS</th></tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" required name="clave" placeholder="Clave" style="width: 600px;">
                </td>                				
				<tr>
				<td>
					<input type="text" class="form-control" required name="departamento" placeholder="Departamento">					
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<button type="submit" class="btn btn-primary" name="guardar" id="guardar" value="&rarr;">Guardar</button>
				</td>
				</tr>	
			</table>		
		</form>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">  	
	</script>
	<script src="js/sweetalert2.min.js"></script>	
</body>
</html>
<?php
if(isset($_POST['crear'])) {
	include_once("Connections/cedulas.php");	

	$clave = htmlspecialchars($_POST['clave']);
	$departamento = htmlspecialchars($_POST['departamento']);

	$sql = "SELECT * FROM departamentos WHERE clave = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->execute(array($clave));
	$checaDepto = $stmt->fetch(PDO::FETCH_ASSOC);

	if($checaDepto) {
		echo '<script type="text/javascript">
					swal({
						position: "top",
				 		text: "Este departamento ya está registrado!",
				 		confirmButtonColor: "#DD6B55"
			 		}).then(function() {
						window.location.href = "index.php?pagina=regDepto.php";
					});
	    			</script>';
	} else {				
		try {
			$sql = "INSERT INTO departamentos (clave, departamento) values (?,?)";
			$stmt = $conexion->prepare($sql);
			$stmt->execute(array($clave, $departamento));
			echo '<script type="text/javascript">
					swal({
						position: "top",
						text: "Has creado un departamento!",
						confirmButtonColor: "#DD6B55"
					}).then(function() {
						window.location.href = "index.php?pagina=regDepto.php";
					});
					</script>';
		}catch(PDOException $e) {
			echo '<script type="text/javascript">
					swal({
						position: "top",
						text: "No se pudo realizar el registro!",
						confirmButtonColor: "#DD6B55"
					}).then(function() {
						window.location.href = "index.php?pagina=regDepto.php";
					});
					</script>';
			$e->getMessage();
		}		
	}
}
?>