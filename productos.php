<?
if ($_POST['funcion'] == 'Carga') {
	include('inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM productos WHERE fechabaja IS NULL ORDER BY codigo");
	foreach ($Auto as $row) {
?>
		<tr align="center">
			<td class="text-uppercase"><?= $row['codigo'] ?></td>
			<td class="text-uppercase" align="left"><?= $row['nombre'] ?></td>
			<td class="text-uppercase">$ <?= number_format( $row['precio'],2) ?></td>
			<td class="text-center" width="200">
				<button type="button" class="btn btn-primary btn-sm editar" registros="<?= $row[0] ?>"><i class="bi bi-pencil"></i> Editar</button>
				<button type="button" class="btn btn-danger btn-sm eliminar" registros="<?= $row[0] ?>"><i class="bi bi-trash3"></i> Eliminar</button>
			</td>
		</tr>
	<?
	}
	exit();
}
if ($_POST['funcion'] == 'Eliminar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE productos SET fechabaja='" . date("Y-m-d H:i:s") . "' WHERE idproductos=" . $_POST['idregistro'] . " ");
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Guardar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("INSERT INTO productos SET nombre='".$_POST['nombre']."', codigo='".$_POST['codigo']."', precio=".$_POST['precio']."");
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Editar') {
	include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE productos SET nombre='" . $_POST['nombre'] . "', codigo='" . $_POST['codigo'] . "', precio='" . $_POST['precio'] . "' WHERE idproductos=" . $_POST['idregistro']);
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Carga_Modal') {
	include('inc/conectar.php');
	if ($_POST['tipo'] != 'Nuevo') {
		$Auto = $consulta->query("SELECT * FROM productos WHERE idproductos=" . $_POST['idregistro'] . "");
		foreach ($Auto as $row);
	}
	?>
	<div class="modal-body modal-lg">
		<div class="row">
			<div class="col-3 font-weight-bold">
				<label>Codigo</label>
				<input type="text" class="form-control" id="codigo" value="<?= $row['codigo'] ?>" placeholder="Codigo ">
			</div>
			<div class="col-6 font-weight-bold">
				<label>Nombre</label>
				<input type="text" class="form-control text-uppercase" id="nombre" value="<?= $row['nombre'] ?>" placeholder="Nombre ">
			</div>
			<div class="col-3 font-weight-bold">
				<label>Precio</label>
				<input type="text" class="form-control text-left" value="<?= $row['precio'] ?>" id="precio">
			</div>
		</div>
	</div>
	<div class="modal-footer modal-lg">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
		<?
		if ($_POST['tipo'] != 'Nuevo') {
		?>
			<button type="button" class="btn btn-primary" id="editar" registros="<?= $_POST['idregistro'] ?>"><i class="bi bi-pencil"></i> Modificar Producto</button>
		<?
		} else {
		?>
			<button type="button" class="btn btn-primary" id="guardar"><i class="bi bi-floppy"></i> Guardar Producto</button>
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
	<meta charset="UTF-8">
	<title>Productos</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link rel="stylesheet" href="alertifyjs/css/alertify.css">
	<link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
	<link rel="stylesheet" href="css/all.css">

</head>

<body>
	<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-box-seam-fill"></i> Productos</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
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
			<div class="col-md-12">
				<div class="row">
					<div class="col">
						&nbsp;
					</div>
					<div class="col-6 text-center ">
						<h3><i class="bi bi-box-seam-fill"></i> Productos</h3>
					</div>
					<div class="col">
						<button type="button" id="Nuevo" class="btn btn-info btn-sm"><i class="bi bi-plus-circle"></i> Producto Nuevo </button>
						<button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>
					</div>
				</div>
				<div class="row">
					<div class="col-1">
					</div>
					<div class="col-10">
						<table id="example" class="table table-sm table-hover" style="width:100%">
							<thead>
								<tr align="center">
									<th>Codigo</th>
									<th>Nombre</th>
									<th>Precio</th>
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
    </div>

  </main>

</div>

	
</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.3.1.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="alertifyjs/alertify.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
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
					funcion: "Carga"
				}),
				dataType: "html",
				async: false,
				success: function(msg) {

					$("#registros_tabla").html(msg);
				}
			});
		}
		$(document).on("click", "#guardar", function(e) {
			if ($("#codigo").val() == "") {
				alertify.error("Ingresa un Codigo ");
				$("#codigo").focus();
				return false;
			}
			if ($("#nombre").val() == "") {
				alertify.error("Ingresa una Nombre");
				$("#nombre").focus();
				return false;
			}
			if ($("#precio").val() == "") {
				alertify.error("Ingresa un Precio");
				$("#precio").focus();
				return false;
			}
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Guardar",
					codigo: $("#codigo").val(),
					nombre: $("#nombre").val(),
					precio: $("#precio").val()
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					console.log(msg);
					alertify.success("Producto Agredado Exitosamente ");
					window.location = "<?= $_SERVER["PHP_SELF"] ?>";
				}
			});
		});

		
		$(document).on("click", "#editar", function(e) {
			var idregistro = $(this).attr("registros");
			if ($("#codigo").val() == "") {
				alertify.error("Ingresa un Codigo ");
				$("#codigo").focus();
				return false;
			}
			if ($("#nombre").val() == "") {
				alertify.error("Ingresa una Nombre");
				$("#nombre").focus();
				return false;
			}
			if ($("#precio").val() == "") {
				alertify.error("Ingresa un Precio");
				$("#precio").focus();
				return false;
			}
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Editar",
					codigo: $("#codigo").val(),
					nombre: $("#nombre").val(),
					precio: Quita_Moneda($("#precio").val()),
					idregistro: idregistro
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					alertify.success("Producto Modificado Exitosamente ");
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
			alertify.confirm("Eliminacion", '¿Estas Seguro de Eliminar el Producto?', function() {
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
						alertify.success("Producto eliminado Exitosamente " + msg);
						window.location = "<?= $_SERVER["PHP_SELF"] ?>";

					}
				});
			}, function() {
				alertify.error('Cancelado')
			});
		});
		function Quita_Moneda(n) {
				n = String(n);
				var s = parseFloat(n.replace(",", "").replace("$", ""));
				if (isNaN(s)) s = 0;
				return s;
			}
		function Formato_Moneda(n, c, d, t) {
			var c = isNaN(c = Math.abs(c)) ? 2 : c,
				d = d == undefined ? "." : d,
				t = t == undefined ? "," : t,
				s = n < 0 ? "-" : "",
				i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
				j = (j = i.length) > 3 ? j % 3 : 0;
			return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
		}
	});
</script>