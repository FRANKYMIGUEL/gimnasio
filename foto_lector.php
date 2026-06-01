<?php
$host = "192.168.1.79"; // IP de tu biométrico
$username = "admin";
$password = "simbiosis2026"; // Contraseña del biométrico

// Obtener parámetros del formulario
$employeeNo = isset($_POST['employeeNo']) ? trim($_POST['employeeNo']) : '';

// Si no hay datos POST, mostrar formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($employeeNo)) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Capturar Rostro a Usuario</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1>Capturar Rostro a Usuario Registrado</h1>
        <p>Asegúrese de que el usuario esté parado frente al lector antes de presionar el botón.</p>
        <form method="POST">
            <label for="employeeNo">ID del Empleado (employeeNo):</label>
            <input type="text" name="employeeNo" id="employeeNo" required>
            <br><br>
            <button type="submit">Capturar Rostro Ahora</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// =========================================================================
// PASO 1: PASAR LA ORDEN AL LECTOR PARA CAPTURAR EL ROSTRO DESDE SU CÁMARA
// =========================================================================
$captureUrl = "http://$host/ISAPI/AccessControl/CaptureFaceData?format=json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $captureUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Darle tiempo al usuario de acomodarse

$rawImage = curl_exec($ch);
$captureHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Validar si el dispositivo pudo capturar el rostro
if ($captureHttpCode !== 200 || empty($rawImage)) {
    try {
        $errorResponse = json_decode($rawImage, true);
        if (isset($errorResponse['errorMessage'])) {
            die("Error del dispositivo: " . htmlspecialchars($errorResponse['errorMessage']));
        }
    } catch (Exception $e) {
        //imprimo la respuesta del dispositivo para depuración
        echo "Respuesta del dispositivo: " . htmlspecialchars($rawImage) . "<br>";
        die("Error desconocido al capturar el rostro. Código HTTP: " . $captureHttpCode);
        
        // Si no es un JSON válido, mostrar el error genérico
    }
}

// Guardamos temporalmente el binario de la imagen en el servidor
$tempImagePath = __DIR__ . "/temp_face_" . $employeeNo . ".jpg";
file_put_contents($tempImagePath, $rawImage);


// =========================================================================
// PASO 2: ASIGNAR LA IMAGEN CAPTURADA AL ID DEL USUARIO
// =========================================================================
$faceUrl = "http://$host/ISAPI/AccessControl/SnapshotConfig/Face?format=json";

// JSON de vinculación requerido por Hikvision
$faceDataJson = json_encode([
    "FaceInfoData" => [
        "employeeNo" => $employeeNo
    ]
]);

// Preparar el cuerpo Multipart
$postFields = [
    'FaceInfoData' => $faceDataJson,
    'img' => new CURLFile($tempImagePath, 'image/jpeg', 'face.jpg')
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $faceUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

$faceResponse = curl_exec($ch);
$faceHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Borrar la foto temporal por seguridad y limpieza
if (file_exists($tempImagePath)) {
    unlink($tempImagePath);
}

// =========================================================================
// RESPUESTA FINAL
// =========================================================================
if ($faceHttpCode == 200) {
    echo "<h2>¡Éxito!</h2> Rosto capturado por el lector y asignado correctamente al empleado ID: " . htmlspecialchars($employeeNo);
    echo "<br><br><a href=''>Volver a registrar otro</a>";
} else {
    echo "<h2>Error en la asignación</h2> Código HTTP del dispositivo: " . $faceHttpCode . "<br>";
    echo "Respuesta del lector: <pre>" . htmlspecialchars($faceResponse) . "</pre>";
    echo "<br><a href=''>Intentar de nuevo</a>";
}