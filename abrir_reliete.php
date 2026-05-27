<?php
// Configuración del lector Hikvision DS-K1T342MFWX-E1
$hikvision_ip = '192.168.1.100'; // IP del lector
$hikvision_port = 8000; // Puerto (por defecto 8000)
$hikvision_user = 'admin'; // Usuario
$hikvision_password = 'admin'; // Contraseña
$relay_id = 1; // ID del relé a abrir

// Función para abrir el relé del lector Hikvision
function abrirRelieте($ip, $port, $user, $password, $relay_id) {
    try {
        // URL para controlar el relé
        $url = "http://{$ip}:{$port}/ISAPI/AccessControl/RemoteOpenDoor";
        
        // Datos XML para la solicitud
        $xml_data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xml_data .= "<RemoteOpenDoorRequest>";
        $xml_data .= "<RemoteOpenDoorRequestParam>";
        $xml_data .= "<SerialNumber>1</SerialNumber>";
        $xml_data .= "<RemoteOpenDoorID>{$relay_id}</RemoteOpenDoorID>";
        $xml_data .= "</RemoteOpenDoorRequestParam>";
        $xml_data .= "</RemoteOpenDoorRequest>";
        
        // Inicializar cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, "{$user}:{$password}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/xml',
            'Content-Length: ' . strlen($xml_data)
        ));
        
        // Ejecutar solicitud
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // Verificar respuesta
        if ($http_code == 200 || $http_code == 201) {
            return array('success' => true, 'message' => 'Relé abierto correctamente');
        } else {
            return array('success' => false, 'message' => "Error: HTTP {$http_code}", 'details' => $error);
        }
    } catch (Exception $e) {
        return array('success' => false, 'message' => 'Excepción: ' . $e->getMessage());
    }
}

// Procesar solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = abrirRelieте($hikvision_ip, $hikvision_port, $hikvision_user, $hikvision_password, $relay_id);
    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// Interfaz HTML (opcional)
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Control de Relé - Hikvision</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; }
        button { padding: 15px 30px; font-size: 18px; cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 5px; }
        button:hover { background-color: #45a049; }
        #response { margin-top: 20px; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Control de Relé - Lector Hikvision</h1>
    <button onclick="abrirRele()">Abrir Relé</button>
    <div id="response"></div>
    
    <script>
        function abrirRele() {
            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                let div = document.getElementById('response');
                div.textContent = data.message;
                div.className = data.success ? 'success' : 'error';
            })
            .catch(error => {
                document.getElementById('response').textContent = 'Error: ' + error;
                document.getElementById('response').className = 'error';
            });
        }
    </script>
</body>
</html>
