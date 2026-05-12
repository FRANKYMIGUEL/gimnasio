<?php

if ($_POST["funcion"]=="Asistencia") {
	include("inc/conectar.php");
    $SQL = "SELECT clientes.*, clases.nombre AS clase, clientesmembresias.fechapago, clientesmembresias.fechavencimiento, clientesmembresias.activo FROM clientes LEFT JOIN clientesmembresias ON clientes.idclientes=clientesmembresias.idclientes LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE clientes.fechabaja IS NULL AND (clientesmembresias.activo LIKE 'activo' OR clientesmembresias.activo IS NULL) AND clientes.codigo LIKE '".$_POST['data']."' GROUP BY clientes.idclientes";
	$Auto = $consulta->query($SQL);
    foreach ($Auto as $row);
    if($row['codigo']==''){
        echo "Error1";
    }else if($row['fechapago']==''){
        echo "Error2";
    }else if($row['fechavencimiento']<date("Y-m-d")){
        echo "Error3";
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
    .margen{
        margin-bottom:5px;
        padding-right:10px;
    }    
    .carta{
        height:400px;
    }
    .nombre{
        height:80px;
    }
        </style>
</head>
<?
include("menu.php");
?>
<div class="container-fluid" style="margin-top:5px;">
	<div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-1" >
                </div>
                <div class="col-4 text-center border rounded" >
                    <div class="row">
                        <div class="col-12 border rounded border-success bg-success bg-gradient-success text-white">
                            <h3>Capturar Asistencia</h3>
                        </div>
                    </div>  
                      
                    <div class="row" >
                        <div class="col-12 nombre">
                            <input list="datosClientes" autocomplete="off" name=""  style="margin-top:5px;"  class="form-control" id="nombre" placeholder="Buscar Cliente" autocomplete value="<?=$nombreb?>">
                            <datalist id="datosClientes" class="col-md-9" active style="width:1000px;">
                            </datalist>
                            <button class="btn btn-success btn-md" style="margin-top:10px;" id="capturar"><i class="bi bi-person-arms-up"></i> Capturar Asistencia</button>
                        </div>
                    </div> 
                </div>
                <div class="col-1 carta" >
                </div>
                <div class="col-4 text-center margen border rounded carta" >
                    <div class="row">
                        <div class="col-12 border rounded border-info bg-info bg-gradient-success text-white">
                            <h3>Capturar por Codigo</h3>
                        </div>
                    </div>    
                    <div class="row" >
                        <div class="col-12 nombre">
                            <button class="btn btn-info btn-md" style="margin-top:5px;" id="camara"><i class="bi bi-qr-code-scan"></i> Activar Camara</button>
                            <span id="error" style='color:darkred;' class="center"></span>
                            <div id="reader" style="width:300px;height:250px; margin-left:20px;"></div>
                            <span id="result" class="center"></span>
                            <button class="btn btn-warning btn-md" style="margin-top:5px;" id="parar"><i class="bi bi-pause-btn"></i> Pausar Camara</button>
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

<button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>
<script src="lib/jsqrcode-combined.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="src/html5-qrcode.js"></script>
<script>
$(document).ready(function() {
    
    $(document).on('click','#camara', function () {
        Cargar_Qr();
        $(this).attr("disabled",true);
	});  
    $(document).on('click','#parar', function () {
        $("#camara").attr("disabled",false);
        window.location = "asistencia.php";
	});
    function Cargar_Qr(){
        $('#reader').html5_qrcode(function(data){
            console.log(data);
            $('#result').html(data);
            Asistencia(data);
          },
          function(error){
            $('#error').html("Scaneando...");
          }, function(videoError){
            $('#error').html("Error de la Camara.");
          }
        );
    }
    $(document).on('click','#capturar', function () {
        if($("#nombre").val()!=''){
            var data = $("#nombre").val();
            var datos = data.split("-");
            Asistencia(datos[0]);
            $("#nombre").val("");
        }else{
            $("#nombre").focus();
            alertify.error("Selecciona un Cliente");
            return false;
        }
	});  
    function Asistencia(data) {
        if(data==''){
            alertify.error("Ingresar un QR");
            return false;
        }

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
                console.log(msg);
                if(msg=='Sucess'){
                    alertify.success("Asistencia Registrada Correctamente ");
                    $('#result').html(data);
                }else if(msg=='Error1'){
                    alertify.error("QR no valido");
                }else if(msg=='Error2'){
                    alertify.error("Cliente Sin Activar alguna Membresia ");
                }else if(msg=='Error3'){
                    alertify.error("Membresia Vencida");
                    alertify.alert("Membresia Vencida Favor de Rennovarla");
                }else if(msg=='Error4'){
                    alertify.error("Asistencia ya Registrada en este dia ");
                }
                
                
            }
        });
    }    
    Carga_Clientes();
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
    
    
	$(document).on('click','#Nuevo', function () {
	  $('#carga_modal').click();
		Cargar_Modal("Nuevo",0);
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
