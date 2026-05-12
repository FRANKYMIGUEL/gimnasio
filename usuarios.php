<?
if ($_POST['funcion'] == 'Carga_Usuarios') {
	include('inc/conectar.php');
	$Auto = $consulta->query("SELECT usuarios.*, sucursales.nombre AS sucursal  FROM usuarios LEFT JOIN sucursales ON sucursales.idsucursales=usuarios.idsucursal WHERE usuarios.activo = 1 ORDER BY nombre");
	foreach ($Auto as $row) {
?>
		<tr>
			<td class="text-uppercase"><?= $row['nombre'] ?></td>
			<td class="text-uppercase"><?= $row['usuario'] ?></td>
			<td class="text-uppercase"><?= $row['sueldo'] ?></td>
			<td class="text-uppercase"><?= $row['fecha_ingreso'] ?></td>
			<td class="text-uppercase"><?= $row['sucursal'] ?></td>
			<td class="text-uppercase"><?= $row['tipo'] ?></td>
			<td class="text-center" width="250">
				<button type="button" class="btn btn-primary btn-sm editar" registros="<?= $row[0] ?>">Editar</button>
				<a href="permisos.php?id=<?= $row[0] ?>" style="color:#FFF; text-decoration:none;" class="btn btn-warning btn-sm">Permisos</a>
				<button type="button" class="btn btn-danger btn-sm eliminar" registros="<?= $row[0] ?>">Eliminar</button>
			</td>
		</tr>
	<?
	}
	exit();
}

if ($_POST['funcion'] == 'Eliminar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE usuarios SET activo=0 WHERE idusuarios=" . $_POST['idregistro'] . " ");
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Guardar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("INSERT INTO usuarios SET nombre='" . $_POST['nombre'] . "', tipo='" . $_POST['tipo'] . "', fecha_ingreso='" . $_POST['fecha_ingreso'] . "', idsucursal='" . $_POST['idsucursal'] . "', sueldo='" . $_POST['sueldo'] . "', usuario='" . $_POST['usuario'] . "', pass='" . $_POST['pass'] . "', fecha_creacion='" . date("Y-m-d H:i:s") . "' ");
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Editar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE usuarios SET nombre='" . $_POST['nombre'] . "', tipo='" . $_POST['tipo'] . "', fecha_ingreso='" . $_POST['fecha_ingreso'] . "', idsucursal='" . $_POST['idsucursal'] . "', usuario='" . $_POST['usuario'] . "', sueldo='" . $_POST['sueldo'] . "', pass='" . $_POST['pass'] . "' WHERE idusuarios=" . $_POST['idregistro']);
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Carga_Modal') {
	include('inc/conectar.php');
	if ($_POST['tipo'] != 'Nuevo') {
		$Auto = $consulta->query("SELECT * FROM usuarios WHERE idusuarios=" . $_POST['idregistro'] . "");
		foreach ($Auto as $row);
	}
	?>
	<div class="modal-body modal-lg">
		<div class="row">
			<div class="col-6 font-weight-bold">
				<label>Nombre</label>
				<input type="text" class="form-control text-uppercase" id="nombre" value="<?= $row['nombre'] ?>" placeholder="Nombre ">
			</div>
			<div class="col-6 font-weight-bold">
				<label>Usuario</label>
				<input type="text" class="form-control text-uppercase" value="<?= $row['usuario'] ?>" id="usuario" placeholder="Usuario">
			</div>
			<div class="col-3 font-weight-bold">
				<label>Password</label>
				<input type="password" class="form-control" value="<?= $row['pass'] ?>" id="pass">
			</div>
			<div class="col-3 font-weight-bold">
				<label>Fecha Ingreso</label>
				<input type="date" class="form-control text-uppercase" value="<?= $row['fecha_ingreso'] ?>" id="fecha_ingreso" placeholder="Ingreso">
			</div>
			<div class="col-3 font-weight-bold">
				<label>Sueldo</label>
				<input type="text" class="form-control text-uppercase" value="<?= $row['sueldo'] ?>" id="sueldo" placeholder="sueldo">
			</div>
			<div class="col-3 font-weight-bold">
				<label>Tipo</label>
				<select class="form-control text-uppercase" id="tipo">
					<option value="admin">Administrador</option>
					<option value="user">Usuario</option>
				</select>
			</div>
			<div class="col-3 font-weight-bold">
				<label>Sucursal</label>
				<select class="form-control text-uppercase" id="idsucursal">
					<option value="-1">Seleccione</option>
					<?
					$Auto = $consulta->query("SELECT * FROM sucursales WHERE activo=1");
					foreach ($Auto as $row) {
					?>
						<option value="<?= $row[0] ?>"><?= $row['nombre'] ?></option>
					<?
					}
					?>
				</select>
			</div>
		</div>
	</div>
	<div class="modal-footer modal-lg">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
		<?
		if ($_POST['tipo'] != 'Nuevo') {
		?>
			<button type="button" class="btn btn-primary" id="editar" registros="<?= $_POST['idregistro'] ?>">Modificar Usuario</button>
		<?
		} else {
		?>
			<button type="button" class="btn btn-primary" id="guardar">Guardar Usuario</button>
		<?
		}
		?>
	</div>
<?
	exit();
}
?>

<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
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
	<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Usuarios</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div id="resultados_modal">
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 text-left">
				<?
				include("menu1.php");
				?>
			</div>
			<div class="col-md-10">
				<div class="row">
					<div class="col">
						&nbsp;
					</div>
					<div class="col-6 text-center ">
						<h3>Usuarios</h3>
					</div>
					<div class="col">
						<button type="button" id="Nuevo" class="btn btn-info btn-sm">Agregar Usuarios</button>
						<button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>
						<button type="button" id="carga_modal_alumnos" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#modalalumnos" data-whatever="@mdo"></button>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<table id="example" class="table table-sm table-hover" style="width:100%">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Usuario</th>
									<th>Sueldo</th>
									<th>Fecha Ingreso</th>
									<th>Sucursal</th>
									<th>Tipo</th>
									<th>Opciones</th>
								</tr>
							</thead>
							<tbody id="registros_tabla">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<!-- .//container -->
<script>
	$(document).ready(function() {
		$(document).on('click', '#Nuevo', function() {
			$('#carga_modal').click();
			Cargar_Modal("Nuevo", 0);
		});

		$(document).on('click', '.editar', function() {
			var idregistro = $(this).attr("registros");
			$('#carga_modal').click();
			Cargar_Modal("Editar", idregistro);
		});

		Carga_Tabla();

		function Carga_Tabla() {
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Carga_Usuarios"
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					$("#registros_tabla").html(msg);
				}
			});
		}
		$(document).on("click", "#guardar", function(e) {
			if ($("#nombre").val() == "") {
				alertify.error("Ingresa un Nombre ");
				$("#nombre").focus();
				return false;
			}
			if ($("#usuario").val() == "") {
				alertify.error("Ingresa un Usuario");
				$("#usuario").focus();
				return false;
			}
			if ($("#pass").val() == "") {
				alertify.error("Ingresa una pass");
				$("#pass").focus();
				return false;
			}
			if ($("#fecha_ingreso").val() == "") {
				alertify.error("Ingresa un Fecha de Ingreso");
				$("#fecha_ingreso").focus();
				return false;
			}
			if ($("#idsucursal option:selected").val() == "-1") {
				alertify.error("Selecciona una Sucursal");
				$("#idsucursal").focus();
				return false;
			}
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Guardar",
					nombre: $("#nombre").val(),
					usuario: $("#usuario").val(),
					pass: $("#pass").val(),
					sueldo: $("#sueldo").val(),
					fecha_ingreso: $("#fecha_ingreso").val(),
					idsucursal: $("#idsucursal option:selected").val(),
					tipo: $("#tipo option:selected").val()
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					alertify.success("Usuario Agredado Exitosamente ");
					window.location = "<?= $_SERVER["PHP_SELF"] ?>";
				}
			});
		});
		$(document).on("click", "#editar", function(e) {
			var idregistro = $(this).attr("registros");
			if ($("#nombre").val() == "") {
				alertify.error("Ingresa un Nombre ");
				$("#nombre").focus();
				return false;
			}
			if ($("#usuario").val() == "") {
				alertify.error("Ingresa un Usuario");
				$("#usuario").focus();
				return false;
			}
			if ($("#pass").val() == "") {
				alertify.error("Ingresa una pass");
				$("#pass").focus();
				return false;
			}
			if ($("#fecha_ingreso").val() == "") {
				alertify.error("Ingresa un Fecha de Ingreso");
				$("#fecha_ingreso").focus();
				return false;
			}
			if ($("#idsucursal option:selected").val() == "-1") {
				alertify.error("Selecciona una Sucursal");
				$("#idsucursal").focus();
				return false;
			}
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Editar",
					nombre: $("#nombre").val(),
					usuario: $("#usuario").val(),
					pass: $("#pass").val(),
					sueldo: $("#sueldo").val(),
					fecha_ingreso: $("#fecha_ingreso").val(),
					idsucursal: $("#idsucursal option:selected").val(),
					idregistro: idregistro,
					tipo: $("#tipo option:selected").val()
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					alertify.success("Usuario Modificado Exitosamente ");
					window.location = "<?= $_SERVER["PHP_SELF"] ?>";
				}
			});
		});

		function Cargar_Modal(tipo, idregistro) {

			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Carga_Modal",
					tipo: tipo,
					idregistro: idregistro
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					$("#resultados_modal").html(msg);
				}
			});
		}
		$('#example').DataTable({
			language: {
				processing: "Procesando...",
				search: "Buscar:",
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

		$(document).on("click", ".eliminar", function() {
			var idregistro = $(this).attr("registros");
			alertify.confirm("Eliminacion", '¿Estas Seguro de Eliminar el Usuario?', function() {
				$.ajax({
					type: "POST",
					url: "<?= $_SERVER["PHP_SELF"] ?>",
					data: ({
						funcion: "Eliminar",
						idregistro: idregistro
					}),
					dataType: "html",
					async: false,
					success: function(msg) {
						alertify.success("Usuario eliminado Exitosamente " + msg);
						window.location = "<?= $_SERVER["PHP_SELF"] ?>";

					}
				});
			}, function() {
				alertify.error('Cancelado')
			});
		});
	});
</script>