<?
if($_POST["funcion"]=="Quitar_Abonos"){
include("inc/conectar.php");
	$Auto = $consulta->query("DELETE FROM cxc WHERE idcxc=".$_POST['idabonos']);
	foreach ($Auto as $Autocontador);
	
	$resultados=$consulta->query("UPDATE ventas SET saldo=saldo+".$_POST['importe'].", abonos=abonos-".$_POST['importe']." WHERE idventas=".$_POST['idventas']."");
	foreach ($resultados as $saldoventas);
	
	exit();
}

if($_POST["funcion"]=="Agrega_Abonos"){
include("inc/conectar.php");
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'cxc'");
	foreach ($Auto as $Autocontador);
	$IMPORTE_PAGO = $_POST['importe']+$_POST['tarjeta'];
	$idcuentas = ($Autocontador["Auto_increment"]);
	$idusuario = $_SESSION["SISTEMA"]["idusuarios"];
	if(isset($_SESSION["SISTEMA"]["idusuarios"]))$idusuario = 1;
	$resultados=$consulta->query("INSERT INTO cxc SET fecha='".date("Y-m-d H:i:s")."', idventas=".$_POST['idventas'].", idusuarios=".$idusuario.", usuarios='".$_SESSION["SISTEMA"]["usuario"]."', importe=".$_POST['importe'].", tarjeta=".$_POST['tarjeta'].", comision=ROUND(".($_POST['tarjeta']*.03)."), observaciones='".$_POST['observaciones']."'");
	foreach ($resultados as $row);
	$resultados=$consulta->query("UPDATE ventas SET saldo=saldo-".$IMPORTE_PAGO.", abonos=abonos+".$IMPORTE_PAGO." WHERE idventas=".$_POST['idventas']."");
	foreach ($resultados as $saldoventas);
	
	$query = $consulta->query("SELECT idclientes, saldo FROM ventas WHERE idventas=".$_POST['idventas']."");
	foreach ($query as $client);
	
	$query = $consulta->query("UPDATE clientes SET saldo=saldo-(".$IMPORTE_PAGO.") WHERE idclientes=".$client['idclientes']);
	foreach ($query as $row);
	if($IMPORTE_PAGO==$_POST['saldo']){
		$resultados=$consulta->query("UPDATE ventas SET fechapago='".date("Y-m-d H:i:s")."' WHERE idventas=".$_POST['idventas']."");
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
            <input type="text" class="hidden invisible" value="<?=number_format($row['importe']-$row['abonos'],0)?>" id="saldo_abonos" />
         </div>
		 <div class="col-lg-3">
		 	<textarea class="form-control" id="observacion" placeholder="Ingresa Observacion" style="resize:none;"></textarea>
		</div>
    <?
	$porcentaje = 0;
	$saldo = $row['saldo'];
	if($row['abonos']>0)$porcentaje = (100/$row['importe'])*$row['abonos'];
	?>
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
                                <td>
									<?
									if( $_SESSION['SISTEMA']['idusuarios']==1){
									?>
									<button class="btn btn-warning btn-sm quitaabono" idabonos="<?=$row['idcxc']?>" idventas="<?=$row['idventas']?>" importe="<?=$row['importe']?>"><img src="img/cancel.png" height="20" title="Eliminar Abono"></button>
									<?
									}
									?>
									<?=$row['fecha']?></td>
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
                   <table class="table table-bordered table-sm">
						<thead>
							<th>Cantidad</th>
							<th>Descripcion</th>
							<th>Precio</th>
						</thead>
					   <?
						$resultados=$consulta->query("SELECT * FROM ventas_detalle WHERE idventas=".$_POST['idventas']."");
						foreach ($resultados as $rows){
						?>
						<tr>
							<td><?=$rows['cantidad']?></td>
							<td><?=utf8_decode($rows['productos'])?></td>
							<td><?=$rows['precio']?></td>
						</tr>
					   <?
						}
						?>
					</table>
                </div>
            </div>
        </div>
        <div class="col-lg-3 text-center">
           <div class="row">
           		<div class="col-lg-12 text-center">
              	 <b>Recibo en Efectivo </b><input type="text" value="0" id="recibo_abono" saldo="<?=$saldo?>" class="form-control text-right" autocomplete="off" />
               </div>
           </div>
           <div class="row">
           		<div class="col-lg-12 text-center">
              	 <b>Pago en Efectivo </b><input type="text" value="0" id="importe_abono" class="form-control text-right" autocomplete="off" />
               </div>
           </div>
           <div class="row" id="cambioinput">
           	<div class="col-lg-12 text-center">
               <b>Cambio Efectivo </b><input type="text" value="0" id="cambio" class="form-control text-right" autocomplete="off" readonly />
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

if ($_POST["funcion"]=="Credito_Cliente") {
include("inc/conectar.php");
	$cadena = '';
	$codigo = explode("-",$_POST['cliente']);
	$Auto = $consulta->query("SELECT * FROM clientes WHERE codigo LIKE '".$codigo[0]."'");
	foreach ($Auto as $clientes);
	if($clientes[0]!=''){
		$cadena =$clientes['limitecredito'].'|'.$clientes['tiempocredito'].'|'.$clientes['saldo'];
	}else{
		$cadena ="No Credito";
	}
	echo $cadena;
exit();
}
if ($_POST["funcion"]=="ventas") {
include("inc/conectar.php");
	$cadena = '';
	$codigo = explode("-",$_POST['clientes']);
	$Auto = $consulta->query("SELECT * FROM clientes WHERE codigo LIKE '".$codigo[0]."'");
	foreach ($Auto as $clientes);
	?>
    <table class="table">
    <?
	$Auto = $consulta->query("SELECT * FROM ventas WHERE tipo NOT LIKE 'Cotizacion' AND idclientes=".$clientes['idclientes']." AND fechapago IS NULL");
	foreach ($Auto as $fila){
		?>
			<tr>
            	<td><?=$fila['folio']?></td>
            	<td><?=$fila['fecha']?></td>
            	<td align="right">$ <?=number_format($fila['importe'],2)?></td>
            	<td align="right">$ <?=number_format($fila['abonos'],2)?></td>
            	<td align="right">$ <?=number_format($fila['saldo'],2)?></td>
            	<td width="100">
                    <input type="text" class="form-control bfh-number text-right pago_manual " readonly style="width:100px;" minimo="1" value="0" maximo="<?=$fila['saldo']?>">
                </td>
            	<td width="100">
                    <button type="button" class="btn btn-warning form-control btn-block btn-md abonar" idventas="<?=$fila['idventas']?>" data-toggle="modal" folio="<?=$fila['folio']?>" data-target="#cxc"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span>Abonar</button>
                </td>
            </tr>
		<?
	}
	?>
    </table>
    <?
exit();
}



if ($_POST["funcion"]=="DataListClientes") {
	include("inc/conectar.php");

	$Auto = $consulta->query("SELECT codigo, nombre FROM clientes WHERE inactivo IS NULL");
	foreach ($Auto as $fila){
		echo "<option value ='$fila[codigo]-$fila[nombre]'>";
	}
	exit();
}


function Auto_Tabla(){
	include("inc/conectar.php");
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'cuentasxcobrar'");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	return $Auto;
}
 ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Cuentas Por Cobrar</title>
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
.oculta{ display:none;}

</style>
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
        <button type="button" class="btn btn-default" data-dismiss="modal" id="cerrarmodales">Cerrar</button>
      </div>
    </div>
  </div>
</div>
		<?
        include("menu1.php");
        ?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
			<center><h2>Cuentas X Cobrar</h2></center>
			<table class="table table-hover table-condensed table-sm" id="example" width="90%">
				<thead>
					<tr style="background-color:#2e353d; color:#FFF;">
						<td width="100"  class="text-center">Folio</td>
						<td width="100"  class="text-center">Tipo</td>
						<td width="180"  class="text-center">Fecha</td>
						<td class="text-center" align="left">Cliente</td>
						<td width="150"  class="text-center">Importe Venta</td>
						<td width="150"  class="text-center">Saldo</td>
						<td width="150"  class="text-center">Abonos</td>
						<td width="50"  class="text-center">&nbsp;</td>                                        
						<td width="50"  class="text-center">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
				<?
				$CARTERAVENCIDA = 0;
				$Auto = $consulta->query("SELECT * FROM ventas WHERE tipo NOT LIKE 'Cotizacion' AND fechapago IS NULL AND tipo NOT LIKE 'Cancelada'  ORDER BY idclientes");
				foreach ($Auto as $row){
					$CARTERAVENCIDA += $row['importe']-$row['abonos'];
					if($row['abonos']<$row['importe']){
				?>
					<tr>
						<td width="100" class="text-center"><?=$row['folio']?></td>
						<td width="100" class="text-center text-uppercase"><?=$row['tipo']?></td>
						<td width="180" class="text-center"><?=$row['fecha']?></td>
						<td class="text-center"><?=$row['clientes']?></td>
						<td width="150" class=" text-right">$ <?=number_format($row['importe'],2)?></td>
						<td width="150" class="text-right">$ <?=number_format($row['importe']-$row['abonos'],2)?></td>
						<td width="150" class="text-right">$ <?=number_format($row['abonos'],2)?></td>
						<td width="50" class="text-right" title="Abonar" class="abonar">
							<button type="button" class="btn btn-warning form-control btn-block btn-md abonar" idventas="<?=$row['idventas']?>" data-toggle="modal" folio="<?=$row['folio']?>" data-target="#cxc"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span>Abonar</button>
						</td>
						<td width="50" class="text-right" title="Abonar" class="abonar">
							<a href="ticket_venta.php?idventas=<?=$row['idventas']?>" target="_blank"><button type="button" class="btn btn-success">Imprimir</button></a>
						</td>
					</tr>
				<?
					}
				}
				?>
				</tbody>
			</table>
			<center><h2>Cuentas X Cobrar: $ <?=number_format($CARTERAVENCIDA,2)?></h2></center>
    	</div>
	</div>
</div>
<script>
$(document).ready(function(e) {
	$('#example').DataTable( {
		order: [
			[5, 'desc']
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
	$(document).on("blur","#recibo_abono",function(){
		var saldo = Quita_Moneda($(this).attr("saldo"));
		var cantidad = Quita_Moneda($(this).val());
		var cambio =0;
		if(cantidad>saldo) {
			cambio =cantidad-saldo;
			$("#importe_abono").val(saldo);			
			$("#cambio").val(cambio);
		}else{
			$("#importe_abono").val(cantidad);	
			$("#cambio").val('0');
		}
	});
	$(document).on("blur","#importe_abono",function(){
		var saldo = Quita_Moneda($(this).attr("saldo"));
		var pagoefectivo = Quita_Moneda($("#recibo_abono").val());
		var cantidadapagar = Quita_Moneda($(this).val());
		var cambio =0;
		if(pagoefectivo>cantidadapagar) {
			cambio =pagoefectivo-cantidadapagar;
			//$("#importe_abono").val(saldo);			
			$("#cambio").val(cambio);
		}else{
			//$("#importe_abono").val(cantidad);	
			$("#cambio").val('0');
		}
	});

	
	
	$(document).on("blur",".pago_manual",function(){
		var maximo = $(this).attr("maximo");
		var cantidad = $(this).val();
		if(cantidad>maximo) {
			$(this).val(maximo);
		}
	});
	$(document).on("click","#cerrarmodales",function(){
		location.reload();
	});
	$(document).on("click",".quitaabono",function(){
		var idabonos = $(this).attr("idabonos");
		var idventas = $(this).attr("idventas");		
		var importe = $(this).attr("importe");
		alertify.confirm('Pregunta del Sistema','¿Estas seguro de Eliminar el Abono?', function(){
			$.ajax({
				type: "POST",
				url: "cuentasxcobrar.php",
				data: ({
					funcion : "Quitar_Abonos",
					idabonos : idabonos,
					importe: importe,
					idventas : idventas
				}),
				dataType: "html",
				async:false,
				success: function(msg){
					Cargar_Pagos(idventas);
				}
			});
			
		}, function(){
		//alertify.error('Cancelado')
		});
	});
	
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
			url: "cuentasxcobrar.php",
			data: ({
				funcion : "Agrega_Abonos",
				importe : Quita_Moneda($("#importe_abono").val()),
				idventas : $("#guardar_abono").attr("idventas"),
				observaciones : $("#observacion").val(),
				saldo : $("#saldo_abonos").val(),
				tarjeta : $("#tarjeta").val() 
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				Cargar_Pagos($("#guardar_abono").attr("idventas"));
				var bob=window.open('','_new');bob.location="ticket_pago.php?identradas="+msg;
				Credito_Cliente($("#clientes").val());
			}
		});
	});
	function Cargar_Pagos(idventas){
		$.ajax({
			type: "POST",
			url: "cuentasxcobrar.php",
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
	$(document).on("click",".abonar",function(){
		var folio = $(this).attr("folio");
		var idventas = $(this).attr("idventas");
		Cargar_Pagos(idventas);
	});
	$( ".pago_manual" ).on( "keypress", function(event) {
		if(event.which == 13 && $(this).val()!='') {
			var maximo = $(this).attr("maximo");
			var cantidad = $(this).val();
			if(cantidad>maximo) {
				$(this).val(maximo);
			}
		}

	});
	//Carga_Clientes();
	function Cargar_Detalle(){
		if($("#clientes").val()!=''){
			$.ajax({
				type: "POST",
				url: "<?=$_SERVER["PHP_SELF"]?>",
				data: ({
					funcion : "ventas",
					clientes : $("#clientes").val()
				}),
				dataType: "html",
				async:false,
				success: function(msg){
					$("#tabla_detalles").html(msg);
				}
			});
		}else{
			alertify.error("Selecciona un Cliente");
			$("#clientes").focus();
		}
	}
	function Carga_Clientes(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "DataListClientes",
				Buscar : $("#clientes").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#datosClientes").html(msg);
				$("#clientes").val("").focus();
				//Credito_Cliente($("#clientes").val());
			}
		});
	}
	function Carga_Caja(tipo){

		if(Quita_Moneda($("#total").val())==0){
			alertify.alert("Punto de Venta ","Ingresa un Producto");
			$('#myModal_caja').modal('hide');
			 $('#cierra_venta').click();
			$("#Productos").focus();
			return false;
		}
		$("#efectivo").focus().select();
		//$('#myModal_caja').modal('show');
		$("#myModalLabel").text(tipo);
	}

	$(document).on("click","#buscar",function(){
		Credito_Cliente();
	});
	$(document).on("click","#caja",function(){
		$('#MODAL_CAJA').modal('show');
		Carga_Caja($("#tipo option:selected").val());
	});
	function Carga_Cliente(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Cliente",
				idcliente : $("#clientes").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#datosModalCliente").html(msg);

			}
		});
	}
	$( "#clientes" ).on( "keypress", function(event) {
		if(event.which == 13 && $(this).val()!='') {
			Credito_Cliente();
		}

	});
	function Credito_Cliente(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Credito_Cliente",
				cliente : $("#clientes" ).val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				var credito = msg.split("|");
				$("#limite_credito").val(Formato_Moneda(credito[0],2));
				$("#tiempo_credito").val(credito[1]);
				$("#disponible_credito").val(Formato_Moneda(credito[0]-credito[2],2));
				Cargar_Detalle();
			}
		});
	}
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
</html>
