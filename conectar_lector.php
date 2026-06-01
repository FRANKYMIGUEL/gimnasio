<?php
$host = "192.168.1.100"; // IP de tu biométrico
$username = "admin";
$password = "TuPasswordSeguro123";

$url = "http://$host/ISAPI/AccessControl/UserInfo/Record?format=json";

// Estructura JSON para crear el usuario
$userData = [
    "UserInfo" => [
        "employeeNo" => "1025",      // ID único del empleado (debe ser un string numérico)
        "name" => "Carlos Mendoza",  // Nombre del usuario
        "userType" => "normal",      // Tipo: normal o admin
        "Valid" => [
            "enable" => true,
            "beginTime" => "2026-01-01T00:00:00", // Fecha inicio de vigencia
            "endTime" => "2036-12-31T23:59:59"    // Fecha fin de vigencia
        ],
        "doorRight" => "1", // Permiso para abrir la puerta 1
        "RightPlan" => [
            [
                "doorNo" => 1,
                "planNo" => 1 // Plan horario por defecto (24/7)
            ]
        ]
    ]
];

$jsonBody = json_encode($userData);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonBody)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "Paso 1 Exitoso: Usuario base creado en el lector.<br>";
} else {
    echo "Error en Paso 1. Código HTTP: " . $httpCode . " Respuesta: " . $response;
    exit;
}