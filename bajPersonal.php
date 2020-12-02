<?php
include_once("Connections/cedulas.php");

$cve_per = $_GET['recordID'];

try {
    $sql = "SELECT e.clave, e.nombre, g.grado, e.vigencia, d.departamento, p.puesto 
	FROM empleados e JOIN grados g 
	ON e.grado = g.id
	JOIN departamentos d
	ON e.departamento = d.clave
	JOIN puestos p
	ON e.puesto = p.clave
	WHERE e.clave = ?";
    $borraEmp = $conexion->prepare($sql);
    $borraEmp->execute(array($cve_per));
    $empleados = $borraEmp->fetch(PDO::FETCH_ASSOC);
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
<body>
	<div class="contenido">
		<div class="row justify-content-center">
		<form name="registrar" class="form" method="POST">
			<table class="table table-sm col-2">
				<tr><th>BORRA PERSONAL</th></tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="clave" style="width: 600px;" value="<?php echo $empleados['clave']; ?>" readonly>
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="nombre" value="<?php echo $empleados['nombre']; ?>" readonly>
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="grado" value="<?php echo $empleados['grado']; ?>" readonly>					
				</td>
				</tr>				
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="vigencia" value="<?php echo $empleados['vigencia']; ?>" readonly>
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="departamento" value="<?php echo $empleados['departamento']; ?>" readonly>
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="puesto" value="<?php echo $empleados['puesto']; ?>" readonly>
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<button type="submit" class="btn btn-primary" name="borrar" id="borrar" value="&rarr;">Borrar</button>
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
if(isset($_POST['borrar'])) {
	$clave = htmlspecialchars($_POST['clave']);
	
	$sql_up = "UPDATE empleados SET baja = ? WHERE clave = ?";
	$stmt = $conexion->prepare($sql_up);	

	try {
		$stmt = $stmt->execute(array(1, $clave));
			
		if($stmt) {
			echo '<script type="text/javascript">
						swal({
							position: "top",
					 		text: "Empleado eliminado!",
					 		confirmButtonColor: "#DD6B55"
				 		}).then(function() {
							window.location.href = "index.php?pagina=lista_del_personal.php";
						});
		    			</script>';
	    }			
	}catch(PDOException $e) {
		echo '<script type="text/javascript">
					swal({
						position: "top",
				 		text: "No se pudo realizar la eliminación!",
				 		confirmButtonColor: "#DD6B55"
			 		}).then(function() {
						window.location.href = "index.php?pagina=lista_del_personal.php";
					});
	    			</script>';
		$e->getMessage();
	}	
}

?>