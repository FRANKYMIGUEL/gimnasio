<?
if($_POST['funcion']=='GuardarE'){
include('inc/conectar.php');
	$Auto = $consulta->query("INSERT INTO movimientoscaja SET fecha='".date("Y-m-d H:i:s")."', idusuarios='".$_SESSION['SISTEMA']['idusuarios']."', usuarios='".$_SESSION['SISTEMA']['usuario']."', observaciones='".$_POST['observaciones']."', importe='".$_POST['importe']."', tipo='Retiro' ");
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='GuardarI'){
include('inc/conectar.php');
	$Auto = $consulta->query("INSERT INTO movimientoscaja SET fecha='".date("Y-m-d H:i:s")."', idusuarios='".$_SESSION['SISTEMA']['idusuarios']."', usuarios='".$_SESSION['SISTEMA']['usuario']."', observaciones='".$_POST['observaciones']."', importe='".$_POST['importe']."', tipo='Ingreso' ");
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='movimientoe'){
include('inc/conectar.php');
$total = 0;
	$Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Retiro' AND fecha BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:00:00'");
	foreach ($Auto as $row){
		$total += $row['importe'];
		//formato de fecha
		$row['fecha'] = date("d/m/Y H:i:s", strtotime($row['fecha']));
	?>
	<tr>
		<td><?=$row['fecha']?></td>
		<td><?=$row['observaciones']?></td>
		<td align="right">$ <?=number_format($row['importe'],2)?></td>
		<td><?=$row['usuarios']?></td>
	</tr>
    <?
	}
		?>
	<tr>
		<td>&nbsp;</td>
		<td>Total Retiros</td>
		<td align="right">$ <?=number_format($total,2)?></td>
		<td>&nbsp;</td>
	</tr>
    <?

exit();
}
if($_POST['funcion']=='movimientoi'){
include('inc/conectar.php');
$total = 0;
	$Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Ingreso' AND fecha BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:00:00'");
	foreach ($Auto as $row){
		$total += $row['importe'];
		$row['fecha'] = date("d/m/Y H:i:s", strtotime($row['fecha']));
	?>
	<tr>
		<td><?=$row['fecha']?></td>
		<td><?=$row['observaciones']?></td>
		<td align="right">$ <?=number_format($row['importe'],2)?></td>
		<td><?=$row['usuarios']?></td>
	</tr>
    <?
	}
		?>
	<tr class=" text-dark">
		<td>&nbsp;</td>
		<td>Total Ingresos</td>
		<td align="right">$ <?=number_format($total,2)?></td>
		<td>&nbsp;</td>
	</tr>
    <?

exit();
}
	?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Consulta de Movimientos</title>
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
<?
include("menu.php");
?>
  <main class="page-content">
    <div class="container-fluid" style="text-align: center;">
     
      <div class="row align-items-center " style="height: 80%;" >
		<div class="col-12 mx-auto my-auto">
            
            <div class="row">
            	<div class="col-12 mx-auto my-auto text-center">
                    <h5><i class="bi bi-wallet"></i> <b>Movimientos de Caja de Efectivo</b></h5>
                </div>
            </div>
			<div class="row principal" >
				<div class="col-1"></div>
				<div class="col-5" style="text-align: center;">
					<div class="row" style="padding-top: 20px; padding-bottom: 20px;">	
						<div class="col-2 text-uppesrcase align-self-center">
							<label for="Importe"><b>Concepto:</b></label>
						</div>
						<div class="col-3">
							<input type="text" class="form-control" id="observaciones" value="<?=$row['observaciones']?>" placeholder="Concepto">
						</div>
						<div class="col-2 text-uppesrcase align-self-center">
							<label for="Importe"><b>Extracion:</b></label>
						</div>
						<div class="col-3">
							<input type="number" class="form-control" id="extracion" value="<?=$row['extracion']?>" placeholder="Importe">
						</div>
						<div class="col-2">
							<button type="button" class="btn btn-danger" id="guardare">Extraer</button>
						</div>
					</div>
					<div class="row" >	
						<div class="col-12 text-uppesrcase align-self-center">
							<table class="table table-hover">
                            	<thead>
                                	<td>Fecha</td>
                                	<td>Concepto</td>
                                	<td>Importe</td>
                                	<td>Usuario</td>
                                </thead>
                            	<tbody id="registros_tablae"></tbody>
                            </table>
						</div>
					</div>
				</div>
				<div class="col-6" style="text-align: center;">
					<div class="row" style="padding-top: 20px; padding-bottom: 20px;">	
						<div class="col-2 text-uppesrcase align-self-center">
							<label><b>Concepto:</b></label>
						</div>
						<div class="col-3">
							<input type="text" class="form-control" id="observacionesI" value="<?=$row['observaciones']?>" placeholder="Concepto">
						</div>
						<div class="col-2 text-uppesrcase align-self-center">
							<label for="Importe"><b>Ingreso</b></label>
						</div>
						<div class="col-3">
							<input type="number" class="form-control" id="ingresar" value="<?=$row['extracion']?>" placeholder="Importe">
						</div>
						<div class="col-2">
							<button type="button" class="btn btn-success" id="guardari">Ingresar</button>
						</div>
					</div>
					<div class="row" >	
						<div class="col-12 text-uppesrcase align-self-center">
							<table class="table table-hover">
                            	<thead>
                                	<td>Fecha</td>
                                	<td>Concepto</td>
                                	<td>Importe</td>
                                	<td>Usuario</td>
                                </thead>
                            	<tbody id="registros_tablai"></tbody>
                            </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
      </div>
    </div>

  </main>

</div>

<script>
$(document).ready(function() {
	Carga_TablaE();
	Carga_TablaI();
	function Carga_TablaE(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "movimientoe",
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#registros_tablae").html(msg);
			}
		});	
	}
	function Carga_TablaI(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "movimientoi"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#registros_tablai").html(msg);
			}
		});	
	}
	$(document).on("click","#guardare",function(e) {
        if($("#extracion").val()==""){
			alertify.error("Ingresa una Cantidad");
			$("#extracion").focus();
			return false;	
		}
        if($("#observaciones").val()==""){
			alertify.error("Ingresa una Concepto");
			$("#observaciones").focus();
			return false;	
		}
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "GuardarE",
				observaciones : $("#observaciones").val(),
				importe : $("#extracion").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				//var e=prompt("",msg);
				alertify.success("Extraccion Guardada Exitosamente ");
				Carga_TablaE();
				$("#extracion").val("");
				$("#observaciones").val("");
			}
		});	
    });	
	$(document).on("click","#guardari",function(e) {
        if($("#ingresar").val()==""){
			alertify.error("Ingresa una Cantidad");
			$("#ingresar").focus();
			return false;	
		}
        if($("#observacionesI").val()==""){
			alertify.error("Ingresa una Concepto");
			$("#observacionesI").focus();
			return false;	
		}
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "GuardarI",
				observaciones : $("#observacionesI").val(),
				importe : $("#ingresar").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				//var e=prompt("",msg);
				alertify.success("Ingreso Guardada Exitosamente ");
				Carga_TablaI();
				$("#ingresar").val("");
				$("#observacionesI").val("");
			}
		});	
    });	
} );
</script>