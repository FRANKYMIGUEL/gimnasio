<?
if($_POST['funcion']=='Carga_Reporte'){
	include("inc/conectar.php");
	$idcategorias ='existencias>0';
	if($_POST['idcategorias']!='0'){
		$idcategorias .= " AND categoria LIKE '".$_POST['idcategorias']."'";
	}
	?>
    <table  class="table table-sm  " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Nombre</th>
                <th width="100">Existencias</th>
                <th align="right" width="180">Valor Costo</th>
                <th align="right" width="180">Precio</th>
                <th align="right" width="150">Costo por Unidad</th>
            </tr>
        </thead>
	<?
	$TOTAL_EXISTENCIAS = 0;
	$VALOR_COSTO = 0;
	$TOTAL_UTILIDAD = 0;
	$TOTAL_COSTO = 0;
	$slq = "SELECT * FROM productos WHERE $idcategorias ORDER BY nombre ASC";
	$resultados=$consulta->query($slq );
	foreach ($resultados as $row) {
		$TOTAL_UTILIDAD += ($row['precio']-$row['costo'])*$row['existencias'];
		$VALOR_COSTO += $row['costo']*$row['existencias'];
		$TOTAL_COSTO += $row['costo'];
		$TOTAL_EXISTENCIAS +=$row['existencias'];
	?>
	<tr class="<?=$cancelado?>">
		<td class="text-uppercase"><?=$row["nombre"]?></td>
		<td align="right" width="200"><?=number_format($row['existencias'],2)?></td>
		<td align="right" width="180">$ <?=number_format($row['costo']*$row['existencias'],2)?></td>
		<td align="right" width="180">$ <?=number_format($row['precio'],2)?></td>
		<td align="right" width="150">$ <?=number_format($row['costo'],2)?></td>
		</td>
	</tr>
<?
	}
	?>
        <tfoot>
            <tr>
                <td align="right"></td>
				<td align="right"><b><?=number_format($TOTAL_EXISTENCIAS,2)?></b></td>
				<td align="right"><b>$ <?=number_format($VALOR_COSTO,2)?></b></td>
				<td align="right"></td>
				<td align="right"></td>
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
	<title>Consulta de Inventario</title>
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
	if(isset($_GET['idcategorias'])){$idcategorias = $_GET['idcategorias'];}else{$idcategorias = '0';}
        include("menu1.php");
	 ?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h4>Reporte de Inventario</h4>
                </div>
            </div>
            <div class="row">

                <div class="col-md-5 text-center ">
                    <b>Categoria</b><br>
                    <select class="form-control " id="idcategorias">
                     	<option se value="0" >Todas</option>
                        <?
                        $Auto = $consulta->query("SELECT * FROM categorias WHERE inactivo IS NULL ORDER BY categoria");
                        foreach ($Auto as $row){
                        ?>
                        <option value="<?=$row['categoria']?>" <? if($idcategorias==$row['categoria'])echo "selected";?>><?=$row['categoria']?></option>
                        <?
                        }
                        ?>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 " id="resultados_reporte">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {

	Carga_Entradas();

	$(document).on("change","#idcategorias",function(){
		var ruta = '<?=$_SERVER["REQUEST_URI"];?>';
		ruta = ruta.split("?");
		if(ruta[1]== undefined){
			window.location = "reporte_inventario.php?idcategorias="+$("#idcategorias option:selected").val();
		}else{
			window.location = "reporte_inventario.php?idcategorias="+$("#idcategorias option:selected").val();
		}
	});

	function Carga_Entradas(){

		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Reporte",
				idcategorias : $("#idcategorias option:selected").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_reporte").html(msg);
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
