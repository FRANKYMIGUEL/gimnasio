<?
if($_POST['funcion']=='Carga_Datos'){
	include("inc/conectar.php");
	$resultados=$consulta->query("SELECT COUNT(*) AS activos FROM clientes WHERE fechaexpiracion >= CURDATE() AND fechabaja IS NULL")->fetch();
	$activos = $resultados['activos'];
	$resultados=$consulta->query("SELECT COUNT(*) AS inactivos FROM clientes WHERE fechaexpiracion < CURDATE() AND fechabaja IS NULL")->fetch();
	$inactivos = $resultados['inactivos'];
	$total = $activos + $inactivos;
	echo $activos."|".$inactivos."|".$total;
	exit();
}


if($_POST['funcion']=='Carga_Clientes'){
include("inc/conectar.php");
	$estado = '';
	if($_POST['estado'] != 'Todas'){
		if($_POST['estado'] == 'Activas'){
			$estado = " AND fechaexpiracion >= CURDATE() ";
		}else if($_POST['estado'] == 'Inactivas'){
			$estado = " AND fechaexpiracion < CURDATE() ";
		}
	}
	if($_POST['genero'] != 'Todos'){
		$estado .= " AND genero = '".$_POST['genero']."' ";
	}
	$resultados=$consulta->query("SELECT clientes.*, clases.nombre AS clase FROM clientes LEFT JOIN clases ON clases.idclases=clientes.idclases WHERE clientes.fechabaja IS NULL ".$estado." ORDER BY clientes.nombre");
	foreach ($resultados as $row) {
		$row['fechapago'] = date("d-m-Y",strtotime($row['fechapago']));
		$row['fechaexpiracion'] = date("d-m-Y",strtotime($row['fechaexpiracion']));
		$row['fecharegistro'] = date("d-m-Y",strtotime($row['fecharegistro']));
	?>
	<tr>
		<td>
			<?=$row['codigo']?>
		</td>
		<td><?=$row['nombre']?></td>
		<td><?=$row['membresia']?></td>
		<td><?=$row['fechapago']?></td>
		<td><?=$row['fechaexpiracion']?></td>
		<td><?=$row['dias_membresia']?></td>
		<td><?=$row['fecharegistro']?></td>
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
                   Masculino <input type="radio" name="genero" value="Masculino" class="genero form-check-input form-control-md" <? if(isset($_GET['genero']) && $_GET['genero'] == 'Masculino') echo "checked";?>>
				   Femenino <input type="radio" name="genero" value="Femenino" class="genero form-check-input form-control-md" <? if(isset($_GET['genero']) && $_GET['genero'] == 'Femenino') echo "checked";?>>
				    Todos <input type="radio" name="genero" value="Todos" class="genero form-check-input form-control-md" <? if($_GET['genero']=='' or $_GET['genero']=='Todos') echo "checked";?>>
                </div>
				<div class="col-1 text-success">
					Activos : 
				</div>
				<div class="col-1">
					<input type="text" id="activos" value="0" class="form-control form-control-sm bg-success text-white" readonly>
				</div>
				<div class="col-1 text-danger">
					Inactivos :
				</div>
				<div class="col-1">
					<input type="text" id="inactivos" value="0" class="form-control form-control-sm bg-danger text-white" readonly>	
				</div>
				<div class="col-1 text-primary">
					Total :
				</div>
				<div class="col-1">
					<input type="text" id="total" value="0" class="form-control form-control-sm bg-primary text-white" readonly>
				</div>
				<div class="col-1">
					Todas 
				</div>
				<div class="col-1">
					<input type="checkbox" class="form-check-input estado" name="estado" id="todas" value="Todas" <? if($_GET['estado']=='' or $_GET['estado']=='Todas') echo "checked";?>>
				</div>					
				<div class="col-1">
					Activas 
				</div>						
				<div class="col-1">
					<input type="checkbox" class="form-check-input estado" name="estado" id="activas" value="Activas" <? if($_GET['estado'] == 'Activas') echo "checked";?>>
				</div>
				<div class="col-1">
					Inactivas
				</div>	
				<div class="col-1">
					<input type="checkbox" class="form-check-input estado" name="estado" id="inactivas" value="Inactivas" <? if($_GET['estado'] == 'Inactivas') echo "checked";?>>
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
							<th>Fecha Registro</th>
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
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.3.1.js"></script>
<script src="alertifyjs/alertify.js"></script>
<link href="css/datatables.min.css" rel="stylesheet">
<script src="js/datatables.min.js"></script>
<script>
$(document).ready(function(e) {
	Carga_Clientes();
	Carga_Datos();
	$(document).on("click",".genero, .estado",function(){
		//redirecciono a la misma pagina pero con el genero seleccionado
		var estado = 'Todas';
		if(this.name == 'estado'){
			estado = $(this).val();
		}
		var genero = 'Todos';
		if(this.name == 'genero'){
			genero = $(this).val();
		}
		window.location="clientesmembresias.php?genero="+genero+"&estado="+estado;
		
	});
	

	function Carga_Clientes(){
		var estado = 'Todas';
		if($("#todas").is(":checked")){
			estado = 'Todas';
		}else if($("#activas").is(":checked") && !$("#inactivas").is(":checked")){
			estado = 'Activas';
		}else if(!$("#activas").is(":checked") && $("#inactivas").is(":checked")){
			estado = 'Inactivas';
		}
		var genero = 'Todos';
		if($(".genero:checked").val() != 'Todos'){
			genero = $(".genero:checked").val();
		}
		$.ajax({
			type: "POST",
			url: "clientesmembresias.php",
			data: ({
				funcion : "Carga_Clientes",
				genero : genero,
				estado : estado
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_productos").html(msg);
				new DataTable('#example', {
					language: {
						info: 'Mostrando página _PAGE_ de _PAGES_',
						infoEmpty: 'No hay registros disponibles',
						infoFiltered: '(filtrado de _MAX_ registros totales)',
						lengthMenu: 'Mostrar _MENU_ registros por página',
						zeroRecords: 'No se encontraron registros',
						search:         "Buscar por Nombre, Apellido o NoCliente:",
					},bSort: false,
					search: {
						searchPlaceholder: "Buscar por Nombre o Apellido"
					}
				});

			}
		});
	}
	function Carga_Datos(){
		$.ajax({
			type: "POST",
			url: "clientesmembresias.php",
			data: ({
				funcion : "Carga_Datos"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				var datos = msg.split("|");
				$("#activos").val(datos[0]);
				$("#inactivos").val(datos[1]);
				$("#total").val(datos[2]);

			}
		});
	}
	
});
</script>
</body>
