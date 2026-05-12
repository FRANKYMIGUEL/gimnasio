<?
if($_POST['funcion']=='Carga_Ventas'){
	include("inc/conectar.php");
	$tipo = '';
	$estado = '';
	$proveedor = '';
	if($_POST['estado']=='pagadas'){
		$estado = ' AND fechapago IS NOT NULL';
	}elseif($_POST['estado']=='credito'){
		$estado = ' AND fechapago IS NULL';
	}
	if($_POST['cliente']!='0'){
		$proveedor = ' AND idclientes='.$_POST['cliente'];
	}
	$tipo = " ";
	$cancelado = '';
	
	?>
    <table  class="table table-sm  " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th align="right">Importe</th>
            </tr>
        </thead>
	<?

	$resultados=$consulta->query("SELECT * FROM compras WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' $estado $proveedor $tipo ORDER BY idcompras DESC");
	foreach ($resultados as $row) {
		$IMPORTE += $row['importe'];
		$SALDO += $row['saldo'];
		$ABONOS += $row['abonos'];
		if($row['tipo']=='Cancelada')$cancelado = 'bg-danger';
	?>
	<tr class="<?=$cancelado?>">
		<td><?=str_pad($row["folio"], 6, "0", STR_PAD_LEFT)?></td>
		<td class="text-uppercase"><?=$row["tipo"]?></td>
		<td><?=substr($row['fecha'],0,10)?></td>
		<td><?=$row['proveedor']?></td>
		<td align="right">$ <?=number_format($row['importe'],2)?></td>
		<td>
			<a href="ticket_compras.php?idcompras=<?=$row[0]?>" target="_blank"><button class='btn-group btn-group-sm btn-info' title='Reimprimir' ><img src="img/print.png" height="20"></button></a>

		</td>
	</tr>
<?
	$cancelado = '';
	}
	?>
        <tfoot>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td align="right">$ <?=number_format($IMPORTE,2)?></td>
            </tr>
        </tfoot>
    </table>
    <?
	exit();
}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Reporte de Compras</title>
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
	$tipo = 'todos';
	if(isset($_GET['fechainicial'])){$fechainicial = $_GET['fechainicial'];}else{$fechainicial = date("Y-m-d");}
	if(isset($_GET['fechafinal'])){$fechafinal = $_GET['fechafinal'];}else{$fechafinal = date("Y-m-d");}
	if(isset($_GET['estado'])){$estado = $_GET['estado'];}else{$estado = 'todos';}
	if(isset($_GET['idcliente'])){$idcliente = $_GET['idcliente'];}else{$idcliente = 0;}
	if(isset($_GET['tipo'])){$tipo = $_GET['tipo'];}else{$tipo = 'todos';}
        include("menu1.php");
	 ?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h4>Reporte de Compras</h4>
                </div>
            </div>
            <div class="row">
				<div class="col-md-2 text-center ">
                   
                </div>
                <div class="col-md-2 text-center ">
                    <b>Fecha Inicial</b>
                    <input type="date" class="form-control " id="fechainicial" value="<?=$fechainicial?>">
                </div>
                <div class="col-md-2 text-center ">
                    <b>Fecha Final</b>
                    <input type="date" class="form-control " id="fechafinal" value="<?=$fechafinal?>">
                </div>
               
                <div class="col-md-4 text-center ">
                    <b>Proveedor</b><br>
                    <select class="form-control  " id="idcliente">
                        <option value="0" <? if($idcliente==0)echo "selected";?>>Todos</option>
                        <?
                        $Auto = $consulta->query("SELECT * FROM proveedores WHERE inactivo IS NULL");
                        foreach ($Auto as $row){
                        ?>
                        <option value="<?=$row['idproveedores']?>" <? if($idcliente==$row[0])echo "selected";?>><?=$row['idproveedores']." - ".$row['nombre']?></option>
                        <?
                        }
                        ?>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 " id="resultados_productos">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {

	Carga_Entradas();

	$(document).on("change","#estado, #fechainicial, #fechafinal, #idcliente",function(){
		var ruta = '<?=$_SERVER["REQUEST_URI"];?>';
		ruta = ruta.split("?");
		if(ruta[1]== undefined){
			window.location = "reporte_compras.php?estado="+$("#estado").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();
		}else{
			window.location = "reporte_compras.php?estado="+$("#estado").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();
		}
	});

	function Carga_Entradas(){

		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Ventas",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val(),
				estado : $("#estado option:selected").val(),
				tipo : $("#tipo option:selected").val(),
				cliente : $("#idcliente option:selected").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_productos").html(msg);
			}
		});
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
