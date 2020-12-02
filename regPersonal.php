<?php
include_once("Connections/cedulas.php");

try {
	$sqlGra = "SELECT * FROM grados ORDER BY id";
	$gra = $conexion->prepare($sqlGra);
	$gra->execute();
	$grado = $gra->fetch(PDO::FETCH_ASSOC);
	
	$sqlDep = "SELECT * FROM departamentos ORDER BY departamento";
	$dep = $conexion->prepare($sqlDep);
	$dep->execute();
	$depto = $dep->fetch(PDO::FETCH_ASSOC);

	$sqlPto = "SELECT * FROM puestos ORDER BY puesto";
	$pto = $conexion->prepare($sqlPto);
	$pto->execute();
	$puesto = $pto->fetch(PDO::FETCH_ASSOC);
}catch(PDOException $e) {
    echo $e->getMessage();
}
?>
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
			<table class="table table-sm col-2">
				<tr><th>ALTA DE PERSONAL</th></tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" required name="clave" placeholder="Clave" style="width: 600px;">
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" required name="nombre" placeholder="Nombre">
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<select name="grado" class="form-control">
						<option value="0" disabled selected>Grados</option>
						<?php
						do {
						?>
						<option value="<?php echo $grado['id']; ?>"><?php echo $grado['grado']; ?></option>
						<?php
						} while ($grado = $gra->fetch(PDO::FETCH_ASSOC));
						?>
					</select>
				</td>
				</tr>				
				<tr>
				<td class="form-group">
					<input type="date" class="form-control" required name="vigencia" placeholder="Vigencia">
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<select name="departamento" class="form-control">
						<option value="0" disabled selected>Departamentos</option>
						<?php
						do {
						?>
						<option value="<?php echo $depto['clave']; ?>"><?php echo $depto['departamento']; ?></option>
						<?php
						} while ($depto = $dep->fetch(PDO::FETCH_ASSOC));
						?>
					</select>
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<select name="puesto" class="form-control">
						<option value="0" disabled selected>Puestos</option>
						<?php
						do {
						?>
						<option value="<?php echo $puesto['clave']; ?>"><?php echo $puesto['puesto']; ?></option>
						<?php
						} while ($puesto = $pto->fetch(PDO::FETCH_ASSOC));
						?>
					</select>
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
if(isset($_POST['guardar'])) {	
	$clave = htmlspecialchars($_POST['clave']);
	$nombre = htmlspecialchars($_POST['nombre']);
	$grado = $_POST['grado'];
	$vigencia = htmlspecialchars($_POST['vigencia']);
	$departamento = $_POST['departamento'];
	$puesto = htmlspecialchars($_POST['puesto']);	

	$sql = "SELECT * FROM empleados WHERE clave = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->execute(array($clave));
	$checaEmpleado = $stmt->fetch(PDO::FETCH_ASSOC);

	if($checaEmpleado) {
		echo '<script type="text/javascript">
					swal({
						position: "top",
				 		text: "Este empleado ya está registrado!",
				 		confirmButtonColor: "#DD6B55"
			 		}).then(function() {
						window.location.href = "index.php?pagina=regPersonal.php";
					});
	    			</script>';
	} else {		
		
		try {
			$sql = "INSERT INTO empleados (clave, nombre, grado, vigencia, departamento, puesto) values (?,?,?,?,?,?)";
			$stmt = $conexion->prepare($sql);
			$stmt->execute(array($clave, $nombre, $grado, $vigencia, $departamento, $puesto));
			
			echo '<script type="text/javascript">
					swal({
						position: "top",
						text: "Has creado un nuevo empleado!",
						confirmButtonColor: "#DD6B55"
					}).then(function() {
						window.location.href = "index.php?pagina=regPersonal.php";
					});
					</script>';
		}catch(PDOException $e) {
			echo '<script type="text/javascript">
					swal({
						position: "top",
						text: "No se pudo realizar el registro!",
						confirmButtonColor: "#DD6B55"
					}).then(function() {
						window.location.href = "index.php?pagina=regPersonal.php";
					});
					</script>';
			$e->getMessage();
		}		
	}
}
?>