<?
if ($_POST['funcion'] == 'Carga_Usuarios') {
	include('inc/conectar.php');
	$Auto = $consulta->query("SELECT *  FROM usuarios ORDER BY nombre");
	foreach ($Auto as $row) {
?>
		<tr>
			<td class="text-uppercase"><?= $row['nombre'] ?></td>
			<td class="text-uppercase"><?= $row['usuario'] ?></td>
			<td class="text-uppercase"><?= $row['tipo']?></td>
			<td class="text-center" width="250">
				<button type="button" class="btn btn-primary btn-sm editar" registros="<?= $row[0] ?>" data-toggle="modal" data-target="#exampleModal">Editar</button>
				<?
				if ($row['idusuarios'] != 1) {
					if($row['inactivo'] == ""){
				?>
				<button type="button" class="btn btn-danger btn-sm eliminar" registros="<?= $row[0] ?>">Eliminar</button>
				<?
					}else{
				?>
				<button type="button" class="btn btn-success btn-sm restaurar" registros="<?= $row[0] ?>">Restaurar</button>
				<?	
					}
				}
				?>
			</td>
		</tr>
	<?
	}
	exit();
}

if ($_POST['funcion'] == 'Eliminar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE usuarios SET inactivo='".date("Y-m-d H:i:s")."' WHERE idusuarios=" . $_POST['idregistro'] . " ");
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Restaurar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE usuarios SET inactivo=null WHERE idusuarios=" . $_POST['idregistro'] . " ");
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Guardar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("INSERT INTO usuarios SET nombre='" . $_POST['nombre'] . "', tipo='" . $_POST['tipo'] . "', pass='" . $_POST['pass'] . "', fecha_creacion='" . date("Y-m-d H:i:s") . "' ");
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Editar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE usuarios SET nombre='" . $_POST['nombre'] . "', tipo='" . $_POST['tipo'] . "', pass='" . $_POST['pass'] . "' WHERE idusuarios=" . $_POST['idregistro']);
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
			<div class="col-3 font-weight-bold">
				<label>Password</label>
				<input type="password" class="form-control" value="<?= $row['pass'] ?>" id="pass">
			</div>
			
			<div class="col-3 font-weight-bold">
				<label>Tipo</label>
				<select class="form-control text-uppercase" id="tipo">
					<option value="Administrador">Administrador</option>
					<option value="Usuario">Usuario</option>
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
					
				</div>
				<div id="resultados_modal">
				</div>
			</div>
		</div>
	</div>

<?
include("menu.php");
?>
  <main class="page-content">
    <div class="container-fluid" style="text-align: center;">
		<div class="row">
			<div class="col-2">
				&nbsp;
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
						<button type="button" id="Nuevo" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal">Agregar Usuarios</button>
						
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<table id="example" class="table table-sm table-hover" style="width:100%">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Usuario</th>
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

  </main>

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
			
			if ($("#pass").val() == "") {
				alertify.error("Ingresa una pass");
				$("#pass").focus();
				return false;
			}
			
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Guardar",
					nombre: $("#nombre").val(),
					pass: $("#pass").val(),
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
			if ($("#pass").val() == "") {
				alertify.error("Ingresa una pass");
				$("#pass").focus();
				return false;
			}
			
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Editar",
					nombre: $("#nombre").val(),
					pass: $("#pass").val(),
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
		$(document).on("click", ".restaurar", function() {
			var idregistro = $(this).attr("registros");
			alertify.confirm("Restauracion", '¿Estas Seguro de Restaurar el Usuario?', function() {
				$.ajax({
					type: "POST",
					url: "<?= $_SERVER["PHP_SELF"] ?>",
					data: ({
						funcion: "Restaurar",
						idregistro: idregistro
					}),
					dataType: "html",
					async: false,
					success: function(msg) {
						//console.log(msg);
						alertify.success("Usuario restaurado Exitosamente " + msg);
						window.location = "<?= $_SERVER["PHP_SELF"] ?>";

					}
				});
			}, function() {
				alertify.error('Cancelado')
			});
		});
	});
</script>