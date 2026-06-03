<?
	$mysqli = new mysqli("localhost","root","12345678","losinges_noname");

	$usuario="root";
	$contrasena="12345678";
	$consulta= new PDO('mysql:host=localhost;dbname=losinges_noname', $usuario, $contrasena);
	
?>