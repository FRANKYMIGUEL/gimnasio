<?
if($_POST['funcion']=='Carga_Clientes'){
include("inc/conectar.php");
	$resultados=$consulta->query("SELECT clientes.*, clases.nombre AS clase FROM clientes LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE clientes.fechabaja IS NULL");
	foreach ($resultados as $row) {
	?>
	<tr>
		<td>
			<?=$row['codigo']?>
		</td>
		<td><?=$row['nombre']?></td>
		<td><?=$row['membresia']?></td>
		<td><?=$row['fecha_inicio']?></td>
		<td><?=$row['fecha_expiracion']?></td>
		<td><?=$row['dias_membresia']?></td>
		<td><?=$row['fecha_registro']?></td>
	</tr>
<?
	}
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Clientes con Membresías</title>
   <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link rel="stylesheet" href="alertifyjs/css/alertify.css">
	<link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
	<link rel="stylesheet" href="css/all.css">
</head>
<body>
<?
include("menu.php");
?>
  <main class="page-content">
    <div class="container-fluid" style="text-align: center;">
      <div class="row">
		<div class="col-md-1"></div>
        <div class="col-md-11">
            <div class="row">
                <div class="col-9 text-center">
                    <h4><i class="bi bi-person-check"></i> Clientes con Membresías</h4>
                </div>
                <div class="col-3">
                   Masculino <input type="radio" name="genero" value="Masculino" class="genero">
				   Femenino <input type="radio" name="genero" value="Femenino" class="genero">
				   Buscar <input type="text" name="buscar" id="buscar" placeholder="Buscar por Nombre o Apellido">
                </div>
				<div class="col-9">
					Activos: <input type="text" id="activos" value="">
					Inactivos <input type="text" id="inactivos" value="">	
					Total <input type="text" id="total" value="">
				</div>
				<div class="col-3">
					Todas <input type="checkbox" name="todas" id="todas" value="1" checked>
					Activas <input type="checkbox" name="activas" id="activas" value="1" checked>
					Inactivas <input type="checkbox" name="inactivas" id="inactivas" value="1" checked>
				</div>	
            </div>
            <div class="row">
           		<div class="col-12">
					<table id="example" class="table table-sm table-hover" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>NoCliente</th>
							<th>Cliente</th>
							<th>Membresia</th>
							<th>Inicio Membresia</th>
							<th>Expiracion Membresia</th>
							<th>Dias Membresia</th>
						</tr>
					</thead>
					<tbody id="resultados_productos">
					</tbody>
				</table>
            </div>
        </div>
   </div>
      </div>
    </div>

  </main>

</div>


	

  </div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.3.1.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="alertifyjs/alertify.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(e) {
	Carga_Clientes();
	function Carga_Clientes(){
		$.ajax({
			type: "POST",
			url: "clientesmembresias.php",
			data: ({
				funcion : "Carga_Clientes"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				//console.log(msg);
				$("#resultados_productos").html(msg);
				//$('#example').DataTable();
			}
		});
	}
	
});
</script>
</body>
