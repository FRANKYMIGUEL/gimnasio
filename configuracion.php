<?
if($_POST['funcion']=='Carga_Usuarios'){
include('inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM usuarios WHERE inactivo IS NULL");
	foreach ($Auto as $row){
		?>
		<tr>
			<td class="text-uppercase"><?=$row['nombre']?></td>
			<td class="text-uppercase"><?=$row['usuario']?></td>
			<td class="text-uppercase"><?=$row['domicilio']?></td>
			<td class="text-uppercase"><?=$row['telefono']?></td>
			<td class="text-center" width="300">
            	<?
				if($row[0]!=1){
				?>
				<button class="btn btn-primary btn-sm editar" registros="<?=$row[0]?>" ><i class="bi bi-pencil"></i> Editar</button>
				<button class="btn btn-danger btn-sm eliminar" registros="<?=$row[0]?>"><i class="bi bi-trash3"></i> Eliminar</button>
				<a href="permisos.php?id=<?=$row[0]?>">
                	<button type="button" class="btn btn-warning btn-sm permiso" registros="<?=$row[0]?>"><i class="bi bi-key"></i> Permisos</button>
                </a>
                <?
				}
				?>
			</td>
		</tr>
		<?
	}
	exit();
}
if($_POST['funcion']=='Guardar'){
include('inc/conectar.php');
	$Auto = $consulta->query("INSERT INTO usuarios SET nombre='".$_POST['nombre']."', usuario='".$_POST['usuario']."', pass='".$_POST['pass']."', telefono='".$_POST['telefono']."', domicilio='".$_POST['domicilio']."' ");
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='Editar'){
include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE usuarios SET nombre='".$_POST['nombre']."', usuario='".$_POST['usuario']."', pass='".$_POST['pass']."', telefono='".$_POST['telefono']."', domicilio='".$_POST['domicilio']."' WHERE idusuarios=".$_POST['idregistro']);
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='Eliminar'){
include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE usuarios SET inactivo='".date("Y-m-d H:i:s")."' WHERE idusuarios=".$_POST['idregistro']);
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='Carga_Modal'){
include('inc/conectar.php');
	if($_POST['tipo']!='Nuevo'){
		$Auto = $consulta->query("SELECT * FROM usuarios WHERE idusuarios=".$_POST['idregistro']."");
		foreach ($Auto as $row);
		$numero = $row['codigo'];
	}
	?>
<div class="modal-body modal-lg" >      
        <div class="row">
            <div class="col-6 font-weight-bold">
                <label>Nombre</label>
                <input type="text" class="form-control text-uppercase" id="nombre" value="<?=$row['nombre']?>" placeholder="Nombre ">
            </div>
            <div class="col-6 font-weight-bold">
                <label>Usuario</label>
                <input type="text" class="form-control text-uppercase" value="<?=$row['usuario']?>" id="usuario" placeholder="Usuario">
            </div>
            <div class="col-6 font-weight-bold">
                <label>Password</label>
                <input type="password" class="form-control" value="<?=$row['pass']?>" id="pass">
            </div>
            <div class="col-6 font-weight-bold">
                <label>Telefono</label>
                <input type="text" class="form-control text-uppercase" value="<?=$row['telefono']?>" id="telefono" placeholder="telefono">
            </div>
            <div class="col-6 font-weight-bold">
                <label>Domicilio</label>
                <input type="text" class="form-control text-uppercase" value="<?=$row['domicilio']?>" id="domicilio" placeholder="Domicilio">
            </div>
          </div>
      <div class="modal-footer modal-lg">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
		  <?
		if($_POST['tipo']!='Nuevo'){
?>
		  <button type="button" class="btn btn-primary" id="editar" registros="<?=$_POST['idregistro']?>"><i class="bi bi-pencil"></i> Modificar Usuario</button>
		  <?
		}else{
			?>
		  <button type="button" class="btn btn-primary" id="guardar"><i class="bi bi-floppy"></i> Guardar Usuario</button>
		  <?
		}
		?>
      </div>
	<?
exit();
}
?>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Usuarios</title>
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
<?
include("menu.php");
?>
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-person-bounding-box"></i>  Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div id="resultados_modal">
		</div>
    </div>
  </div>
</div>
<div class="container-fluid" style="margin-top:5px;">
	<div class="row">
    	<div class="col">
			&nbsp;
		</div>
		<div class="col-6 text-center ">
			<h3><i class="bi bi-person-bounding-box"></i> Usuarios</h3>
		</div>
		<div class="col">
			<button type="button" id="Nuevo" class="btn btn-info btn-sm" ><i class="bi bi-plus-circle"></i> Agregar Usuarios</button>
			<button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>			
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<table id="example" class="table table-striped table-bordered" style="width:100%">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Usuario</th>
					<th>Domicilio</th>
					<th>Telefono</th>
					<th width="300">Opciones</th>
				</tr>
			</thead>
			<tbody id="registros_tabla">
			</tbody>
		</table>
		</div>
	</div>
</div>
</body>
<!-- .//container -->
<script src="js/jquery-3.3.1.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="alertifyjs/css/alertify.css">
<link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
<script src="alertifyjs/alertify.js"></script>

<script>
$(document).ready(function() {
	$(document).on('click','#Nuevo', function () {
	  $('#carga_modal').click();
		Cargar_Modal("Nuevo",0);
	});	
	
	$(document).on('click','.editar', function () {
		var idregistro = $(this).attr("registros");
	  $('#carga_modal').click();
		Cargar_Modal("Editar",idregistro);
	});
	
	Carga_Tabla();
	function Carga_Tabla(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Usuarios"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#registros_tabla").html(msg);
			}
		});	
	}
	$(document).on("click","#guardar",function(e) {
        if($("#nombre").val()==""){
			alertify.error("Ingresa un Nombre ");
			$("#nombre").focus();
			return false;	
		}
        if($("#pass").val()==""){
			alertify.error("Ingresa una Contraseña ");
			$("#pass").focus();
			return false;	
		}
        if($("#usuario").val()==""){
			alertify.error("Ingresa un Usuario ");
			$("#nombre").focus();
			return false;	
		}
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Guardar",
				nombre : $("#nombre").val(),
				pass : $("#pass").val(),
				usuario: $("#usuario").val(),
				domicilio : $("#domicilio").val(),
				telefono : $("#telefono").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				alertify.success("Usuario Agredado Exitosamente ");
				window.location="<?=$_SERVER["PHP_SELF"]?>";
			}
		});	
    });	
	$(document).on("click","#editar",function(e) {
		var idregistro = $(this).attr("registros");
        if($("#nombre").val()==""){
			alertify.error("Ingresa un Nombre ");
			$("#nombre").focus();
			return false;	
		}
        if($("#pass").val()==""){
			alertify.error("Ingresa una Contraseña ");
			$("#pass").focus();
			return false;	
		}
        if($("#usuario").val()==""){
			alertify.error("Ingresa un Usuario ");
			$("#nombre").focus();
			return false;	
		}
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Editar",
				nombre : $("#nombre").val(),
				pass : $("#pass").val(),
				usuario: $("#usuario").val(),
				domicilio : $("#domicilio").val(),
				telefono : $("#telefono").val(),
				idregistro : idregistro
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				alertify.success("Usuario Modificado Exitosamente ");
				window.location="<?=$_SERVER["PHP_SELF"]?>";
			}
		});	
    });	
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
    
	$(document).on("click",".eliminar",function(){
		var idregistro = $(this).attr("registros");
		alertify.confirm("Eliminacion",'¿Estas Seguro de Eliminar el Usuario?', function(){ 
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
					alertify.success("Usuario eliminado Exitosamente ");
					window.location="<?=$_SERVER["PHP_SELF"]?>";
				}
			});	
		}, function(){ 
		alertify.error('Cancelado')});
	});
} );
</script>