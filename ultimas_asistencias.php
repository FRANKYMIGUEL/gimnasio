<?
if($_POST['funcion']=='Pagar_Nuevo'){
	include('inc/conectar.php');
	$fecha_pago = $_POST['fecha'];
	$dias = $_POST['dias'];
	//$fechavence = date($fecha_pago, strtotime($fecha . " +$dias days"));
	$fecha_obj = new DateTime($fecha_pago);
	// Sumar días al objeto DateTime
	$fecha_obj->modify('+' . $dias . ' day');
	// Obtener la nueva fecha formateada como YYYY-MM-DD
	$fechavence = $fecha_obj->format('Y-m-d');
		$Auto = $consulta->query("UPDATE clientesmembresias SET activo='noactivo' WHERE idclientes='".$_POST['idclientes']."'");
		foreach ($Auto as $Autocontador);
		$Auto = $consulta->query("INSERT INTO clientesmembresias SET idmembresias='".$_POST['idmembresias']."', idclientes='".$_POST['idclientes']."', importe='".$_POST['importe']."', fechavencimiento='".$fechavence."', fechapago='".$fecha_pago." ".date("H:i:s")."', fecha='".date("Y-m-d H:i:s")."'");
		foreach ($Auto as $Autocontador);
	exit();
	}
if($_POST['funcion']=='Carga_Modal_Pagos'){
    include('inc/conectar.php');
    $Auto = $consulta->query("SELECT clientes.*, membresias.nombre AS membresia, membresias.duracion AS duracion, membresias.precio AS precio  FROM clientes LEFT JOIN membresias ON membresias.idmembresia=clientes.idmembresia WHERE idclientes=".$_POST['idregistro']."");
    foreach ($Auto as $row);
    ?>
    <div class="modal-body" >
        <div class="row">
            <div class="col-7 font-weight-bold">
                <label>Nombre</label>
                <input type="text" class="form-control" id="nombre" value="<?=$row['nombre']?>" placeholder="Nombre" readonly>
            </div>
            <div class="col-2 font-weight-bold">
                <label>Precio</label>
                <input type="text" class="form-control text-right" id="precio" value="$ <?=number_format($row['precio'],2)?>" placeholder="Precio" readonly>
            </div>
            <div class="col-3 font-weight-bold">
                <label>Dias Membresia</label>
                <input type="text" class="form-control text-center" id="duracion" readonly value="<?=$row['duracion']?>" placeholder="Dias de Duracion">
            </div>		
        </div>
        <hr>
        <div class="row">
            <div class="col-12 font-weight-bold">
                <table class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Fecha Pago</th>
                        <th scope="col">Feha Vencimiento</th>
                        <th scope="col">Importe Pago</th>
                        <th scope="col">Opcion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Auto = $consulta->query("SELECT * FROM clientesmembresias WHERE idclientes=".$_POST['idregistro']." ORDER BY fechavencimiento DESC");
                        foreach ($Auto as $row){
                            ?>
                            <tr>
                                <th scope="row" align="center"><?=substr($row['fechapago'],10,10)." ".substr($row['fechapago'],8,2)."-".substr($row['fechapago'],5,2)."-".substr($row['fechapago'],0,4)?></th>
                                <td><?=substr($row['fechavencimiento'],8,2)."-".substr($row['fechavencimiento'],5,2)."-".substr($row['fechavencimiento'],0,4)?></td>
                                <td>$ <?=number_format($row['importe'],2)?></td>
                                <td></td>
                            </tr>
                            <?
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cerrar</button>
    
    </div>
    </div>
    <?
exit();
}
if($_POST['funcion']=='Carga_Modal'){
    include('inc/conectar.php');
        if($_POST['tipo']=='Pagar'){

            $Auto = $consulta->query("SELECT clientes.*, membresias.nombre AS membresia, membresias.duracion AS duracion, membresias.precio AS precio  FROM clientes LEFT JOIN membresias ON membresias.idmembresia=clientes.idmembresia WHERE idclientes=".$_POST['idregistro']."");
            foreach ($Auto as $row);
            ?>
            <div class="modal-body" >
                <div class="row">
                    <div class="col-6 font-weight-bold">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="nombre" value="<?=$row['nombre']?>" placeholder="Nombre" readonly>
                    </div>
                    <div class="col-3 font-weight-bold">
                        <label>Precio</label>
                        <input type="text" class="form-control text-right" id="precio" value="$ <?=number_format($row['precio'],2)?>" placeholder="Precio" readonly>
                    </div>
                    <div class="col-3 font-weight-bold">
                        <label>Dias Membresia</label>
                        <input type="text" class="form-control text-center" id="duracion" readonly value="<?=$row['duracion']?>" placeholder="Dias de Duracion">
                    </div>
					<div class="col-3 font-weight-bold">
						<label>Fecha de Pago</label>
						<input type="date" class="form-control text-center" id="fecha" value="<?=date("Y-m-d")?>" placeholder="Fecha de Pago">
					</div>				
                </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
                <button type="button" class="btn btn-primary" id="pagar" idclientes="<?=$row['idclientes']?>" idmembresias="<?=$row['idmembresia']?>"><i class="bi bi-cash-coin"></i> Pagar $ <?=number_format($row['precio'],2)?> De Membresia</button>
            
            </div>
            </div>
            <?
        }
      
    exit();
    }
if($_POST['funcion']=='Carga_Ventas'){
	include("inc/conectar.php");
	$SQL = "SELECT asistencias.fecha, clientes.*, clases.nombre AS clase, C3.fechapago, C3.fechavencimiento, C3.activo FROM asistencias LEFT JOIN clientes ON clientes.idclientes=asistencias.idclientes 
LEFT JOIN (SELECT idmembresias, fechapago, idclientes,fechavencimiento, activo FROM clientesmembresias C1 WHERE C1.fechapago = (
                SELECT MAX(fechapago) 
                FROM clientesmembresias C2 
                WHERE C2.idclientes = C1.idclientes
            )) C3 ON clientes.idclientes=C3.idclientes
LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE asistencias.fecha BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:59:59' AND clientes.fechabaja IS NULL GROUP BY clientes.idclientes ORDER BY idasistencias DESC";
    //$SQL = "SELECT asistencias.fecha, clientes.*, clases.nombre AS clase, clientesmembresias.fechapago, clientesmembresias.fechavencimiento, clientesmembresias.activo FROM asistencias LEFT JOIN clientes ON clientes.idclientes=asistencias.idclientes LEFT JOIN clientesmembresias ON clientes.idclientes=clientesmembresias.idclientes LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE asistencias.fecha BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:59:59' AND clientes.fechabaja IS NULL GROUP BY clientes.codigo ORDER BY idasistencias DESC limit 30";
    //echo  $SQL;
	//exit();
	$Auto = $consulta->query($SQL);
    foreach ($Auto as $row){
		$diasvence = '';
		$BOTONPAGAR ='';
		if($row['activo']=='noactivo' or $row['activo']==''){
			//NO TIENE NINGUNA MEMBRESIA ACTIVA 
			$diasvence = '<b class="text-danger">Sin Membresia Activa</b>';
			$BOTONPAGAR ='<button class="btn btn-primary btn-sm pagar" idclientes="'.$row['idclientes'].'"><i class="bi bi-cash-coin"></i> Realizar Pago</button>';
		}else{
			$diasvence = '<b class="text-primary">Membresia Activa</b>';
			$BOTONPAGAR ='<button class="btn btn-success btn-sm ver_pagos" idclientes="'.$row['idclientes'].'"><i class="bi bi-cash-coin"></i> Ver Pagos</button>';
		}
        ?>
		<tr>
			<th scope="row"><?=substr($row['fecha'],10,10)." ".substr($row['fecha'],8,2).substr($row['fecha'],4,4).substr($row['fecha'],0,4)?></th>
			<td><?=$row['codigo']?></td>
			<td><?=$row['nombre']?></td>
			<td><?=$row['clase']?></td>
			<td align="center"><?=$row['dia']?></td>
			<td><?=$BOTONPAGAR?></td>
			<td><?=$diasvence?></td>
		</tr>
		<?
	}
	exit();
}
if($_POST['funcion']=='Eliminar'){
include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE asistencias SET fechabaja ='".date("Y-m-d H:i:s")."' WHERE idasistencias=".$_POST['idregistro']." ");
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
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title> Consulta de Asistencias</title>
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
	 ?>
		<?
        include("menu.php");
        ?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
			<br>
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h4><i class="bi bi-emoji-sunglasses"></i> Ultimas de Asistencias</h4>
                </div>
            </div>
            <div class="row">
				<div class="col-md-1 text-center ">
				</div>
                <div class="col-lg-10 ">
                    <table id="example" class="table table-sm  " cellspacing="0" width="100%">
                        <thead>
                            <tr>
								<th scope="col">Fecha</th>
								<th scope="col">Codigo</th>
								<th scope="col">Nombre</th>
								<th scope="col">Clase</th>
								<th scope="col">Dia de Pago</th>
								<th scope="col">Estatus</th>
								<th scope="col"></th>
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
<div class="modal fade bd-example-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-cookie"></i>  Membresia</h5>
        <button type="button" class="close" id="cerrar_m" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div id="resultados_modal">
		</div>
    </div>
  </div>
</div>
<div class="modal fade bd-example-modal" id="modalverpagos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-cookie"></i>  Ver pagos</h5>
        <button type="button" class="close" id="cerrar_mp" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div id="resultados_modal_pagos">
		</div>
    </div>
  </div>
</div>
<button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>
<button type="button" id="carga_modal_pagos" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#modalverpagos" data-whatever="@mdo"></button>
<script>
$(document).ready(function(e) {
	$(document).on("click","#pagar",function(){
		var idregistro = $(this).attr("idclientes");
		var idmembresias = $(this).attr("idmembresias");
		alertify.confirm("Pagar",'¿Estas Seguro de Recibir el pago de la Membresia?', function(){
			$.ajax({
				type: "POST",
				url: "<?=$_SERVER["PHP_SELF"]?>",
				data: ({
					funcion : "Pagar_Nuevo",
					idclientes : idregistro,
                    idmembresias : idmembresias,
                    importe :  Quita_Moneda($("#precio").val()),
                    dias : $("#duracion").val(),
					fecha : $("#fecha").val()
				}),
				dataType: "html",
				async:false,
				success: function(msg){
					console.log(msg);
                    alertify.success("Membresia Pagada Correctamente ");
                    $("#cerrar_m").click();
                    setTimeout(function() {Carga_Entradas();}, 1000);

				},error : function(jqXHR, status, error) {
                    alert('Disculpe, existió un problema'+error+status);
                }
                });
            }, function(){
            alertify.error('Cancelado')
        });
	});
	$(document).on('click','.pagar', function () {
		var idclientes = $(this).attr("idclientes");
	    $('#carga_modal').click();
		Cargar_Modal("Pagar",idclientes);
	});
	$(document).on('click','.ver_pagos', function () {
		var idclientes = $(this).attr("idclientes");
	    $('#carga_modal_pagos').click();
		Cargar_Modal_Pagos(idclientes);
	});
	function Cargar_Modal_Pagos(idregistro){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Modal_Pagos",
				idregistro : idregistro
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_modal_pagos").html(msg);
			}
		});
	}
	
	function Cargar_Modal(tipo,idregistro){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Modal",
				tipo : tipo,
				idregistro : idregistro
			}),
			dataType: "html",
			async:false,
			success: function(msg){
                
				$("#resultados_modal").html(msg);
			}
		});
	}

	Carga_Entradas();
	function Carga_Entradas(){

		$.ajax({
			type: "POST",
			url: "ultimas_asistencias.php",
			data: ({
				funcion : "Carga_Ventas",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val(),
				cliente : $("#idcliente").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_productos").html(msg);
			}
		});
	}
	
	$(document).on("click",".cancelar",function(){
		var idregistro = $(this).attr("registros");
		alertify.confirm("Eliminacion",'¿Estas Seguro de Cancelar la Asistencia ?', function(){
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
					alertify.success("Asistencia Cancelada Exitosamente "+msg);
					window.location = "ver_asistencias.php?idcliente="+$("#idcliente").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();

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
