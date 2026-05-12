<?
if($_POST['funcion']=='Carga_Ventas_Categoria'){
	include("inc/conectar.php");
	
	$CANTIDAD = 0;
	$TARJETA = 0;
	$UTILIDAD = 0;
	$resultados=$consulta->query("SELECT SUM(ventas_detalle.cantidad) AS cantidad, productos.categoria, SUM((ventas_detalle.precio-productos.costo)*ventas_detalle.cantidad) AS utilidad FROM ventas LEFT JOIN ventas_detalle ON ventas_detalle.idventas=ventas.idventas LEFT JOIN productos ON ventas_detalle.idproductos=productos.idproductos WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' AND( tipo LIKE 'contado' OR tipo LIKE 'credito') GROUP BY productos.categoria ORDER BY cantidad DESC");
	foreach ($resultados as $row) {
		if($row['utilidad']>0){

		
		$CANTIDAD += $row['cantidad'];
		$UTILIDAD += $row['utilidad'];
		$categoria = $row['categoria'];
		if($row['categoria']==''){
			$categoria = "VARIOS";
		}
		?>
		<tr>
			<td align="left"><?=$categoria?></td>
			<td align="right"><?=number_format($row['cantidad'],2)?></td>
			<td align="right">$ <?=number_format($row['utilidad'],2)?></td>
		</tr>
		<?
		}
	}
	?>
		<tr>
			<td></td>
			<td align="right"><?=number_format($CANTIDAD,2)?></td>
			<td align="right">$ <?=number_format($UTILIDAD,2)?></td>
		</tr>
	<?
	exit();
}
if($_POST['funcion']=='Carga_Utilidades'){
	include("inc/conectar.php");
	
	$EFECTIVO = 0;
	$TARJETA = 0;
	$CREDITO = 0;
	$UTILIDAD = 0;
	$resultados=$consulta->query("SELECT SUM(efectivo) AS efectivo, SUM(tarjeta) AS tarjeta, SUM((ventas_detalle.precio-productos.costo)*ventas_detalle.cantidad) AS utilidad FROM ventas LEFT JOIN ventas_detalle ON ventas_detalle.idventas=ventas.idventas LEFT JOIN productos ON ventas_detalle.idproductos=productos.idproductos WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' AND tipo LIKE 'contado'");
	foreach ($resultados as $row) {
		$EFECTIVO += $row['efectivo'];
		$TARJETA += $row['tarjeta'];
		$UTILIDAD += $row['utilidad'];
	}
	$resultados=$consulta->query("SELECT SUM(ventas_detalle.importe) AS credito, SUM((ventas_detalle.precio-productos.costo)*ventas_detalle.cantidad) AS utilidad FROM ventas LEFT JOIN ventas_detalle ON ventas_detalle.idventas=ventas.idventas LEFT JOIN productos ON ventas_detalle.idproductos=productos.idproductos WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' AND tipo LIKE 'credito' ");
	foreach ($resultados as $row) {
		$CREDITO += $row['credito'];
		$UTILIDAD += $row['utilidad'];
	}
	
	echo $TARJETA."|".$EFECTIVO."|".$CREDITO."|".$UTILIDAD;
	exit();
}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Reporte de Utilidades</title>
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
	<?php
	include("inc/conectar.php");
	$estado = 'todos';
	$idusuarios = 'todos';
	if(isset($_GET['fechainicial'])){$fechainicial = $_GET['fechainicial'];}else{$fechainicial = date("Y-m-d");}
	if(isset($_GET['fechafinal'])){$fechafinal = $_GET['fechafinal'];}else{$fechafinal = date("Y-m-d");}
	include("menu1.php");
	 ?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h1>Reporte de Utilidades</h1>
                </div>
            </div>
            <div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-4 text-center ">
							<b>Fecha Inicial</b>
							<input type="date" class="form-control " id="fechainicial" value="<?=$fechainicial?>">
						</div>
						<div class="col-md-4 text-center ">
							<b>Fecha Final</b>
							<input type="date" class="form-control " id="fechafinal" value="<?=$fechafinal?>">
						</div>
						<div class="col-md-4 text-center ">
							<br>
							<button class="form-control btn btn-success" id="filtrar">Filtrar</button>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6 text-center ">
							<table class="table table-striped">
								<tbody>
									<tr>
										<td>En Tarjeta</td>
										<td align="right" id="ventas_tarjeta">$ 0.00</td>
									</tr>
									<tr>
										<td>En Efectivo</td>
										<td align="right" id="ventas_efectivo">$ 0.00</td>
									</tr>
									<tr>
										<td>A Credito</td>
										<td align="right" id="ventas_credito">$ 0.00</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-6 text-center ">
							<h1><label id="ventas_total">$ 0.00</label> <span class="badge bg-success text-light"><label id="ventas_utilidad">$0.00</label> Utilidad</span></h1>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-lg-12 text-center" id="resultados_productos">
							<h4><label>VENTAS POR CATEGORIAS</label> <span class="badge bg-success"></h4>
							<table class="table table-striped table-sm">
								<thead>
									<tr>
										<th>Categoria</th>
										<th>Vendidos</th>
										<th>Utilidad</th>
									</tr>
								</thead>
								<tbody id="resultados_categorias">
									<tdbody>
							</table>
						</div>
					</div>
				</div>
                
				
				
            </div>
            
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {
	Cargar_Totales();
	$(document).on("change","#fechainicial, #fechafinal",function(){
		var ruta = '<?=$_SERVER["REQUEST_URI"];?>';
		ruta = ruta.split("?");
		if(ruta[1]== undefined){
			window.location = "reporte_utilidades.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
		}else{
			window.location = "reporte_utilidades.php??fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
		}
	});

	function Cargar_Totales(){
		$.ajax({
			type: "POST",
			url: "reporte_utilidades.php",
			data: ({
				funcion : "Carga_Utilidades",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				console.log(msg);
				var resultado = msg.split("|");
				$("#ventas_tarjeta").text("$ "+Formato_Moneda(resultado[0],2));
				$("#ventas_efectivo").text("$ "+Formato_Moneda(resultado[1],2));
				$("#ventas_credito").text("$ "+Formato_Moneda(resultado[2],2));
				$("#ventas_total").text("$ "+Formato_Moneda(Quita_Moneda(resultado[0])+Quita_Moneda(resultado[1])+Quita_Moneda(resultado[2]),2));
				console.log(resultado[3]);
				$("#ventas_utilidad").text("$ "+Formato_Moneda(resultado[3],2));
				Carga_Ventas_Categoria();
			},error: function(xhr, status, error) {
				// Función a ejecutar si hay un error en la solicitud
				console.error('Error en la solicitud:', status, error);
			}
		});
	}


	function Carga_Ventas_Categoria(){

		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Ventas_Categoria",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_categorias").html(msg);
			}
		});
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
	function Quita_Moneda(n){
		n=String(n);
		var s=parseFloat(n.replace(",","").replace("$",""));
		if(isNaN(s))s=0;
		return s;
	}
});
</script>
</body>
