<?
if ($_POST['funcion'] == 'Agrega_Detalle_ticket') {
	include("inc/conectar.php");
	$contarow = 0;
	$Auto = $consulta->query("SELECT idventas FROM ventas WHERE folio LIKE '" . $_POST['folio'] . "'");
	foreach ($Auto as $row);
	$Auto1 = $consulta->query("SELECT * FROM ventas_detalle WHERE idventas LIKE '" . $row['idventas'] . "'");
	foreach ($Auto1 as $row1) {

		$precio = $row1['precio'];
		$IVAS = 0;
		if ($row1[0] == '') {
			echo "Sin Resultados";
			exit();
		}
		$numero = str_pad(rand(0, 99999), 6, "0", STR_PAD_LEFT);

		if ($row1["cantidad"]!="0") {

			if ($row1["ESTADO"]=="CAMBIO") {
				?>
				<div  style="display: none;" class="row detalle_productos" filas="<?= $contarow ?>" ventasid="<?= $row['idventas'] ?>" aleatorio="<?= $numero ?>" idproductos="<?= $row1['idproductos'] ?>" idventas="<?= $row1[0] ?>" id="<?= $_POST['codigo'] ?>">
					<div class="col-lg-1">
						<input type="text" class="devol form-control form-control-sm input-group-sm text-center" style="color:#000;" min="0" value="0">
					</div>
					<div class="col-lg-2">
						<input type="text" class="cantidad form-control form-control-sm input-group-sm text-center" style="color:#000;" readonly min="0" value="<?= $row1['cantidad'] ?>">
					</div>
					<div class="col-lg-5 text-center">
						<input type="text" class="form-control form-control-sm nombre" id="nombre_pro" style="width:100%; color:#000;" readonly value="<?= $row1['productos'] ?>">
					</div>
					<div class="col-lg-2">
						<input type="text" class=" form-control form-control-sm text-center precio" style=" color:#000;" readonly aria-describedby="sizing-addon1" value="<?= number_format($precio, 2) ?>">
					</div>
					<div class="col-lg-2">
						<input type="text" style=" color:#000;" class=" form-control form-control-sm text-center importe" aria-describedby="sizing-addon1" readonly value="<?= number_format($row1['cantidad'] * $precio, 2) ?>">
					</div>
					<!-- 			<div class="col-lg-2 text-center  ">
								<img aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Eliminar Producto' class="eliminar" src="img/delete.png" height="20" style="cursor:pointer">
								<img aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" data-toggle="modal" data-target="#devolver_modal" title='devolver Producto' class="devolver" src="img/devolver.png" height="20" style="cursor:pointer">
								<img aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Regresar Efectivo' class="transaction" src="img/transaction.png" height="20" style="cursor:pointer">
				
							</div> -->
				</div>
				<?
						
			} else {
				?>
				<div class="row detalle_productos" filas="<?= $contarow ?>" ventasid="<?= $row['idventas'] ?>" aleatorio="<?= $numero ?>" idproductos="<?= $row1['idproductos'] ?>" idventas="<?= $row1[0] ?>" id="<?= $_POST['codigo'] ?>">
					<div class="col-lg-1">
						<input type="text" class="devol form-control form-control-sm input-group-sm text-center" style="color:#000;" min="0" value="0">
					</div>
					<div class="col-lg-2">
						<input type="text" class="cantidad form-control form-control-sm input-group-sm text-center" style="color:#000;" readonly min="0" value="<?= $row1['cantidad'] ?>">
					</div>
					<div class="col-lg-5 text-center">
						<input type="text" class="form-control form-control-sm nombre" id="nombre_pro" style="width:100%; color:#000;" readonly value="<?= $row1['productos'] ?>">
					</div>
					<div class="col-lg-2">
						<input type="text" class=" form-control form-control-sm text-center precio" style=" color:#000;" readonly aria-describedby="sizing-addon1" value="<?= number_format($precio, 2) ?>">
					</div>
					<div class="col-lg-2">
						<input type="text" style=" color:#000;" class=" form-control form-control-sm text-center importe" aria-describedby="sizing-addon1" readonly value="<?= number_format($row1['cantidad'] * $precio, 2) ?>">
					</div>
					<!-- 			<div class="col-lg-2 text-center  ">
								<img aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Eliminar Producto' class="eliminar" src="img/delete.png" height="20" style="cursor:pointer">
								<img aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" data-toggle="modal" data-target="#devolver_modal" title='devolver Producto' class="devolver" src="img/devolver.png" height="20" style="cursor:pointer">
								<img aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Regresar Efectivo' class="transaction" src="img/transaction.png" height="20" style="cursor:pointer">
				
							</div> -->
				</div>
				<?
						
			}
			
	
		} else {
			# code...
		}
		
		$contarow++;
	}


	exit();
}


include("inc/conectar.php");
if (!isset($_SESSION['SISTEMA']['usuario'])) {
	echo "Sin sessiones" . $_SESSION['SISTEMA']['usuario'];
	header("Location: login.php");
	exit();
}
if ($_POST["funcion"] == "MODIFICAR_VENTA") {
	include("inc/conectar.php");
	//$estado ="Devuelto";
	$detalle = $_POST["detalle"];
	//echo $detalle;
	//exit();
	foreach ($_POST["detalle"] as $key => $val) {
		//INSERTA DETALLE DE VENTA

		$sql_detalle = "UPDATE ventas_detalle SET devuelto='" . $val[1] . "' WHERE  idventasdetalle=" . $val[0];
		$query1 = $consulta->query($sql_detalle);
		foreach ($query1 as $row2);
		//echo $row2;
	}

	exit();
}


if ($_POST["funcion"] == "DEVOLVER_PRODUCTO") {
	include("inc/conectar.php");
	$estado = "DEVUELTO";
	$detalle = $_POST["detalle"];
	$total = $_POST["totales"];
	$folio = $_POST["Folio"];
	$idventas = "";

	foreach ($_POST["detalle"] as $key => $val) {

		$sql_detalle5 = "UPDATE productos SET existencias=(existencias+" . $val[1] . ")	WHERE  idproductos=" . $val[2];
		$query5 = $consulta->query($sql_detalle5);
		foreach ($query5 as $row5);
		$sql_detalle5 = "UPDATE ventas_detalle SET ESTADO='CAMBIO'	WHERE  idventasdetalle=" . $val[0];
		$query5 = $consulta->query($sql_detalle5);
		foreach ($query5 as $row5);
		
	}


	exit();
}
if ($_POST["funcion"] == "MODIFICAR_VENTA_DETALLE") {
	include("inc/conectar.php");
	$estado = "DEVUELTO";
	$detalle = $_POST["detalle"];
	$total = $_POST["totales"];
	$folio = $_POST["Folio"];
	$idventas = "";

	foreach ($_POST["detalle"] as $key => $val) {
		//INSERTA DETALLE DE VENTA
		$idventasT = $val[4];
	//	echo $idventasT;
	//	exit();
		$sql_detalle = "UPDATE ventas_detalle SET ESTADO='" . $estado . "' WHERE  idventasdetalle=" . $val[0];
		$query1 = $consulta->query($sql_detalle);
		foreach ($query1 as $row2);
		$sql_detalle1 = "UPDATE ventas_detalle SET cantidad=(cantidad-" . $val[1] . ")	WHERE  idventasdetalle=" . $val[0];
		$query2 = $consulta->query($sql_detalle1);
		foreach ($query2 as $row3);
		$sql_detalle5 = "UPDATE productos SET existencias=(existencias+" . $val[1] . ")	WHERE  idproductos=" . $val[2];
		$query5 = $consulta->query($sql_detalle5);
		foreach ($query5 as $row5);
		$resultados=$consulta->query("INSERT INTO cxc SET fecha='".date("Y-m-d H:i:s")."', idventas=".$val[4].", idusuarios=".$_SESSION['SISTEMA']['idusuarios'].", usuarios='".$_SESSION["SISTEMA"]["usuario"]."', importe=".(-$total));
		foreach ($resultados as $row);
		$sql_detalle2 = "UPDATE ventas SET totaldevuelto=(totaldevuelto+" . $total . ") WHERE  idventas=" . $val[4];
		$query3 = $consulta->query($sql_detalle2);
		foreach ($query3 as $row4);
		$Auto = $consulta->query("INSERT INTO movimientoscaja SET fecha='".date("Y-m-d H:i:s")."',  observaciones='Devolucion de efectivo en ticket " .$val[4]."', importe='".$total."', tipo='Devolucion' ");
		foreach ($Auto as $Autocontador);	
		//echo "UPDATE ventas SET totaldevuelto='" . $total . "'   WHERE  idventas=" .$val[4]."";
		

		
	}
	$query = $consulta->query("SELECT idventas FROM ventas WHERE folio LIKE '" . $folio . "'");
		foreach ($query as $idventas);
		echo $idventas['idventas'];


	exit();
}
if ($_POST["funcion"] == "Credito_Cliente") {
	include("inc/conectar.php");
	$cadena = '';
	$codigo = explode("-", $_POST['cliente']);
	$Auto = $consulta->query("SELECT * FROM clientes WHERE codigo LIKE '" . $codigo[1] . "'");
	foreach ($Auto as $clientes);
	$Auto1 = $consulta->query("SELECT * FROM ventas WHERE folio LIKE '" . $codigo[0] . "'");
	foreach ($Auto1 as $clientes1);
	if ($clientes[0] != '') {
		$cadena = $clientes['domicilio'] . '|' . $clientes['telefono'] . '|' . $clientes1['fecha'];
	} else {
		$cadena = "No Existe";
	}
	echo $cadena;
	exit();
}
if ($_POST["funcion"] == "Carga_Cliente") {
	include("inc/conectar.php");
	include("inc/conectar.php");
	?>
<div class="row">
	<div class="col-md-6">
		<b>Nombre</b>
		<input type="text" class="form-control" placeholder="Nombre" id="nombre" aria-describedby="basic-addon1" value="<?= $row['nombre'] ?>">
	</div>
	<div class="col-md-6">
		<b>Domicilio</b>
		<input type="text" class="form-control" placeholder="Domicilio" id="domicilio" aria-describedby="basic-addon1" value="<?= $row['domicilio'] ?>">
	</div>
	<div class="col-md-6">
		<b>Ciudad</b>
		<input type="text" class="form-control" placeholder="Ciudad" id="ciudad" aria-describedby="basic-addon1" value="<?= $row['ciudad'] ?>">
	</div>
	<div class="col-md-6">
		<b>Telefono</b>
		<input type="text" class="form-control" placeholder="Telefono" id="telefono" aria-describedby="basic-addon1" value="<?= $row['telefono'] ?>">
	</div>
	<div class="col-md-3">
		<b>Limite de Credito</b>
		<input type="text" class="form-control" id="limitecredito" aria-describedby="basic-addon1" value="<?= $row['limitecredito'] ?>">
	</div>
	<div class="col-md-3">
		<b>Tiempo de Credito</b>
		<input type="text" class="form-control" id="tiempocredito" aria-describedby="basic-addon1" value="<?= $row['tiempocredito'] ?>">
	</div>
	<div class="col-md-12 text-center">
		<b>Observaciones</b>
		<textarea id="observaciones" class="form-control noresize"></textarea>
	</div>
</div>
<?
	exit();
}
if ($_POST["funcion"] == "DataListClientes") {
		$buscar = $consulta->query("SELECT * FROM ventas WHERE importe NOT LIKE totaldevuelto");
		if ($buscar) {
			foreach ($buscar as $fila) {
				echo "<option value ='$fila[folio]-$fila[clientes]'>";
			}
		} else {
			echo "Problemas en la consulta" . mysql_error();
		}

	
	exit();
}
if ($_POST["funcion"] == "DataListProductos") {
	$existencias = 0;
	$buscar = $consulta->query("SELECT codigo, nombre, precio FROM productos WHERE inactivo IS NULL AND existencias !=0");
	if ($buscar) {
		foreach ($buscar as $fila) {
			$existencias  = $fila[precio];
			echo "<option id='$fila[codigo]' style='width:100%;' value ='$fila[codigo]-" . stripslashes($fila[nombre]) . " Precio $ " . number_format($existencias, 2) . "'>";
		}
	} else {
		echo "Problemas en la consulta" . mysql_error();
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
	$folios = str_pad($folios, 8, "0", STR_PAD_LEFT);
	return $folios;
}
function Auto_TablaC()
{
	include("inc/conectar.php");
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'ventas'");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	$Auto = str_pad($Auto, 6, "0", STR_PAD_LEFT);
	return $Auto;
}
function Auto_Tabla()
{
	include("inc/conectar.php");
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'ventas'");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	return $Auto;
}

if ($_POST["funcion"] == "Guardar_Ventas") {
	include("inc/conectar.php");
	$idsuc = 1;
	$TIPO = $_POST['tipo'];
	//$FOLIO = $_POST['folio'];
	$idventas = Auto_Tabla();
	$idusuario = $_SESSION['SISTEMA']['idusuarios'];
	$usuarios = $_SESSION["SISTEMA"]["usuario"];
	if ($idusuario == '') $idusuario = 1;
	if ($usuarios == '') $usuarios = 'Vendedor';
	$pago = ", fechapago='" . date("Y-m-d") . "'";
	$efectivo = 0;
	if ($_POST['tipo'] == 'credito' or $_POST['tipo'] == 'apartado') {
		$pago = '';
		$saldo = ", saldo=" . ($_POST['importe']);
	} else {
		$saldo = ", saldo=" . ($_POST['importe'] - ($_POST['efectivo'] - $_POST['tarjeta']));
		$efectivo = $_POST['efectivo'];
	}
	$idcliente = 1;
	$FOLIO = str_pad($idventas, 8, "0", STR_PAD_LEFT);

	$idclie = explode("-", $_POST['clientes']);
	if ($idclie[0] != '') $idcliente = $idclie[0];
	$query = $consulta->query("SELECT idclientes FROM clientes WHERE codigo LIKE '" . $idclie[0] . "'");
	foreach ($query as $client);

	if ($idclie[0] != '') $idcliente = $idclie[0];
	$idcliente = substr($idcliente, 1, 6);
	$sql_con = "INSERT INTO ventas SET fecha='" . date("Y-m-d H:i:s") . "', idusuarios=" . $idusuario . ", usuarios='" . $usuarios . "', efectivo=" . $_POST['efectivo'] . ", importe=" . $_POST['importe'] . ", descuento=" . $_POST['descuento'] . ", tarjeta=" . $_POST['total_tarjeta'] . ", comision_tarjeta=" . $_POST['comision'] . ", tipo='" . $TIPO . "', idclientes=" . $client['idclientes'] . ", clientes='" . $_POST['clientes'] . "', folio='" . $FOLIO . "' " . $pago . $saldo;
	$stmt = $consulta->prepare($sql_con);
	$stmt->execute();
	if ($stmt->errorCode() == 0) {
		include("inc/conectar.php");
		if ($TIPO == 'credito' or $_POST['tipo'] == 'apartado') {
			$query = $consulta->query("UPDATE clientes SET saldo=saldo+(" . $_POST['importe'] . ") WHERE idclientes=" . $client['idclientes']);
			foreach ($query as $row);
		}
		$ABONOST = 0;
		$ABONOS = $_POST['efectivo'];
		$ABONOST = $_POST['tarjeta'];
		if (($ABONOS + $ABONOST) > 0) {
			if ($ABONOS > $_POST['importe']) $ABONOS = $_POST['importe'];
			$Auto = $consulta->query("SHOW TABLE STATUS Like 'cxc'");
			foreach ($Auto as $Autocontador);
			$idcuentas = ($Autocontador["Auto_increment"]);
			$idusuario = $_SESSION["usuario"]["idusuarios"];
			if (isset($_SESSION["SISTEMA"]["idusuarios"])) $idusuario = 1;
			$resultados = $consulta->query("INSERT INTO cxc SET fecha='" . date("Y-m-d H:i:s") . "', idventas=" . $idventas . ", idusuarios=" . $idusuario . ", usuarios='" . $_SESSION["SISTEMA"]["usuario"] . "', importe=" . $ABONOS . ", tarjeta=" . $ABONOST);
			foreach ($resultados as $row);
			$query = $consulta->query("UPDATE ventas SET abonos=" . $ABONOS . " WHERE idventas=" . $idventas);
			foreach ($query as $row);
		}


		//SI SE INSERTO LA VENTA
		//RECORRO EL ARREGLO DE PRODUCTOS
		foreach ($_POST["detalle"] as $key => $val) {
			//INSERTA DETALLE DE VENTA
			$sql_detalle = "INSERT INTO ventas_detalle SET idventas=" . $idventas . ", idproductos=" . $val[0] . ", productos='" . $val[1] . "', cantidad=" . $val[2] . ", precio=" . $val[3] . ", importe=" . ($val[3] * $val[2]);
			$query1 = $consulta->query($sql_detalle);
			foreach ($query1 as $row2);
			$query = $consulta->query("UPDATE productos SET existencias=(existencias-" . $val[2] . ") WHERE idproductos=" . $val[0]);
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
	$precio = $row['precio'];
	$IVAS = 0;
	if ($row[0] == '') {
		echo "Sin Resultados";
		exit();
	}
	$numero = str_pad(rand(0, 99999), 6, "0", STR_PAD_LEFT);
?>
<div class="row detalle_productos" aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" idproductos="<?= $row[0] ?>" id="<?= $_POST['codigo'] ?>">
	<div class="col-lg-2">
		<input type="text" class=" form-control form-control-sm input-group-sm text-left" style="color:#000;" min="0" value="PRODUCTO">
	</div>
	<div class="col-lg-1">
		<input type="text" class="cantidad form-control form-control-sm input-group-sm text-left" min="0" value="<?= number_format($_POST['cantidad'], 2) ?>">
	</div>
	<div class="col-lg-4 text-center">
		<input type="text" class="form-control form-control-sm nombre" style="width:100%; " readonly value="<?= $row['codigo'] . " - " . $row['nombre'] ?>">
	</div>
	<div class="col-lg-1">
		<input type="text" class=" form-control form-control-sm text-left precio" style=" background-color:#FFF; color:#000;" readonly aria-describedby="sizing-addon1" value="<?= number_format($precio, 2) ?>">
	</div>
	<div class="col-lg-2">
		<input type="text" style=" background-color:#FFF; color:#000;" class=" form-control form-control-sm text-left importe" aria-describedby="sizing-addon1" readonly value="<?= number_format($_POST['cantidad'] * $precio, 2) ?>">
	</div>
	<div class="col-lg-2 text-center ">
		<img aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Eliminar Producto' class="eliminar" src="img/delete.png" height="20" style="cursor:pointer">
	</div>
</div>
<?
	exit();
}

if ($_POST['funcion'] == 'Agrega_Detalle_dev') {
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT * FROM productos WHERE codigo LIKE '" . $_POST['codigo'] . "'");
	foreach ($Auto as $row);
	$precio = $row['precio'];
	$IVAS = 0;
	if ($row[0] == '') {
		echo "Sin Resultados";
		exit();
	}
	$numero = str_pad(rand(0, 99999), 6, "0", STR_PAD_LEFT);
?>
<div class="row detalle_productos" aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" idproductos="<?= $row[0] ?>" id="<?= $_POST['codigo'] ?>">
	<div class="col-lg-2">
		<input type="text" class="cantidad form-control form-control-sm input-group-sm text-left" min="0" value="<?= number_format($_POST['cantidad'], 2) ?>">
	</div>
	<div class="col-lg-4 text-center">
		<input type="text" class="form-control form-control-sm nombre_dev" style="width:100%; " readonly value="<?= $row['codigo'] . " - " . $row['nombre'] ?>">
	</div>
	<div class="col-lg-2">
		<input type="text" class=" form-control form-control-sm text-left precio_dev" style=" background-color:#FFF; color:#000;" readonly aria-describedby="sizing-addon1" value="<?= number_format($precio, 2) ?>">
	</div>
	<div class="col-lg-2">
		<input type="text" style=" background-color:#FFF; color:#000;" class=" form-control form-control-sm text-left importe_dev" aria-describedby="sizing-addon1" readonly value="<?= number_format($_POST['cantidad'] * $precio, 2) ?>">
	</div>
	<div class="col-lg-2 text-center ">
		<img aleatorio1="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Eliminar Producto' class="eliminar_dev" src="img/delete.png" height="20" style="cursor:pointer">
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
	<title>Devoluciones</title>
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
	<div class="modal fade " id="MODAL_CAJA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
										&nbsp;Apartado
										<? $fecha = strtotime('+30 day', strtotime(date("d-m-Y"))) ?>
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
					<h5 class="modal-title" id="exampleModalLabel">Agregar Cliente Nuevo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" id="cerrar_CLIE">&times;</span>
					</button>
				</div>
				<div id="resultados_modal">
					<div class="modal-body" id="modal_cliente">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" id="guardar_cliente">Agregar Cliente</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?
	include("menu1.php");

	?>

	<div class="modal fade bd-example-modal-lg" id="devolver_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">

			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Devolucion de prducto</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="container-fluid">
					<div class="row">


						<div class="col-sm-2">
							<br>Saldo a Favor
							<input class="form-control form-control-sm text-center" type="text" id="saldo_favor">
						</div>
						<div class="col-sm-1"></div>
					</div>
					<div class="row">
						<div class=" col-md-2 text-center">
							<b for="cantidad">Cantidad</b><input type="number" name="" class="form-control text-right" id="cantidad_dev" value="1" placeholder="" min="0">
						</div>
						<div class="col-sm-8">
							<b for="tipo">Productos</b>
							<input list="datosProductos" autocomplete="off" name="" class="form-control" id="Productos_dev" placeholder="Buscar Productos" autocomplete>
							<datalist id="datosProductos" class="col-md-9" active style="width:1000px;">
							</datalist>

						</div>
						<div class="col-sm-2"><br>
							<button class="form-control btn btn-success" id="agregar_dev">Agregar</button>
						</div>
					</div>
					<div class="row ">
						<br>
						<div class="col-sm-12"><br>
							<table class="table table-sm table-hover" width="100%">
								<thead>
									<tr style="background-color:#2e353d; color:#FFF;">
										<td width="100" class="text-center">Cantidad</td>
										<td width="290" class="text-center">Descripci&oacute;n</td>
										<td width="160" class="text-left">Precio</td>
										<td width="160" class="text-left">Importe</td>
										<td width="160">Opciones</td>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<div class="row">
						<div class=" col-lg-12 pre-scrollable" id="tabla_detalles_dev">
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Devolver</button>
				</div>
			</div>

		</div>
	</div>


	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<hr>
				<div class="row">
					<div class="col-md-10">
						<div class="row">
							<div class="form-group col-md-1 text-center">
								<b>Notas</b>
							</div>
							<div class="form-group col-md-8 text-center">
								<input list="datosClientes" name="" autocomplete="off" class="form-control" id="clientes" placeholder="Buscar clientes">
								<datalist id="datosClientes" active>
								</datalist>
							</div>
							<div class="col-md-3">
								<button class="form-control btn btn-warning" style="display:none" id="limpio">LIMPIAR REGISTRO</button>
							</div>
							<div class="form-group col-md-1 ">
								<b>Direccion</b>
							</div>
							<div class="form-group col-md-3 ">
								<input type="text" class="form-control text-center" id="domicilio" readonly value="">
							</div>
							<div class="form-group col-md-1 ">
								<b>Telefono</b>
							</div>
							<div class="form-group col-md-3 ">
								<input type="text" class="form-control text-center" id="telefono" readonly value="">
							</div>
							<div class="form-group col-md-1 ">
								<b>Fecha</b>
							</div>
							<div class="form-group col-md-3">
								<input type="text" class="form-control text-center" id="fecha" readonly>
							</div>
						</div>
						<div class="row" style="display: none">
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
								<table class="table table-sm table-hover">
									<thead class="text-center">
										<tr style="background-color:#2e353d; color:#FFF;">
											<td width="100" class="text-center">Devolver</td>
											<td width="300" class="text-center">Cantidad</td>
											<td width="700" class="text-center">Descripci&oacute;n</td>
											<td width="200" class="text-left">Precio</td>
											<td width="200" class="text-left">Importe</td>
											<!-- 											<td width="200">Opciones</td>
 -->
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

							<div class="row" style="display: none" id="btn_msg">
								<div class="col-md-12 text-center">
									<h3>Guardar Devolución</h3>
								</div>
								<div class="col-md-12 text-center"><br>
									<button type="button" id="guardar_dev" class="btn btn-success">Guardar</button><br><br>
					                             <button type="button"  style="display:none"  data-toggle="modal" data-target="#AddDate" class="btn btn-danger">&nbsp; Devolver Efectivo &nbsp; </button>
 
								</div>
								<div class="col-md-2 text-center"><br>

								</div>
								<div class="col-md-8 text-center"><br>
									<h5>Saldo A Favor</h5>
									<input type="text" id="Favor" value="0.00" class="form-control text-center">
								</div>
								<!-- Default input -->

							</div>

							<div class="modal fade" id="AddDate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
								<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">¿Que desea Hacér?</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="container">
												<div class="row" id="efectvo_modal" style="display: none">

												</div>
												<div class="row" id="botones_dev">
													<div class="col-sm-6">
														<button type="button" id="dev_efectivo" class="btn btn-danger btn-lg">&nbsp;<br> Devolver Efectivo <br> &nbsp; </button>
													</div>
													<div class="col-sm-6">
														<button type="button" id="dev_producto" class="btn btn-danger btn-lg">&nbsp; <br> Regresar Producto <br> &nbsp; </button>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrar_modal" data-dismiss="modal">Cerrar</button>
										</div>
									</div>
								</div>
							</div>




							<div style="display: none" class="col-md-12 text-center">
								<div class="input-group input-group-md">
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">$</span>
									</div>
									<input type="text" class="form-control text-right " id="total" style=" font-size:32px;" readonly value="0.00">
								</div>
							</div>
						</div>
						<div class="row" style="display: none">
							<div class="col-md-12 text-center">
								<h3>Fecha</h3>
								<h2><?= date("d-m-Y") ?></h2>
							</div>

							<div class="col-md-12 text-center">
								<hr>
							</div>
							<div class="col-md-12 text-center">
								<button type="button" data-toggle="modal" data-target="#MODAL_CAJA" id="caja" class="btn btn-success btn-block btn-md text-center ">
									<h5>Pasar a Caja</h5> <img src="img/money.png" height="65">
								</button>
							</div>
							<div class="col-md-12 text-center">
								<hr>
							</div>
							<div class="col-md-12 text-center">
								<h3>saldo actual</h3>
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

				<!-- Modal -->
				<div class="modal fade" id="dev-modal" role="dialog">
					<div class="modal-dialog">

						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Modal Header</h4>
							</div>
							<div class="modal-body">
								<p>Some text in the modal.</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(e) {
			$("#clientes").focus();

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
						//$("#clientes").val("").focus();
						Credito_Cliente($("#clientes").val());
					}
				});
			}

			function Carga_Caja(tipo) {
				if (Quita_Moneda($("#total").val()) == 0) {
					alertify.alert("Punto de Venta ", "Ingresa un Producto");
					//$('#myModal_caja').modal('hide');
					$('#cierra_venta').click();
					$("#Productos").focus();
					return false;
				}
				if ($("#clientes").val() == '' && tipo == 'Ticket') {
					$("#clientes").val('000001-VENTA DE MOSTRADOR');
				}
				if ($("#clientes").val() == '') {
					alertify.alert("Punto de Venta ", "Ingresa un Cliente");
					//$('#myModal_caja').modal('hide');
					return false;
				}
				$("#efectivo").focus().select();
				//$('#myModal_caja').modal('show');
				$("#myModalLabel").text(tipo);
			}
			$(document).on("click", "#caja", function() {
				//$('#MODAL_CAJA').modal('show');
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
					url: "clientes.php",
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
						//$('#MODAL_CLIENTE').modal('hide');
						Credito_Cliente($("#clientes").val());
					}
				});
			});

			$("#clientes").on("keypress", function(event) {
				if (event.which == 13 && $(this).val() != '') {
					Credito_Cliente($(this).val());
					$("#datosProductos").focus();
					Agrega_detalle_venta();
					$('#limpio').show(300);
					$("#msg1").show(300);
					$("#btn_msg").show(300);


				}

			});

			function limpiarCampo() {
				$("#msg1").hide();
				$("#btn_msg").hide();
				$('#domicilio').val("");
				$('#clientes').val("");
				$('#clientes').focus();
				$("#fecha").val("");
				$("#telefono").val("");
				$("#total").val("0.00");
				$("#tabla_detalles").html("");
				$('#limpio').hide();

			}

			$(document).on("click", "#limpio", function(e) {
				limpiarCampo();


			});

			function Credito_Cliente(cliente) {

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
						//var e=prompt("",msg);
						var datos = msg.split("|");
						$("#domicilio").val(datos[0]);
						$("#telefono").val(datos[1]);
						$("#fecha").val(datos[2]);
						//$("#credito_disponible").val(Formato_Moneda(datos[2],2));

					}
				});
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
			$(document).on("click", "#guardar", function() {
				var idventas = 0;
				if (Quita_Moneda($("#total").val()) == 0) {
					alertify.alert("Punto de Venta ", "Ingresa un Producto");
					//$('#myModal_caja').modal('hide');
					$('#cierra_venta').click();
					$("#Productos").focus();
					return false;
				}
				//VALIDAR SI ES DE CONTADO QUE INGRESARON EL EFECTIVO O TARJETA
				if ($("#tipo_venta").val() == "contado") {
					var efectivo = Quita_Moneda($("#efectivo").val());
					var tarjeta = Quita_Moneda($("#tarjeta").val());
					var total = Quita_Moneda($("#total_m").val());
					if ((efectivo + tarjeta) < total) {
						alertify.error("Cantidad Menor al Importe de la Venta");
						$("#efectivo").focus();
						return false;
					}
				}
				alertify.success("Guardando Venta por favor Espere");
				var Detalle = new Array();
				var cnt = 0;
				$(".detalle_productos").each(function() {
					var idproductos = $(this).attr("idproductos");
					var productos = $(this).find(".nombre").val();
					var cantidad = Quita_Moneda($(this).find(".cantidad").val());
					var precio = Quita_Moneda($(this).find(".precio").val());
					Detalle[cnt] = Array(idproductos, productos, cantidad, precio);
					cnt++;
				});
				$.ajax({
					type: "POST",
					url: "<?= $_SERVER["PHP_SELF"] ?>",
					data: ({
						funcion: "Guardar_Ventas",
						efectivo: Quita_Moneda($("#efectivo").val()),
						tarjeta: Quita_Moneda($("#tarjeta").val()),
						comision: Quita_Moneda($("#comision").val()),
						total_tarjeta: Quita_Moneda($("#total_tarjeta").val()),
						importe: Quita_Moneda($("#total_m").val()),
						folio: $("#folio").val(),
						tipo: $("#tipo_venta").val(),
						clientes: $("#clientes").val(),
						descuento: Quita_Moneda($("#descuento_general").val()),
						detalle: Detalle
					}),
					dataType: "html",
					async: false,
					success: function(msg) {
						idventas = msg;
						if (msg > 0) {
							alertify.success("Venta Guardada Correctamente" + msg);
							var bob = window.open('', '_new');
							bob.location = "ticket_venta.php?idventas=" + idventas;
							window.location = "pventas.php";
						} else {
							alertify.alert("Punto de Venta ", "Venta No Guardada Realizarla de nuevo ");
							window.location = "pventas.php";
							return false
						}
					}
				});
				$('#guardar').attr("disabled", false);
				return false;
			});
			$(document).on("click", "#agregarm", function(e) {
				if ($("#cantidad").val() == '') {
					alertify.alert("Ingresa una Cantidad ");
					$("#cantidad").focus().select();
					return false;
				}
				if ($("#Productos").val() == '') {
					alertify.alert("Punto de Venta ", "Ingresa un Producto");
					$("#Productos").focus();
					return false;
				}
				Agrega_Productos($("#cantidad").val(), $("#Productos").val());
			});
			$(document).on("click", "#agregar_dev", function(e) {
				if ($("#cantidad_dev").val() == '') {
					alertify.alert("Ingresa una Cantidad ");
					$("#cantidad_dev").focus().select();
					return false;
				}
				if ($("#Productos_dev").val() == '') {
					alertify.alert("Punto de Venta ", "Ingresa un Producto");
					$("#Productos").focus();
					return false;
				}
				Agrega_Productos_dev($("#cantidad_dev").val(), $("#Productos_dev").val());
			});

			function Agrega_detalle_venta(producto) {
				var clientes = $("#clientes").val();
				var res = clientes.split("-");
				var folio = res[0];
				//	var comodin = producto.split("-");
				//	var codigo = comodin[0];
				$.ajax({

					type: "POST",
					url: "<?= $_SERVER["PHP_SELF"] ?>",
					data: ({
						funcion: "Agrega_Detalle_ticket",
						//				codigo : codigo,
						folio: folio


					}),
					dataType: "html",
					async: false,
					success: function(msg) {
						//alert(msg);
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
			};

			function Agrega_Productos_dev(cantidad, producto) {
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
							funcion: "Agrega_Detalle_dev",
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
								$("#tabla_detalles_dev").append(msg);
								$("#cantidad_dev").val(1);
								$("#base").val(1);
								$("#altura").val(1);
								$("#Productos_dev").val("").focus();
								//Totales();
							}
						}
					});
				} else {
					$("#cantidad").val(1);
					$("#Productos").val("").focus();
				}
				return false;

			}

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
			$(document).on("click", "#tabla_detalles_dev .eliminar_dev", function() {
				var id = $(this).attr("aleatorio1");
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
			/* 			$(document).on("click", "#tabla_detalles .devolver", function() {
							var id = $(this).attr("aleatorio");
							var nombre = $(this).parent().parent().find(".nombre").val();
							var precio = $(this).parent().parent().find(".precio").val();
							var cantidad = $(this).parent().parent().find(".cantidad").val();
							//	$("#devolver_modal").modal();
							$("#descripcion_modal").html(nombre);
							$("#precio_modal").html(precio);
							$("#cantidad_modal").html(cantidad);
							$("#saldo_favor").val(precio);
							$('#devolver_modal').modal('show');
						});
			 */

			$(document).on("click", "#tabla_detalles .transaction", function() {
				var id = $(this).attr("aleatorio");
				var nombre = $(this).parent().parent().find(".nombre").val();
				//	alert(nombre);
				var precio = $(this).parent().parent().find(".precio").val();

				alertify.confirm('Estas Seguro de Regresar el dinero del Producto', nombre + " Con valor de $" + precio, function() {


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



			$(document).on("change", ".devol", function() {

				var Detalle = new Array();
				var cnt = 0;
				var total = 0;
				var cant_row = 0;
				var conteo1 = 0;
				var conteo = 0;

				var espacio = "-";
				$(".detalle_productos").each(function() {
					var devolver = Quita_Moneda($(this).find(".devol").val());
					var cant = Quita_Moneda($(this).find(".cantidad ").val());
					if (devolver > cant) {
							alertify.alert('Error', 'EL valor elegido es mayor que la Cantidad', function() {
						});
						return false;


					} else {
						if (devolver == 0) {
							$("#Favor").val(Formato_Moneda(total, 2));
						} else {
							var idventas = $(this).attr("idventas");
							var productos = $(this).find(".nombre").val();
							var cantidad = $(this).find(".devol").val();
							var precio = Quita_Moneda($(this).find(".precio").val());
							//alert(precio);
							Detalle[cnt] = Array(idventas);
							total += precio * devolver;
							conteo1 += cantidad + conteo;

							cnt++;

							//
						}
					}


					//return false;

				});
				//alert(total);
				$("#Favor").val(Formato_Moneda(total, 2));
			});
			//$("#AddDate").hide();

			$(document).on("click", "#guardar_dev", function() {
				//$("#AddDate").hide();
				var Detalle = new Array();
				var cnt = 0;
				var total = 0;
				var cant_row = 0;
				var conteo1 = 0;
				var conteo = 0;

				var espacio = "-";
				$(".detalle_productos").each(function() {
					var devolver = $(this).find(".devol").val();
					//alert(devolver);

					if (devolver == 0) {
						//Detalle[cnt] = Array("");
						//cnt++;
					} else {
						var idventas = $(this).attr("idventas");
						var productos = $(this).find(".nombre").val();
						var cantidad = $(this).find(".devol").val();
						var precio = $(this).find(".precio").val();
						Detalle[cnt] = Array(idventas);
						total += precio * devolver;
						conteo1 += cantidad + conteo;

						cnt++;

					}


				});
				if (total == 0) {
					alertify.alert('Seleccione un valor', 'Seleccione un valor para devolver!', function() {
						alertify.error('Error');
					});
					return false;


				} else {
					alertify.confirm('Estas Seguro de Guardar la Devolucion??', function() {
						jQuery.noConflict();
						$('#AddDate').modal('show'); 
						//	$("#modal_guardar").modal("show");
					}, function() {
						alertify.error('Cancelado');
						//$("#AddDate").hide();
					});



				}


			});



			$(document).on("click", "#dev_efectivo", function() {
				alertify.confirm('Pregunta del Sistema', '¿Estas seguro de Devolver el Efectivo?', function() {
					var id = $(".detalle_productos").attr("aleatorio");
					//alert(id);
					var idventas = 0;
					var Detalle = new Array();
					var cnt = 0;
					var total = 0;
					var cant_row = 0;
					var conteo1 = 0;
					var conteo = 0;
					alertify.success("Guardando Venta por favor Espere");
					var Detalles = new Array();
					var cnt = 0;

					$(".detalle_productos").each(function() {
						var devolver = $(this).find(".devol").val();

						if (devolver == 0) {
							//Detalle[cnt] = Array("");
							//cnt++;
						} else {
							var idventas = $(this).attr("idventas");
							var idproductos = $(this).attr("idproductos");
							var productos = $(this).find(".nombre").val();
							var cantidad = Quita_Moneda($(this).find(".devol").val());
							var precio = Quita_Moneda($(this).find(".precio").val());
							var ventasid = $(this).attr("ventasid");
							Detalles[cnt] = Array(idventas, cantidad, idproductos, precio, ventasid);
							total += precio * devolver;
							cnt++;
						}
					});

					//alert(Detalles);
					var clientes = $("#clientes").val();
					var res = clientes.split("-");
					var folio = res[0];
					var saldo = Quita_Moneda($("#Favor").val());
					//alert(saldo);
					//return false;
					$.ajax({
						type: "POST",
						url: "<?= $_SERVER["PHP_SELF"] ?>",
						data: ({
							funcion: "MODIFICAR_VENTA_DETALLE",
							detalle: Detalles,
							totales: saldo,
							Folio: folio
						}),
						dataType: "html",
						async: false,
						success: function(msg) {
							//alert(msg);
							idventas = msg;
							if (msg > 0) {
								alertify.success("Venta Guardada Correctamente" + msg);
								var bob = window.open('', '_new');
								bob.location = "ticket_ventas_devolucion.php?idventas=" + idventas;
								window.location = "devoluciones.php";
							} else {
								alertify.alert("Punto de Venta ", "Venta No Guardada Realizarla de nuevo ");
								window.location = "devoluciones.php";

								return false;
							}
						}
					});




				}, function() {
					alertify.error('Cancelado')
				});

			});
			$(document).on("click", "#dev_producto", function() {
				alertify.confirm('Pregunta del Sistema', '¿Estas seguro de Regresar el producto?', function() {
					var saldo = Quita_Moneda( $("#Favor").val());
					//alert(saldo);
					
					//return false;
					var id = $(".detalle_productos").attr("aleatorio");
					//alert(id);
					var idventas = 0;
					var Detalle = new Array();
					var cnt = 0;
					var total = 0;
					var cant_row = 0;
					var conteo1 = 0;
					var conteo = 0;
				//	alertify.success("Guardando Venta por favor Espere");
					var Detalles = new Array();
					var cnt = 0;

					$(".detalle_productos").each(function() {
						var devolver = $(this).find(".devol").val();

						if (devolver == 0) {
							//Detalle[cnt] = Array("");
							//cnt++;
						} else {
							var idventas = $(this).attr("idventas");
							var idproductos = $(this).attr("idproductos");
							var productos = $(this).find(".nombre").val();
							var cantidad = Quita_Moneda($(this).find(".devol").val());
							var precio = Quita_Moneda($(this).find(".precio").val());
							var ventasid = $(this).attr("ventasid");
							Detalles[cnt] = Array(idventas, cantidad, idproductos, precio, ventasid);
							total += precio * devolver;
							cnt++;
						}
					});





					$.ajax({
						type: "POST",
						url: "<?= $_SERVER["PHP_SELF"] ?>",
						data: ({
							funcion: "DEVOLVER_PRODUCTO",
							detalle: Detalles
							
						}),
						dataType: "html",
						async: false,
						success: function(msg) {
							//alert(msg);
							window.location = "pventas.php?saldo=" + saldo;
						}
					});




				


				}, function() {
					//alertify.error('Cancelado')
				});


			});
			$(document).on("click", "#cerrar_modal", function() {
				$("#botones_dev").show(300);
				$("#Regresar").hide();
				$("#efectvo_modal").hide();
				//	$("#tabla_modal_dev").html("");


			});
			$(document).on("click", "#Regresar", function() {
				$("#botones_dev").show(300);
				$("#Regresar").hide();
				$("#efectvo_modal").hide();



			});
			$(document).on("click", "#salir", function() {
				alertify.confirm('Pregunta del Sistema', '¿Estas seguro de Cancelar la Venta?', function() {
					window.location = "devoluciones.php";
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
	</script>
</body>

</html>