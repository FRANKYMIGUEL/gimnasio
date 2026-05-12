<?
if($_POST['funcion']=='Carga_Compras'){
	include("inc/conectar.php");
	$tipo = '';
	$estado = '';
	$proveedor = '';
	if($_POST['estado']=='pagadas'){
		$estado = ' AND fechapago IS NOT NULL';
	}elseif($_POST['estado']=='credito'){
		$estado = ' AND fechapago IS NULL';
	}
	if($_POST['proveedor']!='0'){
		$proveedor = ' AND idproveedores='.$_POST['proveedor'];
	}

	if($_POST['tipo']=='Credito'){
		$tipo = " AND tipo LIKE 'Credito'";
	}
	$cancelado = '';
	
	$resultados=$consulta->query("SELECT * FROM compras WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' $estado $proveedor $tipo");
	foreach ($resultados as $row) {
		if($row['tipo']=='Cancelada')$cancelado = 'bg-danger';
	?>
	<tr class="<?=$cancelado?>">
		<td><?=str_pad($row["folio"], 6, "0", STR_PAD_LEFT)?></td>
		<td><?=substr($row['fecha'],0,10)?></td>
		<td><?=$row['proveedor']?></td>
		<td align="right">$ <?=number_format($row['importe'],2)?></td>
		<td>
        	<?
        	if( $row['tipo']=='Remision'){
				?>
			<button class='btn-group btn-group-xs btn-success abonos' idventas='<?=$row[0]?>' folio='<?=str_pad($row["folio"], 6, "0", STR_PAD_LEFT)?>' title='Abonos' ><img src="img/pago.png" height="20"></button>
            <?
			}
            ?>
			<a href="ticket_compra.php?idcompras=<?=$row[0]?>" target="_blank"><button class='btn-group btn-group-sm btn-info' title='Reimprimir' ><img src="img/print.png" height="20"></button></a>
            <?
			if($row['pagado']!='' and $row['tipo']=='Remision'){
				?>
			<button class='btn-group btn-group-xs btn-success' title='Compra Pagada' ><img src="img/pagada.png" height="20"></button>
			<?
            }
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
	$Auto = $consulta->query("UPDATE compras SET tipo='Cancelada' WHERE idcompras=".$_POST['idregistro']." ");
	foreach ($Auto as $Autocontador);
	$Auto = $consulta->query("DELETE FROM cxp WHERE idcompras=".$_POST['idregistro']." ");
	foreach ($Auto as $Autocontador);
	exit();
}
function Auto_Tabla(){
	include("inc/conectar.php");
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'cxp'");
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
	<title>Consulta de Compras</title>
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
	if(isset($_GET['idproveedor'])){$idcliente = $_GET['idproveedor'];}else{$idcliente = 0;}
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
                	<h4>Consulta de Compras</h4>
                </div>
            </div>
            <div class="row">
				<div class="col-md-1 text-center ">
                  
                </div>
                <div class="col-md-2 text-center ">
                    <b>Fecha Inicial</b>
                    <input type="date" class="form-control " id="fechainicial" value="<?=$fechainicial?>">
                </div>
                <div class="col-md-2 text-center ">
                    <b>Fecha Final</b>
                    <input type="date" class="form-control " id="fechafinal" value="<?=$fechafinal?>">
                </div>
                <div class="col-md-2 text-center ">
                    <b>Estado</b><br>
                    <select class="form-control " id="estado">
                        <option se value="0" <? if($estado=="todos")echo "selected";?> >Todos</option>
                        <option value="pagadas" <? if($estado=='pagadas')echo "selected";?> >Pagadas</option>
                        <option value="credito" <? if($estado=='credito')echo "selected";?> >Credito</option>
                    </select>
                </div>
              
                <div class="col-md-4 text-center ">
                    <b>Proveedor</b><br>
                    <select class="form-control  " id="idproveedores">
                        <option value="0" <? if($idcliente==0)echo "selected";?>>Todos</option>
                        <?
                        $Auto = $consulta->query("SELECT * FROM proveedores WHERE inactivo IS NULL");
                        foreach ($Auto as $row){
                        ?>
                        <option value="<?=$row['idproveedores']?>" <? if($idproveedores==$row[0])echo "selected";?>><?=$row['codigo']." - ".$row['nombre']?></option>
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
                                <th>Proveedor</th>
                                <th>Importe</th>
                                <th width="150" align="center"></th>
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

	$(document).on("change","#estado, #fechainicial, #fechafinal, #idcliente",function(){
		var ruta = '<?=$_SERVER["REQUEST_URI"];?>';
		ruta = ruta.split("?");
		if(ruta[1]== undefined){
			window.location = "ver_compras.php?estado="+$("#estado").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idproveedores="+$("#idproveedores").val();
		}else{
			window.location = "ver_compras.php?estado="+$("#estado").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idproveedores="+$("#idproveedores").val();
		}
	});
	$(document).on("click",".abonar",function(){
		var folio = $(this).attr("folio");
		var idcompras = $(this).attr("idcompras");
		Cargar_Pagos(idcompras);
	});
	function Cargar_Pagos(idcompras){
		$.ajax({
			type: "POST",
			url: "cuentasxpagar.php",
			data: ({
				funcion : "Carga_AbonosC",
				idcompras : idcompras
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#modal_abonos").html(msg);
			},error: function(xhr, status, error) {
				// Función a ejecutar si hay un error en la solicitud
				console.error('Error en la solicitud:', status, error);
			}
		});
	}


	function Carga_Entradas(){

		$.ajax({
			type: "POST",
			url: "ver_compras.php",
			data: ({
				funcion : "Carga_Compras",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val(),
				estado : $("#estado option:selected").val(),
				proveedor : $("#idproveedores option:selected").val()
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
			},error: function(xhr, status, error) {
				// Función a ejecutar si hay un error en la solicitud
				console.error('Error en la solicitud cargar:', status, error);
			}
		});
	}
	//ABONOS

	$(document).on("click","#guardar_abono",function(){
		if(Quita_Moneda($("#importe_abono").val())>Quita_Moneda($("#saldo_abonos").val())){
			$("#importe_abono").val($("#saldo_abonos").val());
			alertify.error("El Importe no puede se mayor al saldo");
			$("#importe_abono").focus();
			return false;
		}
		if($("#importe_abono").val()<=0){
			alertify.error("Ingresa un Importe Mayor que 0");
			$("#importe_abono").focus();
			return false;
		}
		$.ajax({
			type: "POST",
			url: "ver_salidas.php",
			data: ({
				funcion : "Agrega_Abonos",
				importe : Quita_Moneda($("#importe_abono").val()),
				idventas : $("#guardar_abono").attr("idventas"),
				observaciones : $("#observacion").val(),
				idrecibe_pago : $("#recibe_pago option:selected").val(),
				recibe_pago : $("#recibe_pago option:selected").text(),
				saldo : $("#saldo_abonos").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				var bob=window.open('','_new');bob.location="ticket_pago.php?identradas="+msg;
				window.location = "ver_ventas.php?estado="+$("#estado").val()+"&tipo="+$("#tipo").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();
			},error: function(xhr, status, error) {
				// Función a ejecutar si hay un error en la solicitud
				console.error('Error en la solicitud:', status, error);
			}
		});
	});
	$(document).on("click",".cancelar",function(){
		var idregistro = $(this).attr("registros");
		alertify.confirm("Eliminacion",'¿Estas Seguro de Cancelar la Compra ?', function(){
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
					alertify.success("Compra Cancelada Exitosamente "+msg);
					window.location = "ver_compras.php?estado="+$("#estado").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idproveedores="+$("#idproveedores").val();
				},error: function(xhr, status, error) {
				// Función a ejecutar si hay un error en la solicitud
				console.error('Error en la solicitud:', status, error);
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
