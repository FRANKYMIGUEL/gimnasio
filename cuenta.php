<?
if($_POST["funcion"] == "EditarUsuario"){
	include("inc/conectar.php");
	$Auto = $consulta->query("UPDATE usuarios SET nombre = '".$_POST["nombre"]."', usuario = '".$_POST["usuario"]."', pass = '".$_POST["pass"]."', idsucursal = '".$_POST["sucursal"]."' WHERE idusuarios = '".$_POST["idusuarios"]."'");
	foreach ($Auto as $clientes);
		if ($Auto) {
			echo "Usuario modificado correctamente";
		}else{
			echo "Error al modificar";
		}
	exit();
}
include("inc/conectar.php");
$Auto = $consulta->query("SELECT * FROM  usuarios WHERE idusuarios = ".$_SESSION["SISTEMA"]["idusuarios"]);
foreach ($Auto as $datos);
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Imprimee</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="css/bootstrap.css">
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
<!--COTIZADOR-->
<div class="container-fluid">
	<div class="row">
    	<div class="col-md-2 text-left">
		<?
      include("menu.php");
        ?>
        </div>
        <div class="col-md-10">
           <div class="row" style="display: none;">
                <div class="col-md-offset-3 col-md-9">
                    <input type="text" id="idusuarios" value="<?= $_SESSION["SISTEMA"]["idusuarios"]?>">
                </div>
            </div>
            <div class="row">
                  <div class=" col-md-10 text-center">
                      <h3>Configurar Usuario</h3>
                  </div>
                  <br>
                    <div class="form-group col-md-5 col-md-offset-3">
                      <div class="input-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" placeholder="" id="nombre" value="<?=utf8_decode($datos["nombre"])?>">
                      </div>
                    </div>
                    
                    <div class="form-group col-md-5 col-md-offset-3">
                      <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-weight: bold">Usuario</span>
                        <input type="text" class="form-control" placeholder="" id="usuario" value="<?=$datos["usuario"]?>">
                      </div>
                    </div>
                    <div class="col-md-5 col-md-offset-3">
                        <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-weight: bold">Contraseña</span>
                        <input type="text" class="form-control" placeholder="" id="pass" value="<?=$datos["pass"]?>">
                      </div>
                    </div>
                    <div class="row">               
                         <div class=" col-md-10 text-center">
                              <br>
                          </div>
                    </div>
                  <div class="col-md-5 col-md-offset-3">
                     <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-weight: bold">Sucursal</span>
                        <select id="idsucursal" class="form-control" required>
                         <option disabled value="-1">Seleccione una sucursal</option>
                               <?
                                  $query = $consulta->query("SELECT * FROM sucursales WHERE activo = 1 ORDER BY idsucursales");
                                  foreach ($query as $row1){
                                    if ($row1["idsucursales"] == $datos["idsucursal"]) {
                                    ?>
                                     <option value="<?=$row1['idsucursales']?>" selected><?= $row1['nombre']?></option>
                                     <?
                                    }else{
                                      ?>
                                       <option value="<?=$row1['idsucursales']?>"><?= $row1['nombre']?></option>
                                       <?
                                    }
                              }  ?>
                        </select>
                    </div>
                  </div>
            </div>
            <div class="row">               
            	 <div class=" col-md-5 col-md-offset-3 text-center">
                      <hr>
                  </div>
			</div>
             <div class="row">               
            	 <div class=" col-md-5 col-md-offset-3 text-center">
                      <button class="btn btn-primary form-control" id="editarUsuario">Guardar cambios</button>
                  </div>
			</div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="alertifyjs/css/alertify.css">
<link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
<script src="alertifyjs/alertify.js"></script>
<script>
$(document).ready(function(e) {
    $(document).on('click', '#editarUsuario', function() {
		$.ajax({
			type: "POST",
			url: "confUsuario.php",
			data:({
				funcion: "EditarUsuario",
				nombre: $("#nombre").val(),
				usuario: $("#usuario").val(),
				pass: $("#pass").val(),
				idsucursal: $("#idsucursal option:selected").val(),
				idusuarios : $("#idusuarios").val()
			}),
			dataType: 'html',
			success:function(msg){
				alertify.success(""+msg);
				//window.location.reload(false);
			}
		});
	});

});
</script>
</body>
</html>