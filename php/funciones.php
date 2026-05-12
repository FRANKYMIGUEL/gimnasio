<?
if($_POST['funcion']=='Carga_Productos'){
include('../inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM productos WHERE inactivo IS NULL");
	foreach ($Auto as $row){
		?>
		<tr>
			<td class="text-uppercase text-center"><?=$row['codigo']?></td>
			<td class="text-uppercase text-center"><?=$row['nombre']?></td>
			<td class="text-uppercase text-right">$ <?=number_format($row['costo'],2)?></td>
			<td class="text-uppercase text-center"><?=$row['utilidad']?>%</td>
			<td class="text-uppercase text-right">$ <?= number_format($row['precio'],2)?></td>
			<td class="text-uppercase text-right"><?=$row['existencias']?></td>
			<td class="text-center" width="220">
				<button type="button" style="margin:0;" class="btn btn-primary btn-sm editar" registros="<?=$row['idproductos']?>">Editar</button>
				<button type="button"  style="margin:0;" class="btn btn-danger btn-sm eliminar" registros="<?=$row['idproductos']?>">Eliminar</button>
			</td>
		</tr>
		<?
	}
	exit();
}
if($_POST['funcion']=='Carga_Clientes'){
include('../inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM clientes WHERE inactivo IS NULL");
	foreach ($Auto as $row){
		?>
		<tr>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['nombre']?></td>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['domicilio']?></td>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['ciudad']?></td>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['telefono']?></td>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['observaciones']?></td>
			<td  style=" height: 1px;" class="text-center" width="220">
				<button type="button" style="margin:0;" class="btn btn-primary btn-sm editar" registros="<?=$row[0]?>">Editar</button>
				<button type="button" style="margin:0;" class="btn btn-danger btn-sm eliminar" registros="<?=$row[0]?>">Eliminar</button>
			</td>
		</tr>
		<?
	}
	exit();
}
if($_POST['funcion']=='Carga_Usuarios'){
include('../inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM usuarios WHERE inactivo IS NULL");
	foreach ($Auto as $row){
		?>
		<tr>
			<td class="text-uppercase"><?=$row['nombre']?></td>
			<td class="text-uppercase"><?=$row['usuario']?></td>
			<td class="text-uppercase"><?=$row['telefono']?>sss</td>
			<td class="text-uppercase"><?=$row['domicilio']?></td>
			<td class=" text-center"><img style="width: 80px;" <?php echo "src='"."uploads/".$row['imagen']."";?>> </td>
			<td class="text-center" width="220">
				<button type="button" class="btn btn-primary btn-sm editar" registros="<?=$row[0]?>">Editar</button>
				<button type="button" class="btn btn-danger btn-sm eliminar" registros="<?=$row[0]?>">Eliminar</button>
				<a href="permisos.php?id=<?=$row[0]?>"><button type="button" class="btn btn-danger btn-sm permiso" registros="<?=$row[0]?>">Permisos</button></a>
			</td>
		</tr>
		<?
	}
	exit();
}
if($_POST['funcion']=='Carga_Clientes'){
include('../inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM clientes WHERE inactivo IS NULL");
	foreach ($Auto as $row){
		?>
		<tr>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['nombre']?></td>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['domicilio']?></td>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['ciudad']?></td>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['telefono']?></td>
			<td  style=" height: 1px;" class="text-uppercase"><?=$row['observaciones']?></td>
			<td  style=" height: 1px;" class="text-center" width="220">
				<button type="button" style="margin:0;" class="btn btn-primary btn-sm editar" registros="<?=$row[0]?>">Editar</button>
				<button type="button" style="margin:0;" class="btn btn-danger btn-sm eliminar" registros="<?=$row[0]?>">Eliminar</button>
			</td>
		</tr>
		<?
	}
	exit();
}
if($_POST['funcion']=='Carga_Movimientos'){
include('../inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM movimientoscaja");
	foreach ($Auto as $row){
		?>
		<tr>
			<td class="text-uppercase"><?=$row['idmovimientoscaja']?></td>
			<td class="text-uppercase"><?=$row['fecha']?></td>
			<td class="text-uppercase"><?=$row['tipo']?></td>
			<td class="text-uppercase"><?=$row['importe']?></td>
			<td class="text-uppercase"><?=$row['usuario']?></td>
			<td class="text-center" width="220">
				<button style="margin: 0;" type="button" class="btn btn-primary btn-sm editar" registros="<?=$row[0]?>">Editar</button>
				<button  style="margin: 0;" type="button" class="btn btn-danger btn-sm eliminar" registros="<?=$row[0]?>">Eliminar</button>
			</td>
		</tr>
		<?
	}
	exit();
}


?>
