<?php
if($_POST["funcion"] == "Cron_Limpieza") {
   include("inc/conectar.php") ;
   $Limpiar ="Listo";
  //consulto si se ejecuto el cron de limpieza de usuarios en el hikvision
  $resultados = $consulta->query("SELECT * FROM limpieza WHERE fecha > CURDATE() ");
	foreach ($resultados as $row);
  if ($row['idlimpieza'] =='') {
    $Limpiar ="Limpiar";
  }
  echo $Limpiar;
  exit();
}
?>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Softsimbiosis</title>
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
  <main class="page-content">
    <div class="container-fluid" style="text-align: center;">
       
      <h2><img src="img/logo.jpg"></h2>
      <hr>
      <div class="row">
        <div class="form-group col-md-12">
        
          <p> <a href="https://www.softsimbiosis.com/" target="_blank" style="text-decoration-line: none;">Software: Softsimbiosis 2.0 <br><img src="img/logo1.png"></a></p>
        </div>
      </div>
      </div>
    </div>

  </main>

</div>
<script src="js/jquery-3.3.1.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
  $(document).ready(function() {
    function Cron_Limpieza() {
      $.ajax({
        type: "POST",
        url: "index.php",
        data: ({
          funcion: "Cron_Limpieza"
        }),
        dataType: "html",
        async: false,
        success: function(msg) {
          console.log(msg);
          if (msg == "Limpiar") {
            //abro en una nueva ventana el cron de limpieza de usuarios en el hikvision
            window.open("cron_limpieza.php", "_blank", "width=400,height=400");
          }
        }
      });
    }
    Cron_Limpieza();
  });
</script>
