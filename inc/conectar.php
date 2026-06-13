<?php
$tiempo_vida = 72000;

// 2. Cambiar el tiempo de vida de la cookie de sesión en el navegador
ini_set('session.cookie_lifetime', $tiempo_vida);

// 3. Cambiar el tiempo permitido de inactividad en el servidor antes de que se borre
ini_set('session.gc_maxlifetime', $tiempo_vida);
@session_start();

date_default_timezone_set('America/Mexico_City');
//$usuario="softsimbiosis_bebidas";
//$contrasena="SYNgw8^XqX0x";
//$consulta= new PDO('mysql:host=localhost;dbname=softsimbiosis_bebidas', $usuario, $contrasena);
$usuario = "root";
$contrasena = "";
$consulta = new PDO('mysql:host=localhost;dbname=gimnasio', $usuario, $contrasena);

?>