<?
if($_POST['funcion']=='Carga_Usuarios'){
include('inc/conectar.php');
	$Auto = $consulta->query("SELECT productos.*, productos_sucursales.existencias, categorias.nombre AS categoria, departamentos.nombre AS departamento FROM productos LEFT JOIN categorias ON categorias.idcategorias=productos.idcategoria LEFT JOIN departamentos ON departamentos.iddepartamentos=productos.iddepartamento LEFT JOIN productos_sucursales ON productos_sucursales.idproductos=productos.idproductos WHERE productos.activo = 1 AND idsucursales=".$_SESSION["SISTEMA"]["idsucursales"]." ORDER BY productos.codigo");
	foreach ($Auto as $row){
		?>
		<tr>
			<td class="text-uppercase"><?=$row['codigo']?></td>
			<td class="text-uppercase"><?=$row['codigo_barras']?></td>
			<td class="text-uppercase"><?=$row['nombre']?></td>
			<td class="text-uppercase"><?=$row['medidas']?></td>
			<td class="text-uppercase"><?=$row['categoria']?></td>
			<td class="text-uppercase"><?=$row['departamento']?></td>
			<td class="text-center"><?=number_format($row['existencias'],2)?></td>
		</tr>
		<?
	}
	exit();
}
?>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Existencias</title>
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
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div id="resultados_modal">
		</div>
    </div>
  </div>
</div>
<div class="container-fluid">
	<div class="row">
    	<div class="col-md-2 text-left">
		<?
        include("menu1.php");
        ?>
        </div>
        <div class="col-md-10">
            <div class="row">
                <div class="col">
                    &nbsp;
                </div>
                <div class="col-6 text-center ">
                    <h3>Productos</h3>
                </div>
                <div class="col">
                    <button type="button" id="Nuevo" class="btn btn-info btn-sm" >Agregar Productos</button>
                    <button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>
                    <button type="button" id="carga_modal_alumnos" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#modalalumnos" data-whatever="@mdo"></button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="example" class="table table-sm table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>C. Barras</th>
                            <th>Nombre</th>
                            <th>Unidad</th>
                            <th>Categoria</th>
                            <th>Departamento</th>
                            <th>Existencias</th>
                        </tr>
                    </thead>
                    <tbody id="registros_tabla">
                    </tbody>
                </table>
                </div>
            </div>
       </div>
   </div>
</div>
</body>
<!-- .//container -->
<script>
$(document).ready(function() {
	Carga_Tabla();
	function Carga_Tabla(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Usuarios"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#registros_tabla").html(msg);
			}
		});
	}
    $('#example').DataTable( {
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
	 });
});
</script>
