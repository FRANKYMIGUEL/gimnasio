<?php
echo "Limpiando<br>";
require('HikvisionService.php');
require('inc/conectar.php');

$ip = '192.168.101.50';
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
//inserto en la tabla limpieza la fecha de ejecucion del cron

$resultados = $consulta->query("INSERT INTO limpieza SET fecha = '".date('Y-m-d H:i:s')."'");
echo "Limpieza de usuarios en Hikvision completada.";
?>
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script>
$(document).ready(function(e) {
 cerrar() ;
  function cerrar() {
	   setTimeout(window.close,3000); }
});
</script>