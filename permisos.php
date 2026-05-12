<?
if($_POST['funcion']=='Carga_Permisos'){
include('inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM permisos LEFT JOIN modulos ON modulos.idmodulos=permisos.idmodulos WHERE idusuarios=".$_POST['idusuarios']." ORDER BY nombre");
	foreach ($Auto as $row){
		?>
		<tr>
			<td class="text-uppercase"><?=$row['nombre']?></td>
			<td class="text-center" width="250">
				<button type="button" class="btn btn-danger btn-sm eliminar" registros="<?=$row['idmodulos']?>"><i class="bi bi-trash3"></i> Eliminar</button>
			</td>
		</tr>			
		<?
	}
	exit();
}

if($_POST['funcion']=='Eliminar'){
include('inc/conectar.php');
	$Auto = $consulta->query("DELETE FROM permisos WHERE idusuarios=".$_POST['idusuarios']." AND idmodulos= ".$_POST['idmodulos']);
	foreach ($Auto as $Autocontador);
	exit();
}
if($_POST['funcion']=='Guardar'){
include('inc/conectar.php');
	if($_POST['idmenu']=='0'){
		$Auto = $consulta->query("DELETE FROM permisos WHERE idusuarios='".$_POST['idusuarios']."'");
		foreach ($Auto as $Autocontador);
		$consu = $consulta->query("SELECT * FROM modulos");
		foreach ($consu as $menu){
			$Auto = $consulta->query("INSERT INTO permisos SET idusuarios='".$_POST['idusuarios']."', idmodulos='".$menu['idmodulos']."', fechacreacion='".date("Y-m-d H:i:s")."' ");
			foreach ($Auto as $Autocontador);
		}
	}else{
		$Auto = $consulta->query("SELECT * FROM permisos WHERE idusuarios='".$_POST['idusuarios']."' AND idmodulos=".$_POST['idmenu']);
		foreach ($Auto as $MENU);
		if($MENU[0]==''){
			$Auto = $consulta->query("INSERT INTO permisos SET idusuarios='".$_POST['idusuarios']."', idmodulos='".$_POST['idmenu']."', fechacreacion='".date("Y-m-d H:i:s")."' ");
			foreach ($Auto as $Autocontador);
		}else{
			echo "1";	
		}
	}
exit();
}
if($_POST['funcion']=='Carga_Modal'){
include('inc/conectar.php');
	?>
<div class="modal-body modal-lg" >      
	<div class="row">
		<div class="col-12 font-weight-bold">
			<label>Permiso</label>
			<select  class="form-control text-uppercase" id="idmenu">
                <option value="-1">Seleccione</option>
                <option value="0" class="bg-success" >Agregar Todos</option>
				<?
                $Auto = $consulta->query("SELECT * FROM modulos ORDER BY nombre");
                foreach ($Auto as $row){
                    ?>
                    <option value="<?=$row[0]?>" ><?=$row['nombre']?></option>
                    <?
                }
                ?>
            </select>
		</div>
	  </div>
	</div>

      <div class="modal-footer modal-lg">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
		  <button type="button" class="btn btn-primary" id="guardar" tipo="<?=$_POST['tipo']?>" idusuarios="<?=$_GET['idregistro']?>"><i class="bi bi-floppy"></i> Guardar</button>
      </div>
	<?
exit();
}
?>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Permisos</title>
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
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Permisos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div id="resultados_modal">
		</div>
    </div>
  </div>
</div>
<?php
 include("menu.php");
?>
<div class="container-fluid" style="margin-top:5px;">
	<div class="row">
    	<div class="col-md-12 text-left">
		<?
       
		$Auto = $consulta->query("SELECT * FROM usuarios WHERE idusuarios=".$_GET['id']."");
		foreach ($Auto as $usuario);
        ?>
        </div>
        <div class="col-md-12">
        	<div class="row">
                <div class="col-6 text-center ">
                    <h3>Usuario: <?=$usuario['nombre']?> <input type="text" class="invisible" value="<?=$usuario['idusuarios']?>" id="idusuarios"></h3>
                </div>
                <div class="col">
                    <button type="button" id="Nuevo" class="btn btn-info btn-sm" ><i class="bi bi-plus-circle"></i> Agregar Permisos</button>
                    <button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>			
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="example" class="table table-sm  table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre Permiso</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="registros_tabla">
                    </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
	$(document).on('click','#Nuevo', function () {
	  $('#carga_modal').click();
		Cargar_Modal("Nuevo",0);
	});	

	Carga_Tabla();
	function Carga_Tabla(){
		$.ajax({
			type: "POST",
			url: "permisos.php",
			data: ({
				funcion : "Carga_Permisos",
				idusuarios : $("#idusuarios").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#registros_tabla").html(msg);
			}
		});	
	}
	$(document).on("click","#guardar",function(e) {
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Guardar",
				idmenu : $("#idmenu option:selected").val(),
				idusuarios : $("#idusuarios").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				if(msg==''){
					alertify.success("Permiso Agredado Exitosamente ");
					window.location="<?=$_SERVER["PHP_SELF"]?>?id="+$("#idusuarios").val();
				}else{
					alertify.error("Permiso Ya Existe");
					alertify.error("No se puede Volver a Agregar");
				}
			}
		});	
    });	
	
	function Cargar_Modal(idregistro,tipo){
		
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Modal",
				tipo : tipo,
				idregistro : $("#idusuarios").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_modal").html(msg);
			}
		});	
	}
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
	 });
	
	$(document).on("click",".eliminar",function(){
		var idregistro = $(this).attr("registros");
		alertify.confirm("Eliminacion",'¿Estas Seguro de Eliminar el Permiso?', function(){ 
			$.ajax({
				type: "POST",
				url: "<?=$_SERVER["PHP_SELF"]?>",
				data: ({
					funcion : "Eliminar",
					idmodulos : idregistro,
					idusuarios : $("#idusuarios").val()
				}),
				dataType: "html",
				async:false,
				success: function(msg){
					alertify.success("Permiso eliminado Exitosamente "+msg);
					window.location="<?=$_SERVER["PHP_SELF"]?>?id="+$("#idusuarios").val();
					
				}
			});	
		}, function(){ 
		alertify.error('Cancelado')});
	});
} );

</script>

