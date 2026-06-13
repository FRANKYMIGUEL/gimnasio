<?php

include("HikvisionService.php");
include("inc/conectar.php");
error_reporting(E_ALL);
$controlador = new HikvisionService('192.168.101.50', 'admin', 'simbiosis2026');

$resultado = $consulta->query("SELECT idclientes, nombre, fechaexpiracion FROM clientes WHERE dispositivo = 0 AND fechabaja IS NULL LIMIT 20");

foreach ($resultado as $cliente) {
    $controlador->createUser($cliente['idclientes'], $cliente['nombre'], $cliente['fechaexpiracion'], $cliente['fechaexpiracion']);
    print_r($cliente['idclientes'] . " " . $cliente['nombre'] . " " . $cliente['fechaexpiracion']);
    print_r("</br>");
	$consulta->query("UPDATE clientes SET dispositivo = 1 WHERE idclientes = " . $cliente['idclientes']);
	sleep(2);
}

