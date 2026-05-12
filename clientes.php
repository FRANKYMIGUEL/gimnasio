<?
if($_POST['funcion']=='Carga_Clientes'){
include("inc/conectar.php");
	$resultados=$consulta->query("SELECT clientes.*, clases.nombre AS clase FROM clientes LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE clientes.fechabaja IS NULL");
	foreach ($resultados as $row) {
	?>
	<tr>
		<td>
			<button type="button" class="btn btn-default btn-sm btn-primary">
			<?=$row['codigo']?>
			</button>
		</td>
		<td><?=$row['nombre']?></td>
		<td><?=$row['domicilio']?></td>
		<td><?=$row['genero']?></td>
		<td><?=$row['telefono']?></td>
		<td><?=$row['fecha_inicio']?></td>
		<td><?=$row['fecha_expiracion']?></td>
		<td><?=$row['imagen']?></td>
		<td><?=$row['fecha_registro']?></td>
		<td>
			<button type="button" class="btn btn-default btn-sm btn-info editar" data-toggle="modal" data-target="#myModal" idregistro="<?=$row[0]?>">
			<i class="bi bi-pencil"></i> Editar
			</button>
			<button type="button" class="btn btn-default btn-sm btn-danger eliminar" idregistro="<?=$row[0]?>">
			<i class="bi bi-trash3"></i>  Eliminar
			</button>
		</td>
	</tr>
<?
	}
	exit();
}

if($_POST['funcion']=='Carga_Folio'){
include("inc/conectar.php");
	$Auto = $consulta->query("SELECT MAX(idclientes)+1 AS Auto_increment FROM clientes");
	foreach ($Auto as $Autocontador);
	$Auto =str_pad($Autocontador["Auto_increment"], 6, "0", STR_PAD_LEFT);
	echo $Auto;
	return $Auto;
	exit();
}
if($_POST['funcion']=='Guardar'){
include("inc/conectar.php");
	$Auto = $consulta->query("SELECT MAX(idclientes)+1 AS Auto_increment FROM clientes");
	foreach ($Auto as $Autocontador);
	$CODIGO =str_pad($Autocontador["Auto_increment"], 6, "0", STR_PAD_LEFT);
	$Auto = $consulta->query("INSERT INTO clientes SET codigo='".$CODIGO."', nombre='".$_POST['nombre']."', domicilio='".$_POST['domicilio']."', idmembresia='".$_POST['idmembresia']."', membresia='".$_POST['membresia']."', dia='".$_POST['dia']."', telefono='".$_POST['telefono']."', idclases='".$_POST['idclases']."', observaciones='".$_POST['observaciones']."' ");
	foreach ($Auto as $Autocontador);
	echo $CODIGO;
exit();
}
if($_POST['funcion']=='Editar_Productos'){
include("inc/conectar.php");
	$Auto = $consulta->query("UPDATE clientes SET codigo='".$_POST['codigo']."', nombre='".$_POST['nombre']."', domicilio='".$_POST['domicilio']."', idmembresia='".$_POST['idmembresia']."', membresia='".$_POST['membresia']."', dia='".$_POST['dia']."', telefono='".$_POST['telefono']."', idclases='".$_POST['idclases']."', observaciones='".$_POST['observaciones']."' WHERE idclientes=".$_POST['idregistro']);
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='Eliminar'){
include("inc/conectar.php");
	$Auto = $consulta->query("UPDATE clientes SET fechabaja='".date("Y-m-d H:i:s")."' WHERE idclientes=".$_POST['idregistro']);
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='Carga_Modal'){
include("inc/conectar.php");
        if($_POST['tipo']!='Nuevo'){
			$Auto = $consulta->query("SELECT * FROM clientes WHERE idclientes=".$_POST['idregistro']."");
			foreach ($Auto as $row);
		}
		?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-2 text-center">
          	<b>Codigo</b>
              <input type="text" class="form-control text-center" id="codigo" placeholder="codigo" aria-describedby="basic-addon1" value="<?=$row['codigo']?>" readonly>
		  </div>
			<div class="col-md-6">
                <b>Nombre</b>
              <input type="text" class="form-control" placeholder="Nombre" id="nombre" aria-describedby="basic-addon1" value="<?=$row['nombre']?>">
		  </div>
          <div class="col-md-4">
            <b>Domicilio</b>
              <input type="text" class="form-control" placeholder="Domicilio" id="domicilio" aria-describedby="basic-addon1" value="<?=$row['domicilio']?>">
		  </div>
		  <div class="col-md-2">
            <b>Dia de Pago</b>
              <input type="text" class="form-control" placeholder="Dia" id="dia" aria-describedby="basic-addon1" value="<?=$row['dia']?>">
		  </div>
          <div class="col-md-3">
            <b>Clase</b>
              <select type="text" class="form-control" id="idclases">
				<?php
					$Autom = $consulta->query("SELECT * FROM clases WHERE fechabaja IS NULL ORDER BY nombre");
					foreach ($Autom as $mem){
						?>
						<option value="<?=$mem['idclases']?>" <? if($mem['idclases']==$row['idclases']) echo "selected" ?>><?=$mem['nombre']?></option>
						<?
					}
				?>
			</select>
		  </div>
		  <div class="col-md-3">
            <b>Membresia</b>
              <select type="text" class="form-control" id="idmembresia">
				<?php
					$Autom = $consulta->query("SELECT * FROM membresias WHERE fechabaja IS NULL ORDER BY nombre");
					foreach ($Autom as $mem){
						?>
						<option value="<?=$mem['idmembresia']?>" <? if($mem['idmembresia']==$row['idmembresia']) echo "selected" ?>><?=$mem['nombre']?></option>
						<?
					}
				?>
			</select>
		  </div>
          <div class="col-md-3">
            <b>Telefono</b>
              <input type="text" class="form-control" placeholder="Telefono" id="telefono" aria-describedby="basic-addon1" value="<?=$row['telefono']?>">
		  </div>
        	 <div class="col-md-12 text-center">
              <b>Observaciones</b>
             <textarea id="observaciones" class="form-control noresize"><?=$row['observaciones']?></textarea>
             </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
       <?
        if($_POST['tipo']=='Nuevo'){
		?>
        <button type="button" class="btn btn-primary" id="Guardar" idregistro="<?=$row[0]?>"><i class="bi bi-floppy"></i> Guardar Nuevo</button>
        <?
		}else{
		?>
        <button type="button" class="btn btn-primary" id="editar_producto" idregistro="<?=$row[0]?>"><i class="bi bi-pencil"></i> Guardar Modificaciones</button>
      </div>
      <?
	}

exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Datos de Clientes registrados</title>
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
<?
include("menu.php");
?>
  <main class="page-content">
    <div class="container-fluid" style="text-align: center;">
      <div class="row">
		<div class="col-md-1"></div>
        <div class="col-md-11">
            <div class="row">
                <div class="col-9 text-center">
                    <h4><i class="bi bi-person-check"></i> Datos de clientes registrados</h4>
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-success btn-md" data-toggle="modal" id="nuevo" data-target="#myModal">
                        <span class="glyphicon glyphicon-apple" aria-hidden="true"></span>
						<i class="bi bi-plus-circle"></i>  Cliente Nuevo
                    </button>
                </div>
            </div>
            <div class="row">
           		<div class="col-12">
					<table id="example" class="table table-sm table-hover" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>NoCliente</th>
							<th>Nombres</th>
							<th>Domicilio</th>
							<th>Genero</th>
							<th>Telefono</th>
							<th>Fecha Inicio</th>
							<th>Fecha Expiracion</th>
							<th>Imagen</th>
							<th>Fecha Registro</th>
							<th width="200"></th>
						</tr>
					</thead>
					<tbody id="resultados_productos">
					</tbody>
				</table>
            </div>
        </div>
   </div>
      </div>
    </div>

  </main>

</div>


	
<div class="modal fade" id="myModal" tabindex="-1"  data-target=".bs-example-modal-lg" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div id="contenido_modal">
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
  </div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.3.1.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="alertifyjs/alertify.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(e) {
	Carga_Productos();
	$('#example').DataTable( {
		language: {
			processing:     "Procesando...",
			search:         "Buscar por Nombre, Apellido o NoCliente:",
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
	$(document).on("click","#nuevo",function(){
		Carga_Modal("Nuevo",0);
		Carga_Folio();
	});

	$(document).on("click",".editar",function(){
		var idregistro = $(this).attr("idregistro");
		Carga_Modal("Editar",idregistro);
	});
	$(document).on("click",".eliminar",function(){
		var idregistro = $(this).attr("idregistro");
		alertify.confirm('Estas Seguro de Eliminar el Cliente', function(){
		alertify.success('Si') ;
			$.ajax({
				type: "POST",
				url: "clientes.php",
				data: ({
					funcion : "Eliminar",
					idregistro : idregistro
				}),
				dataType: "html",
				async:false,
				success: function(msg){
					alertify.success("Cliente eliminado Exitosamente ");
					window.location="clientes.php";
				}
			});
		}, function(){
		alertify.error('Cancelado')});
	});
	function Carga_Modal(tipo,idregistro){
		$.ajax({
			type: "POST",
			url: "clientes.php",
			data: ({
				funcion : "Carga_Modal",
				tipo : tipo,
				idregistro : idregistro
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				//var e=prompt("",msg);
				$("#contenido_modal").html(msg);
			}
		});

	}
	$(document).on("click","#Guardar",function(e) {
        if($("#nombre").val()==""){
			alertify.error("Ingresa un Nombre");
			$("#nombre").focus();
			return false;
		}
		if($("#dia").val()==""){
			alertify.error("Ingresa un dia");
			$("#dia").focus();
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: "clientes.php",
			data: ({
				funcion : "Guardar",
				codigo : $("#codigo").val(),
				nombre : $("#nombre").val(),
				domicilio : $("#domicilio").val(),
				idclases : $("#idclases option:selected").val(),
				idmembresia : $("#idmembresia option:selected").val(),
				membresia : $("#idmembresia option:selected").text(),
				telefono : $("#telefono").val(),
				dia : $("#dia").val(),
				observaciones : $("#observaciones").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				console.log(msg);
				alertify.success("Cliente Agredado Exitosamente ");
				window.location="clientes.php";
			}
		});
    });
	$(document).on("click","#editar_producto",function(e) {
		var idregistro = $(this).attr("idregistro");
        if($("#nombre").val()==""){
			alertify.error("Ingresa un Nombre");
			$("#nombre").focus();
			return false;
		}
		if($("#dia").val()==""){
			alertify.error("Ingresa un dia");
			$("#dia").focus();
			return false;
		}
		$.ajax({
			type: "POST",
			url: "clientes.php",
			data: ({
				funcion : "Editar_Productos",
				codigo : $("#codigo").val(),
				nombre : $("#nombre").val(),
				idclases : $("#idclases option:selected").val(),
				idmembresia : $("#idmembresia option:selected").val(),
				membresia : $("#idmembresia option:selected").text(),
				telefono : $("#telefono").val(),
				dia : $("#dia").val(),
				observaciones : $("#observaciones").val(),
				idregistro : idregistro
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				console.log(msg);
				alertify.success("Cliente Modificado Exitosamente ");
				window.location="clientes.php";
			}
		});
    });
	function Carga_Folio(){
		$.ajax({
			type: "POST",
			url: "clientes.php",
			data: ({
				funcion : "Carga_Folio"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#codigo").val(msg);
			}
		});
	}
	function Carga_Productos(){
		$.ajax({
			type: "POST",
			url: "clientes.php",
			data: ({
				funcion : "Carga_Clientes"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_productos").html(msg);
				//$('#example').DataTable();
			}
		});
	}
});
</script>
</body>
