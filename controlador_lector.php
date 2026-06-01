<?php

class HikvisionReaderXML
{
    private string $ip;
    private string $usuario;
    private string $password;
    
    // El namespace obligatorio para el firmware ISAPI de Hikvision
    private string $xmlns = 'xmlns="http://www.isapi.org/ver20/XMLSchema" version="2.0"';

    public function __construct(string $ip, string $usuario, string $password)
    {
        $this->ip = $ip;
        $this->usuario = $usuario;
        $this->password = $password;
    }

    private function ejecutarPeticion(string $method, string $endpoint, $payload = null, array $headers = [])
    {
        $ch = curl_init();
        $url = "http://{$this->ip}{$endpoint}";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        // Autenticación Digest Nativa
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->usuario}:{$this->password}");

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($payload) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'code' => $httpCode,
            'body' => $respuesta
        ];
    }

    public function agregarUsuarioInfo(string $employeeNo, string $nombre): bool
    {
        // Uso de Heredoc (<<<XML) para mantener el esquema limpio y legible
        $xmlPayload = <<<XML
        <UserInfo {$this->xmlns}>
            <employeeNo>{$employeeNo}</employeeNo>
            <name>{$nombre}</name>
            <userType>normal</userType>
            <Valid>
                <enable>true</enable>
                <beginTime>2024-01-01T00:00:00</beginTime>
                <endTime>2035-12-31T23:59:59</endTime>
            </Valid>
            <doorRight>1</doorRight>
            <RightPlan>
                <RightPlanItem>
                    <doorNo>1</doorNo>
                    <planTemplateNo>1</planTemplateNo>
                </RightPlanItem>
            </RightPlan>
        </UserInfo>
        XML;

        $headers = ['Content-Type: application/xml'];

        //imprimo la peticion
            echo "xml " . $xmlPayload. "\n";
            echo "headers " . implode(", ", $headers) . "\n";

        try {
            $respuesta = $this->ejecutarPeticion('POST', '/ISAPI/AccessControl/UserInfo/Record', $xmlPayload, $headers);
        } catch (Exception $e) {
            //imprimo la peticion para debug
             echo "HTTP Code: " . $respuesta['code'] . "\n";
             echo "Response Body: " . $respuesta['body'] . "\n";

            echo "Error al agregar usuario: " . $e->getMessage();
            //imprimo el error para debug
             echo "HTTP Code: " . $respuesta['code'] . "\n";
             echo "Response Body: " . $respuesta['body'] . "\n";
            return false;
        }

           


        return $respuesta['code'] === 200;
    }


    public function capturarFotoEnVivo()
    {
        $respuesta = $this->ejecutarPeticion('GET', '/ISAPI/Streaming/channels/1/picture');
        
        if ($respuesta['code'] === 200) {
            return $respuesta['body'];
        }
        return false;
    }


    public function asignarRostro(string $employeeNo, string $imagenBytes): bool {
        $xmlFaceData = <<<XML
        <FaceDataRecord {$this->xmlns}>
            <faceLibType>blackFD</faceLibType>
            <FDID>1</FDID>
            <FPID>{$employeeNo}</FPID>
        </FaceDataRecord>
        XML;

        // Construcción manual del Multipart para garantizar los Content-Type internos
        $boundary = "----HikvisionBoundary" . md5(uniqid());
        
        $body = "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"FaceDataRecord\"\r\n";
        $body .= "Content-Type: application/xml\r\n\r\n";
        $body .= $xmlFaceData . "\r\n";
        
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"FaceImage\"; filename=\"rostro.jpg\"\r\n";
        $body .= "Content-Type: image/jpeg\r\n\r\n";
        $body .= $imagenBytes . "\r\n";
        $body .= "--{$boundary}--\r\n";

        $headers = [
            "Content-Type: multipart/form-data; boundary={$boundary}",
            "Content-Length: " . strlen($body)
        ];
        try {
            $respuesta = $this->ejecutarPeticion('POST', '/ISAPI/Intelligent/FDLib/FaceDataRecord', $body, $headers);
        } catch (Exception $e) {
            echo "Error al asignar rostro: " . $e->getMessage();
            return false;
        }
       
        // cacho de código para debug
         echo "HTTP Code: " . $respuesta['code'] . "\n";
         echo "Response Body: " . $respuesta['body'] . "\n";

        return $respuesta['code'] === 200;
    }

    public function agregarUsuario(string $idUsuario, string $nombre) {
        $this->agregarUsuarioInfo($idUsuario, $nombre);
        //$img = $this->capturarFotoEnVivo();
        //$this->asignarRostro($idUsuario, $img);
    }

}