<?php
@session_start();
date_default_timezone_set('America/Mexico_City');
//$usuario="softsimbiosis_bebidas";
//$contrasena="SYNgw8^XqX0x";
//$consulta= new PDO('mysql:host=localhost;dbname=softsimbiosis_bebidas', $usuario, $contrasena);
$usuario = "root";
$contrasena = "miguelangel12";
$consulta = new PDO('mysql:host=localhost;dbname=gimnasio', $usuario, $contrasena);
error_reporting(0);
?>