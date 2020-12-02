<?php 
include_once("Connections/cedulas.php");

$cve_dep = $_GET['recordID'];

try {
	$sqlDep = "SELECT * FROM departamentos WHERE clave = ?";
	$dep = $conexion->prepare($sqlDep);
	$dep->execute(array($cve_dep));
	$depto = $dep->fetch(PDO::FETCH_ASSOC);	
}catch(PDOException $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
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
				<tr><th>MODIFICA DEPARTAMENTO</th></tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" name="clave" placeholder="clave" value="<?php echo str_pad($depto['clave'], 5, "0", STR_PAD_LEFT); ?>" style="width: 600px;" required readonly>
				</td>
				</tr>
				<tr>
				<td class="form-group">
				<input type="text" class="form-control" name="departamento" placeholder="Departamento" value="<?php echo $depto['departamento']; ?>" required>
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
    $clave = (int) $_POST['clave'];
    $departamento = htmlspecialchars($_POST['departamento']);    
	
	try {		
		$sql_up = "UPDATE departamentos SET departamento = ? WHERE clave = ?";
		$stmt = $conexion->prepare($sql_up);
		$stmt = $stmt->execute(array($departamento, $clave));
				
		if($stmt) {
			echo '<script type="text/javascript">
						swal({
							position: "top",
							text: "Departamento modificado!",
							confirmButtonColor: "#DD6B55"
						}).then(function() {
							window.location.href = "index.php?pagina=lista_mod_depto.php";
						});
					</script>';
		}			
	}catch(PDOException $e) {
		echo '<script type="text/javascript">
					swal({
						position: "top",
						text: "No se pudo realizar la modificación!",
						confirmButtonColor: "#DD6B55"
					}).then(function() {
						window.location.href = "index.php?pagina=lista_mod_depto.php";
					});
				</script>';
		$e->getMessage();
	}		
}
?>