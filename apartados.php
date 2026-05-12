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
	$tipo = " AND tipo LIKE 'Apartado'";
	$cancelado = '';
	$resultados=$consulta->query("SELECT * FROM ventas WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' $estado $proveedor $tipo");
	foreach ($resultados as $row) {
		if($row['tipo']=='Cancelada')$cancelado = 'bg-danger';
	?>
	<tr class="<?=$cancelado?>">
		<td><?=str_pad($row["folio"], 6, "0", STR_PAD_LEFT)?></td>
		<td><?=substr($row['fecha'],0,10)?></td>
		<td><?=$row['clientes']?></td>
		<td align="right">$ <?=number_format($row['abonos'],2)?></td>
		<td width="50">
			 <button type="button" class="btn btn-success form-control btn-block btn-sm abonar" idventas="<?=$row['idventas']?>" data-toggle="modal" folio="<?=$row['folio']?>" data-target="#cxc"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span>Abonos</button>
		</td>
		<td>
			<a href="ticket_venta.php?idventas=<?=$row[0]?>" target="_blank"><button class='btn-group btn-group-sm btn-info' title='Reimprimir Ticket' ><img src="img/print.png" height="20"></button></a>
            <?
			if($row['fechapago']!=''){
				?>
			<button class='btn-group btn-group-xs btn-success' title='Venta Pagada' ><img src="img/pagada.png" height="20"></button>
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
	$Auto = $consulta->query("UPDATE ventas SET tipo='Cancelada' WHERE idventas=".$_POST['idregistro']." ");
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
include("inc/conectar.php");
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'cxc'");
	foreach ($Auto as $Autocontador);
	$idcuentas = ($Autocontador["Auto_increment"]);
	$idusuario = $_SESSION["usuario"]["idusuarios"];
	if(isset($_SESSION["SISTEMA"]["idusuarios"]))$idusuario = 1;
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

if($_POST['funcion']=='Carga_AbonosC'){
include("inc/conectar.php");
		$resultados=$consulta->query("SELECT * FROM ventas WHERE idventas=".$_POST['idventas']."");
		foreach ($resultados as $row);
		$IDCLIE = $row['idcliente'];
	?>
    <div class="row">
         <div class="col-lg-3">
            <button class="btn btn-primary col-lg-12" type="button">
              Importe <span class="badge">$ <?=number_format($row['importe'],2)?></span>
            </button>
         </div>
         <div class="col-lg-3">
            <button class="btn btn-danger col-lg-12" type="button">
              Abonos <span class="badge">$ <?=number_format($row['abonos'],2)?></span>
            </button>
         </div>
         <div class="col-lg-3">
            <button class="btn btn-info col-lg-12" type="button">
              Saldo <span class="badge">$ <?=number_format($row['importe']-$row['abonos'],2)?></span>
            </button>
            <input type="text" class="hidden invisible" value="<?=number_format($row['saldo'],0)?>" id="saldo_abonos" />
         </div>
    <?
	$porcentaje = 0;
	$saldo = $row['saldo'];
	if($row['abonos']>0)$porcentaje = (100/$row['importe'])*$row['abonos'];
	?>
         <div class="col-lg-3">
            <div class="progress">
              <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?=number_format($porcentaje,0)?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=number_format($porcentaje,0)?>%">
                <?=number_format($porcentaje,0)?>% Pagado
              </div>
            </div>
         </div>
    </div>

    <div class="row">
        <div class="col-lg-9 text-center">
            <div class="row">
           		<div class="col-lg-12 text-center">
                    <table class="table table-sm " cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Observaciones</th>
                                <th>Abono</th>
                                <th>Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?
                        $reg = 0;
                        $resultados=$consulta->query("SELECT * FROM cxc WHERE idventas=".$_POST['idventas']." ORDER BY fecha DESC");
                        foreach ($resultados as $row) {
        
                        ?>
                            <tr>
                                <td><?=$row['fecha']?></td>
                                <td><?=$row['observaciones']?></td>
                                <td align="right">$ <?=number_format($row['importe']+$row['tarjeta'],2)?></td>
                                <td><a href="ticket_pago.php?identradas=<?=$row[0]?>" target="_blank"><button class='btn-group btn-group-sm btn-info' title='Reimprimir' ><img src="img/print.png" height="20"></button></a></td>
                            </tr>
                        <?
                        $reg++;
                        }
                        if($reg==0){
                        ?>
                            <tr>
                                <td colspan="3">Sin Abonos</td>
                            </tr>
                        <?
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
           		<div class="col-lg-12 text-center">
                    <b>Observaciones</b><textarea class="form-control" id="observacion" placeholder="Ingresa Observacion" style="resize:none;"></textarea>
                </div>
            </div>
        </div>
        <div class="col-lg-3 text-center">
           <div class="row">
           		<div class="col-lg-12 text-center">
              	 <b>Pago en Efectivo </b><input type="text" value="0" id="importe_abono" class="form-control text-right" autocomplete="off" />
               </div>
           </div>
           <div class="row">
           	<div class="col-lg-12 text-center">
               <b>Pago con Tarjeta </b><input type="text" value="0" id="tarjeta" class="form-control text-right" autocomplete="off" />
               </div>
           </div>
           <div class="row">
               <div class="col-lg-12 text-center">
               <b>Comision </b><input type="text" readonly value="0" id="comision" class="form-control text-right" autocomplete="off" />
               </div>
           </div>
           <div class="row">
               <div class="col-lg-12 text-center">
               <b>Total Tarjeta</b><input type="text" readonly value="0" id="importe_tarjeta" class="form-control text-right" autocomplete="off" />
               </div>
           </div>
           <br>
           <div class="row text-center">
			<?
            $desabilita = '';
            if($saldo==0)$desabilita = 'disabled="disabled"';
            ?>
                <button type="button" <?=$desabilita?>  class="btn btn-success" id="guardar_abono" idventas="<?=$_POST['idventas']?>">
                  <span class="glyphicon glyphicon-saved" aria-hidden="true"></span> Guardar Abono
                </button>
            </div>
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
	<title>Consulta de Apartados</title>
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
	include("menu1.php");
        ?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h4>Consulta de Movimientos</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 text-center ">
                    <b>Inicial</b>
                    <input type="date" class="form-control " id="fechainicial" value="<?=$fechainicial?>">
                </div>
                <div class="col-md-2 text-center ">
                    <b>Final</b>
                    <input type="date" class="form-control " id="fechafinal" value="<?=$fechafinal?>">
                </div>
                <div class="col-md-2 text-center ">
                    <b>Estado</b><br>
                    <select class="form-control " id="estado">
                        <option se value="0" <? if($estado=="todos")echo "selected";?> >Todos</option>
                        <option value="pagadas" <? if($estado=='pagadas')echo "selected";?> >Pagados</option>
                        <option value="credito" <? if($estado=='credito')echo "selected";?> >Apartado</option>
                    </select>
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
                                <th>Pago</th>
                                <th>&nbsp;</th>
                                <th width="150" align="center"></th>
                            </tr>
                        </thead>
                        <tbody id="resultados_productos">
                            <tr>
                                <td colspan="6">Sin Resultados</td>
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
			window.location = "ver_ventas.php?estado="+$("#estado").val()+"&tipo="+$("#tipo").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();
		}else{
			window.location = "ver_ventas.php?estado="+$("#estado").val()+"&tipo="+$("#tipo").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();
		}
	});
	$(document).on("click",".abonar",function(){
		var folio = $(this).attr("folio");
		var idventas = $(this).attr("idventas");
		Cargar_Pagos(idventas);
	});
	function Cargar_Pagos(idventas){
		$.ajax({
			type: "POST",
			url: "apartados.php",
			data: ({
				funcion : "Carga_AbonosC",
				idventas : idventas
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#modal_abonos").html(msg);
			}
		});
	}


	function Carga_Entradas(){

		$.ajax({
			type: "POST",
			url: "apartados.php",
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
				$('#example').DataTable( {
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
	$(document).on("click","#guardar_abono",function(){
		if(Quita_Moneda($("#importe_abono").val())>Quita_Moneda($("#saldo_abonos").val())){
			$("#importe_abono").val($("#saldo_abonos").val());
			alertify.error("El Importe no puede se mayor al saldo");
			$("#importe_abono").focus();
			return false;
		}
		if($("#importe_abono").val()<=0 && $("#tarjeta").val()<=0){
			alertify.error("Ingresa un Importe Mayor que 0");
			$("#importe_abono").focus();
			return false;
		}
		$.ajax({
			type: "POST",
			url: "cuentasxcobrar.php",
			data: ({
				funcion : "Agrega_Abonos",
				importe : Quita_Moneda($("#importe_abono").val()),
				tarjeta : $("#tarjeta").val() ,
				idventas : $("#guardar_abono").attr("idventas"),
				observaciones : $("#observacion").val(),
				saldo : $("#saldo_abonos").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				Cargar_Pagos($("#guardar_abono").attr("idventas"));
				var bob=window.open('','_new');bob.location="ticket_pago.php?identradas="+msg;
			}
		});
	});
		

	$(document).on("click",".cancelar",function(){
		var idregistro = $(this).attr("registros");
		alertify.confirm("Eliminacion",'¿Estas Seguro de Cancelar la Venta ?', function(){
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
					alertify.success("Venta Cancelada Exitosamente "+msg);
					window.location = "ver_ventas.php?estado="+$("#estado").val()+"&tipo="+$("#tipo").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val()+"&idcliente="+$("#idcliente").val();

				}
			});
		}, function(){
		alertify.error('Cancelado')});
	});
	$(document).on("blur","#tarjeta",function(){
		var total = Quita_Moneda($("#tarjeta").val());
		if($("#tarjeta").val()>0){
			var tarjeta = Quita_Moneda($("#tarjeta").val());
			var comision = tarjeta *.02  ;
			$("#comision").val(Formato_Moneda(comision,2));
			$("#importe_tarjeta").val(Formato_Moneda(tarjeta+comision,2));
		}else{
			$("#comision").val(Formato_Moneda(0,2));
			$("#importe_tarjeta").val(Formato_Moneda(0,2));
		}
	});
	function Formato_Moneda(n,c,d,t){
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
