<?
include("HikvisionService.php");

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
			<td>
				<button type="button" class="btn btn-default btn-sm btn-primary">
					<?= $row['codigo'] ?>
				</button>
			</td>
			<td><?= $row['nombre'] ?></td>
			<td><?= $row['domicilio'] ?></td>
			<td><?= $row['genero'] ?></td>
			<td><?= $row['telefono'] ?></td>
			<td><?= substr($row['fechapago'], 0, 10) ?></td>
			<td><?= substr($row['fechaexpiracion'], 0, 10) ?></td>
			<td>
				<img src="<?= $row['imagen'] ?>" width="100">
			</td>
			<td><?= substr($row['fecharegistro'], 0, 10) ?></td>
			<td>
				<button type="button" class="btn btn-default btn-sm btn-info editar" data-toggle="modal" data-target="#myModal"
					idregistro="<?= $row[0] ?>">
					<i class="bi bi-pencil"></i> Editar
				</button>
				<button type="button" class="btn btn-default btn-sm btn-danger eliminar" idregistro="<?= $row[0] ?>">
					<i class="bi bi-trash3"></i> Eliminar
				</button>
				<button type="button" class="btn btn-default btn-sm btn-secondary foto" data-toggle="modal"
					data-target="#modalFoto" idregistro="<?= $row[0] ?>">
					<i class="bi bi-camera"></i>Actualizar Foto
				</button>
			</td>
		</tr>
		<?
	}
	exit();
}

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

if ($_POST['funcion'] == 'Guardar') {
	include("inc/conectar.php");
	include("controlador_lector.php");

	$controlador = new HikvisionService("192.0.0.64", "admin", "simbiosis2026");

	// Consulta del proximo ID
	$Auto = $consulta->query("SELECT MAX(idclientes)+1 AS Auto_increment FROM clientes");
	foreach ($Auto as $Autocontador)
		;
	// Nuevo ID
	$idNuevo = $Autocontador["Auto_increment"];
	// Creacion del codigo mediante ID
	$CODIGO = str_pad($Autocontador["Auto_increment"], 6, "0", STR_PAD_LEFT);
	// Insercion BD
	$Auto = $consulta->query("INSERT INTO clientes SET codigo='" . $CODIGO . "', nombre='" . $_POST['nombre'] . "', domicilio='" . $_POST['domicilio'] . "', idmembresia='" . $_POST['idmembresia'] . "', membresia='" . $_POST['membresia'] . "', telefono='" . $_POST['telefono'] . "', observaciones='" . $_POST['observaciones'] . "', genero='" . $_POST['genero'] . "' ");
	foreach ($Auto as $Autocontador)
		;

	$membresia = $consulta->prepare("SELECT duracion FROM membresias WHERE id = '" . $_POST['idmembresia'] . "'");

	// id, nombre, membresiaInicio, membresiaFinal
	$response = $controlador->createUser($idNuevo, $_POST['nombre'], );
	echo $repsonse;
	//echo $CODIGO;
	exit();
}

if ($_POST['funcion'] == 'Editar_Productos') {
	include("inc/conectar.php");
	$Auto = $consulta->query("UPDATE clientes SET codigo='" . $_POST['codigo'] . "', nombre='" . $_POST['nombre'] . "', domicilio='" . $_POST['domicilio'] . "', idmembresia='" . $_POST['idmembresia'] . "', membresia='" . $_POST['membresia'] . "', telefono='" . $_POST['telefono'] . "', observaciones='" . $_POST['observaciones'] . "', genero='" . $_POST['genero'] . "' WHERE idclientes=" . $_POST['idregistro']);
	foreach ($Auto as $Autocontador)
		;
	exit();
}
if ($_POST['funcion'] == 'Registrar_Pago') {
	include("inc/conectar.php");
	$duracion = $_POST['duracion'];
	$fechaexpiracion = date("Y-m-d H:i:s", strtotime("+$duracion day"));
	$Auto = $consulta->query("UPDATE clientes SET fechapago='" . date("Y-m-d H:i:s") . "', importepago='" . $_POST['importepago'] . "', fechaexpiracion='" . $fechaexpiracion . "' WHERE idclientes=" . $_POST['idregistro']);
	foreach ($Auto as $Autocontador)
		;
	try {
		$controlador = new HikvisionService('192.0.0.64', 'admin', 'simbiosis2026');

		$fechaInicio = date("Y-m-d\TH:i:s");
		$fechaFin = date("Y-m-d\TH:i:s", strtotime("+$duracion day"));

		$respuesta = $controlador->updateUserExpiration(
			$_POST['idregistro'],
			$fechaInicio,
			$fechaFin
		);
		//insertanmos el pago en la tabla de movimientoscaja
		$Auto = $consulta->query("INSERT INTO movimientoscaja SET idclientes=" . $_POST['idregistro'] . ", importe='" . $_POST['importepago'] . "', fecha='" . date("Y-m-d H:i:s") . "', tipo='Membresia', observaciones='Pago de Membresia', idusuarios=" . $_SESSION['SISTEMA']['idusuarios'] . ", usuarios='" . $_SESSION['SISTEMA']['usuario'] . "'");
		foreach ($Auto as $Autocontador)
			;

	} catch (Exception $e) {
		echo "Error en la comunicación: " . $e->getMessage();
	}


	exit();
}

if ($_POST['funcion'] == 'Eliminar') {
	include("inc/conectar.php");
	$Auto = $consulta->query("UPDATE clientes SET fechabaja='" . date("Y-m-d H:i:s") . "' WHERE idclientes=" . $_POST['idregistro']);
	foreach ($Auto as $Autocontador)
		;
	try {
		$controlador = new HikvisionService('192.0.0.64', 'admin', 'simbiosis2026');
		$controlador->deleteUser($_POST['idregistro']);
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
							  echo "selected" ?>><?= $mem['nombre'] ?></option>
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
					<table class="table table-bordered table-sm table-success table-hover table-striped">
						<thead>
							<tr>
								<th>Fecha Ultimo Pago</th>
								<th>Fecha de Expiracion</th>
								<th>Registro Rostro</th>
								<th>Dispositivo</th>
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
											echo "<span class='badge badge-success'>Activa hasta el " . $row['fechaexpiracion'] . "</span>";
										}
									} else {
										echo "Sin Registro de Expiracion";
									}
									?>
								</td>
								<td>
									<? if ($row['registrorostro'] == 1) { ?>
										<span class="badge badge-success">Registrado</span>
									<? } else { ?>
										<button type="button" class="btn btn-default btn-sm btn-warning registrar_rostro"
											idregistro="<?= $row[0] ?>">
											<i class="bi bi-camera"></i> Registrar Rostro
										</button>
									<? } ?>
								</td>
								<td>
									<? if ($row['dispositivo'] == 1) {
										//valido que el dispositivo este registrado en el lector de huellas
										try {
											$controlador = new HikvisionService('192.0.0.64', 'admin', 'simbiosis2026');

											$fechaInicio = date("Y-m-d\TH:i:s");
											$fechaFin = date("Y-m-d\TH:i:s", strtotime("+$duracion day"));

											$respuesta = $controlador->getUserByID($_POST['idregistro']);
											// Revisamos si el estatus interno de Hikvision fue exitoso
											if ($respuesta['status'] == "200") {
												?>
												<b class="badge badge-success">Registrado</b>
												<?
											} else {
												?>
												<span class="badge badge-danger">No Registrado en el Lector</span><br>
												<button type="button" class="btn btn-default btn-sm btn-warning registrar_dispositivo"
													idregistro="<?= $idclientes ?>">Registrar</button>
												<?
											}
										} catch (Exception $e) {
											?>
											<span class="badge badge-danger">Error de Comunicación</span>
											<?
										}


										?>
									<? } else { ?>
										<button type="button" class="btn btn-default btn-sm btn-warning registrar_dispositivo"
											idregistro="<?= $row[0] ?>">
											<i class="bi bi-camera"></i> Registrar Dispositivo
										</button>
									<? } ?>
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

// Toma la fotografia y la guarda en capturas/rostro{$idregistro}.jpg
if ($_POST['funcion'] == 'tomarFotografia') {
	$idregistro = $_POST['idregistro'];

	$hikvision = new HikvisionService(
		'192.0.0.64',
		'admin',
		'simbiosis2026'
	);

	$ok = $hikvision->takeLivePicture($idregistro);

	header('Content-Type: application/json');
	echo json_encode([
		'success' => $ok,
		'imagen' => "rostro" . $idregistro . ".jpg"
	]);

	exit();
}
// Asignacion de foto en hikvision y acutalizacion de direccion en bd
if ($_POST['funcion'] == 'asignarFoto') {
	$idregistro = $_POST['idregistro'];
	$imagePath = "capturas/rostro{$idregistro}.jpg";

	$hikvision = new HikvisionService(
		'192.0.0.64',
		'admin',
		'simbiosis2026'
	);

	// Agrega la foto al hikvision y dependiendo el status de la respuesta continua con la bd
	$response = $hikvision->updateFace($idregistro);

	// insercion a la bd
	if ($response['status'] == 200) {

		$stmt = $consulta->prepare(
			"UPDATE clientes
             SET imagen = ?
             WHERE idclientes = ?"
		);

		$stmt->execute([
			$imagePath,
			$idregistro
		]);
	}

	header('Content-Type: application/json');
	echo json_encode($response);
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

	<!-- MODAL PARA FOTO -->
	<div class="modal" tabindex="-1" role="dialog" id="modalFoto">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div>
						<img src="" alt="Vista previa" class="img-fluid" id="previewFoto">
						<button id="tomarFotobtn" class="btn btn-primary">Tomar foto</button>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="guardarFotoModal">Guardar Foto</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>


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
					alertify.success('Si');
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
							console.log(msg);
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

			$(document).on("click", ".registrar_rostro", function () {
				var idregistro = $(this).attr("idregistro");
				alertify.confirm('Estas Seguro de Registrar el Rostro para este Cliente', function () {
					alertify.success('Si');
					$.ajax({
						type: "POST",
						url: "clientes.php",
						data: ({
							funcion: "Registrar_Rostro",
							idregistro: idregistro
						}),
						dataType: "html",
						async: false,
						success: function (msg) {
							console.log("Registrando Rostro" + msg);
							alertify.success("Rostro registrado Exitosamente ");
							//window.location="clientes.php";
						}
					});
				}, function () {
					alertify.error('Cancelado')
				});
			});

			$(document).on("click", ".registrar_dispositivo", function () {
				var idregistro = $(this).attr("idregistro");
				alertify.confirm('Estas Seguro de Registrar el Dispositivo para este Cliente', function () {
					alertify.success('Si');
					$.ajax({
						type: "POST",
						url: "clientes.php",
						data: ({
							funcion: "Registrar_Dispositivo",
							nombre: $("#nombre").val(),
							idregistro: idregistro
						}),
						dataType: "html",
						async: false,
						success: function (msg) {
							console.log(msg);
							alertify.success("Dispositivo registrado Exitosamente ");
							//tiempo de espera
							setTimeout(function () {
								//window.location="clientes.php";
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
				alert(idregistro);
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
							$("#previewFoto").attr(
								"src",
								"capturas/" + response.imagen
							);
							alertify.success('Foto tomada correctamente.');
						} else {
							alertify.error('Error al tomar la foto.');
						}
					}
				});
			});


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
					dataType: "html",
					async: false,
					success: function (msg) {
						alert(msg);
						console.log(msg);
						alertify.success("Cliente Agredado Exitosamente ");
					}
				});
			});
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
					dataType: "html",
					async: false,
					success: function (msg) {
						console.log(msg);
						alertify.success("Cliente Modificado Exitosamente ");
						window.location = "clientes.php";
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