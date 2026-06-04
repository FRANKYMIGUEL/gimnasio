<?php

include("inc/conectar.php");
$Auto = $consulta->query("INSERT INTO puerta SET fecha='".date("Y-m-d H:i:s")."', motivo='Apertura Manual desde el Sistema', idusuarios='".$_SESSION['SISTEMA']['idusuarios']."'");
foreach ($Auto as $Autocontador);
$host = "192.0.0.64"; // IP del biométrico
$username = "admin";
$password = "simbiosis2026"; // Contraseña del biométrico



try {
    $url = "http://$host/ISAPI/AccessControl/RemoteControl/door/1"; // Endpoint para la puerta 1

    // Cuerpo del XML requerido por Hikvision para abrir la puerta
    $xmlBody = '<?xml version="1.0" encoding="utf-8"?>
<RemoteControlDoor xmlns="http://www.isapi.org/ver20/XMLSchema" version="2.0">
    <cmd>open</cmd>
</RemoteControlDoor>';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Hikvision usa PUT para comandos
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlBody);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Configurar Autenticación Digest obligatoria para Hikvision
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/xml',
        'Content-Length: ' . strlen($xmlBody)
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        echo "Puerta abierta exitosamente.";
    } else {
        echo "Error al abrir la puerta. Código HTTP: " . $httpCode;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}