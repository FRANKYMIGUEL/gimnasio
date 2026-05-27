<?php
/**
 * Conexión a Terminal Hikvision DS-K1T342MFwx-E1
 * Envío de datos de usuario nuevo
 */

class HikvisionTerminal {
    private $ip;
    private $puerto;
    private $usuario;
    private $contrasena;
    
    public function __construct($ip = '192.168.1.100', $puerto = 8000, $usuario = 'admin', $contrasena = 'admin') {
        $this->ip = $ip;
        $this->puerto = $puerto;
        $this->usuario = $usuario;
        $this->contrasena = $contrasena;
    }
    
    /**
     * Conectar a la terminal
     */
    private function conectar() {
        $url = "http://{$this->ip}:{$this->puerto}/";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->usuario}:{$this->contrasena}");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        return $ch;
    }
    
    /**
     * Agregar nuevo usuario
     * @param array $datosUsuario - Datos del usuario a registrar
     */
    public function agregarUsuario($datosUsuario) {
        $ch = $this->conectar();
        
        // Estructura XML para envío de usuario a Hikvision
        $xml = $this->construirXmlUsuario($datosUsuario);
        
        $url = "http://{$this->ip}:{$this->puerto}/ISAPI/AccessControl/UserInfo/Create";
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/xml',
            'Content-Length: ' . strlen($xml)
        ));
        
        $respuesta = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return array(
            'codigo' => $httpcode,
            'respuesta' => $respuesta,
            'exito' => ($httpcode == 200 || $httpcode == 201)
        );
    }
    
    /**
     * Construir XML para envío de usuario
     */
    private function construirXmlUsuario($datos) {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<UserInfo>' . "\n";
        $xml .= '  <employeeNo>' . htmlspecialchars($datos['empleadoNo'] ?? '') . '</employeeNo>' . "\n";
        $xml .= '  <name>' . htmlspecialchars($datos['nombre'] ?? '') . '</name>' . "\n";
        $xml .= '  <userType>' . htmlspecialchars($datos['tipoUsuario'] ?? 'Normal') . '</userType>' . "\n";
        $xml .= '  <closeDelayRequired>false</closeDelayRequired>' . "\n";
        $xml .= '  <doorUnlockComValue>0</doorUnlockComValue>' . "\n";
        $xml .= '  <Card>' . "\n";
        $xml .= '    <cardNo>' . htmlspecialchars($datos['numeroTarjeta'] ?? '') . '</cardNo>' . "\n";
        $xml .= '    <cardStatus>Normal</cardStatus>' . "\n";
        $xml .= '  </Card>' . "\n";
        $xml .= '  <Biometric>' . "\n";
        $xml .= '    <fingerPrintCount>0</fingerPrintCount>' . "\n";
        $xml .= '    <faceCount>0</faceCount>' . "\n";
        $xml .= '  </Biometric>' . "\n";
        $xml .= '</UserInfo>' . "\n";
        
        return $xml;
    }
}

// Ejemplo de uso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datosUsuario = array(
        'empleadoNo' => $_POST['empleadoNo'] ?? '001',
        'nombre' => $_POST['nombre'] ?? 'Usuario Nuevo',
        'tipoUsuario' => $_POST['tipoUsuario'] ?? 'Normal',
        'numeroTarjeta' => $_POST['numeroTarjeta'] ?? '123456789'
    );
    
    $terminal = new HikvisionTerminal('192.168.1.100', 8000, 'admin', 'admin');
    $resultado = $terminal->agregarUsuario($datosUsuario);
    
    echo json_encode($resultado);
}
?>
