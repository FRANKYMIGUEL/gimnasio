<?php

require_once '../HikvisionService.php';
require_once '../inc/conectar.php';

$ip = '192.168.18.102';
$username = 'admin';
$password = 'simbiosis2026';


$hikvision = new HikvisionService($ip, $username, $password);

$query = $consulta->query("SELECT idclientes, nombre FROM clientes WHERE fechaexpiracion < CURDATE() and dispositivo = 1 AND fechabaja IS NULL");

foreach ($query as $client) {
    $idregistro = $client['idclientes'];

    $response = $hikvision->deleteUser($idregistro);

    if ($response['status'] == 200) {
        $consulta->query('UPDATE clientes SET dispositivo = 0 WHERE idclientes = ' . $idregistro);
    }
}

// Se pueden agregar logs para saber cuantos se eliminaron x clientes
// Implementacion de logs para errores
