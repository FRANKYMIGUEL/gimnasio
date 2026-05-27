<?php
if($_POST["funcion"]=="Valida_Usuario"){
include("inc/conectar.php");
	$Auto = $consulta->query("SELECT * FROM usuarios WHERE usuario LIKE '".$_POST["usuario"]."' AND pass LIKE '".$_POST["contraseña"]."'");
	foreach ($Auto as $row);
	if ($row['usuario']!="" ) {
		echo "admin";
		$_SESSION['SISTEMA']['idusuarios'] = $row['idusuarios'];
		$_SESSION['SISTEMA']['usuario'] = $row['usuario'];
	}else {
		echo "Incorrecto";
	}
  exit();
}
 ?>
<!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
        <link rel="stylesheet" href="css/login.css">
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Softsimbiosis</title>
    </head>
    <body >
      <section class="login-block">
        <div class="container">
	         <div class="row">
		           <div class="col-md-4 login-sec">
		               <h2 class="text-center">Bienvenidos</h2>
                   <div class="">
                     <b><i class="fas fa-user-circle"></i> &nbsp;Usuario:</b>
                     <input type="text" class="form-control"  name="usuario" id="usuario" placeholder="Nombre de Usuario" required>
                   </div>
                   <div class=""><br>
                     <b>&nbsp;Contraseña:</b>
                     <input type="password" class="form-control" name="contraseña" id="contraseña" placeholder="Contraseña"><br>
                    </div>
                    <div class="form-check">
                      <button type="submit" id="ingresar" class="btn btn-success float-right">Ingresar</button>
                    </div>
                    <div class="copy-text">  ® 2026 Simbiosis</div>
		                </div>
		                  <div class="col-md-8 banner-sec">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                          <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" link="" id="bot" value="1" class=""></li>
                          </ol>
                        <div class="carousel-inner" align="center" role="listbox">
                            <div class="carousel-item active">
    
                          <img class="d-block img-fluid" src="img/logo.jpg" height="200" alt="First slide">
                      </div>
                  </div>
		            </div>
	          </div>
         </div>
      </section>
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-3.3.1.js"></script>
    <script src="alertifyjs/alertify.js"></script>
    </body>

<script type="text/javascript">
$(document).ready(function(e) {
    $(document).on("click","#bot",function(){
	  window.location="index.php";
   });
	$(document).keypress(function(e) {
		if(e.which == 13) {
			if($("#usuario").val()==""){
				alertify.error('Ingresa Un usuario');
				$("#usuario").focus();
				return false;
			}
			 if($("#contraseña").val()==""){
				alertify.error('Ingresa Una contraseña');
			  $("#contraseña").focus();
				return false;
			}
			Validar();
		}
	});
  
  
	$(document).on("click","#ingresar",function(){
		if($("#usuario").val()==""){
			alertify.error('Ingresa Un usuario');
			$("#usuario").focus();
			return false;
		}
		 if($("#contraseña").val()==""){
			alertify.error('Ingresa Una contraseña');
		  $("#contraseña").focus();
			return false;
		}
		Validar();
	});	
	function Validar(){
	  $.ajax({
		type: "POST",
		url: "<?=$_SERVER["PHP_SELF"]?>",
		data: ({
		  funcion : "Valida_Usuario",
		  usuario : $ ("#usuario").val(),
		  contraseña : $ ("#contraseña").val()
		}),
		dataType: "html",
		async:false,
		success: function(msg){
		  if (msg=="admin") {
			alertify.success('Bienvenido admin');
			window.location="index.php";
		  }else{
			alertify.error('Error al ingresar');
			$("#usuario").val("");
			$("#contraseña").val("");
			$("#usuario").focus();
		  }
		}
	  });
	}

});
</script>
</html>
