<?php
include_once("Connections/cedulas.php");

try {
    $sqlEmp = "SELECT * FROM empleados WHERE baja = 0 ORDER BY nombre";
	$emp = $conexion->prepare($sqlEmp);
	$emp->execute();
	$empleado = $emp->fetch(PDO::FETCH_ASSOC);

	$sqlGra = "SELECT * FROM grados ORDER BY id";
	$gra = $conexion->prepare($sqlGra);
	$gra->execute();
    $grado = $gra->fetch(PDO::FETCH_ASSOC);
    
    $sqlPto = "SELECT * FROM puestos WHERE baja = 0 ORDER BY puesto";
	$pto = $conexion->prepare($sqlPto);
	$pto->execute();
	$puesto = $pto->fetch(PDO::FETCH_ASSOC);
	
	$sqlDep = "SELECT * FROM departamentos WHERE baja = 0 ORDER BY departamento";
	$dep = $conexion->prepare($sqlDep);
	$dep->execute();
	$depto = $dep->fetch(PDO::FETCH_ASSOC);	
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
    
    <script language="javascript">
		function valida(f) {
	      var ok = true;
	      var msg = "Debes escribir algo en los campos:\n";
	      if(f.elements["empleado"].value == 0)
	      {
	        msg += "- Empleado\n";
	        ok = false;
	      }

	      if(f.elements["grado"].value == 0)
	      {
	        msg += "- Grado\n";
	        ok = false;
	      }

	      if(f.elements["puesto"].value == 0)
	      {
	        msg += "- Puesto\n";
	        ok = false;
	      }

	      if(f.elements["departamento"].value == 0)
	      {
	        msg += "- Area\n";
	        ok = false;
	      }

	      if(f.elements["vigencia"].value == "")
	      {
	        msg += "- Vigencia\n";
	        ok = false;
	      }

	      if(ok == false)
	        alert(msg);
	        return ok;
    	}  		
	</script>
    
    <style>
        body {
            background-color: #eef5f9;
        }
    </style>
</head>
<body onload="registrar.empleado.focus()">
	<div class="contenido">
		<div class="row justify-content-center">
		<form name="registrar" class="form" method="POST" action="regCedulas.php" enctype="multipart/form-data" onsubmit="return valida(this)">
			<table class="table table-sm">                
                <tr><h5>ALTA DE CEDULA</h5></tr>
                <tr>
					<th>EMPLEADO:</th>
				</tr>                
				<tr>
				<td class="form-group">                    
                    <select name="empleado" class="form-control" required>
						<option value="0" disabled selected>-- Seleccione una Opción --</option>
						<?php
						do {
						?>
						<option value="<?php echo $empleado['clave']; ?>"><?php echo $empleado['nombre']; ?></option>
						<?php
						} while ($empleado = $emp->fetch(PDO::FETCH_ASSOC));
						?>
					</select>
				</td>
				</tr>
				<tr>
					<th>GRADO:</th>
				</tr>
				<tr>
				<td class="form-group">
					<select name="grado" class="form-control" required>
						<option value="0" disabled selected>-- Seleccione una Opción --</option>
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
					<th>PUESTO:</th>
                </tr>
                <tr>
				<td class="form-group">
					<select name="puesto" class="form-control" required>
						<option value="0" disabled selected>-- Seleccione una Opción --</option>
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
					<th>AREA:</th>
                </tr>
                <tr>
				<td class="form-group">
					<select name="departamento" class="form-control" required>
						<option value="0" disabled selected>-- Seleccione una Opción --</option>
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
					<th>VIGENCIA:</th>
                </tr>				
				<tr>
				<td class="form-group">
					<input type="date" class="form-control" required name="vigencia">
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
    $empleado = htmlspecialchars($_POST['empleado']);
    $grado = htmlspecialchars($_POST['grado']);
    $puesto = htmlspecialchars($_POST['puesto']);	
    $departamento = htmlspecialchars($_POST['departamento']);
    $vigencia = htmlspecialchars($_POST['vigencia']);   
	
	$sql = "SELECT * FROM cedulas WHERE empleado = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->execute(array($empleado));
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
			$sql = "INSERT INTO cedulas (empleado, grado, puesto, area, vigencia) values (?,?,?,?,?)";
			$stmt = $conexion->prepare($sql);
			$stmt->execute(array($empleado, $grado, $puesto, $departamento, $vigencia));
			
			echo '<script type="text/javascript">
					swal({
						position: "top",
						text: "Has creado una nueva cedula!",
						confirmButtonColor: "#DD6B55"
					}).then(function() {
						window.location.href = "index.php?pagina=regCedulas.php";
					});
					</script>';
		}catch(PDOException $e) {
			echo '<script type="text/javascript">
					swal({
						position: "top",
						text: "No se pudo realizar el registro!",
						confirmButtonColor: "#DD6B55"
					}).then(function() {
						window.location.href = "index.php?pagina=regCedulas.php";
					});
					</script>';
			$e->getMessage();
		}		
	}
}
?>