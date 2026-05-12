<?php

use function PHPSTORM_META\elementType;

if ($_POST["funcion"]=="Cargar_Asistencias") {
	include("inc/conectar.php");
    ?>
    <table class="table table-sm">
        <thead class="thead-light">
            <tr>
            <th scope="col" width="190">Fecha</th>
            <th scope="col">Codigo</th>
            <th scope="col">Nombre</th>
            <th scope="col">Clase</th>
            <th scope="col">Dia de Cobro</th>
            <th scope="col">Estatus</th>
            </tr>
        </thead>
        <tbody>
    <?
  //  $SQL = "SELECT asistencias.fecha, clientes.*, clases.nombre AS clase, clientesmembresias.fechapago, clientesmembresias.fechavencimiento, clientesmembresias.activo FROM asistencias LEFT JOIN clientes ON clientes.idclientes=asistencias.idclientes LEFT JOIN clientesmembresias ON clientes.idclientes=clientesmembresias.idclientes LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE asistencias.fecha BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:59:59' AND clientes.fechabaja IS NULL GROUP BY clientes.codigo ORDER BY idasistencias DESC limit 30";
  $SQL = "SELECT asistencias.fecha, clientes.*, clases.nombre AS clase, C3.fechapago, C3.fechavencimiento, C3.activo FROM asistencias LEFT JOIN clientes ON clientes.idclientes=asistencias.idclientes 
LEFT JOIN (SELECT idmembresias, fechapago, idclientes,fechavencimiento, activo FROM clientesmembresias C1 WHERE C1.fechapago = (
                SELECT MAX(fechapago) 
                FROM clientesmembresias C2 
                WHERE C2.idclientes = C1.idclientes
            )) C3 ON clientes.idclientes=C3.idclientes
LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE asistencias.fecha BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:59:59' AND clientes.fechabaja IS NULL GROUP BY clientes.idclientes ORDER BY idasistencias DESC";
   // echo  $SQL;
	$Auto = $consulta->query($SQL);
    foreach ($Auto as $row){
        $estatus ='';
        if($row['fechapago']==''){
            if($row['fechapago']==''){
                $estatus = '<b class="text-danger">Sin Membresia Activa</b> <i class="bi bi-emoji-frown"></i>';
            }else{
                $fecha = substr($row['fechapago'],5,2);
                $dia = substr($row['fechapago'],8,2);
                if($fecha==(date("m")-1)){
                    if($dia>=date("d")){
                        $estatus = '<b class="text-danger">Membresia Activa</b> <i class="bi bi-emoji-frown"></i>';
                    }else{
                        $estatus = '<b class="text-danger">Sin Membresia Activa</b> <i class="bi bi-emoji-frown"></i>';
                    }
                }else{
                    $estatus = '<b class="text-danger">Sin Membresia Activa</b> <i class="bi bi-emoji-frown"></i>';
                }
                
            }
            
        }else{
            $fecha = substr($row['fechapago'],5,2);
            $dia = substr($row['fechapago'],8,2);
            if(date("d")>$dia){
                $estatus = '<b class="text-danger">Sin Membresia Activa</b> <i class="bi bi-emoji-frown"></i>';
            }else{
                $estatus ='Activo <i class="bi bi-star-fill"></i>';
            }

            
        }
        ?>
        <tr title="Ultimo pago: <?=$row['fechapago']?>">
            <th scope="row"><?=substr($row['fecha'],10,10)." ".substr($row['fecha'],8,2).substr($row['fecha'],4,4).substr($row['fecha'],0,4)?></th>
            <td><?=$row['codigo']?></td>
            <td><?=$row['nombre']?></td>
            <td><?=$row['clase']?></td>
            <td><?=$row['dia']?></td>
            <td><?=$estatus?></td>
        </tr>
    <?
    }
?>
 </tbody>
</table>
<?
exit();
}
if ($_POST["funcion"]=="Asistencia") {
	include("inc/conectar.php");
    $codigo = explode("S",$_POST['data']);
    $SQL = "SELECT clientes.*, clases.nombre AS clase, clientesmembresias.fechapago, clientesmembresias.fechavencimiento, clientesmembresias.activo FROM clientes LEFT JOIN clientesmembresias ON clientes.idclientes=clientesmembresias.idclientes LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE clientes.fechabaja IS NULL AND (clientesmembresias.activo LIKE 'activo' OR clientesmembresias.activo IS NULL) AND clientes.codigo LIKE '".$codigo[0]."' GROUP BY clientes.idclientes";
	$Auto = $consulta->query($SQL);
    foreach ($Auto as $row);
    if($row['codigo']==''){
        echo "Error1";
    }else{
        $Auto = $consulta->query("SELECT * FROM asistencias WHERE idclientes='".$row['idclientes']."' AND fecha BETWEEN '".date("Y-m-d")." 00:00:00' AND '".date("Y-m-d")." 23:59:59' AND fechabaja IS NULL ");
        foreach ($Auto as $fila);
        if($fila[0]==''){
            $Auto = $consulta->query("INSERT INTO asistencias SET idusuarios='".$_SESSION['SISTEMA']['idusuarios']."', idclientes='".$row['idclientes']."', fecha='".date("Y-m-d H:i:s")."'");
            foreach ($Auto as $Autocontador);
            echo "Sucess";
        }else{
            echo "Error4";
        }
        
    }
	exit();
}
if ($_POST["funcion"]=="DataClientes") {
	include("inc/conectar.php");
	$existencias = 0;
	$buscar = $consulta->query("SELECT * FROM clientes WHERE fechabaja IS NULL");
		foreach ($buscar as $row){
			echo "<option id='$row[codigo]' value ='$row[codigo]-".stripslashes ($row['nombre'])."'>";
		}
	exit();
}
if($_POST['funcion']=='Pagar_Nuevo'){
include('inc/conectar.php');
$dias = $_POST['dias'];
$fechavence = date('Y-m-d', strtotime($fecha . " +$dias days"));
    $Auto = $consulta->query("UPDATE clientesmembresias SET activo='noactivo' WHERE idclientes='".$_POST['idclientes']."'");
    foreach ($Auto as $Autocontador);
    $Auto = $consulta->query("INSERT INTO clientesmembresias SET idmembresias='".$_POST['idmembresias']."', idclientes='".$_POST['idclientes']."', importe='".$_POST['importe']."', fechavencimiento='".$fechavence."', fechapago='".date("Y-m-d H:i:s")."', fecha='".date("Y-m-d H:i:s")."'");
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
if($_POST['funcion']=='Cargar_Membresias'){
include('inc/conectar.php');
$nombre = '';
$nombreb = '';
if($_POST['nombre']!=''){
    $nombre = " AND clientes.codigo LIKE '".substr($_POST['nombre'],0,6)."'";
    $nombreb = $_POST['nombre'];
}
?>

<?
$estado ="AND (clientesmembresias.activo LIKE 'activo' OR clientesmembresias.activo IS NULL)";
if($_POST['estado']=='vencida'){
    $estado = " AND (fechavencimiento < CURDATE() OR clientesmembresias.fechapago IS NULL) AND (clientesmembresias.activo LIKE 'activo' OR clientesmembresias.activo IS NULL) ";
}
if($_POST['estado']=='activa'){
    $estado = " AND fechavencimiento >= CURDATE() AND clientesmembresias.activo LIKE 'activo' ";
}
$idclases = '';
if($_POST['idclases']!='' and $_POST['idclases']!='0'){
    $idclases = " AND clientes.idclases=".$_POST['idclases'];
}


$Auto = $consulta->query("SELECT clientes.*, clases.nombre AS clase, clientesmembresias.fechapago, clientesmembresias.fechavencimiento, clientesmembresias.activo FROM clientes LEFT JOIN clientesmembresias ON clientes.idclientes=clientesmembresias.idclientes LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE clientes.fechabaja IS NULL $estado $idclases $nombre GROUP BY clientes.idclientes");

$contador = 0;

foreach ($Auto as $row) {
    $diasvence = '';
    $BOTONPAGAR ='';
    if($row['fechapago']=='' or $row['fechavencimiento']<date("Y-m-d")){
        //NO TIENE NINGUNA MEMBRESIA ACTIVA 
        $diasvence = '<b class="text-danger">Sin Membresia Activa</b>';
        $BOTONPAGAR ='<button class="btn btn-primary btn-sm pagar" idclientes="'.$row['idclientes'].'"><i class="bi bi-cash-coin"></i> Realizar Pago</button>';
    }else{
        $diasvence = '<b class="text-primary">Membresia Activa</b>';
        $BOTONPAGAR ='<button class="btn btn-success btn-sm ver_pagos" idclientes="'.$row['idclientes'].'"><i class="bi bi-cash-coin"></i> Ver Pagos</button>';
    }
?>
<div class="col-2 text-center margen border rounded carta" >
    <div class="row">
        <div class="col-12 border rounded border-primary bg-secondary bg-gradient-secondary text-white">
            <?=$row['clase']?>
        </div>
    </div>    
    <div class="row">
        <div class="col-12 nombre">
            <b class="card-title"><?=$row['codigo']."-".substr($row['nombre'],0,35)?></b>
        </div>
    </div>   
    <div class="row">
        <div class="col-12">
            <?=$BOTONPAGAR?>
        </div>
    </div>   
    <div class="row ">
        <div class="col">
           <?=$diasvence?>
        </div>
    </div> 
</div> 
<?php
}
exit();
}
?>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Softsimbiosis</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
	<script src="js/jquery-3.3.1.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="alertifyjs/css/alertify.css">
    <link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
    <script src="alertifyjs/alertify.js"></script>
    <style>


        </style>
</head>
<?
//include("menu.php");
?>
<div class="container-fluid" style="margin-top:5px;">
	<div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-3 text-center" >
                    <div class="row">
                        <div class="col-12 rounded border-primary bg-primary bg-gradient-success text-white">
                            <h3>Capturar Asistencia</h3>
                        </div>
                    </div>  
                    <div class="row" >
                        <div class="col-12 nombre">
                            <input autocomplete="off" style="margin-top:15px;"  class="form-control" id="nombre" placeholder="Ingresar QR" value="<?=$nombreb?>">
                            <button class="btn btn-success btn-lg" style="margin-top:10px; width:100%;" id="capturar"><i class="bi bi-person-arms-up"></i> Guardar Asistencia</button>
                            <br><br>
                            <a href="https://somosimbiosis.com/cross" style="text-decoration-line: none;">
                            <img src="img/logo.png" height="100"></a>
                            <br>
                            <a href="https://www.softsimbiosis.com/" target="_blank" style="text-decoration-line: none;"><img src="img/logo1.png" height="100"></a>
                        </div>
                    </div> 
                </div>
                <div class="col-9 text-center border" >
                    <div class="row">
                        <div class="col-12 rounded border-info bg-info text-white">
                            <h3>Ultimas Asistencias Registradas</h3>
                        </div>
                    </div>    
                    <div class="row" >
                        <div class="col-12" id="asistencias_table">
                            
                        </div>
                    </div> 
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

<audio src="error.mp3" id="error">
  Your browser does not support the <code>audio</code> element.
</audio>
<audio src="correct.mp3" id="correcto">
  Your browser does not support the <code>audio</code> element.
</audio>
<button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>
<script src="lib/jsqrcode-combined.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="src/html5-qrcode.js"></script>
<script>
$(document).ready(function() {
   $("#nombre").focus();
    $(document).on('click','#capturar', function () {
        if($("#nombre").val()!=''){
            var data = $("#nombre").val();
            var datos = data.split("-");
            Asistencia(datos[0]);
            $("#nombre").val("");
        }else{
            $("#nombre").focus();
            alertify.error("Ingrese un Codigo QR");
            return false;
        }
	});
    $(document).on("keydown", "#nombre", function(e) {
        if (e.which == 13) {
            if ($(this).val() !='') {
                var data = $("#nombre").val();
                var datos = data.split("-");
                Asistencia(datos[0]);
            } else {
                $("#nombre").focus();
                alertify.error("Ingrese un Codigo QR");
                $("#error")[0].play();
            }
            
        }
    });  
    function Asistencia(data) {
        $.ajax({
            type: "POST",
            url: "<?= $_SERVER["PHP_SELF"] ?>",
            data: ({
                funcion: "Asistencia",
                data: data
            }),
            dataType: "html",
            async: false,
            success: function(msg) {
                if(msg=='Sucess'){
                    alertify.success("Asistencia Registrada Correctamente ");
                    $('#result').html(data);
                    Cargar_Asistencias();
                    $("#correcto")[0].play();
                }else if(msg=='Error1'){
                    alertify.error("QR no valido");
                    $("#error")[0].play();
                }else if(msg=='Error4'){
                    alertify.error("Asistencia ya Registrada en este dia ");
                    $("#error")[0].play();
                }
                $("#nombre").val("");
                
            }
        });
    }    
   // Carga_Clientes();
    Cargar_Asistencias();
    function Carga_Clientes() {
        $.ajax({
            type: "POST",
            url: "<?= $_SERVER["PHP_SELF"] ?>",
            data: ({
                funcion: "DataClientes",
                Buscar: $("#nombre").val()
            }),
            dataType: "html",
            async: false,
            success: function(msg) {
                console.log(msg);
                $("#datosClientes").html(msg);
            }
        });
    }    
    $(document).on('change','#estado, #idclases', function () {
		Cargar_Membresias("");
	});
    $(document).on('click','#buscar', function () {
        var nombre = $("#nombre").val();
		Cargar_Membresias(nombre);
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
	
    function Cargar_Asistencias(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Cargar_Asistencias"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#asistencias_table").html(msg);
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
                    dias : $("#duracion").val()
				}),
				dataType: "html",
				async:false,
				success: function(msg){
                    alertify.success("Membresia Pagada Correctamente ");
                    $("#cerrar_m").click();
                    setTimeout(function() {Cargar_Membresias("");}, 2000);

				},error : function(jqXHR, status, error) {
                    alert('Disculpe, existió un problema'+error+status);
                }
                });
            }, function(){
            alertify.error('Cancelado')
        });
	});
    function Quita_Moneda(n) {
				n = String(n);
				var s = parseFloat(n.replace(",", "").replace("$", ""));
				if (isNaN(s)) s = 0;
				return s;
			}
	function Formato_Moneda(n,c,d,t){
		var c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	}
} );
</script>
