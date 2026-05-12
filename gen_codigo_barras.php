<?php

$codigo = $_GET["codigo"];
$cadena = $codigo;
$array = explode(",", $cadena);
$cantidad = $array[1];

if ($_POST["funcion"] == "valor") {
    echo $array[0];
}
for ($i = 1; $i <= $cantidad; $i++) {

    ?><img data-value="<?php echo $array[0] ?>" data-text="<?php echo $array[0] ?>" class="codigo" />
    <br>
    <?php
}

    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <title>Generar codigo de Barras</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="alertifyjs/css/alertify.css">
    <link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
    <script src="alertifyjs/alertify.js"></script>
    <script type="text/javascript" src="js/JsBarcode.all.min.js"></script>

</head>

<body>


    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/mdb.js"></script>
    <link rel="stylesheet" href="alertifyjs/css/alertify.css">
    <link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
    <script src="alertifyjs/alertify.js"></script>
    <script type="text/javascript" src="js/JsBarcode.all.min.js"></script>
</body>
<script>
     var valor="";
     codigo();
    function codigo(){
        $.ajax({
					type: "POST",
					url: "<?= $_SERVER["PHP_SELF"] ?>",
					data: ({
						funcion: "valor"
					}),
					dataType: "html",
					async: false,
					success: function(msg) {
						msg = valor;

					}
				});
    }
    JsBarcode(".codigo").init();
    cerrar();
  var num = Math.round(Math.random() * (10000 - 1) + 1);
  JsBarcode(".codigo", valor, {
    format: "pharmacode",
  lineColor: "#0aa",
  width: 4,
  height: 40,
  displayValue: false
});
function cerrar() {
  window.print();
  setTimeout(window.close, 3000);
}
</script>

</html>