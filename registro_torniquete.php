<?
if($_POST['funcion']=='Carga_Puerta'){
	include("inc/conectar.php");
	$tipo = '';
	
	$cancelado = '';
	$resultados=$consulta->query("SELECT puerta.*, usuarios.nombre AS usuarios FROM puerta LEFT JOIN usuarios ON puerta.idusuarios = usuarios.idusuarios WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' ");
	foreach ($resultados as $row) {
		$fecha = date("d-m-Y H:i:s",strtotime($row['fecha']));
	?>
	<tr text-uppercase">
		<td><?=$fecha?></td>
		<td><?=$row['motivo']?></td>
		<td><?=$row['usuarios']?></td>
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
	<title> Consulta de Reilete</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-3.3.1.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="alertifyjs/css/alertify.css">
    <link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
    <script src="alertifyjs/alertify.js"></script>
</head>
<body>
	<?php
	include("inc/conectar.php");
	$estado = 'todos';
	$tipo = 'todos';
	if(isset($_GET['fechainicial'])){$fechainicial = $_GET['fechainicial'];}else{$fechainicial = date("Y-m-d");}
	if(isset($_GET['fechafinal'])){$fechafinal = $_GET['fechafinal'];}else{$fechafinal = date("Y-m-d");}
	 ?>
		<?
        include("menu.php");
		
        ?>
<div class="container-fluid" style="margin-top:5px;">
	<div class="row">
		<div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h4><i class="bi bi-calendar2-week"></i> Consulta de Reilete</h4>
                </div>
            </div>
            <div class="row">
				<div class="col-md-4 text-center ">
                    
                </div>
                <div class="col-md-2 text-center ">
                    <b>Fecha Inicial</b>
                    <input type="date" class="form-control " id="fechainicial" value="<?=$fechainicial?>">
                </div>
                <div class="col-md-2 text-center ">
                    <b>Fecha Final</b>
                    <input type="date" class="form-control " id="fechafinal" value="<?=$fechafinal?>">
                </div>
               
                
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 ">
                    <table id="example" class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Motivo</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="resultados_productos">
                            <tr>
                                <td colspan="3">Sin Resultados</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(e) {

	Carga_Entradas();
	
	$(document).on("change","#fechainicial, #fechafinal",function(){
		var ruta = '<?=$_SERVER["REQUEST_URI"];?>';
		ruta = ruta.split("?");
		if(ruta[1]== undefined){
			window.location = "registro_torniquete.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
		}else{
			window.location = "registro_torniquete.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
		}
	});
	function Carga_Entradas(){
		$.ajax({
			type: "POST",
			url: "registro_torniquete.php",
			data: ({
				funcion : "Carga_Puerta",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_productos").html(msg);
				console.log(msg);
				$('#example').DataTable( {
					order: [
						[0, 'desc']
					],
					language: {
						processing:     "Procesando...",
						search:         "Buscar:",
						lengthMenu:    "Mostrar _MENU_ ",
						info:           "Mostrando _START_ de _END_ de Total de  _TOTAL_ resultados",
						infoEmpty:      "Sin Registros 0 de 0 de 0 Mostrando",
						infoFiltered:   "(Filtrando de _MAX_ Filtrados)",
						infoPostFix:    "",
						loadingRecords: "Chargement en cours...",
						zeroRecords:    "Sin Resultados",
						emptyTable:     "Sin Resultados en la Tabla",
						paginate: {
							first:      "Primero",
							previous:   "Anterior",
							next:       "Siguiente",
							last:       "Ultimo"
						},
						aria: {
							sortAscending:  ": Ordenar Ascendente",
							sortDescending: ": Ordenar Desendente"
						}
					}
				} );
				
			}
		});
	}
	
	

	function Quita_Moneda(n){
		n=String(n);
		var s=parseFloat(n.replace(",","").replace("$",""));
		if(isNaN(s))s=0;
		return s;
	}
});
</script>
</body>
