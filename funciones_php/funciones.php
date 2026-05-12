<?
	include("../inc/conectar.php");
if($_POST['funcion']=='Carga_Precios'){
	$precio = 0;
	if($_POST['lista']!='' or $_POST['codigo']!=''){
		$sql= "SELECT lista".$_POST['lista']." FROM productos WHERE idproductos=".$_POST['codigo']."";
		$query = $consulta->query($sql);
		foreach ($query as $row);
		if($row[0]!=''){
			if($_POST['largo']>0){
				$precio = ($row[0]*($_POST['cantidad']*$_POST['largo']));
			}else{
				$precio = ($row[0]*$_POST['cantidad']);
			}
		}
	}
	echo $row[0]."|".number_format($precio,2);
}
if($_POST['funcion']=='Carga_Lista'){
	$lista = 6;
	$sql= "SELECT lista FROM clientes WHERE idclientes=".$_POST['idcliente']."";
	//echo $sql;
	$query = $consulta->query($sql);
	foreach ($query as $row);
	if($row[0]!='')$lista = $row[0];
	echo $lista;
}
function Folio($tabla,$idsucursales){
	include("../inc/conectar.php");
	if($idsucursales!=''){
		$Auto = $consulta->query("SELECT * FROM sucursales WHERE idsucursales=$idsucursales");
		foreach ($Auto as $Autocontador);
		$Auto = ($Autocontador["folio"]+1);
		$Auto = "T-".str_pad($Auto, 8, "0", STR_PAD_LEFT); 
		return $Auto;
	}else{
		$Auto = $consulta->query("SHOW TABLE STATUS Like '$tabla'");
		foreach ($Auto as $Autocontador);
		$Auto = $Autocontador["Auto_increment"];
		$Auto = str_pad($Auto, 6, "0", STR_PAD_LEFT); 
		return $Auto;
	}
}
?>