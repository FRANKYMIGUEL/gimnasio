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
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Inicio</title>
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <div style="display: flex; min-height: 100vh;">
    <aside style="width: 250px; background:#343434; color:#fff; padding: 20px; overflow-y: auto;">
      <link rel="shortcut icon" href="img/favicon1.ico" type="image/x-icon">
      <link rel="icon" href="img/favicon1.ico" type="image/x-icon">
      <nav style="margin-top: 20px;">
        <ul class="navbar-nav" style="flex-direction: column;">
          <li class="nav-item dropdown" >
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-cash-coin" width="32" height="32"> </i> Captura</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <? if (in_array("2", $modulos)) { ?>
              <a class="dropdown-item" href="asistencia.php">
              <i class="bi bi-cash"></i>
                Captura de Asistencia</a>
              <? }
              if (in_array("26", $modulos)) { ?>
              <a class="dropdown-item" href="ultimas_asistencias.php"><i class="bi bi-emoji-sunglasses"></i> Ultimas Asistencias</a>
              <? }
               if (in_array("26", $modulos)) { ?>
                <a class="dropdown-item" href="ver_asistencias.php"><i class="bi bi-calendar2-week"></i> Consulta Asistencias</a>
                <? }
              if (in_array("26", $modulos)) { ?>
              <a class="dropdown-item" href="dashboard.php"><i class="bi bi-truck"></i> Dashboard</a>
              <? }
               if (in_array("26", $modulos)) { ?>
                <a class="dropdown-item" href="pendientes.php"><i class="bi bi-truck"></i> Pendientes de Pago</a>
                <? }
                ?>
            </div>
          </li>
          <li class="nav-item dropdown" >
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-cash-coin" width="32" height="32"> </i> Ventas</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <? if (in_array("2", $modulos)) { ?>
              <a class="dropdown-item" href="pventas.php">
              <i class="bi bi-cash"></i>
                Punto de Venta</a>
              <? }
              if (in_array("26", $modulos)) { ?>
              <a class="dropdown-item" href="ver_ventas.php"><i class="bi bi-calendar2-week"></i> Consulta de Ventas</a>
              <? }
                ?>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-pc-display-horizontal"></i> </i> Caja</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <?
              if (in_array("25", $modulos)) { ?>
              <a class="dropdown-item" href="extracion.php"><i class="bi bi-arrow-left-right"></i> Captura de Movimientos</a>
              <? }
              if (in_array("25", $modulos)) { ?>
                <a class="dropdown-item" href="cortecaja.php"><i class="bi bi-receipt-cutoff"></i> Corte de Caja</a>
                <? }
                if (in_array("25", $modulos)) { ?>
                  <a class="dropdown-item" href="extracion.php"><i class="bi bi-clipboard2-data"></i> Consulta de Cortes</a>
                  <? }
               ?>
            </div>
          </li>  
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-box-seam-fill"></i>
                Productos</a>

            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <? if (in_array("10", $modulos)) { ?>
              <a class="dropdown-item" href="productos.php"> <i class="bi bi-box"></i> Productos </a>
              <? }
              ?>
            </div>
          </li>
          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-person-check"></i>
            Clientes</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <? if (in_array("3", $modulos)) { ?>
              <a class="dropdown-item" href="clientes.php"> <i class="bi bi-file-earmark-person"></i> Clientes</a>
              <? }
              if (in_array("5", $modulos)) { ?>
              <a class="dropdown-item" href="cuentasxcobrar.php"><i class="bi bi-credit-card"></i> Clientes por cobrar</a>
              <? }
               if (in_array("3", $modulos)) { ?>
                <a class="dropdown-item" href="membresias.php"><i class="bi bi-credit-card"></i> Membresias</a>
                <? }
              if (in_array("3", $modulos)) { ?>
              <a class="dropdown-item" href="clases.php"><i class="bi bi-cash-stack"></i> Clases</a>
              <? } ?>
            </div>
          </li>
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
            <path d="M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z"/>
          </svg>
            Reportes</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <? if (in_array("29", $modulos)) { ?>
              <a class="dropdown-item" href="reporte_ventas.php">Reporte de Ventas</a>
              <? }
              if (in_array("30", $modulos)) { ?>
              <a class="dropdown-item" href="reporte_membresias.php">Reporte de Membresias</a>
              <? }
              if (in_array("30", $modulos)) { ?>
              <a class="dropdown-item" href="reporte_compras.php">Reporte de Pagos</a>
              <? }
               ?>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-gear-wide"></i>
Configuracion</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <? if (in_array("29", $modulos)) { ?>
              <a class="dropdown-item" href="configuracion.php"><i class="bi bi-person-bounding-box"></i> Usuarios</a>
              <?} 
              
             ?>
            </div>
          </li>      
          <li class="nav-item">
            <a class="nav-link" href="https://www.softsimbiosis.com/"style="text-decoration:none;" target="_blank">Softsimbiosis <img src="img/IO.png" height="30"></a>
          </li>
        </ul>
        <ul class="navbar-nav nav-flex-icons">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Usuario: <?= $_SESSION['SISTEMA']['usuario'] ?></a>
            <div class="dropdown-menu gradiente púrpura" aria-labelledby="navbarDropdownMenuLink">


              <a class="dropdown-item" href="cerrarsesion.php"><i class="bi bi-box-arrow-left"></i> Salir del Programa</a>
            </div>
          </li>
        </ul>
        <ul class="navbar-nav nav-flex-icons">
          <li class="nav-item dropdown" style=" visibility:hidden;">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <script>
   
   
  </script>

