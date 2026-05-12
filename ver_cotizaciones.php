<?
if($_POST['funcion']=='Carga_Ventas'){
	include("inc/conectar.php");
	$tipo = '';
	$estado = '';
	$proveedor = '';
	if($_POST['cliente']!='0'){
		$proveedor = ' AND idclientes='.$_POST['cliente'];
	}
	
	$cancelado = '';
	$resultados=$consulta->query("SELECT * FROM ventas WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' AND tipo LIKE 'Cotizacion' $estado $proveedor ");
	foreach ($resultados as $row) {
		if($row['tipo']=='Cancelada')$cancelado = 'bg-danger';
	?>
	<tr class="<?=$cancelado?>">
		<td><?=str_pad($row["folio"], 6, "0", STR_PAD_LEFT)?></td>
		<td><?=substr($row['fecha'],0,10)?></td>
		<td><?=$row['clientes']?></td>
		<td align="right">$ <?=number_format($row['importe'],2)?></td>
		<td>
        	<?
        	if( $row['tipo']=='Remision'){
				?>
			<button class='btn-group btn-group-xs btn-success abonos' idventas='<?=$row[0]?>' folio='<?=str_pad($row["folio"], 6, "0", STR_PAD_LEFT)?>' title='Abonos' ><img src="img/pago.png" height="20"></button>
            <?
			}
            ?>
			<a href="ticket_cotizacion.php?idventas=<?=$row[0]?>" target="_blank"><button class='btn-group btn-group-sm btn-info' title='Reimprimir' ><img src="img/print.png" height="20"></button></a>
			<a href="ticket_cotizacion_vista.php?idventas=<?=$row[0]?>" target="_blank"><button class='btn-group btn-group-sm btn-success' title='Ver Cotizacion' ><img src="img/editar.png" height="20"></button></a>
			<a href="pventas.php?idventas=<?=$row[0]?>" target="_blank"><button class='btn-group btn-group-sm btn-success' title='Convertir a Pedido' ><img src="img/buy.png" height="20"></button></a>
            <?
			
			if($row['tipo']!='Cancelada'){
				//if($_SESSION["SISTEMA"]["tipo"]=='admin'){
				?>
			<button class='btn-group btn-group-xs btn-warning cancelar' registros="<?=$row[0]?>" title='Cancelar Venta' >
            <img src="img/cancel.png" height="20"></button>
			<?
           		// }
			}
			?>
		</td>
	</tr>
<?
	$cancelado = '';
	}
	exit();
}
if($_POST['funcion']=='Eliminar'){
include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE ventas SET tipo='Cancelada' WHERE idventas=".$_POST['idregistro']." ");
	foreach ($Auto as $Autocontador);
	$Auto = $consulta->query("DELETE FROM cxc WHERE idventas=".$_POST['idregistro']." ");
	foreach ($Auto as $Autocontador);
	exit();
}
function Auto_Tabla(){
	include("inc/conectar.php");
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'cxc'");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	return $Auto;
}

if($_POST["funcion"]=="Agrega_Abonos"){
	include("inc/conectar.php");;
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'cxc'");
	foreach ($Auto as $Autocontador);
	$idcuentas = ($Autocontador["Auto_increment"]);
	$idusuario = $_SESSION["usuario"]["idusuarios"];
	if(isset($_SESSION["SISTEMA"]["idusuarios"]))$idusuarios = 1;
	$resultados=$consulta->query("INSERT INTO cxc SET fecha='".date("Y-m-d H:i:s")."', idventas=".$_POST['idventas'].", idusuarios=".$idusuario.", usuarios='".$_SESSION["SISTEMA"]["usuario"]."', importe=".$_POST['importe'].", observaciones='".$_POST['observaciones']."'");
	foreach ($resultados as $row);
	$resultados=$consulta->query("UPDATE ventas SET saldo=saldo-".$_POST['importe'].", abonos=abonos+".$_POST['importe']." WHERE idventas=".$_POST['idventas']."");
	$query = $consulta->query("SELECT idclientes, saldo FROM ventas WHERE idventas=".$_POST['idventas']."");
	foreach ($query as $client);
	$query = $consulta->query("UPDATE clientes SET saldo=saldo-(".$_POST['importe'].") WHERE idclientes=".$client['idclientes']);
	foreach ($query as $row);
	foreach ($resultados as $row);
	if($_POST['importe']==$client['saldo']){
		$resultados=$consulta->query("UPDATE ventas SET pagado='".date("Y-m-d H:i:s")."' WHERE idventas=".$_POST['idventas']."");
		foreach ($resultados as $row);
	}
		echo $idcuentas;
exit();
}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Consulta de Cotizaciones</title>
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
	 ?>
		<?
        include("menu1.php");
        ?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h4>Consulta de Cotizaciones</h4>
                </div>
            </div>
            <div class="row">
				<div class="col-md-2 text-center ">
                    
                </div>
                <div class="col-md-2 text-center ">
                    <b>Inicial</b>
                    <input type="date" class="form-control " id="fechainicial" value="<?=$fechainicial?>">
                </div>
                <div class="col-md-2 text-center ">
                    <b>Final</b>
                    <input type="date" class="form-control " id="fechafinal" value="<?=$fechafinal?>">
                </div>
               
                
                <div class="col-md-4 text-center ">
                    <b>Cliente</b><br>
                    <select class="form-control  " id="idcliente">
                        <option value="0" <? if($idcliente==0)echo "selected";?>>Todos</option>
                        <?
                        $Auto = $consulta->query("SELECT * FROM clientes WHERE inactivo IS NULL");
                        foreach ($Auto as $row){
                        ?>
                        <option value="<?=$row['idclientes']?>" <? if($idcliente==$row[0])echo "selected";?>><?=$row['codigo']." - ".$row['nombre']?></option>
                        <?
                        }
                        ?>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 ">
                    <table id="example" class="table table-sm  " cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Importe</th>
                                <th width="200" align="center"></th>
                            </tr>
                        </thead>
                        <tbody id="resultados_productos">
                            <tr>
                                <td colspan="5">Sin Resultados</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cxc" role="dialog">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Abonos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_abonos">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(e) {

	Carga_Entradas();

	$(document).on("change","#estado, #fechainicial, #fechafinal, #idcliente, #tipo",function(){
		var ruta = '<?=$_SERVER["REQUEST_URI"];?>';
		ruta = ruta.split("?");
		if(ruta[1]== undefined){
			window.location = "ver_cotizaciones.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();
		}else{
			window.location = "ver_cotizaciones.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();
		}
	});
	


	function Carga_Entradas(){

		$.ajax({
			type: "POST",
			url: "ver_cotizaciones.php",
			data: ({
				funcion : "Carga_Ventas",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val(),
				cliente : $("#idcliente option:selected").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_productos").html(msg);
				$('#example').DataTable( {
					order: [
						[0, 'desc']
					],
					language: {
						processing:     "Procesando...",
						search:         "Buscar:",
						lengthMenu:    "Mostrar _MENU_ ",
						info:           "Mostrando _START_ de _END_ de Total de  _TOTAL_ resultados",
						infoEmpty:      "Sin Registros 0 de 0 de 0 Mostrando",
						infoFiltered:   "(Filtrando de _MAX_ Filtrados)",
						infoPostFix:    "",
						loadingRecords: "Chargement en cours...",
						zeroRecords:    "Sin Resultados",
						emptyTable:     "Sin Resultados en la Tabla",
						paginate: {
							first:      "Primero",
							previous:   "Anterior",
							next:       "Siguiente",
							last:       "Ultimo"
						},
						aria: {
							sortAscending:  ": Ordenar Ascendente",
							sortDescending: ": Ordenar Desendente"
						}
					}
				} );
			}
		});
	}
	//ABONOS

	
	$(document).on("click",".cancelar",function(){
		var idregistro = $(this).attr("registros");
		alertify.confirm("Eliminacion",'¿Estas Seguro de Cancelar la Cotizacion ?', function(){
			$.ajax({
				type: "POST",
				url: "<?=$_SERVER["PHP_SELF"]?>",
				data: ({
					funcion : "Eliminar",
					idregistro : idregistro
				}),
				dataType: "html",
				async:false,
				success: function(msg){
					alertify.success("Cotizacion Cancelada Exitosamente "+msg);
					window.location = "ver_cotizaciones.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();

				}
			});
		}, function(){
		alertify.error('Cancelado')});
	});

	function Quita_Moneda(n){
		n=String(n);
		var s=parseFloat(n.replace(",","").replace("$",""));
		if(isNaN(s))s=0;
		return s;
	}
});
</script>
</body>
