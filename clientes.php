<?

include('HikvisionService.php');
$ip = '192.168.101.50';
$username = 'admin';
$password = 'simbiosis2026';

// Carga de clientes registrados
if ($_POST['funcion'] == 'Carga_Clientes') {
	include("inc/conectar.php");
	$resultados = $consulta->query("SELECT * FROM clientes WHERE clientes.fechabaja IS NULL");
	foreach ($resultados as $row) {
		//formato de fecha fechapago y fechaexpiracion, fecha_registro
		$row['fechapago'] = date("d-m-Y", strtotime($row['fechapago']));
		$row['fechaexpiracion'] = date("d-m-Y", strtotime($row['fechaexpiracion']));
		$row['fecharegistro'] = date("d-m-Y", strtotime($row['fecharegistro']));
		?>
		<tr>
			<td class="align-middle text-center">
				<button type="button" class="btn btn-default btn-sm btn-primary">
					<?= $row['codigo'] ?>
				</button>
			</td>
			<td class="align-middle text-center"><?= $row['nombre'] ?></td>
			<td class="align-middle text-center"><?= $row['domicilio'] ?></td>
			<td class="align-middle text-center"><?= $row['genero'] ?></td>
			<td class="align-middle text-center"><?= $row['telefono'] ?></td>
			<td class="align-middle text-center"><?= substr($row['fechapago'], 0, 10) ?></td>
			<td class="align-middle text-center"><?= substr($row['fechaexpiracion'], 0, 10) ?></td>
			<td class="align-middle text-center">
				<?
				$rutaImagen = $row['imagen'] ? $row['imagen'] : './img/placeholderFotoPerfil.jpg';
				$rutaImagen = file_exists($rutaImagen) ? $rutaImagen : './img/placeholderFotoPerfil.jpg';
				?>
				<img src="<?= $rutaImagen ?>" class="img-thumbnail" width="100">
			</td>
			<td class="align-middle text-center"><?= substr($row['fecharegistro'], 0, 10) ?></td>
			<td class="align-middle text-center">
				<div class="btn-group " role="group">
					<button type="button" class="btn btn-default btn-sm btn-info text-sm editar" data-toggle="modal"
						data-target="#myModal" idregistro="<?= $row[0] ?>">
						<i class="bi bi-pencil"></i> Editar
					</button>
					<button type="button" class="btn btn-default btn-sm btn-danger text-sm eliminar"
						idregistro="<?= $row[0] ?>">
						<i class="bi bi-trash3"></i> Eliminar
					</button>
					<button type="button" class="btn btn-default btn-sm btn-secondary text-sm foto" data-toggle="modal"
						data-target="#modalFoto" idregistro="<?= $row[0] ?>">
						<i class="bi bi-camera"></i> Actualizar Foto
					</button>
				</div>
			</td>
		</tr>
		<?
	}
	exit();
}


// Obtener proximo folio de clientes
if ($_POST['funcion'] == 'Carga_Folio') {
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT MAX(idclientes)+1 AS Auto_increment FROM clientes");
	foreach ($Auto as $Autocontador)
		;
	$Auto = str_pad($Autocontador["Auto_increment"], 6, "0", STR_PAD_LEFT);
	echo $Auto;
	return $Auto;
	exit();
}


// ----------------- FUNCIONES DE CRUD -------------------------------
// Edicion de clientes
if ($_POST['funcion'] == 'Editar_Productos') {
	include("inc/conectar.php");
	// Actualizacion en la BD
	$consulta->query("UPDATE clientes SET codigo='" . $_POST['codigo'] . "', nombre='" . $_POST['nombre'] . "', domicilio='" . $_POST['domicilio'] . "', idmembresia='" . $_POST['idmembresia'] . "', membresia='" . $_POST['membresia'] . "', telefono='" . $_POST['telefono'] . "', observaciones='" . $_POST['observaciones'] . "', genero='" . $_POST['genero'] . "' WHERE idclientes=" . $_POST['idregistro']);

	$resultadoBD = $consulta->query("SELECT dispositivo, fechaexpiracion FROM clientes WHERE idclientes = " . $_POST['idregistro']);
	foreach ($resultadoBD as $cliente)
		;
	if ($cliente && $cliente['dispositivo'] == 1) {
		try {
			$controlador = new HikvisionService($ip, $username, $password);

			$fechaInicio = date("Y-m-d\T00:00:00");
			if (!empty($cliente['fechaexpiracion']) && $cliente['fechaexpiracion'] != '1969-12-31') {
				$fechaFin = date("Y-m-d\T23:59:59", strtotime($cliente['fechaexpiracion']));
			} else {
				$fechaFin = date("Y-m-d\T23:59:59", strtotime("+1 month"));
			}

			$controlador->updateUser($_POST['idregistro'], $_POST['nombre'], $fechaInicio, $fechaFin);

		} catch (Exception $e) {
			echo json_encode([
				'success' => false,
				'mensaje' => 'Error al sincronizar los cambios con el lector.'
			]);
		}
	}

	echo json_encode([
		'success' => true,
		'mensaje' => 'Cliente modificado exitosamente.'
	]);

	exit();
}

// Eliminación de clientes
if ($_POST['funcion'] == 'Eliminar') {
	include("inc/conectar.php");

	try {
		// Elimniacion logica en la BD
		$Auto = $consulta->query("UPDATE clientes SET fechabaja='" . date("Y-m-d H:i:s") . "' WHERE idclientes=" . $_POST['idregistro']);
		foreach ($Auto as $Autocontador)
			;

		// Eliminacion en el controlador hikvision
		$controlador = new HikvisionService($ip, $username, $password);
		// Eliminación del registro del cliente en el controlador
		$controlador->deleteUser($_POST['idregistro']);
		// Eliminacion de la foto
		unlink("capturas/rostro" . $_POST['idregistro'] . ".jpg");
	} catch (Exception $e) {
		echo "Error en la comunicación: " . $e->getMessage();
	}

	exit();
}

// Limpieza de imagenes si se cancela la operacion
if ($_POST['funcion'] == 'limpiarFotoTemporal') {
	$idregistro = $_POST['idregistro'];
	$imagePath = "capturas/rostro{$idregistro}.jpg";

	if (file_exists($imagePath)) {
		unlink($imagePath);
	}
	exit();
}


// Nuevo registro de cliente y asignación al lector de huellas/facial
if ($_POST['funcion'] == 'Guardar') {
	include("inc/conectar.php");

	$controlador = new HikvisionService(
		$ip,
		$username,
		$password
	);

	try {
		// Obtener el siguiente ID de cliente para usarlo como ID de usuario en el hikvision
		$Auto = $consulta->query("SELECT MAX(idclientes)+1 AS Auto_increment FROM clientes");
		foreach ($Auto as $Autocontador)
			;
		// Obtencion del ID
		$nextId = $Autocontador["Auto_increment"];
		// Generación del código de cliente con ceros a la izquierda
		$CODIGO = str_pad($Autocontador["Auto_increment"], 6, "0", STR_PAD_LEFT);


		$Auto = $consulta->query("INSERT INTO clientes SET codigo='" . $CODIGO . "', nombre='" . $_POST['nombre'] . "', domicilio='" . $_POST['domicilio'] . "', idmembresia='" . $_POST['idmembresia'] . "', membresia='" . $_POST['membresia'] . "', telefono='" . $_POST['telefono'] . "', observaciones='" . $_POST['observaciones'] . "', genero='" . $_POST['genero'] . "' ");
		foreach ($Auto as $Autocontador)
			;


		$fechaInicio = date("Y-m-d\T00:00:00");
		$fechaFin = date("Y-m-d\T23:59:59", strtotime("+1 day"));
		$response = $controlador->createUser($nextId, $_POST['nombre'], $fechaInicio, $fechaFin);

		if ($response['status'] == 200) {
			$consulta->query("UPDATE clientes SET dispositivo = 1 WHERE idclientes = " . $nextId);
		}

	} catch (Exception $e) {
		echo "Error en la comunicación: " . $e->getMessage();
	}


	echo json_encode($response);
	exit();
}

// ----------------- FIN FUNCIONES DE CRUD ------------------------------


// ultima modificacion - sincronizar cliente con el lector y cambiar el estado a 1 en BD
if ($_POST['funcion'] == 'Registrar_Pago') {
	include("inc/conectar.php");
	$idregistro = $_POST['idregistro'];
	$duracion = $_POST['duracion'];
	$fechaexpiracion = date("Y-m-d\T23:59:59", strtotime("+ $duracion day"));
	$Auto = $consulta->query("UPDATE clientes SET fechapago='" . date("Y-m-d H:i:s") . "', importepago='" . $_POST['importepago'] . "', fechaexpiracion='" . $fechaexpiracion . "' WHERE idclientes=" . $_POST['idregistro']);
	foreach ($Auto as $Autocontador)
		;
	try {
		$controlador = new HikvisionService($ip, $username, $password);

		$fechaInicio = date("Y-m-d\T00:00:00");
		$fechaFin = date("Y-m-d\T23:59:59", strtotime("+$duracion day"));

		// obtencion del status del cliente
		$query = $consulta->query("SELECT nombre, dispositivo, imagen FROM clientes WHERE idclientes = " . $idregistro);
		foreach ($query as $client)
			;

		if ($client['dispositivo'] == 1) {
			$respuesta = $controlador->updateUserExpiration(
				$_POST['idregistro'],
				$fechaInicio,
				$fechaFin
			);

		} else {
			$respuesta = $controlador->createUser($idregistro, $client['nombre'], $fechaInicio, $fechaFin);

			if ($respuesta['status'] == 200) {
				if (!empty($client['imagen']) && file_exists($cliente['imagen'])) {
					$controlador->updateFace($idregistro);
				}
				$consulta->query("UPDATE clientes SET dispositivo = 1 WHERE idclientes = " . $idregistro);
			}
		}


		//insertanmos el pago en la tabla de movimientoscaja
		$Auto = $consulta->query("INSERT INTO movimientoscaja SET idclientes=" . $_POST['idregistro'] . ", importe='" . $_POST['importepago'] . "', fecha='" . date("Y-m-d H:i:s") . "', tipo='Membresia', observaciones='Pago de Membresia', idusuarios=" . $_SESSION['SISTEMA']['idusuarios'] . ", usuarios='" . $_SESSION['SISTEMA']['usuario'] . "'");
		foreach ($Auto as $Autocontador)
			;

	} catch (Exception $e) {
		echo "Error en la comunicación: " . $e->getMessage();
	}

	exit();
}

if ($_POST['funcion'] == 'Cargar_Costos') {
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT * FROM membresias WHERE idmembresia=" . $_POST['idregistro']);
	foreach ($Auto as $Autocontador)
		;
	echo $Autocontador['precio'] . "|" . $Autocontador['duracion'];
	exit();
}


// OPERACIONES DE REGISTRO EN DISPOSITIVOS DE CONTROL DE ACCESO (HIKVISION)
// Toma de fotografía
if ($_POST['funcion'] == 'tomarFotografia') {
	$idregistro = $_POST['idregistro'];

	try {
		$hikvision = new HikvisionService(
			$ip,
			$username,
			$password
		);

		$ok = $hikvision->takeLivePicture($idregistro);

		echo json_encode([
			'success' => $ok,
			'imagen' => "rostro" . $idregistro . ".jpg"
		]);
	} catch (Exception $e) {
		echo "Error en la comunicacion: " . $e->getMessage();
	}

	exit();
}

// Asignación de fotografía
if ($_POST['funcion'] == 'asignarFoto') {
	include('inc/conectar.php');
	$idregistro = $_POST['idregistro'];
	$imagePath = "capturas/rostro{$idregistro}.jpg";

	$hikvision = new HikvisionService(
		$ip,
		$username,
		$password
	);

	// Agrega la foto al hikvision y dependiendo el status de la respuesta continua con la bd
	$response = $hikvision->updateFace($idregistro);

	$consulta->query("UPDATE clientes SET imagen = '" . $imagePath . "' WHERE idclientes = " . $idregistro);

	echo json_encode([
		'success' => true,
		'message' => 'Fotografia añadida correctamente.'
	]);
	exit();
}


// ------------------ CARGA DE MODAL ------------------------
// Carga de modal para edicion y nuevo cliente
if ($_POST['funcion'] == 'Carga_Modal') {
	include("inc/conectar.php");
	$idclientes = 0;
	if ($_POST['tipo'] != 'Nuevo') {
		$Auto = $consulta->query("SELECT * FROM clientes WHERE idclientes=" . $_POST['idregistro'] . "");
		foreach ($Auto as $row)
			;

		$idclientes = $row[0];
	}
	?>
	<div class="modal-body">
		<div class="row">
			<div class="col-md-2 text-center">
				<b>Codigo</b>
				<input type="text" class="form-control text-center" id="codigo" placeholder="codigo"
					aria-describedby="basic-addon1" value="<?= $row['codigo'] ?>" readonly>
			</div>
			<div class="col-md-6">
				<b>Nombre</b>
				<input type="text" class="form-control" placeholder="Nombre" id="nombre" aria-describedby="basic-addon1"
					value="<?= $row['nombre'] ?>">
			</div>
			<div class="col-md-4">
				<b>Telefono</b>
				<input type="text" class="form-control" placeholder="Telefono" id="telefono" aria-describedby="basic-addon1"
					value="<?= $row['telefono'] ?>">
			</div>
			<div class="col-md-4">
				<b>Domicilio</b>
				<input type="text" class="form-control" placeholder="Domicilio" id="domicilio"
					aria-describedby="basic-addon1" value="<?= $row['domicilio'] ?>">
			</div>
			<div class="col-md-4">
				<b>Genero</b>
				<select type="text" class="form-control" id="genero">
					<option value="Masculino" <? if ($row['genero'] == 'Masculino')
						echo "selected" ?>>Masculino</option>
						<option value="Femenino" <? if ($row['genero'] == 'Femenino')
						echo "selected" ?>>Femenino</option>
					</select>
				</div>
				<div class="col-md-4">
					<b>Membresia</b>
					<select type="text" class="form-control" id="idmembresia">
						<?php
					$Autom = $consulta->query("SELECT * FROM membresias WHERE fechabaja IS NULL ORDER BY nombre");
					foreach ($Autom as $mem) {
						?>
						<option value="<?= $mem['idmembresia'] ?>" <? if ($mem['idmembresia'] == $row['idmembresia'])
							  echo "selected" ?>>
							<?= $mem['nombre'] ?>
						</option>
						<?
					}
					?>
				</select>
			</div>
			<div class="col-md-12 text-center">
				<b>Membresia</b>
				<?

				if ($_POST['tipo'] != 'Nuevo') {
					//formato de fecha fechapago y fechaexpiracion
					$row['fechapago'] = date("d-m-Y", strtotime($row['fechapago']));
					$row['fechaexpiracion'] = date("d-m-Y", strtotime($row['fechaexpiracion']));
					?>
					<table class="table table-bordered table-sm  table-striped">
						<thead>
							<tr>
								<th>Fecha Ultimo Pago</th>
								<th>Fecha de Expiracion</th>
								<th>Estatus en el Escaner</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<?
									if ($row['fechapago'] != "31-12-1969") {
										echo $row['fechapago'];
									} else {
										echo "Sin Registro de Pago <br>";
										?>
										<button id="registrar_pago" type="button"
											class="btn btn-default btn-sm btn-warning registrar_pago" idregistro="<?= $row[0] ?>"
											pago="0" duracion="0">
											<i class="bi bi-cash-coin"></i> Registrar Pago
										</button>
										<?
									}
									?>
								</td>
								<td>
									<?
									if ($row['fechaexpiracion'] != "31-12-1969") {
										//si la fecha de expiracion es menor a la fecha actual, mostrar en rojo que la membresia esta expirada
										if (strtotime($row['fechaexpiracion']) < strtotime(date("Y-m-d"))) {
											echo "<b class='badge badge-danger'>Expirada el " . $row['fechaexpiracion'] . "</b>";
											?><br>
											<button id="renovar_pago" type="button" class="btn btn-default btn-sm btn-info renovar_pago"
												idregistro="<?= $row[0] ?>" pago="0" duracion="0">Renovar Membresia </button>
											<?
										} else {
											echo "<span class='badge badge-success text-black'>Activa hasta el " . $row['fechaexpiracion'] . "</span>";
										}
									} else {
										echo "Sin Registro de Expiracion";
									}
									?>
								</td>
								<td>
									<?
									if ($row['dispositivo'] == 1) {
										echo "<span class='badge badge-primary text-black '>Cliente Sincronizado</span>";
									} else {
										echo "<button class = 'btn btn-primary sincronizarCliente' idregistro = " . $row['idclientes'] . ">Sincronizar</button>";
									}
									?>
								</td>
							</tr>
						</tbody>
					</table>
					<?
				} else {
					?>
					<table class="table table-bordered table-sm table-hover table-striped">
						<thead>
							<tr>
								<th>Favor de Registrar el Cliente</th>
							</tr>
						</thead>
					</table>
					<?
				}
				?>

			</div>

			<div class="col-md-12 text-center">
				<b>Observaciones</b>
				<textarea id="observaciones" class="form-control noresize"><?= $row['observaciones'] ?></textarea>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
		<?
		if ($_POST['tipo'] == 'Nuevo') {
			?>
			<button type="button" class="btn btn-primary" id="Guardar" idregistro="<?= $row[0] ?>"><i class="bi bi-floppy"></i>
				Guardar Nuevo</button>
			<?
		} else {
			?>
			<button type="button" class="btn btn-primary" id="editar_producto" idregistro="<?= $row[0] ?>"><i
					class="bi bi-pencil"></i> Guardar Modificaciones</button>
		</div>
		<?
		}

		exit();
}
// ---------------------------------- FIN CARGA DE MODAL ---------------------------------------------------------

// Sincronizar usuario con el dispositivo
if ($_POST['funcion'] == 'sincronizarCliente') {
	include('inc/conectar.php');
	$idregistro = $_POST['idregistro'];

	// Extraccion de la informacion del cliente de la DB
	$clienteDb = $consulta->query("SELECT * FROM clientes WHERE idclientes = " . $idregistro);
	foreach ($clienteDb as $cliente)
		;

	try {
		$controlador = new HikvisionService($ip, $username, $password);

		// Verificacion de fecha de expiracion
		$fechaInicio = date("Y-m-d\T00:00:00");
		if (!empty($cliente['fechaexpiracion']) && $cliente['fechaexpiracion'] != '1969-12-31') {
			// Le agregamos la hora límite al final del día
			$fechaFin = date("Y-m-d\T23:59:59", strtotime($cliente['fechaexpiracion']));
		} else {
			$fechaFin = date("Y-m-d\T23:59:59", strtotime("+1 day"));
		}

		$responseCreate = $controlador->createUser($idregistro, $cliente['nombre'], $fechaInicio, $fechaFin);

		if ($responseCreate['status'] == 200) {
			$consulta->query("UPDATE clientes SET dispositivo = 1 WHERE idclientes = " . $idregistro);

			echo json_encode([
				'success' => true,
				'mensaje' => 'Cliente sincronizado correctamente.',
				'hikvision' => $responseCreate,
			]);
		} else {
			echo json_encode([
				'success' => false,
				'mensaje' => 'El dispositivo rechazo la sincronizacion',
				'hikvision' => $responseCreate
			]);
		}
	} catch (Exception $e) {
		echo json_encode([
			'success' => false,
			'mensaje' => 'Error de conexion: ' . $e->getMessage(),
			'hikvision' => $responseCreate
		]);
	}

	exit();
}

?>


<!DOCTYPE html>
<html>

<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Datos de Clientes registrados</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-3.3.1.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap4.min.js"></script>
	<link rel="stylesheet" href="alertifyjs/css/alertify.css">
	<link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
	<script src="alertifyjs/alertify.js"></script>
</head>

<body>
	<?
	include("menu.php");
	?>
	<main class="page-content">
		<div class="container-fluid" style="text-align: center;">
			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-11">
					<div class="row">
						<div class="col-9 text-center">
							<h4><i class="bi bi-person-check"></i> Datos de clientes registrados</h4>
						</div>
						<div class="col-3">
							<button type="button" class="btn btn-success btn-md" data-toggle="modal" id="nuevo"
								data-target="#myModal">
								<span class="glyphicon glyphicon-apple" aria-hidden="true"></span>
								<i class="bi bi-plus-circle"></i> Cliente Nuevo
							</button>
						</div>

					</div>
					<div class="row">
						<div class="col-12">
							<table id="example" class="table table-sm table-hover" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>NoCliente</th>
										<th>Nombres</th>
										<th>Domicilio</th>
										<th>Genero</th>
										<th>Telefono</th>
										<th>Fecha Inicio</th>
										<th>Fecha Expiracion</th>
										<th>Imagen</th>
										<th>Fecha Registro</th>
										<th width="200"></th>
									</tr>
								</thead>
								<tbody id="resultados_productos">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</main>

	</div>


	<!-- MODAL PARA EDICION Y REGISTRO DE CLIENTES -->
	<div class="modal fade" id="myModal" tabindex="-1" data-target=".bs-example-modal-lg" role="dialog"
		aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
				</div>
				<div id="contenido_modal">
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
	<!-- FIN MODAL PARA EDICION Y REGISTRO DE CLIENTES -->

	<!-- MODAL PARA FOTO -->
	<div class="modal" tabindex="-1" role="dialog" id="modalFoto">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Toma de fotografía</h5>
				</div>
				<div class="modal-body">
					<div class="" id="alertaFotoModals"></div>
					<div class="d-flex justify-content-between">
						<img src="./img/placeholderFotoPerfil.jpg" alt="Vista previa" class="img-fluid w-75"
							id="previewFoto">
						<button id="tomarFotobtn" class="btn btn-primary">Tomar foto</button>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="guardarFotoModal"><i class="bi bi-floppy">
						</i>Guardar Foto</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i>
						Cancelar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- FIN MODAL PARA FOTO -->


	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-3.3.1.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="alertifyjs/alertify.js"></script>
	<script src="js/dataTables.bootstrap4.min.js"></script>

	<script>
		$(document).ready(function (e) {
			Carga_Productos();
			$('#example').DataTable({
				language: {
					processing: "Procesando...",
					search: "Buscar por Nombre, Apellido o NoCliente:",
					lengthMenu: "Mostrar _MENU_ ",
					info: "Mostrando _START_ de _END_ de Total de  _TOTAL_ resultados",
					infoEmpty: "Sin Registros 0 de 0 de 0 Mostrando",
					infoFiltered: "(Filtrando de _MAX_ Filtrados)",
					infoPostFix: "",
					loadingRecords: "Chargement en cours...",
					zeroRecords: "Sin Resultados",
					emptyTable: "Sin Resultados en la Tabla",
					paginate: {
						first: "Primero",
						previous: "Anterior",
						next: "Siguiente",
						last: "Ultimo"
					},
					aria: {
						sortAscending: ": Ordenar Ascendente",
						sortDescending: ": Ordenar Desendente"
					}
				}
			});
			$(document).on("click", "#nuevo", function () {
				Carga_Modal("Nuevo", 0);
				Carga_Folio();
			});


			$(document).on("change", "#idmembresia", function () {
				Cargar_Costos();
			});

			function Cargar_Costos() {
				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "Cargar_Costos",
						idregistro: $("#idmembresia option:selected").val()
					}),
					dataType: "html",
					async: false,
					success: function (msg) {
						console.log(msg);
						var datos = msg.split("|");

						$("#registrar_pago").attr("pago", datos[0]);
						$("#registrar_pago").attr("duracion", datos[1]);
						$("#renovar_pago").attr("pago", datos[0]);
						$("#renovar_pago").attr("duracion", datos[1]);
					}
				});
			}
			//registrar_pago
			$(document).on("click", ".registrar_pago, #renovar_pago", function () {
				var idregistro = $(this).attr("idregistro");
				var pago = $(this).attr("pago");
				var duracion = $(this).attr("duracion");

				alertify.confirm("Pago Membresia", 'Estas Seguro de Registrar el Pago para este Cliente $' + pago + ' por ' + duracion + ' dias', function () {
					$.ajax({
						type: "POST",
						url: "clientes.php",
						data: ({
							funcion: "Registrar_Pago",
							idregistro: idregistro,
							importepago: pago,
							duracion: duracion
						}),
						dataType: "html",
						async: false,
						success: function (msg) {
							alertify.success("Pago registrado Exitosamente ");
							//retardo para que se muestre el mensaje de pago registrado exitosamente antes de recargar la pagina
							setTimeout(function () {
								Carga_Modal("Editar", idregistro);
								Cargar_Costos();
							}, 2000);
						}
					});
				}, function () {
					alertify.error('Cancelado')
				});
			});

			$(document).on("click", ".editar", function () {
				var idregistro = $(this).attr("idregistro");
				Carga_Modal("Editar", idregistro);
				Cargar_Costos();
			});

			$(document).on("click", ".eliminar", function () {
				var idregistro = $(this).attr("idregistro");
				alertify.confirm('Estas Seguro de Eliminar el Cliente', function () {
					alertify.success('Si');
					$.ajax({
						type: "POST",
						url: "clientes.php",
						data: ({
							funcion: "Eliminar",
							idregistro: idregistro
						}),
						dataType: "html",
						async: false,
						success: function (msg) {
							alertify.success("Cliente eliminado Exitosamente ");
							window.location = "clientes.php";
						}
					});
				}, function () {
					alertify.error('Cancelado')
				});
			});

			$(document).on('click', ".foto", function () {
				var idregistro = $(this).attr('idregistro');
				Carga_Modal_Foto(idregistro);
			});

			// Asignar foto al cliente despues de tomar la foto, se asigna al cliente en el hikvision y se guarda la ruta en la base de datos
			$(document).on('click', '#guardarFotoModal', function () {
				var idregistro = $(this).attr('idregistro');
				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "asignarFoto",
						idregistro: idregistro
					}),
					dataType: "json",
					async: false,
					success: function (response) {
						//alert(JSON.stringify(response));
						if (response.success) {
							alertify.success(response.mensaje);

							setTimeout(function () {
								window.location = "clientes.php";
							}, 2000);
						} else {
							$('#alertaFotoModals').html(
								'<div class="alert alert-danger alert-dismissible fade show text-sm" role="alert">' +
								'<strong>¡Atención!</strong> ' + response.mensaje +
								'<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
								'<span aria-hidden="true">&times;</span></button></div>'
							);

							// Limpiamos el src del preview para obligarlo a tomar otra foto
							$("#previewFoto").attr("src", "./img/placeholderFotoPerfil.jpg");

							alertify.error('Vuelve a tomar la fotografía.');
						}
					},
					error: function (response) {
						alertify.error('Error en la comunicación con el dispositivo.');
					}
				});
			});


			// Limpieza de foto temporal si se cierra el modal sin guardar la foto
			$('#modalFoto').on('hidden.bs.modal', function () {
				let idregistro = $('#guardarFotoModal').attr('idregistro');
				$("#previewFoto").attr("src", "./img/placeholderFotoPerfil.jpg");
				$('#alertaFotoModals').html('');
				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "limpiarFotoTemporal",
						idregistro: idregistro
					}),
				});
			});

			function Carga_Modal(tipo, idregistro) {
				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "Carga_Modal",
						tipo: tipo,
						idregistro: idregistro
					}),
					dataType: "html",
					async: false,
					success: function (msg) {
						//var e=prompt("",msg);
						$("#contenido_modal").html(msg);
					}
				});
			}

			function Carga_Modal_Foto(idregistro) {
				$('#guardarFotoModal').attr('idregistro', idregistro);
			}

			// Captura de foto y cargada 
			$(document).on("click", "#tomarFotobtn", function (e) {
				let idregistro = $("#guardarFotoModal").attr('idregistro');
				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "tomarFotografia",
						idregistro: idregistro,
					}),
					dataType: "json",
					success: function (response) {
						if (response.success) {
							let timestamp = new Date().getTime();
							$("#previewFoto").attr(
								"src",
								"capturas/" + response.imagen + "?t=" + timestamp
							);
							alertify.success('Foto tomada correctamente.');
						} else {
							alertify.error('Error al tomar la foto.');
						}
					}
				});
			});

			// Sincronizar cliente con el dispositivo
			$(document).on("click", ".sincronizarCliente", function (e) {
				var idregistro = $(this).attr("idregistro");

				alertify.confirm('Sincronizar Cliente', '¿Deseas sincronizar este cliente con el dispositivo?', function () {
					$.ajax({
						type: "POST",
						url: "clientes.php",
						data: ({
							funcion: "sincronizarCliente",
							idregistro: idregistro
						}),
						dataType: "json",
						async: false,
						success: function (response) {
							if (response.success) {
								alertify.success('Cliente sincronizado correctamente.');
								setTimeout(function () {
									Carga_Modal("Editar", idregistro);
									Cargar_Costos();
								}, 1500);
							} else {
								alertify.error('Error al sincronizar el cilente.');
							}
						},
						error: function (response) {
							alertify.error('Error en la comunicacion con el dispositivo.');
						}
					});
				}, function () {
					alertify.error('Sincronización cancelada');
				});
			});

			// Guardar el cliente
			$(document).on("click", "#Guardar", function (e) {
				if ($("#nombre").val() == "") {
					alertify.error("Ingresa un Nombre");
					$("#nombre").focus();
					return false;
				}
				if ($("#dia").val() == "") {
					alertify.error("Ingresa un dia");
					$("#dia").focus();
					return false;
				}

				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "Guardar",
						codigo: $("#codigo").val(),
						nombre: $("#nombre").val(),
						domicilio: $("#domicilio").val(),
						idmembresia: $("#idmembresia option:selected").val(),
						membresia: $("#idmembresia option:selected").text(),
						genero: $("#genero option:selected").val(),
						telefono: $("#telefono").val(),
						observaciones: $("#observaciones").val()
					}),
					dataType: "json",
					async: false,
					success: function (msg) {
						alert(JSON.stringify(msg));
						console.log(msg);
						alertify.success("Cliente Agredado Exitosamente ");
						setTimeout(function () {
							window.location = "clientes.php";
						}, 2000);
					}
				});
			});
			// Edicion del cliente
			$(document).on("click", "#editar_producto", function (e) {
				var idregistro = $(this).attr("idregistro");
				if ($("#nombre").val() == "") {
					alertify.error("Ingresa un Nombre");
					$("#nombre").focus();
					return false;
				}

				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "Editar_Productos",
						codigo: $("#codigo").val(),
						nombre: $("#nombre").val(),
						idmembresia: $("#idmembresia option:selected").val(),
						membresia: $("#idmembresia option:selected").text(),
						telefono: $("#telefono").val(),
						genero: $("#genero option:selected").val(),
						domicilio: $("#domicilio").val(),
						observaciones: $("#observaciones").val(),
						idregistro: idregistro
					}),
					dataType: "json",
					async: false,
					success: function (response) {
						if (response.success) {
							alertify.success(response.mensaje);
							setTimeout(function () {
								window.location = "clientes.php";
							}, 2000);
						} else {
							alertify.error('Error al modificar.', response.mensaje);
						}
					}
				});
			});
			function Carga_Folio() {
				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "Carga_Folio"
					}),
					dataType: "html",
					async: false,
					success: function (msg) {
						$("#codigo").val(msg);
					}
				});
			}
			function Carga_Productos() {
				$.ajax({
					type: "POST",
					url: "clientes.php",
					data: ({
						funcion: "Carga_Clientes"
					}),
					dataType: "html",
					async: false,
					success: function (msg) {
						$("#resultados_productos").html(msg);
						//$('#example').DataTable();
					}
				});
			}


		});
	</script>
</body>