<?
if($_POST['funcion']=='Carga_Ventas'){
	include("inc/conectar.php");
	$tipo = '';
	
	$cancelado = '';

	$resultados=$consulta->query("SELECT * FROM ventas WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' ");
	foreach ($resultados as $row) {
		if($row['tipo']=='Cancelada')$cancelado = 'bg-danger';
	?>
	<tr class="<?=$cancelado?> text-uppercase">
		<td><?=str_pad($row["folio"], 6, "0", STR_PAD_LEFT)?></td>
		<td><?=substr($row['fecha'],0,10)?></td>
		<td align="right">$ <?=number_format($row['importe'],2)?></td>
		<td>
			<?
            
			if($row['tipo']!='Cancelada'){
				//if($_SESSION["SISTEMA"]["tipo"]=='admin'){
				?>
			<button class='btn-group btn-group-xs btn-warning cancelar' registros="<?=$row[0]?>" title='Cancelar Venta' >
            <i class="bi bi-x-circle"></i></button>
			<?
           		
			}
			?>
		</td>
	</tr>
<?
	$cancelado = '';
	}
	exit();
}
if($_POST['funcion']=='Eliminar'){
include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE ventas SET tipo='Cancelada' WHERE idventas=".$_POST['idregistro']." ");
	foreach ($Auto as $Autocontador);
	exit();
}
function Auto_Tabla(){
	include("inc/conectar.php");
	$Auto = $consulta->query("SHOW TABLE STATUS Like 'cxc'");
	foreach ($Auto as $Autocontador);
	$Auto = ($Autocontador["Auto_increment"]);
	return $Auto;
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title> Consulta de Ventas</title>
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
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h4><i class="bi bi-calendar2-week"></i> Consulta de Ventas</h4>
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
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Importe</th>
                                <th width="150" align="center"></th>
                            </tr>
                        </thead>
                        <tbody id="resultados_productos">
                            <tr>
                                <td colspan="4">Sin Resultados</td>
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
			window.location = "ver_ventas.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
		}else{
			window.location = "ver_ventas.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
		}
	});
	function Carga_Entradas(){
		$.ajax({
			type: "POST",
			url: "ver_ventas.php",
			data: ({
				funcion : "Carga_Ventas",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_productos").html(msg);
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
	
	$(document).on("click",".cancelar",function(){
		var idregistro = $(this).attr("registros");
		alertify.confirm("Eliminacion",'¿Estas Seguro de Cancelar la Venta ?', function(){
			$.ajax({
				type: "POST",
				url: "<?=$_SERVER["PHP_SELF"]?>",
				data: ({
					funcion : "Eliminar",
					idregistro : idregistro
				}),
				dataType: "html",
				async:false,
				success: function(msg){
					alertify.success("Venta Cancelada Exitosamente "+msg);
					window.location = "ver_ventas.php?fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
				}
			});
		}, function(){
		alertify.error('Cancelado')});
	});

	function Quita_Moneda(n){
		n=String(n);
		var s=parseFloat(n.replace(",","").replace("$",""));
		if(isNaN(s))s=0;
		return s;
	}
});
</script>
</body>
