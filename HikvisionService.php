<?php
class HikvisionService
{
    private string $ip;
    private string $username;
    private string $password;

    public function __construct(string $ip, string $username, string $password)
    {
        $this->ip = $ip;
        $this->username = $username;
        $this->password = $password;
    }

    // Encargado de realizar las consultas
    private function request(string $method, string $endpoint, ?array $body = null): array
    {
        //Creacion de la ip mas endpoint
        $url = "http://{$this->ip}{$endpoint}";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "{$this->username}:{$this->password}",
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'Content-Type : application/json'
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5
        ]);

        if ($body !== null) {
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                json_encode($body, JSON_UNESCAPED_UNICODE)
            );
        }

        $response = curl_exec($ch);

        $httpCode = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        if (curl_errno($ch)) {
            throw new Exception(
                curl_error($ch)
            );
        }

        curl_close($ch);

        return [
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }

    // Funcion para realizar request con multiples partes (imagenes)
    private function requestMultipart(string $method, string $endpoint, array $postFields): array
    {
        $url = "http://{$this->ip}{$endpoint}";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "{$this->username}:{$this->password}",
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $postFields
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        $decoded = json_decode($response, true);

        return [
            'status' => $httpCode,
            'data' => $decoded !== null ? $decoded : $response
        ];
    }

    // Obtener numero total de usuarios registrados
    public function getUserCount(): array
    {
        return $this->request(
            'GET',
            '/ISAPI/AccessControl/UserInfo/Count'
        );
    }

    // Obtener todos los usuarios
    public function getUsers(int $position = 0, int $maxResults = 30): array
    {
        return $this->request(
            'POST',
            '/ISAPI/AccessControl/UserInfo/Search?format=json',
            [
                'UserInfoSearchCond' => [
                    'searchID' => '1',
                    'searchResultPosition' => $position,
                    'maxResults' => $maxResults
                ]
            ]
        );
    }

    // Creacion de un nuevo usuario (SIN FOTO)
    public function createUser(string $employeeNo, string $name, string $beginTime, string $endTime): array
    {
        return $this->request(
            'POST',
            '/ISAPI/AccessControl/UserInfo/Record?format=json',
            [
                'UserInfo' => [
                    'employeeNo' => $employeeNo,
                    'name' => $name,
                    'userType' => 'normal',
                    'doorRight' => '1',
                    'Valid' => [
                        'enable' => true,
                        'beginTime' => $beginTime,
                        'endTime' => $endTime,
                        'timeType' => 'local'
                    ]
                ]
            ]
        );
    }

    // Eliminación del usuario
    public function deleteUser(string $employeeNo): array
    {
        return $this->request(
            'PUT',
            '/ISAPI/AccessControl/UserInfo/Delete?format=json',
            [
                'UserInfoDelCond' => [
                    'EmployeeNoList' => [
                        [
                            'employeeNo' => $employeeNo
                        ]
                    ]
                ]
            ]
        );
    }
    public function uploadUserFace(string $employeeNo, string $imagePath): array
    {
        $endpoint = '/ISAPI/AccessControl/UserInfo/Face?format=json';
        $url = "http://{$this->ip}{$endpoint}";

        // 1. Crear el JSON de asociación
        $faceData = json_encode([
            'FaceInfoInfo' => [
                'employeeNo' => $employeeNo,
                'faceLibType' => 'blackSheet' // Tipo por defecto en Hikvision
            ]
        ]);

        // 2. Preparar los datos multipart (CURLFile requiere PHP 5.5+)
        $postData = [
            'FaceInfoInfo' => $faceData,
            'img' => new CURLFile($imagePath, 'image/jpeg', 'face.jpg')
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "{$this->username}:{$this->password}",
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            // Al enviar un array en CURLOPT_POSTFIELDS, cURL configura automáticamente el Content-Type a multipart/form-data
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('Error cURL:' . curl_error($ch));
        }
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Error HTTP {$httpCode}. Respuesta: " . $response);
        }

        return json_decode($response, true);
    }
    //actualizo fecha de expiracion del usuario
    public function updateUserExpiration(string $employeeNo, string $beginTime, string $endTime): array
    {
        return $this->request(
            'PUT', // Mantenemos PUT
            '/ISAPI/AccessControl/UserInfo/Modify?format=json', // <--- Cambiado de Record a Modify
            [
                'UserInfo' => [
                    'employeeNo' => $employeeNo,
                    // En el endpoint /Modify no siempre es necesario el campo 'mode'
                    'Valid' => [
                        'enable' => true,
                        'beginTime' => $beginTime,
                        'endTime' => $endTime,
                        'timeType' => 'local'
                    ]
                ]
            ]
        );
    }

    // Buscar usuario especifico por ID
    public function getUserByID(string $employeeNo): array
    {
        return $this->request(
            'POST',
            '/ISAPI/AccessControl/UserInfo/Search?format=json',
            [
                'UserInfoSearchCond' => [
                    'searchID' => '1',
                    'searchResultPosition' => 0,
                    'maxResults' => 1,
                    'EmployeeNoList' => [
                        [
                            'employeeNo' => $employeeNo
                        ]
                    ]
                ]
            ]
        );
    }



    // Captura de imagenes sin guardar mediante el terminal hikvision
    public function getLivePicture(): string
    { // Quitamos el parámetro que no se usaba
        $endpoint = '/ISAPI/Streaming/channels/101/picture';
        $url = "http://{$this->ip}{$endpoint}";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "{$this->username}:{$this->password}",
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('Error cURL:' . curl_error($ch));
        }
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Error HTTP {$httpCode}. Asegúrate de que el canal 101 exista (Stream principal).");
        }

        return $response;
    }

    public function captureAndSaveFace(string $employeeNo): array
    {
        $endpoint = '/ISAPI/AccessControl/CaptureFace?format=json';
        $url = "http://{$this->ip}{$endpoint}";

        // El JSON que Hikvision espera para activar la cámara del lector
        $payload = json_encode([
            'CaptureFace' => [
                'employeeNo' => $employeeNo
            ]
        ]);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "{$this->username}:{$this->password}",
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('Error cURL:' . curl_error($ch));
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Error HTTP {$httpCode}. Respuesta del equipo: " . $response);
        }

        return json_decode($response, true);
    }

    // Toma de la captura para despues ser guardada con el numero de empleado
    public function takeLivePicture(string $employeeNo): bool
    {
        $savePath = __DIR__ . "/capturas/rostro{$employeeNo}.jpg";

        $image = $this->getLivePicture();

        $saved = file_put_contents(
            $savePath,
            $image
        );

        return $saved !== false;
    }



    // asignacion de foto
    public function updateFace(string $employeeNo): array
    {
        $imagePath = __DIR__ . "/capturas/rostro{$employeeNo}.jpg";
        if (!file_exists($imagePath)) {
            throw new Exception("La imagen no existe en la ruta especificada: {$imagePath}");
        }

        $faceDataInfo = [
            'faceLibType' => 'blackFD',
            'FDID' => '1',
            'FPID' => $employeeNo
        ];

        $postFields = [
            'FaceDataRecord' => json_encode($faceDataInfo, JSON_UNESCAPED_UNICODE),
            'FaceImage' => new CURLFile($imagePath, 'image/jpeg', "rostro_{$employeeNo}.jpg")
        ];

        return $this->requestMultipart(
            'POST',
            '/ISAPI/Intelligent/FDLib/FaceDataRecord?format=json',
            $postFields
        );
    }
    public function opendoor(string $employeeNo): array
    {
        return $this->request(
            'POST',
            '/ISAPI/AccessControl/RemoteControl/doorTrigger?format=json',
            [
                'DoorTrigger' => [
                    'employeeNo' => $employeeNo,
                    'doorIndexCode' => '1',
                    'controlType' => 'open'
                ]
            ]
        );
    }

    // Actualización de datos generales del usuario (Nombre y Vigencia)
    public function updateUser(string $employeeNo, string $name, string $beginTime, string $endTime): array
    {
        return $this->request(
            'PUT',
            '/ISAPI/AccessControl/UserInfo/Modify?format=json',
            [
                'UserInfo' => [
                    'employeeNo' => $employeeNo,
                    'name' => $name,
                    'userType' => 'normal',
                    'Valid' => [
                        'enable' => true,
                        'beginTime' => $beginTime,
                        'endTime' => $endTime,
                        'timeType' => 'local'
                    ]
                ]
            ]
        );
    }


    public function probar(string $endpoint, string $method = 'GET'): array
    {
        return $this->request(
            $method,
            $endpoint
        );
    }


}

