<?
  $totalVENTAS = 0;
  $totalVENTAST = 0;
  $totalMEMBRESIAS = 0;
if ($_POST['funcion'] == 'membresias') {
  include('inc/conectar.php');
  $desde = $_POST['desde'];
  $asta = $_POST['asta'];

    $Auto = $consulta->query("SELECT * FROM movimientoscaja LEFT JOIN clientes ON movimientoscaja.idclientes = clientes.idclientes WHERE tipo='Membresia' AND fecha BETWEEN '" . $desde . " 00:00:00' AND '" . $asta . " 23:00:00'");
  foreach ($Auto as $row) {
    $totalMEMBRESIAS += $row['importe'];
    //formato de fecha mexico 
    $row['fechapago'] = date("d/m/Y H:i:s", strtotime($row['fechapago']));
    ?>
    <tr>
      <td align="left"><?= $row['fechapago'] ?></td>
      <td><?= $row['membresia']?></td>
      <td><?= $row['codigo']."-".$row['nombre']?></td>
      <td align="right">$ <?= number_format($row['importe'], 2) ?></td>
    </tr>
<?
  }
  ?>
  <tr>
     
      <td align="right" colspan="4">Total:$ <?= number_format( $totalMEMBRESIAS, 2) ?></td>
    </tr>
  <?
  exit();
}
  if ($_POST['funcion'] == 'totales') {
    include('inc/conectar.php');
    $total = 0;
    $desde = $_POST['desde'];
    $asta = $_POST['asta'];
    $totalRETIRO = 0;
    $totalDEVUELTO=0;
    $Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Retiro' AND fecha BETWEEN '" . $desde . " 00:00:00' AND '" . $asta . " 23:00:00'");
    foreach ($Auto as $row) {
      $totalRETIRO += $row['importe'];
    }
    $Auto = $consulta->query("SELECT * FROM movimientoscaja LEFT JOIN clientes ON movimientoscaja.idclientes = clientes.idclientes WHERE tipo='Membresia' AND fecha BETWEEN '" . $desde . " 00:00:00' AND '" . $asta . " 23:00:00'");
  foreach ($Auto as $row) {
    $totalMEMBRESIAS += $row['importe'];

  }

  $totalINGRESO = 0;
  $Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Ingreso' AND fecha BETWEEN '" . $desde . " 00:00:00' AND '" . $asta . " 23:00:00'");
  foreach ($Auto as $row) {
    $totalINGRESO += $row['importe'];
  }

	$result = $consulta->query("SELECT SUM(importe)  fecha FROM ventas WHERE fecha BETWEEN '".$desde." 00:00:00' AND '".$asta." 23:59:00' AND tipo IS NULL");
	foreach ($result as $row);
	 $totalVENTAS += $row[0];
  ?>
<tr>
  <td align="left"><b>Extracion En Efectivo</b> </td>
  <td align="right">$ <?= number_format($totalRETIRO, 2) ?></td>
</tr>
<tr>
  <td align="left"><b>Ingresos En Efectivo</b> </td>
  <td align="right">$ <?= number_format($totalINGRESO, 2) ?></td>
</tr>
<tr>
  <td align="left"><b>Ingreso En Ventas</b> </td>
  <td align="right">$ <?= number_format($totalVENTAS, 2) ?></td>
</tr>
<tr>
  <td align="left"><b>Ingreso En Membresias</b> </td>
  <td align="right">$ <?= number_format($totalMEMBRESIAS, 2) ?></td>
</tr>
<tr>
  <?     
 $Auto1 = $consulta->query("SELECT * FROM ventas WHERE fecha BETWEEN '".$desde." 00:00:00' AND '".$asta." 23:00:00'");
 foreach ($Auto1 as $row1);
?>
  <td align="left"><b>Total en Caja</b> </td>
  <td align="right">$ <?= number_format(((($totalINGRESO + $totalVENTAS+$totalMEMBRESIAS) - $totalRETIRO)), 2) ?></td>
</tr>

<?
  exit();
}
if ($_POST['funcion'] == 'corte') {
  include('inc/conectar.php');
  $total = 0;
  $desde = $_POST['desde'];
  $asta = $_POST['asta'];
  echo $desde . "/".$asta;
  exit();
}
if ($_POST['funcion'] == 'ventas') {
  include('inc/conectar.php');
  $total = 0;
  $desde = $_POST['desde'];
  $asta = $_POST['asta'];
        $Auto1 = $consulta->query("SELECT * FROM ventas WHERE fecha BETWEEN '".$desde." 00:00:00' AND '".$asta." 23:00:00'");
        foreach ($Auto1 as $row1){
          //formato de fecha mexico
          $row1['fecha'] = date("d/m/Y H:i:s", strtotime($row1['fecha']));
      ?>
<tr align="center">
  <td><?= $row1['fecha']
   ?>
  </td>
  <td><?= $row1['folio'] ?></td>
  <td><?= $row1['tipo'] ?></td>
  <td align="right">$ <?= number_format($row1[4], 2) ?></td>
  <?
		}
          ?>
</tr>
<?
  exit();
}

if ($_POST['funcion'] == 'extraccion') {
  include('inc/conectar.php');
  $total = 0;
  $desde = $_POST['desde'];
  $asta = $_POST['asta'];


  $Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Retiro' AND fecha BETWEEN '" .$desde . " 00:00:00' AND '" . $asta . " 23:00:00'");
  foreach ($Auto as $row) {
    $total += $row['importe'];
     $row['fecha'] = date("d/m/Y H:i:s", strtotime($row['fecha']));
  ?>
<tr>
  <td><?= $row['fecha'] ?></td>
  <td align="left">$ <?= number_format($row['importe'], 2) ?></td>
  <td><?= $row['observaciones'] ?></td>
  <td><?= $row['usuarios'] ?></td>
</tr>
<?
  }
  ?>
<tr>
  <td>Total</td>
  <td align="right" colspan="2">$ <?= number_format($total, 2) ?></td>
</tr>
<?

  exit();
}
if ($_POST['funcion'] == 'ingreso') {
  include('inc/conectar.php');
  $total = 0;
  $desde = $_POST['desde'];
  $asta = $_POST['asta'];

  
  $total = 0;
  $Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Ingreso' AND fecha BETWEEN '" . $desde . " 00:00:00' AND '" . $asta . " 23:00:00'");
  foreach ($Auto as $row) {
    $total += $row['importe'];
     $row['fecha'] = date("d/m/Y H:i:s", strtotime($row['fecha']));
  ?>
<tr align="left">
  <td><?= $row['fecha'] ?></td>
  <td align="left">$ <?= number_format($row['importe'], 2) ?></td>
  <td><?= $row['observaciones'] ?></td>
  <td><?= $row['usuarios'] ?></td>
</tr>
<?
  }
  ?>
<tr class=" text-dark">
  <td></td>
  <td></td>
  <td>Total</td>
  <td align="right" colspan="2">$ <?= number_format($total, 2) ?></td>
</tr>
<?
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Corte de Caja</title>
  <!-- MDB icon -->
  <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

  <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon">
  <!-- Font Awesome -->
	<link rel="stylesheet" href="css/all.css">
  <!-- Google Fonts Roboto -->
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="alertifyjs/css/alertify.css">
    <link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
    <script src="alertifyjs/alertify.js"></script>
</head>

<body>
  <?
include("menu.php");
?>
  <main class="page-content">
    <div class="container-fluid" style="text-align: center;">
       <div class="row">
      <div class="col-lg-4"></div>
      <div class="col-lg-4"><br>
        <h1>Corte de Caja </h1>
      </div>
      <div class="col-lg-4">
        <div class="row"><br>
          <div class="col-6 mx-auto my-auto text-center">
            <b>Fecha Inicio</b>
            <input type="date" id="desde" value="<?= date("Y-m-d") ?>" class="form-control">
          </div>
          <div class="col-6 mx-auto my-auto text-center"><b>Fecha Fin</b>
            <input type="date" id="asta" value="<?= date("Y-m-d") ?>" class="form-control">

          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-1"></div>
      <div class="col-lg-8">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="pills-membresias" data-toggle="pill" href="#membresias" role="tab" aria-controls="pills-home" aria-selected="true">Ingresos Membresias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " id="pills-home-tab" data-toggle="pill" href="#ventas" role="tab" aria-controls="pills-home" aria-selected="true">Ingresos Ventas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#ingreso" role="tab" aria-controls="pills-profile" aria-selected="false">Ingreso Efectivo</a>
          </li>
       
          <li class="nav-item">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#extracion" role="tab" aria-controls="pills-contact" aria-selected="false">Extracion Efectivo</a>
          </li>
        </ul>
        
        <div class="tab-content pt-2 pl-1" id="pills-tabContent">
          <div class="tab-pane fade show active" id="membresias" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="col-12 text-uppesrcase align-self-center">
              <table class="table table-hover table-sm">
                <thead align="center">
                  <td><b>Fecha</b></td>
                  <td><b>Membresia</b></td>
                  <td><b>Usuario</b></td>
                  <td align="right"><b>Importe</b></td>
                </thead>
                <tbody id="registros_tablam">

                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade show" id="ventas" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="col-12 text-uppesrcase align-self-center">
              <table class="table table-hover table-sm">
                <thead align="center">
                  <td><b>Fecha</b></td>
                  <td><b>Folio</b></td>
                  <td><b>Tipo</b></td>
                  <td align="right"><b>Importe</b></td>
                </thead>
                <tbody id="registros_tablav">

                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="ingreso" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="col-12 text-uppesrcase align-self-center">
              <table width="100%" class="table table-hover table-sm">
                <thead>
                  <td>Fecha</td>
                  <td>Importe</td>
                  <td>Observaciones</td>
                  <td>Usuario</td>
                </thead>
                <tbody id="registros_tablai">
                  <?
                  $total = 0;
                  $Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Ingreso' AND fecha BETWEEN '" . date("Y-m-d") . " 00:00:00' AND '" . date("Y-m-d") . " 23:00:00'");
                  foreach ($Auto as $row) {
                    $total += $row['importe'];
                  ?>
                  <tr align="left">
                    <td><?= $row['fecha'] ?></td>
                    <td align="left"><?= number_format($row['importe'], 2) ?></td>
                    <td><?= $row['observaciones'] ?></td>
                    <td><?= $row['usuarios'] ?></td>
                  </tr>
                  <?
                  }
                  ?>
                  <tr class=" text-dark">
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td align="right" colspan="2">$ <?= number_format($total, 2) ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="extracion" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div class="col-12 text-uppesrcase align-self-center">
              <table class="table table-hover table-sm" width="100%">
                <thead>
                  <td>Fecha</td>
                  <td>Importe</td>
                  <td>Observaciones</td>
                  <td>Usuario</td>
                </thead>
                <tbody id="registros_tablae">
                  <?
                  $total = 0;
                  $Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Retiro' AND fecha BETWEEN '" . date("Y-m-d") . " 00:00:00' AND '" . date("Y-m-d") . " 23:00:00'");
                  foreach ($Auto as $row) {
                    $total += $row['importe'];
                  ?>
                  <tr>
                    <td><?= $row['fecha'] ?></td>
                    <td align="left"><?= number_format($row['importe'], 2) ?></td>
                    <td><?= $row['observaciones'] ?></td>
                    <td><?= $row['usuarios'] ?></td>
                  </tr>
                  <?
                  }
                  ?>
                  <tr>
                    <td>Total</td>
                    <td align="right" colspan="2">$ <?= number_format($total, 2) ?></td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="col-12" style="text-align: center;">
          <div class="row" style="padding-top: 20px; padding-bottom: 20px;">
            <div class="col-12 text-uppesrcase align-self-center">
              <b>Total del Corte</b>
            </div>
          </div>
          <div class="row">
            <div class="col-12 text-uppesrcase align-self-center">
              <table class="table table-hover table-sm">
                <thead id="totales">
                  <tr>
                    <td align="left"><b>Extracion En Efectivo</b> </td>
                    <td align="right">$ <?= number_format($totalRETIRO, 2) ?></td>
                  </tr>
                  <tr>
                    <td align="left"><b>Ingresos En Efectivo</b> </td>
                    <td align="right">$ <?= number_format($totalINGRESO, 2) ?></td>
                  </tr>
                  <tr>
                    <td align="left"><b>Ingreso En Ventas</b> </td>
                    <td align="right">$ <?= number_format($totalVENTAS, 2) ?></td>
                  </tr>
                  <tr>
                    <td align="left"><b>Ingreso En Membresias.</b> </td>
                    <td align="right">$ <?= number_format($totalMEMBRESIAS, 2) ?></td>
                  </tr>
                  <tr>
                    <td align="left"><b>Total en Caja</b> </td>
                    <td align="right">$ <?= number_format(((($totalINGRESO + $totalVENTAS) - $totalRETIRO)), 2) ?></td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div class="col-8 mx-auto my-auto text-center"><br>
          <a class="text-left" id="corte_caja" target="_blank"><button type="button" class="btn btn-success">Imprimir Corte</button></a>
        </div>
      </div>
      <div class="col-lg-1"></div>
    </div>
     
      </div>
    </div>

  </main>

</div>



  
<script>
  $(document).ready(function(e) {
    extraer();
      ingresar();
      ventas();
      totales();
      membresias();
    $(document).on('change', '#desde, #asta', function() {
      extraer();
      ingresar();
      ventas();
      totales();
      membresias();
    });

    $(document).on('click', '#corte_caja', function() {
      realizar_corte();
    });

    function realizar_corte() {
      var desde = $("#desde").val();
      var asta = $("#asta").val();
      // alert(desde);
      //alert(asta);

      $.ajax({
        type: "POST",
        url: "<?= $_SERVER["PHP_SELF"] ?>",
        data: ({
          funcion: "corte",
          desde: desde,
          asta: asta

        }),
        dataType: "html",
        async: false,
        success: function(msg) {
          //alert(msg);
          // alertify.success("Realizando Corte");
          var bob = window.open('', '_new');
          bob.location = "ticket_corte.php?fecha=" + msg;
        }
      });
    }

    function extraer() {
      var desde = $("#desde").val();
      var asta = $("#asta").val();
      // alert(desde);
      //alert(asta);

      $.ajax({
        type: "POST",
        url: "<?= $_SERVER["PHP_SELF"] ?>",
        data: ({
          funcion: "extraccion",
          desde: desde,
          asta: asta

        }),
        dataType: "html",
        async: false,
        success: function(msg) {
          //alert(msg);
          $("#registros_tablae").html(msg);
        }
      });
    }
    function membresias() {
      var desde = $("#desde").val();
      var asta = $("#asta").val();
      $.ajax({
        type: "POST",
        url: "<?= $_SERVER["PHP_SELF"] ?>",
        data: ({
          funcion: "membresias",
          desde: desde,
          asta: asta
        }),
        dataType: "html",
        async: false,
        success: function(msg) {
          console.log(msg);
          $("#registros_tablam").html(msg);
        }
      });
    }
   

    function ventas() {
      var desde = $("#desde").val();
      var asta = $("#asta").val();
      // alert(desde);
      //alert(asta);

      $.ajax({
        type: "POST",
        url: "<?= $_SERVER["PHP_SELF"] ?>",
        data: ({
          funcion: "ventas",
          desde: desde,
          asta: asta

        }),
        dataType: "html",
        async: false,
        success: function(msg) {
         // alert(msg);
         		//				var e=prompt("",msg);

          $("#registros_tablav").html(msg);
        }
      });
    }

    function ingresar() {
      var desde = $("#desde").val();
      var asta = $("#asta").val();

      $.ajax({
        type: "POST",
        url: "<?= $_SERVER["PHP_SELF"] ?>",
        data: ({
          funcion: "ingreso",
          desde: desde,
          asta: asta

        }),
        dataType: "html",
        async: false,
        success: function(msg) {
          //  alert(msg);
          $("#registros_tablai").html(msg);
        }
      });
    }

    function totales() {
      $("#totales").html("");
      var desde = $("#desde").val();
      var asta = $("#asta").val();
      // alert(desde);
      //alert(asta);

      $.ajax({
        type: "POST",
        url: "<?= $_SERVER["PHP_SELF"] ?>",
        data: ({
          funcion: "totales",
          desde: desde,
          asta: asta

        }),
        dataType: "html",
        async: false,
        success: function(msg) {
          //alert(msg);
        //  var e=prompt("",msg);
          $("#totales").html(msg);
        }
      });

    }
  });
</script>

</html>