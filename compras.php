<?
if ($_POST['funcion'] == 'guardar_proveedor') {
	include('inc/conectar.php');
	$limite = $_POST['limitecredito'];
	$tiempo = $_POST['tiempocredito'];
	$Auto = $consulta->query("INSERT INTO proveedores SET nombre='" . $_POST['nombre'] . "', domicilio='" . $_POST['domicilio'] . "', url='" . $_POST['url'] . "', telefono='" . $_POST['telefono'] . "', observaciones='" . $_POST['observaciones'] . "'");
	foreach ($Auto as $Autocontador);
	exit();
}
if ($_POST['funcion'] == 'Guarda_Productos') {
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT MAX(idclientes)+1 AS Auto_increment FROM proveedores");
	foreach ($Auto as $Autocontador);
	$CODIGO = str_pad($Autocontador["Auto_increment"], 6, "0", STR_PAD_LEFT);
	$Auto = $consulta->query("INSERT INTO proveedores SET codigo='" . $CODIGO . "', nombre='" . $_POST['nombre'] . "', domicilio='" . $_POST['domicilio'] . "', ciudad='" . $_POST['ciudad'] . "', telefono='" . $_POST['telefono'] . "', limitecredito='" . $_POST['limitecredito'] . "', tiempocredito='" . $_POST['tiempocredito'] . "', observaciones='" . $_POST['observaciones'] . "' ");
	foreach ($Auto as $Autocontador);
	foreach ($Auto as $Autocontador);
	echo "" . $CODIGO;
	exit();
}
include("inc/conectar.php");

if ($_POST["funcion"] == "Credito_Cliente") {
	include("inc/conectar.php");
	$cadena = '';
	$codigo = explode("-", $_POST['cliente']);
	$Auto = $consulta->query("SELECT * FROM proveedores WHERE idproveedores=" . $codigo[0] . "");
	foreach ($Auto as $clientes);
	if ($clientes[0] != '') {
		$cadena = $clientes['domicilio'] . '|' . $clientes['telefono'] . '|' . ($clientes['limitecredito'] - $clientes['saldo']);
	} else {
		$cadena = "No Existe";
	}
	echo $cadena;
	exit();
}
if ($_POST["funcion"] == "Carga_Cliente") {
	include("inc/conectar.php");
?>
	<div class="row">
		<div class="col-12 font-weight-bold">
			<label for="Nombre">Nombre</label>
			<input type="text" class="form-control text-uppercase" id="nombre" value="<?= $row['nombre'] ?>" placeholder="Nombre ">
		</div>
		<div class="col-4 font-weight-bold">
			<label for="Domicilio">Domicilio</label>
			<input type="text" class="form-control text-uppercase" value="<?= $row['domicilio'] ?>" id="domicilio" placeholder="Domicilio">
		</div>
		<div class="col-4 font-weight-bold">
			<label for="Telefono">Telefono</label>
			<input type="text" class="form-control text-uppercase" value="<?= $row['telefono'] ?>" id="telefono" placeholder="Telefono">
		</div>
		<div class="col-4 font-weight-bold">
			<label for="Telefono">URL</label>
			<input type="text" class="form-control text-uppercase" value="<?= $row['url'] ?>" id="url" placeholder="url">
		</div>
		<div class="col-12 font-weight-bold">
			<label for="Observaciones">Observaciones</label>
			<textarea type="text" class="form-control text-uppercase" value="" id="observaciones" placeholder="Observaciones"><?= $row['observaciones'] ?></textarea>
		</div>
	</div>
<?
	exit();
}
if ($_POST["funcion"] == "DataListClientes") {
	include("inc/conectar.php");
	$buscar = $consulta->query("SELECT * FROM proveedores WHERE inactivo IS NULL");
	foreach ($buscar as $row){
		echo "<option value ='$row[idproveedores]-$row[nombre]'>";
	}
	exit();
}
if ($_POST["funcion"] == "DataListProductos") {
	include("inc/conectar.php");
	$existencias = 0;
	$buscar = $consulta->query("SELECT codigo, nombre, precio, costo FROM productos WHERE inactivo IS NULL");
	foreach ($buscar as $row){
		$existencias  = $row['costo'];
		echo "<option id='$row[codigo]' value ='$row[codigo]-".stripslashes ($row['nombre'])." Costo $ ".number_format($existencias,2)."'>";
	}
	exit();
}

function Folio($tabla, $idsucursales, $tipo)
{
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT * FROM sucursales WHERE idsucursales=" . $_SESSION["SISTEMA"]["idsucursales"]);
	foreach ($Auto as $Autocontador);
	if ($tipo == 'Ticket') {
		$folios = ($Autocontador['folio'] + 1);
	} elseif ($tipo == 'Remision') {
		$folios = ($Autocontador['remision'] + 1);
	} elseif ($tipo == 'Venta') {
		$folios = ($Autocontador['nota'] + 1);
	} elseif ($tipo == 'Cotizacion') {
		$folios = ($Autocontador['cotizacion'] + 1);
	}
	$folios = str_pad($folios, 6, "0", STR_PAD_LEFT);
	return $folios;
}
function Auto_TablaC()
{
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT MAX(idcompras)+1 AS Auto_increment FROM compras");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	$Auto = str_pad($Auto, 6, "0", STR_PAD_LEFT);
	return $Auto;
}
function Auto_Tabla()
{
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT MAX(idcompras)+1 AS Auto_increment FROM compras");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	return $Auto;
}

if ($_POST["funcion"] == "Guardar_compra") {
	include("inc/conectar.php");
	$idsuc = 1;
	$TIPO = $_POST['tipo'];
	//$FOLIO = $_POST['folio'];
	$idventas = Auto_Tabla();
	$idusuario = $_SESSION['SISTEMA']['idusuarios'];
	$usuarios = $_SESSION["SISTEMA"]["usuario"];
	if ($idusuario == '') $idusuario = 1;
	if ($usuarios == '') $usuarios = 'Proveedor';
	$pago = ", fechapago='" . date("Y-m-d") . "'";
	$efectivo = 0;

	$idcliente = 1;
	$FOLIO = str_pad($idventas, 6, "0", STR_PAD_LEFT);

	$idclie = explode("-", $_POST['clientes']);
	if ($idclie[0] != '') $idcliente = $idclie[0];
	$query = $consulta->query("SELECT idproveedores FROM proveedores WHERE nombre LIKE '" . $idclie[0] . "'");
	foreach ($query as $client);
	if ($idclie[0] != '') $idcliente = $idclie[0];
	$idcliente = substr($idcliente, 0, 6);
	//echo $idcliente;
	$sql_con = "INSERT INTO compras SET fecha='" . date("Y-m-d H:i:s") . "', importe='" . $_POST['total'] . "', idusuarios=" . $idusuario . ", usuarios='" . $usuarios . "', idproveedores='" . $_POST['idcliente'] . "', proveedor='" . $_POST['clientes'] . "', folio='" . $FOLIO . "' ";
	
	$stmt = $consulta->prepare($sql_con);
	$stmt->execute();
	if ($stmt->errorCode() == 0) {
		include("inc/conectar.php");
		
		//RECORRO EL ARREGLO DE PRODUCTOS

		foreach ($_POST["detalle"] as $key => $val) {
			//INSERTA DETALLE DE VENTA
			$query = $consulta->query("SELECT precio FROM productos WHERE idproductos LIKE '" . $val[0] . "'");
			foreach ($query as $precio);
			
			$query = $consulta->query("UPDATE productos SET  costo=" . $val[3] . " WHERE idproductos=" . $val[0]);
			foreach ($query as $row);
			$sql_detalle = "INSERT INTO detallecompras SET idcompras=" . $idventas . ", idproductos=" . $val[0] . ", productos='" . $val[1] . "', cantidad=" . $val[2] . ", precio=" . $val[3] . ", importe=" . ($val[3] * $val[2]);
			//echo $sql_detalle;
			$query1 = $consulta->query($sql_detalle);
			foreach ($query1 as $row2);
			$query = $consulta->query("UPDATE productos SET existencias=(existencias+" . $val[2] . ") WHERE idproductos=" . $val[0]);
			foreach ($query as $row);
		}
		echo $idventas;
	} else {
		$errors = $stmt->errorInfo();
		echo ($errors[2]);
		echo "Errores";
		$query = $consulta->query("INSERT INTO registro_errores SET fecha='" . date("Y-m-d H:i:s") . "', error='" . addslashes($errors[2]) . "'");
		foreach ($query as $row);
	}
	exit();
}
if ($_POST['funcion'] == 'Agrega_Detalle') {
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT * FROM productos WHERE codigo LIKE '" . $_POST['codigo'] . "'");
	foreach ($Auto as $row);
	$precio = $row['costo'];
	$IVAS = 0;
	if ($row[0] == '') {
		echo "Sin Resultados";
		exit();
	}
	$numero = str_pad(rand(0, 99999), 6, "0", STR_PAD_LEFT);
?>
	<div class="row detalle_productos" aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" idproductos="<?= $row[0] ?>" id="<?= $_POST['codigo'] ?>">
		<div class="col-lg-1">
			<input type="text" class="cantidad form-control-sm input-group-sm text-left" min="0" value="<?= number_format($_POST['cantidad'], 2) ?>">
		</div>
		<div class="col-lg-4 text-center">
			<input type="text" class="form-control-sm nombre" style="width:100%; background-color:#FFF; color:#000; border-color:#FFF;" readonly value="<?= $row['codigo'] . " - " . $row['nombre'] ?>">
		</div>
		<div class="col-lg-2">
			<input type="text" class=" form-control-sm text-right costo" readonly style=" background-color:#FFF; color:#000;" aria-describedby="sizing-addon1" disabled  value="<?= number_format($row['costo'], 2) ?>">
		</div>
		<div class="col-lg-2">
			<input type="text" class=" form-control-sm text-right precio" style=" background-color:#FFF; color:#000;" aria-describedby="sizing-addon1" value="<?= number_format($row['costo'], 2) ?>">
		</div>
		<div class="col-lg-2">
			<input type="text" style=" background-color:#FFF; color:#000;" class=" form-control-sm text-right importe" aria-describedby="sizing-addon1" readonly value="<?= number_format($_POST['cantidad'] * $row['costo'], 2) ?>">
		</div>
		<div class="col-lg-1 text-center ">
			<button type="button" style="margin:0;" aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Eliminar Producto' class="btn btn-danger btn-sm eliminar" registros="<?= $row[0] ?>"><i class="fas fa-eraser"></i></button>

			<!-- <img aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Eliminar Producto'  class="eliminar" src="" height="20" style="cursor:pointer"> -->
		</div>
	</div>
<?
	exit();
}

?>
<!DOCTYPE html>
<html>

<head>
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Compras</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-3.3.1.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="alertifyjs/css/alertify.css">
    <link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
    <script src="alertifyjs/alertify.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
	</head>

<body>
	<style>
		.pre-scrollable {
			max-height: 210px;
			overflow-y: scroll;
		}

		.borderless table {
			border-top-style: none;
			border-left-style: none;
			border-right-style: none;
			border-bottom-style: none;
		}

		.oculta {
			display: none;
		}
	</style>
	<div class="modal fade modal_caja1" id="MODAL_CAJA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Realizar Venta</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-6 text-center">
							<h1><b>Importe a Pagar</b></h1>
						</div>
						<div class="col-6 text-center">
							<h1><b id="total_texto">$ 0.00</b></h1>
						</div>
						<div class="col-12 text-center">
							<hr>
						</div>
						<div class="col-3 text-center">
							<div class="row">
								<div class="col-12">
									<div class="input-group-text">
										<input type="checkbox" class="tipopagos" id="contado" checado="1" checked aria-label="Checkbox for following text input">
										&nbsp;Pagado
									</div>
									<input type="text" value="<?= date("d-m-Y") ?>" readonly class="form-control text-center" aria-label="checkbox">
								</div>

							</div>
							<BR>
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group-text">
										<input type="checkbox" class="tipopagos" id="credito" checado="0" aria-label="Checkbox for following text input">
										&nbsp; Cr&eacute;dito
									</div>
									<input type="text" value="<?= date("d-m-Y") ?>" readonly class="form-control text-center" aria-label="checkbox">
								</div>
							</div>
							<BR>
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group-text">
										<input type="checkbox" class="tipopagos" checado="0" id="apartado" aria-label="Checkbox for following text input">
										&nbsp;Apartado<? $fecha = strtotime('+30 day', strtotime(date("d-m-Y"))) ?>
									</div>
									<input type="text" value="<?= date('j-m-Y', $fecha); ?>" readonly class="form-control text-center" aria-label="checkbox">
								</div>
							</div>
							<div class="row">
								<div class="col-12 text-center invisible">
									<input type="text" value="contado" id="tipo_venta">
								</div>
							</div>
						</div>
						<div class="col-4 text-center">
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text">Efectivo </span>
											<span class="input-group-text">$</span>
										</div>
										<input type="text" class="form-control text-right" id="efectivo" aria-label="Amount (to the nearest dollar)">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text">Transferencia </span>
											<span class="input-group-text">$</span>
										</div>
										<input type="text" class="form-control text-right" id="tarjeta" aria-label="Amount (to the nearest dollar)">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text">Comision </span>
											<span class="input-group-text">$</span>
										</div>
										<input type="text" class="form-control text-right" id="comision" readonly aria-label="Amount (to the nearest dollar)">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text">Total Transf. </span>
											<span class="input-group-text">$</span>
										</div>
										<input type="text" class="form-control text-right" id="total_tarjeta" readonly aria-label="Amount (to the nearest dollar)">
									</div>
								</div>
							</div>
						</div>
						<div class="col-5 text-center">
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text"><b>Descuento </b></span>
											<span class="input-group-text"><b>% </b></span>
										</div>
										<input type="text" class="form-control text-right" aria-label="Amount (to the nearest dollar)" id="descuento_general">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text"><b>Total </b> </span>
											<span class="input-group-text"><b>$ </b></span>
										</div>
										<input type="text" class="form-control text-right" id="total_m" readonly aria-label="Amount (to the nearest dollar)">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 text-center">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text"><b>Cambio</b></span>
											<span class="input-group-text"><b>$</b></span>
										</div>
										<input type="text" class="form-control text-right" id="cambio_modal" readonly aria-label="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="cierra_venta">Cerrar</button>
					<button type="button" class="btn btn-primary" id="guardar">Finalizar Venta</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="MODAL_CLIENTE" tabindex="-1" role="dialog" aria-labelledby="MODAL_CLIENTE" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Agregar Proveedor Nuevo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" id="cerrar_CLIE">&times;</span>
					</button>
				</div>
				<div id="resultados_modal">
					<div class="modal-body" id="modal_cliente">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" id="guardar_proveedor">Agregar Proveedor</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include("menu1.php"); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<hr>
				<div class="row">
					<div class="col-md-10">
						<div class="row">
							<div class="form-group col-md-1 text-center">
								<b>Proveedor</b>
							</div>
							<div class="form-group col-md-8 text-center">
								<input list="datosClientes" name="" autocomplete="off" class="form-control" id="clientes" placeholder="Buscar Proveedor">
								<datalist id="datosClientes" active>
								</datalist>
							</div>
							<div class="col-md-3">
								<button class="form-control btn btn-info" id="cliente_new" data-toggle="modal" data-target="#MODAL_CLIENTE" data-whatever="@mdo">Proveedor Nuevo</button>
							</div>
							<!-- <div class="form-group col-md-1 ">
                            <b>Direccion</b>
                        </div>
                        <div class="form-group col-md-3 ">
                            <input type="text" class="form-control text-center" id="domicilio" readonly value="" >
                        </div>
                        <div class="form-group col-md-1 ">
                            <b>Telefono</b>
                        </div>
                        <div class="form-group col-md-3 ">
                            <input type="text" class="form-control text-center" id="telefono" readonly value="" >
                        </div>
                        <div class="form-group col-md-2 ">
                            <b>Credito Disponible</b>
                        </div>
                        <div class="form-group col-md-2 ">
                            <input type="text" class="form-control text-center" id="credito_disponible" readonly value="0" >
                        </div> -->
						</div>
						<div class="row">
							<div class=" col-md-2 text-center">
								<b for="cantidad">Cantidad</b><input type="number" name="" class="form-control text-right" id="cantidad" value="1" placeholder="" min="0">
							</div>
							<div class=" col-md-8 text-center">
								<b for="tipo">Productos</b>
								<input list="datosProductos" autocomplete="off" name="" class="form-control" id="Productos" placeholder="Buscar Productos" autocomplete>
								<datalist id="datosProductos" class="col-md-9" active style="width:1000px;">
								</datalist>
							</div>
							<div class=" col-md-2 text-center">
								&nbsp;
								<button class="form-control btn btn-success" id="agregarm">Agregar</button>
							</div>
						</div>
						<div class="row ">
							<div class="col-sm-12">
								<table class="table table-sm table-hover" width="90%">
									<thead>
										<tr style="background-color:#2e353d; color:#FFF;">
											<td width="100" class="text-center">Cantidad</td>
											<td class="text-center">Descripci&oacute;n</td>
											<td width="160" class="text-center">Costo</td>
											<td width="160" class="text-center">Precio</td>
											<td width="160" class="text-center">Importe</td>
											<td width="80">&nbsp;</td>
										</tr>
									</thead>
								</table>
							</div>
						</div>
						<div class="row">
							<div class=" col-lg-12 pre-scrollable" id="tabla_detalles">
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-12 text-center">
								<h3>Fecha</h3>
								<h2><?= date("d-m-Y") ?></h2>
							</div>
							<div class="col-md-12 text-center">
								<h3>Folio</h3>
								<input type="text" class="form-control text-center" id="folio" style="background-color:#FFFFFF;" value="<?= Auto_TablaC() ?>" disabled>
							</div>
							<div class="col-md-12 text-center">
								<hr>
							</div>
							<div class="col-md-12 text-center">
								<button type="button" data-toggle="modal" id="compra" class="btn btn-success btn-block btn-md text-center ">
									<h5>Guardar Compra</h5>
								</button>
							</div>
							<div class="col-md-12 text-center">
								<hr>
							</div>
							<div class="col-md-12 text-center">
								<h3>Total</h3>
							</div>
							<div class="col-md-12 text-center">
								<div class="input-group input-group-md">
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">$</span>
									</div>
									<input type="text" class="form-control text-right " id="total" style=" font-size:32px;" readonly value="0.00">
								</div>
							</div>
						</div>
					</div>
				</div>



			</div>
		</div>
	</div>

</body>


<script>
	$(document).ready(function(e) {
		$(document).on("click", "#clientes", function() {
			$("#clientes").select();
		});

		$(document).on("click", ".tipopagos", function() {
			var tipo = $(this).attr("id");
			$(".tipopagos").each(function(index, element) {
				$(this).attr("checado", 0);
				$(this).prop("checked", false);
			});
			if (tipo == 'credito') {
				$("#descuento_general").val(0).prop("disabled", true);
				if ($("#credito_disponible").val() == 0) {
					tipo = "contado";
					alertify.error('Cliente Sin Credito Disponible');
				}
				$("#tipo_venta").val("credito");
			} else if (tipo == 'contado') {
				$("#descuento_general").prop("disabled", false).val("");
				$("#tipo_venta").val("contado");
			} else {
				$("#descuento_general").val(0).prop("disabled", true);
				$("#tipo_venta").val("apartado");
				$("#efectivo").val(0);
			}
			$("#" + tipo).attr("checado", 1);
			$("#" + tipo).prop("checked", true);
		});
		Carga_Clientes();
		$("#Productos").val("").focus();
		Carga_articulos();

		function Carga_articulos() {
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "DataListProductos",
					Buscar: $("#Productos").val()
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					$("#datosProductos").html(msg);
					$("#Productos").val("").focus();
				}
			});
		}

		function Carga_Clientes() {
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "DataListClientes",
					Buscar: $("#clientes").val()
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					//alert(msg);
					$("#datosClientes").html(msg);
					$("#clientes").val("").focus();
					Credito_Cliente($("#clientes").val());
				}
			});
		}

		function Carga_Caja(tipo) {
			if (Quita_Moneda($("#total").val()) == 0) {
				alert(Quita_Moneda($("#total").val()));
				alertify.alert("Punto de Venta ", "Ingresa un Producto");
				$('#MODAL_CAJA').modal('hide');
				$('#cierra_venta').click();
				$("#Productos").focus();
				return false;
			}
			if ($("#clientes").val() == '' && tipo == 'Ticket') {
				$("#clientes").val('000001-VENTA DE MOSTRADOR');
			}
			if ($("#clientes").val() == '') {
				alertify.alert("Punto de Venta ", "Ingresa un Cliente");
				$('#myModal_caja').modal('hide');
				return false;
			}
			$("#efectivo").focus().select();
			$('#myModal_caja').modal('show');
			$("#myModalLabel").text(tipo);
		}
		$(document).on("click", "#caja", function() {
			$('#MODAL_CAJA').modal('show');
			Carga_Caja($("#tipo option:selected").val());
		});
		$(document).on("click", "#cliente_new", function() {
			Carga_Cliente();
		});

		function Carga_Cliente() {
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Carga_Cliente",
					idcliente: $("#clientes").val()
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					$("#modal_cliente").html(msg);
				}
			});
		}
		$(document).on("click", "#guardar_cliente", function(e) {
			if ($("#nombre").val() == "") {
				alertify.error("Ingresa un Nombre");
				$("#nombre").focus();
				return false;
			}
			if ($("#direccion").val() == "") {
				alertify.error("Ingresa una Direccion");
				$("#direccion").focus();
				return false;
			}
			if ($("#ciudad").val() == "") {
				alertify.error("Ingresa una Ciudad");
				$("#ciudad").focus();
				return false;
			}
			if ($("#limitecredito").val() == "") {
				alertify.error("Ingresa El Limite");
				$("#limitecredito").focus();
				return false;
			}
			if ($("#tiempocredito").val() == "") {
				alertify.error("Ingresa Dias de Credito");
				$("#tiempo").focus();
				return false;
			}
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Guarda_Productos",
					nombre: $("#nombre").val(),
					ciudad: $("#ciudad").val(),
					domicilio: $("#domicilio").val(),
					telefono: $("#telefono").val(),
					tiempocredito: $("#tiempocredito").val(),
					limitecredito: $("#limitecredito").val(),
					observaciones: $("#observaciones").val()
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					alertify.success("Cliente Agredado Exitosamente ");
					$("#clientes").val(msg + "-" + $("#nombre").val());
					$("#cerrar_CLIE").click();
					// $('#MODAL_CLIENTE').modal('hide');
					Credito_Cliente($("#clientes").val());
				}
			});
		});

		$("#clientes").on("keypress", function(event) {
			if (event.which == 13 && $(this).val() != '') {
				Credito_Cliente($(this).val());
				$("#datosProductos").focus();
			}

		});

		function Credito_Cliente(cliente) {
			console.log(cliente);
			if(cliente!=''){
				$.ajax({
					type: "POST",
					url: "<?= $_SERVER["PHP_SELF"] ?>",
					data: ({
						funcion: "Credito_Cliente",
						cliente: cliente
					}),
					dataType: "html",
					async: false,
					success: function(msg) {
						var datos = msg.split("|");
						$("#domicilio").val(datos[0]);
						$("#telefono").val(datos[1]);
						$("#credito_disponible").val(Formato_Moneda(datos[2], 2));

					}
				});
			}
			
		}
		$("#efectivo").on("keypress", function(event) {
			if (event.which == 13) {
				var efectivo = Quita_Moneda($("#efectivo").val());
				var total = Quita_Moneda($("#total_m").val());
				var tarjeta = Quita_Moneda($("#tarjeta").val());
				var cambio = total - efectivo;
				var total_venta = total;
				if (cambio <= 0) {
					$("#guardar").prop("disabled", false);
					$("#cambio_modal").val(Formato_Moneda(cambio * -1));
					$("#guardar").focus();
				}
				$("#tarjeta").focus();
			}
		});
		$("#tarjeta").on("keypress", function(event) {
			if (event.which == 13) {
				var total = Quita_Moneda($("#total_m").val());
				if ($("#tarjeta").val() > 0) {
					var tarjeta = Quita_Moneda($("#tarjeta").val());
					var efectivo = Quita_Moneda($("#efectivo").val());
					var cambio = (total - tarjeta) - efectivo;
					var comision = tarjeta * .02;
					$("#comision").val(Formato_Moneda(comision, 2));
					$("#total_tarjeta").val(Formato_Moneda(tarjeta + comision, 2));
					if (tarjeta < total && cambio <= 0) {
						$("#cambio_modal").val(Formato_Moneda(cambio * -1));
					} else {
						//$("#efectivo").val(Formato_Moneda(0,2));
						$("#cambio_modal").val(Formato_Moneda(0));
					}
					$("#descuento_general").val(0).prop("disabled", true);
				} else {
					$("#descuento_general").prop("disabled", false).val("");
					$("#comision").val(Formato_Moneda(0, 2));
					$("#total_tarjeta").val(Formato_Moneda(0, 2));
				}
			}
		});
		$(document).on("blur", "#descuento_general", function() {
			var total = Quita_Moneda($("#total").val());
			if ($(this).val() > 0) {
				var Descuento = Quita_Moneda($("#descuento_general").val()) / 100;
				var totales = total - (((Descuento + 1) * total) - total);
				$("#total_m").val(Formato_Moneda(totales));
				var efectivo = Quita_Moneda($("#efectivo").val());
				var total = Quita_Moneda($("#total_m").val());
				var tarjeta = Quita_Moneda($("#tarjeta").val());
				var cambio = total - efectivo;
				var total_venta = total;
				if (cambio <= 0) {
					$("#guardar").prop("disabled", false);
					$("#cambio_modal").val(Formato_Moneda(cambio * -1));
					$("#guardar").focus();
				}
			} else {
				$("#total_m").val(Formato_Moneda(total));
				$("#efectivo").val(Formato_Moneda(total)).select();
			}
		});
		$(document).on("blur", "#efectivo", function() {
			var efectivo = Quita_Moneda($("#efectivo").val());
			var total = Quita_Moneda($("#total_m").val());
			var tarjeta = Quita_Moneda($("#tarjeta").val());
			var cambio = (total - tarjeta) - efectivo;
			var total_venta = total;

			if (cambio <= 0) {
				$("#guardar").prop("disabled", false);
				$("#cambio_modal").val(Formato_Moneda(cambio * -1));
				$("#guardar").focus();
			}
		});
		$(document).on("blur", "#tarjeta", function() {
			var total = Quita_Moneda($("#total_m").val());
			if ($("#tarjeta").val() > 0) {
				var tarjeta = Quita_Moneda($("#tarjeta").val());
				var efectivo = Quita_Moneda($("#efectivo").val());
				var cambio = (total - tarjeta) - efectivo;
				var comision = tarjeta * .02;
				$("#comision").val(Formato_Moneda(comision, 2));
				$("#total_tarjeta").val(Formato_Moneda(tarjeta + comision, 2));
				if (tarjeta < total && cambio <= 0) {
					$("#cambio_modal").val(Formato_Moneda(cambio * -1));
				} else {
					//$("#efectivo").val(Formato_Moneda(0,2));
					$("#cambio_modal").val(Formato_Moneda(0));
				}
				$("#descuento_general").val(0).prop("disabled", true);
			} else {
				$("#descuento_general").prop("disabled", false).val("");
				$("#comision").val(Formato_Moneda(0, 2));
				$("#total_tarjeta").val(Formato_Moneda(0, 2));
			}
		});
		$(document).on("click", "#compra", function() {
			var clietes = $("#clientes").val();
			var a = clietes.split("-");
			var cliente = a[0];
			if ($("#clientes").val() == "") {
				alertify.alert("Compras", "Ingresa un Proveedor");
				$('#myModal_caja').modal('hide');
				$('#cierra_venta').click();
				$("#clientes").focus();
				return false;
			}
			var idventas = 0;
			if (Quita_Moneda($("#total").val()) == "0") {
				alertify.alert("Compras", "Ingresa un Producto");
				$('#myModal_caja').modal('hide');
				$('#cierra_venta').click();
				$("#Productos").focus();
				return false;
			}
			//VALIDAR SI ES DE CONTADO QUE INGRESARON EL EFECTIVO O TARJETA


			//get the closable setting value.
			var closable = alertify.alert().setting('closable');
			alertify.alert()
				.setting({
					'label': 'De Acuerdo',
					'message': 'Guardando Compra por favor Espere',
					'onok': function() {
			var Detalle = new Array();
			var cnt = 0;
			
			$(".detalle_productos").each(function() {
				var idproductos = $(this).attr("idproductos");
				var productos = $(this).find(".nombre").val();
				var costo = $(this).find(".costo").val();
				var cantidad = Quita_Moneda($(this).find(".cantidad").val());
				var precio = Quita_Moneda($(this).find(".precio").val());
				Detalle[cnt] = Array(idproductos, productos, cantidad, precio,costo);
				cnt++;
			});
			$.ajax({
				type: "POST",
				url: "<?= $_SERVER["PHP_SELF"] ?>",
				data: ({
					funcion: "Guardar_compra",
					folio: $("#folio").val(),
					total: Quita_Moneda($("#total").val()),
					idcliente: a[0],
					clientes: a[1],
					detalle: Detalle
				}),
				dataType: "html",
				async: false,
				success: function(msg) {
					idventas = msg;
					if (msg > 0) {
						alertify.success("Compra Guardada Correctamente");
						//var bob=window.open('','_new');bob.location="ticket_venta.php?idventas="+idventas;
						alertify.alert("Compras ", "Compra Guardada Correctamente");
						setTimeout(function() { 
							window.location="compras.php";
						}, 2000);
						
					} else {
						alertify.alert("Compras ", "Compra No Guardada Realizarla de nuevo ");
						//window.location="compras.php";
						return false
					}
				},error: function(xhr, status, error) {
						// Función a ejecutar si hay un error en la solicitud
						console.error('Error en la solicitud:', status, error);
					}
			});
						
						
					}
				}).show();

			$('#compra').attr("disabled", false);
			return false;
		});
		$(document).on("click", "#agregarm", function(e) {
			if ($("#cantidad").val() == '') {
				alertify.alert("Ingresa una Cantidad ");
				$("#cantidad").focus().select();
				return false;
			}
			if ($("#Productos").val() == '') {
				alertify.alert("Compras ", "Ingresa un Producto");
				$("#Productos").focus();
				return false;
			}
			Agrega_Productos($("#cantidad").val(), $("#Productos").val());
		});

		function Agrega_Productos(cantidad, producto) {
			var comodin = producto.split("-");
			var codigo = comodin[0];
			//alert(cantidad+"...."+comodin[0]+" ---"+comodin[1]);
			//BUSCAR REGISTRO EN EL DETALLE
			var agregado = true;
			if (agregado) {
				$.ajax({
					type: "POST",
					url: "<?= $_SERVER["PHP_SELF"] ?>",
					data: ({
						funcion: "Agrega_Detalle",
						codigo: codigo,
						cantidad: cantidad,
						base: $("#base").val(),
						altura: $("#altura").val(),
						lista: $("#lista").val()
					}),
					dataType: "html",
					async: false,
					success: function(msg) {
						if (msg == 'Sin Resultados') {
							alertify.alert("Punto de Venta", "Sin Resultados");
							$("#Productos").val("").focus();
						} else {
							$("#tabla_detalles").append(msg);
							$("#cantidad").val(1);
							$("#base").val(1);
							$("#altura").val(1);
							$("#Productos").val("").focus();
							Totales();
						}
					}
				});
			} else {
				$("#cantidad").val(1);
				$("#Productos").val("").focus();
			}
			return false;

		}
		$(document).on("keydown", ".cantidad", function(e) {
			if (e.which == 13) {
				if ($(this).val() > 0) {
					var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").val());
					var importe = $(this).val() * precio;
					$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe, 2));
				} else {
					$(this).val(Formato_Moneda(1, 2));
				}
				Totales();
			}
		});
		$(document).on("blur", ".cantidad", function(e) {
			if ($(this).val() > 0) {
				var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").val());
				var importe = $(this).val() * precio;
				$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe, 2));
			} else {
				$(this).val(Formato_Moneda(1, 2));
			}
			Totales();
		});
		$(document).on("keydown", ".precio", function(e) {
			if (e.which == 13) {
				if ($(this).val() > 0) {
					var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").val());
					var importe = $(this).val() * precio;
					$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe, 2));
				} else {
					$(this).val(Formato_Moneda(1, 2));
				}
				Totales();
			}
		});
		$(document).on("blur", ".precio", function(e) {
			if ($(this).val() > 0) {
				var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").val());
				var importe = $(this).val() * precio;
				$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe, 2));
			} else {
				$(this).val(Formato_Moneda(1, 2));
			}
			Totales();
		});
		$(document).on("keyup", ".cantidad", function(e) {
			e.preventDefault();
			// alert(e.which + ": " + String.fromCharCode(e.which));
			if (e.which == 46) {
				var id = $(this).parent().parent().parent().attr("codigo");
				$("#" + id).remove();
				Totales();
				$("#Productos").focus();
			} else if (e.which == 38) {
				$("#Productos").focus();
			} else if (e.which == 39) {
				$(this).parent().parent().parent().find(".descuento").select();
			} else if (e.which == 37) {
				$(this).parent().parent().parent().find(".descuento").select();
			} else if (e.which == 13) {
				$("#Productos").select();
			}
			//derecha 39 izquierda 37
		});
		$(document).on("keyup", "#Productos", function(e) {
			e.preventDefault();
			//alert(e.which + ": " + String.fromCharCode(e.which));
			if (e.which == 13) {
				if ($("#Productos").val() != '') {
					Agrega_Productos($("#cantidad").val(), $("#Productos").val());
				}
			}
		});
		$(document).on("keyup", "body", function(e) {
			e.preventDefault();
			if (e.which == 27) {
				$("#Productos").focus();
			}
			return false;
		});



		$(document).on("click", "#tabla_detalles .eliminar", function() {
			var id = $(this).attr("aleatorio");
			alertify.confirm('Estas Seguro de Eliminar el Producto', '', function() {
				$(".detalle_productos").each(function(index, element) {
					if ($(this).attr("aleatorio") == id) {
						$(this).remove();
					}
				});
				//$("#tabla_detalles #"+id)..remove();
				alertify.success('Borrando');
				Totales();
			}, function() {
				alertify.error('Cancelado')
			});
		});
		$(document).on("click", "#salir", function() {
			alertify.confirm('Pregunta del Sistema', '¿Estas seguro de Cancelar la Venta?', function() {
				window.location = "pventas.php";
			}, function() {
				//alertify.error('Cancelado')
			});
		});

		function Totales() {
			var total = 0;
			var importe = 0;
			var iva = 0;
			var cant_row = 0;
			$(".detalle_productos").each(function(index, element) {
				var cant_row = Quita_Moneda($(this).find(".cantidad").val());
				var precio = Quita_Moneda($(this).find(".precio").val());
				var importe = Quita_Moneda(precio) * cant_row;
				$(this).find(".importe").val(Formato_Moneda(importe, 2));
				total += precio * cant_row;
			});
			$("#efectivo").val(Formato_Moneda(total, 2));
			$("#total").val(Formato_Moneda(total, 2));
			$("#total_m").val(Formato_Moneda(total, 2));
			$("#total_texto").text("$ " + Formato_Moneda(total, 2));
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

		function Quita_Moneda(n) {
			n = String(n);
			var s = parseFloat(n.replace(",", "").replace("$", ""));
			if (isNaN(s)) s = 0;
			return s;
		}

	});
	$(document).on("click", "#guardar_proveedor", function(e) {
		//	alert($("#tiempo").val());
		//	alert($("#limite").val());
		if ($("#nombre").val() == "") {
			alertify.error("Ingresa un Nombre ");
			$("#nombre").focus();
			return false;
		}
		if ($("#domicilio").val() == "") {
			alertify.error("Ingresa un Domicilio");
			$("#domicilio").focus();
			return false;
		}
		if ($("#telefono").val() == "") {
			alertify.error("Ingresa un Telefono");
			$("#telefono").focus();
			return false;
		}
		if ($("#url").val() == "") {
			alertify.error("Ingresa un Url");
			$("#url").focus();
			return false;
		}
		$.ajax({
			type: "POST",
			url: "<?= $_SERVER["PHP_SELF"] ?>",
			data: ({
				funcion: "guardar_proveedor",
				nombre: $("#nombre").val(),
				domicilio: $("#domicilio").val(),
				telefono: $("#telefono").val(),
				url: $("#url").val(),
				observaciones: $("#observaciones").val()
			}),
			dataType: "html",
			async: false,
			success: function(msg) {
				//	alert(msg);
				alertify.success("Clientes Agredado Exitosamente ");
				window.location = "<?= $_SERVER["PHP_SELF"] ?>";
			}
		});
	});
</script>