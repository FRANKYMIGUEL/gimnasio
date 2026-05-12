<?
$saldo = $_GET["saldo"];
//echo $saldo;
include("inc/conectar.php");
if( !isset($_SESSION['SISTEMA']['usuario'])){
	echo "Sin sessiones".$_SESSION['SISTEMA']['usuario'];
	header("Location: login.php");
	exit();
}

if ($_POST["funcion"]=="DataListProductos") {
	include("inc/conectar.php");
	$existencias = 0;
	$buscar = $consulta->query("SELECT codigo, nombre, precio FROM productos WHERE fechabaja IS NULL");
		foreach ($buscar as $row){
			$existencias  = $row['precio'];
			echo "<option id='$row[codigo]' value ='$row[codigo]-".stripslashes ($row['nombre'])." Precio $ ".number_format($existencias,2)."'>";
		}
	exit();
}

function Auto_TablaC(){
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT MAX(idventas)+1 as Auto_increment FROM ventas");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	$Auto = str_pad($Auto, 6, "0", STR_PAD_LEFT);
	return $Auto;
}
function Auto_Tabla(){
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT MAX(idventas)+1 as Auto_increment FROM ventas");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	return $Auto;
}
if($_POST["funcion"]=="Guardar_Ventas"){
	include("inc/conectar.php");
	$idsuc= 1;
	$idventas = Auto_Tabla();
	
	$idusuario = $_SESSION['SISTEMA']['idusuarios'];
	$usuarios = $_SESSION["SISTEMA"]["usuario"];
	if($idusuario=='')$idusuario = 1;
	if($usuarios=='')$usuarios = 'Vendedor';
	$pago = ", fechapago='".date("Y-m-d")."'";
	$efectivo = 0;

	$sql_con = "INSERT INTO ventas SET fecha='".date("Y-m-d H:i:s")."', idusuarios=".$idusuario.", usuarios='".$usuarios."', efectivo=".$_POST['efectivo'].", importe=".$_POST['importe'].", folio='".$_POST['folio']."' ".$pago.$saldo;

$stmt = $consulta->prepare($sql_con);
$stmt->execute();
if($stmt->errorCode() == 0) {
	include("inc/conectar.php");
	
	
	//SI SE INSERTO LA VENTA
		//RECORRO EL ARREGLO DE PRODUCTOS
		foreach($_POST["detalle"] as $key=>$val){
			//INSERTA DETALLE DE VENTA
			
			$sql_detalle = "INSERT INTO ventas_detalle SET idventas=".$idventas.", idproductos=".$val[0] .", productos='".$val[1]."', cantidad=".$val[2].", precio=".$val[3].", importe=".($val[3]*$val[2]."");
			
			$query1 = $consulta->query($sql_detalle);
			foreach ($query1 as $row2);
			
		}
		 echo $idventas;

} 
 exit();
}
if($_POST['funcion']=='Agrega_Detalle'){
	include("inc/conectar.php");
	$Auto = $consulta->query("SELECT * FROM productos WHERE codigo LIKE '".$_POST['codigo']."' AND fechabaja IS NULL");
	foreach ($Auto as $row);
	$precio = $row['precio'];
	$IVAS = 0;

	if($row[0]!=''){
		
		$numero = str_pad(rand(0,99999), 6, "0", STR_PAD_LEFT);
		?>
	<div class="row detalle_productos" aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" idproductos="<?= $row[0] ?>" id="<?= $_POST['codigo'] ?>">
		<div class="col-lg-2">
			<input type="text" class="cantidad form-control-sm input-group-sm text-right" min="0" value="<?= number_format($_POST['cantidad'], 2) ?>">
		</div>
		<div class="col-lg-5 text-center">
			<input type="text" class="form-control-sm nombre" style="width:100%; background-color:#FFF; color:#000; border-color:#FFF;" readonly value="<?= $row['codigo'] . " - " . $row['nombre'] ?>">
		</div>
		<div class="col-lg-2">
			<input type="text" class=" form-control-sm text-right precio" style=" background-color:#FFF; color:#000;"  aria-describedby="sizing-addon1" value="<?= number_format($precio, 2) ?>">
		</div>
		<div class="col-lg-2">
			<input type="text" style=" background-color:#FFF; color:#000;" class=" form-control-sm text-right importe" aria-describedby="sizing-addon1" readonly value="<?= number_format($_POST['cantidad'] * $precio, 2) ?>">
		</div>
		<div class="col-lg-1 text-center ">
			<i class="bi bi-x-circle" aleatorio="<?= $numero ?>" codigo="<?= $_POST['codigo'] ?>" title='Eliminar Producto' class="eliminar" style="cursor:pointer"></i>
		</div>
</div>
<?
		}
exit();
}

 ?>
<!DOCTYPE html>
<html>

<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Softsimbiosis</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-3.3.1.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap4.min.js"></script>
	<link rel="stylesheet" href="alertifyjs/css/alertify.css">
	<link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
	<script src="alertifyjs/alertify.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
						<div class="col-4 text-center">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">Efectivo </span>
									<span class="input-group-text">$</span>
								</div>
								<input type="text" class="form-control text-right" id="efectivo" aria-label="Amount (to the nearest dollar)">
							</div>
						</div>
						<div class="col-4 text-center">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><b>Total </b> </span>
									<span class="input-group-text"><b>$ </b></span>
								</div>
								<input type="text" class="form-control text-right" id="total_m" readonly aria-label="Amount (to the nearest dollar)">
							</div>
						</div>
			
						<div class="col-4 text-center">
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
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="cierra_venta">Cerrar</button>
					<button type="button" class="btn btn-primary" id="guardar">Finalizar Venta</button>
				</div>
			</div>
		</div>
	</div>
	
	</div>
	<?
	include("menu.php");
	
	?>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			<input type="text" style="height:1px; width:1px;" id="duplicado" class="invisible" value="<?=$IDVENTAS?>">
				<div class="row">
					<div class="col-md-10">
						
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
								<button class="form-control btn btn-success" id="agregarm"><i class="bi bi-plus-circle"></i> Agregar</button>
							</div>
						</div>
						<div class="row ">
							<div class="col-sm-12">
								<table class="table table-sm table-hover" width="90%">
									<thead>
										<tr style="background-color:#2e353d; color:#FFF;">
											<td width="100" class="text-center">Cantidad</td>
											<td class="text-center">Descripci&oacute;n</td>
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
								<?php
									include("inc/conectar.php");
									$Auto = $consulta->query("SHOW TABLE STATUS Like 'ventas'");
									foreach ($Auto as $Autocontador);
									$Auto = ($Autocontador["Auto_increment"]);
									$Auto = str_pad($Auto, 6, "0", STR_PAD_LEFT);
								?>
								<input type="text" class="form-control text-center" id="folio" style="background-color:#FFFFFF;" value="<?= $Auto?>" disabled>
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
							<div class="col-md-12 text-center" id="">
								<h3>Total</h3>
							</div>
							<div class="col-md-12 text-center" id="">
								<div class="input-group input-group-md">
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">$</span>
									</div>
									<input type="text" class="form-control  text-right " id="total" readonly value="0.00">
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(e) {
			var bandera = true;
			$(window).on('beforeunload', function (e) {
				if(bandera){
					var message = '¿Estás seguro de que quieres abandonar esta página?';
                (e || window.event).returnValue = message; // Para la mayoría de los navegadores
                return message; // Para algunos navegadores como Firefox
				}
               
            });
			
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
						//console.log(msg);
						$("#datosProductos").html(msg);
						$("#Productos").val("").focus();
					}
				});
			}
			
			function Carga_Caja(tipo) {
				if (Quita_Moneda($("#total").val()) == 0) {
					alertify.alert("Punto de Venta ", "Ingresa un Producto");
					$('#cierra_venta').click();
					$("#Productos").focus();
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
			
			$(document).on("click", "#guardar", function() {
				var idventas = 0;
				if (Quita_Moneda($("#total").val()) == 0) {
					alertify.alert("Punto de Venta ", "Ingresa un Producto");
					//$('#myModal_caja').modal('hide');
					$('#cierra_venta').click();
					$("#Productos").focus();
					return false;
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
					url: "pventas.php",
					data: ({
						funcion: "Guardar_Ventas",
						efectivo: Quita_Moneda($("#efectivo").val()),
						importe: Quita_Moneda($("#total_m").val()),
						folio: $("#folio").val(),
						detalle: Detalle
					}),
					dataType: "html",
					async: false,
					success: function(msg) {
						idventas = msg;
						if (msg > 0) {
							bandera = false;
							alertify.success("Venta Guardada Correctamente" + msg);
						
							setTimeout(function() {window.location = "pventas.php";}, 2000);
							
						} else {
							bandera = true;
							alertify.alert("Punto de Venta ", "Venta No Guardada Realizarla de nuevo ");
							console.log(msg);
							
							return false
						}
					},error: function(xhr, status, error) {
						// Función a ejecutar si hay un error en la solicitud
						console.error('Error en la solicitud:', status, error);
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

			function Agrega_Productos(cantidad, producto) {
				var comodin = producto.split("-");
				var codigo = comodin[0];
				//BUSCAR REGISTRO EN EL DETALLE
				var agregado = true;
				$(".detalle_productos").each(function() {
					if($(this).attr("codigo")==codigo ){
						agregado = false;
						var cant = Quita_Moneda($(this).find(".cantidad").val());
						$(this).find(".cantidad").val(Formato_Moneda(cant+Quita_Moneda($("#cantidad").val()),2));
					}				
				});
				if (!agregado){
					Totales();
				}else{
					$.ajax({
						type: "POST",
						url: "<?= $_SERVER["PHP_SELF"] ?>",
						data: ({
							funcion: "Agrega_Detalle",
							codigo: codigo,
							nombre: producto,
							cantidad: cantidad,
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
								$("#Productos").val("").focus();
								Totales();
							}
						}
					});
				} 
				$("#cantidad").val(1);
				$("#Productos").val("").focus();
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
			$(document).on("keydown", ".precio", function(e) {
				if ($(this).val() > 0) {
					var precio = Quita_Moneda($(this).parent().parent().parent().find(".precio").val());
					var importe = $(this).val() * precio;
					$(this).parent().parent().parent().find(".importe").val(Formato_Moneda(importe, 2));
				} else {
					$(this).val(Formato_Moneda(1, 2));
				}
				Totales();
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
			$(document).on("click", "#salir", function() {
				alertify.confirm('Pregunta del Sistema', '¿Estas seguro de Cancelar la Venta?', function() {
					window.location = "pventas.php";
				}, function() {
					//alertify.error('Cancelado')
				});
			});

			$(document).on("keyup", ".precio", function(e) {
				e.preventDefault();
				//alert(e.which + ": " + String.fromCharCode(e.which));
				if (e.which == 13) {
					Totales();
				}
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
				$("#total-importe").val(Formato_Moneda(total, 2));
				$("#total").val(Formato_Moneda(total, 2));
				$("#total_m").val(Formato_Moneda(total, 2));
				$("#total_texto").text("$ " + Formato_Moneda(total, 2));
				$("#total_textoC").text("$ " + Formato_Moneda(total, 2));
				
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