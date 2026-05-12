<?php

$codigo = $_GET["codigo"];
$cadena = $codigo;
$array = explode(",", $cadena);
//echo $array[0];
$cnt=count($array);
$contado =array_chunk($array, 2);
$codi=0;
$canti=0;
for($x=0;$x<=$cnt;$x++){
    $valor = $contado[$codi][0];

    for ($i = 0; $i < $contado[$codi][1]; $i++) {
        ?><img data-value="<?php echo $valor ?>" data-text="<?php echo $valor ?>" class="codigo" />  <br>
        <?php
    }$codi++;
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

    JsBarcode(".codigo").init();
    cerrar();
function cerrar() {
  window.print();
  setTimeout(window.close, 3000);
}
</script>

</html>
