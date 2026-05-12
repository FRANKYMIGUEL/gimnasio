<?
if($_POST['funcion']=='Carga'){
include('inc/conectar.php');
	$Auto = $consulta->query("SELECT * FROM membresias WHERE fechabaja IS NULL");
	foreach ($Auto as $row){
		?>
		<tr>
			<td class="text-uppercase"><?=$row['nombre']?></td>
			<td class="text-uppercase" width="150" align="right">$ <?=number_format($row['precio'],2)?></td>
			<td class="text-uppercase" align="center"><?=$row['duracion']?></td>
			<td class="text-center" >
				<button type="button" class="btn btn-primary btn-sm editar" registros="<?=$row[0]?>"><i class="bi bi-pencil"></i> Editar</button>
				<button type="button" class="btn btn-danger btn-sm eliminar" registros="<?=$row[0]?>"><i class="bi bi-trash3"></i> Eliminar</button>
			</td>
		</tr>
		<?
	}
	exit();
}

if($_POST['funcion']=='Eliminar'){
include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE membresias SET fechabaja='".date("Y-m-d H:i:s")."' WHERE idmembresia=".$_POST['idregistro']." ");
	foreach ($Auto as $Autocontador);
	exit();
}
if($_POST['funcion']=='Guardar'){
include('inc/conectar.php');
	$Auto = $consulta->query("INSERT INTO membresias SET nombre='".$_POST['nombre']."', precio='".$_POST['precio']."', duracion='".$_POST['duracion']."', fechaalta='".date("Y-m-d H:i:s")."'");
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='Editar'){
include('inc/conectar.php');
	$Auto = $consulta->query("UPDATE membresias SET nombre='".$_POST['nombre']."', precio='".$_POST['precio']."', duracion='".$_POST['duracion']."' WHERE idmembresia=".$_POST['idregistro']);
	foreach ($Auto as $Autocontador);
exit();
}
if($_POST['funcion']=='Carga_Modal'){
include('inc/conectar.php');
	if($_POST['tipo']!='Nuevo'){
		$Auto = $consulta->query("SELECT * FROM membresias WHERE idmembresia=".$_POST['idregistro']."");
		foreach ($Auto as $row);
	}
	?>
    <div class="modal-body" >
        <div class="row">
            <div class="col-4 font-weight-bold">
                <label>Nombre</label>
                <input type="text" class="form-control" id="nombre" value="<?=$row['nombre']?>" placeholder="Nombre">
            </div>
			<div class="col-4 font-weight-bold">
                <label>Precio</label>
                <input type="text" class="form-control text-right" id="precio" value="<?=$row['precio']?>" placeholder="Precio">
            </div>
			<div class="col-4 font-weight-bold">
                <label>Duracion</label>
                <input type="text" class="form-control" id="duracion" value="<?=$row['duracion']?>" placeholder="Dias de Duracion">
            </div>		
          </div>
        </div>
      <div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
		  <?
		if($_POST['tipo']!='Nuevo'){
?>
		  <button type="button" class="btn btn-primary" id="editar" registros="<?=$_POST['idregistro']?>"><i class="bi bi-pencil"></i> Modificar Membresia</button>
		  <?
		}else{
			?>
		  <button type="button" class="btn btn-primary" id="guardar"><i class="bi bi-floppy"></i> Guardar Membresia</button>
		  <?
		}
		?>
      </div>
	<?
exit();
}
?>
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Membresias</title>
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
<div class="modal fade bd-example-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-cookie"></i>  Membresia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div id="resultados_modal">
		</div>
    </div>
  </div>
</div>
<?
include("menu.php");
?>
  <main class="page-content">
    <div class="container-fluid" style="text-align: center;">
      <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col">
                    &nbsp;
                </div>
                <div class="col-6 text-center ">
                    <h3><i class="bi bi-cookie"></i> Membresias</h3>
                </div>
                <div class="col">
                    <button type="button" id="Nuevo" class="btn btn-info btn-sm" ><i class="bi bi-plus-circle"></i> Membresia Nueva</button>
                    <button type="button" id="carga_modal" class="invisible btn btn-info  btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"></button>
                </div>
            </div>
            <div class="row">
				<div class="col-1">
                    &nbsp;
                </div>
                <div class="col-10">
                    <table id="example" class="table table-sm table-hover" style="width:100%">
                    <thead>
                        <tr>
							<th>Nombre</th>
							<th>Precio</th>
							<th>Duracion</th>
                            <th width="250">Opciones</th>
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
    </div>

  </main>

</div>
</body>
<!-- .//container -->
<script>
$(document).ready(function() {
	$(document).on('click','#Nuevo', function () {
	  $('#carga_modal').click();
		Cargar_Modal("Nuevo",0);
	});

	$(document).on('click','.editar', function () {
		var idregistro = $(this).attr("registros");
	  $('#carga_modal').click();
		Cargar_Modal("Editar",idregistro);
	});

	Carga_Tabla();
	function Carga_Tabla(){
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga"
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#registros_tabla").html(msg);
			}
		});
	}
	$(document).on("click","#guardar",function(e) {
        if($("#nombre").val()==""){
			alertify.error("Ingresa un Nombre");
			$("#nombre").focus();
			return false;
		} 
		if($("#precio").val()==""){
			alertify.error("Ingresa el Precio");
			$("#precio").focus();
			return false;
		}
		if($("#duracion").val()==""){
			alertify.error("Ingresa el Tiempo en dias");
			$("#duracion").focus();
			return false;
		}
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Guardar",
				nombre : $("#nombre").val(),
				precio : $("#precio").val(),
				duracion : $("#duracion").val()
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				alertify.success("Membresia Agredado Exitosamente ");
				window.location="<?=$_SERVER["PHP_SELF"]?>";
			}
		});
    });
	$(document).on("click","#editar",function(e) {
		var idregistro = $(this).attr("registros");
        if($("#nombre").val()==""){
			alertify.error("Ingresa una nombre");
			$("#nombre").focus();
			return false;
		}
        if($("#precio").val()==""){
			alertify.error("Ingresa el Precio");
			$("#precio").focus();
			return false;
		}
		 if($("#duracion").val()==""){
			alertify.error("Ingresa el Tiempo en dias");
			$("#duracion").focus();
			return false;
		}
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Editar",
				nombre : $("#nombre").val(),
				duracion : $("#duracion").val(),
				precio : $("#precio").val(),
				idregistro : idregistro
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				//console.log(msg);
				alertify.success("Membresia Modificada Exitosamente ");
				window.location="<?=$_SERVER["PHP_SELF"]?>";
			}
		});
    });

	function Cargar_Modal(tipo,idregistro){

		$.ajax({
			type: "POST",
			url: "<?=$_SERVER["PHP_SELF"]?>",
			data: ({
				funcion : "Carga_Modal",
				tipo : tipo,
				idregistro : idregistro
			}),
			dataType: "html",
			async:false,
			success: function(msg){
				$("#resultados_modal").html(msg);
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
	$(document).on("click",".eliminar",function(){
		var idregistro = $(this).attr("registros");
		alertify.confirm("Eliminacion",'¿Estas Seguro de Eliminar la Membresia?', function(){
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
					alertify.success("Membresia eliminada Exitosamente "+msg);
					window.location="<?=$_SERVER["PHP_SELF"]?>";

				}
			});
		}, function(){
		alertify.error('Cancelado')});
	});
	function Formato_Moneda(n,c,d,t){
		var c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	}
} );
</script>