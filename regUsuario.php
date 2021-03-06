<?php
include_once("Connections/cedulas.php");

try {
	$sqlRol = "SELECT * FROM roles";
	$rol = $conexion->prepare($sqlRol);
	$rol->execute();
	$roles = $rol->fetch(PDO::FETCH_ASSOC);		
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
<body onload="registrar.nombre.focus()">
	<div class="contenido">
		<div class="row justify-content-center">
		<form name="registrar" class="form" method="POST">
			<table class="table table-sm col-2">
				<tr><th>ALTA DE USUARIO</th></tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" required name="nombre" placeholder="Nombre">
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<input type="text" class="form-control" required name="usuario" placeholder="Usuario">
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<input type="password" class="form-control" required name="contraseña" placeholder="Contrase&ntilde;a">
				</td>
				</tr>				
				<tr>
				<td class="form-group">
					<select name="rol" class="form-control">
						<option value="0" disabled selected>Roles</option>
						<?php
						do {
						?>
						<option value="<?php echo $roles['id']; ?>"><?php echo $roles['rol']; ?></option>
						<?php
						} while ($roles = $rol->fetch(PDO::FETCH_ASSOC));
						?>
					</select>
				</td>
				</tr>
				<tr>
				<td class="form-group">
					<div class="g-recaptcha" name="g-recaptcha-response" data-sitekey="6LdGV-AUAAAAAMfnVkDNWqN-FProfBN3P3bm7Ip0"></div>
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
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
<?php
if(isset($_POST['guardar'])) {
	define("6LdGV-AUAAAAAOJL9ScRFyczn93ExCJpOMu6wlca", "6LdGV-AUAAAAAMfnVkDNWqN-FProfBN3P3bm7Ip0");	
	
	if (!isset($_POST["g-recaptcha-response"]) || empty($_POST["g-recaptcha-response"])) {
		echo '<script type="text/javascript">
					swal({
						position: "top",
				 		text: "Debe completar el reCAPTCHA!",
				 		confirmButtonColor: "#DD6B55"
			 		}).then(function() {
						window.location.href = "index.php?pagina=regUsuario.php";
					});
	    	  </script>';
	}

	function verificarToken($token, $claveSecreta) {
		# La API en donde verificamos el token
		$url = "https://www.google.com/recaptcha/api/siteverify";
		# Los datos que enviamos a Google
		$datos = [
			"secret" => $claveSecreta,
			"response" => $token,
		];
		// Crear opciones de la petición HTTP
		$opciones = array(
			"http" => array(
				"header" => "Content-type: application/x-www-form-urlencoded\r\n",
				"method" => "POST",
				"content" => http_build_query($datos), # Agregar el contenido definido antes
			),
		);
		# Preparar petición
		$contexto = stream_context_create($opciones);
		# Hacerla
		$resultado = file_get_contents($url, false, $contexto);
		# Si hay problemas con la petición (por ejemplo, que no hay internet o algo así)
		# entonces se regresa false. Este NO es un problema con el captcha, sino con la conexión
		# al servidor de Google
		if ($resultado === false) {
			# Error haciendo petición
			return false;
		}

		# En caso de que no haya regresado false, decodificamos con JSON
		# https://parzibyte.me/blog/2018/12/26/codificar-decodificar-json-php/

		$resultado = json_decode($resultado);
		# La variable que nos interesa para saber si el usuario pasó o no la prueba
		# está en success
		$pruebaPasada = $resultado->success;
		# Regresamos ese valor, y listo (sí, ya sé que se podría regresar $resultado->success)
		return $pruebaPasada;
	}	

	$token = $_POST["g-recaptcha-response"];
	$verificado = verificarToken($token, "6LdGV-AUAAAAAOJL9ScRFyczn93ExCJpOMu6wlca");	
	
	$nombre = htmlspecialchars($_POST['nombre']);
	$usuario = htmlspecialchars($_POST['usuario']);
	$password = htmlspecialchars($_POST['contraseña']);
	$contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
	$rol = htmlspecialchars($_POST['rol']);

	$sql = "SELECT * FROM usuarios WHERE usuario = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->execute(array($usuario));
	$checaUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

	if($checaUsuario) {
		echo '<script type="text/javascript">
					swal({
						position: "top",
				 		text: "Este usuario ya está registrado!",
				 		confirmButtonColor: "#DD6B55"
			 		}).then(function() {
						window.location.href = "index.php?pagina=regUsuario.php";
					});
	    			</script>';
	}else{		
		if ($verificado) {
			try {
				$sql = "INSERT INTO usuarios (nombre, usuario, sin_cifrar, password, rol) values (?,?,?,?,?)";
				$stmt = $conexion->prepare($sql);
				$stmt->execute(array($nombre, $usuario, $password, $contraseña, $rol));
				echo '<script type="text/javascript">
						swal({
							position: "top",
							text: "Has abierto una cuenta!",
							confirmButtonColor: "#DD6B55"
						}).then(function() {
							window.location.href = "index.php?pagina=regUsuario.php";
						});
						</script>';
			}catch(PDOException $e) {
				echo '<script type="text/javascript">
						swal({
							position: "top",
							text: "No se pudo realizar el registro!",
							confirmButtonColor: "#DD6B55"
						}).then(function() {
							window.location.href = "index.php?pagina=regUsuario.php";
						});
						</script>';
				$e->getMessage();
			}
		} else {
			echo '<script type="text/javascript">
						swal({
							position: "top",
							text: "Lo siento, parece que eres un robot!",
							confirmButtonColor: "#DD6B55"
						}).then(function() {
							window.location.href = "index.php?pagina=regUsuario.php";
						});
				  </script>';
		}
	}
}
?>