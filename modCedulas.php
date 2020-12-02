<?php
include_once("Connections/cedulas.php");

$cve_emp = $_GET['recordID'];

try {    
    $sqlCed = "SELECT * FROM cedulas WHERE empleado = ? ORDER BY empleado";
    $ced = $conexion->prepare($sqlCed);
    $ced->execute(array($cve_emp));
    $cedulas = $ced->fetch(PDO::FETCH_ASSOC);

    $sqlEmp = "SELECT * FROM empleados WHERE baja = 0 AND clave = ? ORDER BY clave";
	$emp = $conexion->prepare($sqlEmp);
	$emp->execute(array($cedulas['empleado']));
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

		  if(f.elements["estatus"].value == "")
	      {
	        msg += "- Estatus\n";
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
		<form name="registrar" class="form" method="POST" enctype="multipart/form-data" onsubmit="return valida(this)">
			<table class="table table-sm">                
                <tr><h5>MODIFICA CEDULA</h5></tr>
                <tr>
                    <th>EMPLEADO:</th>
                    <th>NOMBRE:</th>
				</tr>                
				<tr>
				<td class="form-group" style="width: 10%;">
                    <input type="text" class="form-control" name="empleado" value="<?Php echo $cedulas['empleado']; ?>" readonly>                                        
                </td>
                <td class="form-group" style="width: 90%;">                    
                    <input type="text" class="form-control" name="nombre" value="<?Php echo $empleado['nombre']; ?>" readonly>                                        
				</td>
				</tr>
				<tr>
					<th>GRADO:</th>
				</tr>
				<tr>
				<td class="form-group" colspan="2">
					<select name="grado" class="form-control" required>
						<option value="0" disabled selected>-- Seleccione una Opción --</option>
						<?php
						do {
						?>
						<option value="<?php echo $grado['id']; ?>"<?php if (!(strcmp($grado['id'], $cedulas['grado']))) {echo "SELECTED";} ?>><?php echo $grado['grado']; ?></option>
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
				<td class="form-group" colspan="2">
					<select name="puesto" class="form-control" required>
						<option value="0" disabled selected>-- Seleccione una Opción --</option>
						<?php
						do {
						?>
						<option value="<?php echo $puesto['clave']; ?>"<?php if (!(strcmp($puesto['clave'], $cedulas['puesto']))) {echo "SELECTED";} ?>><?php echo $puesto['puesto']; ?></option>
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
				<td class="form-group" colspan="2">
					<select name="departamento" class="form-control" required>
						<option value="0" disabled selected>-- Seleccione una Opción --</option>
						<?php
						do {
						?>
						<option value="<?php echo $depto['clave']; ?>"<?php if (!(strcmp($depto['clave'], $cedulas['area']))) {echo "SELECTED";} ?>><?php echo $depto['departamento']; ?></option>
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
				<td class="form-group" colspan="2">
					<input type="date" class="form-control" required name="vigencia" value="<?Php echo $cedulas['vigencia'] ?>">
				</td>
				</tr>
				<tr>
					<th>ESTATUS:</th>
                </tr>				
				<tr>
				<td class="form-group" colspan="2">
					<select name="estatus" class="form-control" required>
						<option value="" disabled selected>-- Seleccione una Opción --</option>
						<option value="0"<?php if (!(strcmp('0', $cedulas['baja']))) {echo "SELECTED";} ?>>ACTIVA</option>
						<option value="1"<?php if (!(strcmp('1', $cedulas['baja']))) {echo "SELECTED";} ?>>INACTIVA</option>						
					</select>
				</td>
				</tr>
				<tr>
				<td class="text-secondary" style="text-align: center; background-color: #343a40" colspan="2"><span style="color:#FFF">CEDULA FIRMADA EN PDF</span></td>
				</tr>
				<tr>						
					<td class="form-group" colspan="2">						
						<input type="file" name="archivo" id="archivo" class="form-control" style="height:38px; font-size:12px;">		
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
	$estatus = htmlspecialchars($_POST['estatus']);
	
	$destino = 'pdf';
	$archivo = '';

	if($_FILES['archivo']['name'] != '') {		
		$archivo = $_FILES['archivo']['name'];

		$ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
		$archivo = $empleado.".".$ext;
				
		if($cedulas['archivo'] != NULL) {
			unlink('pdf/'.$cedulas['archivo']);
		}		

		if(!copy($_FILES['archivo']['tmp_name'], $destino.'/'.$archivo)){ 
			echo "<SCRIPT LANGUAGE=\"JavaScript\">\n"."<!-- Hide from older browsers \n"."alert('El archivo no se ha importado con exito'); \n"." --> \n "."</SCRIPT>";
			echo "<SCRIPT LANGUAGE=\"JavaScript\">\n"."<!-- Hide from older browsers \n"."window.location.href='index.php?pagina=lista_mod_cedulas.php'; \n"." --> \n "."</SCRIPT>"; 
		}
	} else {
		$archivo =  htmlspecialchars($cedulas['archivo']);
	}

	try {
		$sql = "UPDATE cedulas SET grado = ?, puesto = ?, area = ?, vigencia = ?, baja = ?, archivo = ? WHERE empleado = ?";
		$stmt = $conexion->prepare($sql);
		$stmt->execute(array($grado, $puesto, $departamento, $vigencia, $estatus, $archivo, $empleado));
			
		echo '<script type="text/javascript">
				swal({
					position: "top",
					text: "Has modificado la cedula con exito!",
					confirmButtonColor: "#DD6B55"
				}).then(function() {
					window.location.href = "index.php?pagina=lista_mod_cedulas.php";
				});
				</script>';
	}catch(PDOException $e) {
		echo '<script type="text/javascript">
				swal({
					position: "top",
					text: "No se pudo realizar la modificación!",
					confirmButtonColor: "#DD6B55"
				}).then(function() {
					window.location.href = "index.php?pagina=lista_mod_cedulas.php";
				});
				</script>';
		$e->getMessage();
	}
}
?>