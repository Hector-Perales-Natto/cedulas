<?php
include_once("Connections/cedulas.php");

$cve_emp = $_GET['recordID'];

try {
    $sql = "SELECT e.nombre, g.grado, p.puesto, d.departamento, c.vigencia 
    FROM cedulas c JOIN empleados e 
    ON c.empleado = e.clave
    JOIN grados g
    ON c.grado = g.id
    JOIN puestos p
    ON c.puesto = p.clave
    JOIN departamentos d
    ON c.area = d.clave	
    WHERE c.empleado = ?";
    $borraCed = $conexion->prepare($sql);
    $borraCed->execute(array($cve_emp));
    $cedulas = $borraCed->fetch(PDO::FETCH_ASSOC);
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
                <tr><th>BORRA CEDULA</th></tr>
                <tr>
					<th>EMPLEADO:</th>
				</tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="empleado" style="width: 600px;" value="<?php echo $cedulas['nombre']; ?>" readonly>
				</td>
                </tr>
                <tr>
					<th>GRADO:</th>
				</tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="grado" value="<?php echo $cedulas['grado']; ?>" readonly>
				</td>
                </tr>
                <tr>
					<th>PUESTO:</th>
                </tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="puesto" value="<?php echo $cedulas['puesto']; ?>" readonly>					
				</td>
                </tr>
                <tr>
					<th>AREA:</th>
                </tr>				
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="departamento" value="<?php echo $cedulas['departamento']; ?>" readonly>
				</td>
                </tr>
                <tr>
					<th>VIGENCIA:</th>
                </tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="vigencia" value="<?php echo $cedulas['vigencia']; ?>" readonly>
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
	try {
        $sql_up = "UPDATE cedulas SET baja = ? WHERE empleado = ?";
	    $stmt = $conexion->prepare($sql_up);	
		$stmt = $stmt->execute(array(1, $cve_emp));
			
		if($stmt) {
			echo '<script type="text/javascript">
						swal({
							position: "top",
					 		text: "Cedula eliminada!",
					 		confirmButtonColor: "#DD6B55"
				 		}).then(function() {
							window.location.href = "index.php?pagina=lista_del_cedulas.php";
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
						window.location.href = "index.php?pagina=lista_del_cedulas.php";
					});
	    			</script>';
		$e->getMessage();
	}	
}

?>