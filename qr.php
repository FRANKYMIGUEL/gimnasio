
    
   <head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Codgigo Clientes</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
 <?
include("inc/conectar.php");
$Auto = $consulta->query("SELECT * FROM clientes WHERE codigo=".$_GET['codigo']."");
			foreach ($Auto as $row);
?>
    <!-- jQuery y el plugin jquery.qrcode.js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.qrcode.min.js"></script>
    
    <!-- Archivo JS Personalizado -->  
    <div class="container-fluid" style="margin-top:5px;">
		  <div class="row">
        <div class="col-md-4">
          
        </div>
			  <div class="col-md-4 text-center">
          <h3>Codigo: <?=$_GET['codigo']?></h3><br>
          <h3>Nombre: <?=$row['nombre']?></h3>
        <div id="qrcodeholder"></div> 
        </div>
        <div class="col-md-4">
          
        </div>
        <div class="col-md-5">
          
        </div>
        <div class="col-md-2 text-center">
          <h3>Codigo Cliente</h3>
          <input type="text" value="<?=$_GET['codigo']?>" id="codigo" readonly class="form-control">
          <a href="clientes.php"><button class="form-control">Regresar a Clientes</button></a>
        </div>
			</div>
  </body>
<script>
 
 $(document).ready(function(){
    
    $('#qrcodeholder').qrcode({ 
        text    : $("#codigo").val()+"|Software: softimbiosis TECNOLOGICO SUPERIOR DE JALISCO", // URL a enlazar
        render  : "canvas", // Elemento en donde se mostrará el código QR. También puedes usar la opción 'table' 
        background : "#ffffff", // Color de fondo
        foreground : "#000000", // Color de los fragmentos del código QR 
        width : 300, // Ancho
        height: 300
    });
});
  </script>
</html>