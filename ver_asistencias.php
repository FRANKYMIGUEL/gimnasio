<?
if($_POST['funcion']=='Carga_Ventas'){
	include("inc/conectar.php");
	$cliente = '';
	if($_POST['cliente']!=''){
		$cliente = " AND clientes.codigo LIKE '".substr($_POST['cliente'],0,6)."'";
	}
	
	$cancelado = '';

	$resultados=$consulta->query("SELECT asistencias.idasistencias, asistencias.fechabaja AS fechabajas, asistencias.fecha AS fecha, clientes.* FROM asistencias LEFT JOIN clientes ON clientes.idclientes=asistencias.idclientes WHERE fecha BETWEEN '".$_POST['fechai']." 00:00:00' AND '".$_POST['fechaf']." 23:00:00' $cliente");
	foreach ($resultados as $row) {
		if($row['fechabajas']!='')$cancelado = 'bg-danger';
	?>
	<tr class="<?=$cancelado?>">
		<td><?=substr($row['fecha'],0,10)." ".substr($row['fecha'],10,10)?></td>
		<td><?=$row['codigo']."-".$row['nombre']?></td>
		<td>
            <?
			if($row['fechabajas']==''){
				//if($_SESSION["SISTEMA"]["tipo"]=='admin'){
				?>
				<button class='btn-group btn-group-xs btn-warning cancelar' registros="<?=$row['idasistencias']?>" title='Cancelar Asistencia' >
				<i class="bi bi-x-circle"></i> Cancelar Asistencia</button>
			<?
           		// }
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
	$Auto = $consulta->query("DELETE FROM asistencias WHERE idasistencias=".$_POST['idregistro']." ");
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
	<title> Consulta de Asistencias</title>
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
	if(isset($_GET['idcliente'])){$idcliente = $_GET['idcliente'];}else{$idcliente = '';}
	if(isset($_GET['fechainicial'])){$fechainicial = $_GET['fechainicial'];}else{$fechainicial = date("Y-m-d");}
	if(isset($_GET['fechafinal'])){$fechafinal = $_GET['fechafinal'];}else{$fechafinal = date("Y-m-d");}
	 ?>
		<?
        include("menu.php");
        ?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center ">
                	<h4><i class="bi bi-person-arms-up"></i> Consulta de Asistencias</h4>
                </div>
            </div>
            <div class="row">
				<div class="col-md-2 text-center ">
				</div>
				<div class="col-md-2 text-center ">
                    <b>Fecha Inicial</b>
                    <input type="date" class="form-control " id="fechainicial" value="<?=$fechainicial?>">
                </div>
                <div class="col-md-2 text-center ">
                    <b>Fecha Final</b>
                    <input type="date" class="form-control " id="fechafinal" value="<?=$fechafinal?>">
                </div>
                <div class="col-md-3 text-center ">
                    <b>Cliente</b><br>
                    <input list="datosClientes" autocomplete="off" name=""  style="margin-top:5px;"  class="form-control" id="idcliente" placeholder="Buscar Cliente" autocomplete value="<?=$idcliente?>">
					<datalist id="datosClientes" class="col-md-9" active style="width:1000px;">
					</datalist>
                </div>
            </div>
            <br>
            <div class="row">
				<div class="col-md-1 text-center ">
				</div>
                <div class="col-lg-10 ">
                    <table id="example" class="table table-sm  " cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Fecha Asistencia</th>
                                <th>Cliente</th>
                                <th width="200" align="center"></th>
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
<div class="modal fade" id="cxc" role="dialog">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Abonos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_abonos">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(e) {
	Carga_Clientes();
    function Carga_Clientes() {
        $.ajax({
            type: "POST",
            url: "asistencia.php",
            data: ({
                funcion: "DataClientes",
                Buscar: $("#nombre").val()
            }),
            dataType: "html",
            async: false,
            success: function(msg) {
                $("#datosClientes").html(msg);
            }
        });
    }    
	Carga_Entradas();

	$(document).on("change","#fechainicial, #fechafinal",function(){
		var ruta = '<?=$_SERVER["REQUEST_URI"];?>';
		ruta = ruta.split("?");
		if(ruta[1]== undefined){
			window.location = "ver_asistencias.php?idcliente="+$("#idcliente").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
		}else{
			window.location = "ver_asistencias.php?idcliente="+$("#idcliente").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();
		}
	});
	function Carga_Entradas(){

		$.ajax({
			type: "POST",
			url: "ver_asistencias.php",
			data: ({
				funcion : "Carga_Ventas",
				fechai : $("#fechainicial").val(),
				fechaf : $("#fechafinal").val(),
				cliente : $("#idcliente").val()
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
		alertify.confirm("Eliminacion",'¿Estas Seguro de Cancelar la Asistencia ?', function(){
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
					alertify.success("Asistencia Cancelada Exitosamente "+msg);
					window.location = "ver_asistencias.php?idcliente="+$("#idcliente").val()+"&fechainicial="+$("#fechainicial").val()+"&fechafinal="+$("#fechafinal").val();

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
