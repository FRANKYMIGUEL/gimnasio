<?
include("inc/conectar.php");

$actual = 'active';
if (!isset($_SESSION['SISTEMA']['usuario'])) {
  echo "Sin session";
  header("Location: login.php");
  exit();
}
$modulos = array();
$Auto = $consulta->query("SELECT modulos.idmodulos FROM permisos LEFT JOIN modulos ON modulos.idmodulos=permisos.idmodulos WHERE idusuarios=" . $_SESSION['SISTEMA']['idusuarios'] . "");
$contador = 0;
foreach ($Auto as $permisos) {
  $modulos[$contador] = $permisos[0];
  $contador++;
}
?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="menu.css">
<div class="page-wrapper chiller-theme toggled">
  <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
  </a>
  <nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
      <div class="sidebar-brand">
        <a href="#">Vital Gimnasio </a>
        <div id="close-sidebar">
          <i class="fas fa-times"><img src="img/logo.jpg" alt="Vital Gimnasio" height="30"></i>
        </div>
      </div>
      <div class="sidebar-header">
        <div class="user-info">
          <span class="user-name">Usuario: <? echo $_SESSION['SISTEMA']['usuario'] ?>
          </span>
        </div>
       
      </div>
      <!-- sidebar-header  -->
      <div class="sidebar-menu">
        <ul>
          <li>
            <a href="index.php">
              <i class="fa fa-home"></i>
              <i class="bi bi-house"></i>
              <span>Inicio</span>
            </a>
          </li>
          <li>
            <a href="#" id="abrir_puerta">
              <i class="bi bi-door-open"></i>
              <span>Abrir Puerta</span>
            </a>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="bi bi-gear-wide-connected"></i>
              <span>Panel de Control</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="clientesmembresias.php"><i class="bi bi-card-list"></i> Membresias
                  </a>
                </li>
                 <li>
                  <a href="clientes.php"><i class="bi bi-people"></i> Clientes
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="bi bi-cash-stack"></i>
              <span>Caja</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="extracion.php">Captura de Movimientos</a>
                </li>
                <li>
                  <a href="cortecaja.php">Corte de Caja</a>
                </li>
              </ul>
            </div>
          </li>
          <li>
            <a href="ver_ventas.php">
              <i class="bi bi-bar-chart"></i>
              <span>Control de Ventas</span>
            </a>
          </li>
          <li>
            <a href="pventas.php">
              <i class="bi bi-cash-stack"></i>
              <span>Punto de Ventas</span>
            </a>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="bi bi-cart"></i>
              <span>Gestion de Inventario</span>
              <span class="badge badge-pill badge-danger"></span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="productos.php"><i class="bi bi-box-seam"></i> Productos

                  </a>
                </li>
                <li>
                  <a href="categorias.php"><i class="bi bi-box-seam"></i> Categorias
                  </a>
                </li>
                <li>
                  <a href="reporte_inventario.php"><i class="bi bi-clipboard-data"></i> Inventario</a>
                </li>
               
              </ul>
            </div>
          </li>
           <li>
            <a href="registro_torniquete.php">
              <i class="bi bi-person-check"></i>
              <span>Registro de Torniquete</span>
            </a>
          </li>
           <li>
            <a href="clientes.php">
              <i class="bi bi-people"></i>
              <span>Clientes con Membresía</span>
            </a>
          </li>
         
          
          
          <li class="header-menu">
            <span>Reportes</span>
            <i class="bi bi-bar-chart-line"></i> 

          </li>
          <li>
            <a href="reporte_membresias.php">
              <i class="bi bi-book"></i>
              <span>Reporte de Membresias</span>
            </a>
          </li>
          <li>
            <a href="reporte_ventas.php">
              <i class="bi bi-bar-chart"></i>
              <span>Reporte de Ventas</span>
            </a>
          </li>
          <li>
            <a href="reporte_corte_caja.php">
              <i class="bi bi-folder"></i>
              <span>Reporte de Corte de Caja</span>
            </a>
          </li>
           
        </ul>
      </div>
      <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    <div class="sidebar-footer">
     
      
      <a href="cerrarsesion.php">
        <i class="bi bi-box-arrow-right"></i>
        <span>Cerrar Sesion</span>
      </a>
    </div>
  </nav>
  <!-- sidebar-wrapper  -->

  <!-- page-content" -->

<!-- page-wrapper -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="menu.js"></script>
<script>
  $(document).ready(function() {
   $(document).on("click","#abrir_puerta",function(){
		alertify.confirm("Abrir Reilete",'Estas Seguro de Abrir la Puerta', function(){
		alertify.success('Si') ;
			$.ajax({
				type: "POST",
				url: "abrir_reliete.php",
				data: ({
					funcion : "opendoor",
					employeeNo : "1"
				}),
				dataType: "html",
				async:false,
				success: function(msg){
					console.log(msg);
					alertify.success("Puerta abierta Exitosamente ");
					//window.location="clientes.php";
				}
			});
		}, function(){
			alertify.error('Cancelado')});
	});
  });
  </script>