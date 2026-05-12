<?
include("inc/conectar.php");
$totalRETIRO = 0;
$Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Retiro' AND fecha BETWEEN '" . date("Y-m-d") . " 00:00:00' AND '" . date("Y-m-d") . " 23:00:00'");
foreach ($Auto as $row) {
  $totalRETIRO += $row['importe'];
}
$totalINGRESO = 0;
$Auto = $consulta->query("SELECT * FROM movimientoscaja WHERE tipo='Ingreso' AND fecha BETWEEN '" . date("Y-m-d") . " 00:00:00' AND '" . date("Y-m-d") . " 23:00:00'");
foreach ($Auto as $row) {
  $totalINGRESO += $row['importe'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Cpanel</title>
  <!-- MDB icon -->
  <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <? include("menu1.php");?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4"></div>
      <div class="col-lg-4"><br>
        <h1>Corte de Caja <span class="badge orange"><i class="fab fa-btc fa-1x" aria-hidden="true"></i></span></h1>
      </div>
      <div class="col-lg-4">
        <div class="row"><br>
          <div class="col-6 mx-auto my-auto text-center">
            <br>
            <input type="date" value="<?= date("Y-m-d") ?>" class="form-control">
          </div>
          <div class="col-6 mx-auto my-auto text-center"><br>
            <a href="ticket_corte.php?idusuarios=<?= $_SESSION['SISTEMA']['idusuarios'] ?>&usuario=<?= $_SESSION['SISTEMA']['usuario'] ?>&fecha=<?= date("Y-m-d") ?>" target="_blank"><input type="button" value="Imprimir Corte" class=" btn btn-block btn-success"></a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-1"></div>
      <div class="col-lg-7">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#ventas" role="tab" aria-controls="pills-home" aria-selected="true">Ingresos Ventas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#ingreso" role="tab" aria-controls="pills-profile" aria-selected="false">Ingreso Efctivo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#transferencia" role="tab" aria-controls="pills-contact" aria-selected="false">Total tranferencia</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#caja" role="tab" aria-controls="pills-contact" aria-selected="false">Total caja</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#extracion" role="tab" aria-controls="pills-contact" aria-selected="false">Extracion Efctivo</a>
          </li>
        </ul>
        <div class="tab-content pt-2 pl-1" id="pills-tabContent">
          <div class="tab-pane fade show active" id="ventas" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="col-12 text-uppesrcase align-self-center">
              <table class="table table-hover table-sm">
                <thead>
                  <td><b>Fecha</b></td>
                  <td><b>Folio</b></td>
                  <td><b>Tipo</b></td>
                  <td><b>Importe</b></td>
                  <td><b>Producto</b></td>
                </thead>
                <tbody id="registros_tablav">
                  <?
                  $totalVENTAS = 0;
                  $totalVENTAST = 0;
                  $Auto = $consulta->query("SELECT SUM(cxc.importe), SUM(cxc.tarjeta), cxc.fecha, ventas.tipo, ventas.idventas, ventas.folio FROM cxc LEFT JOIN ventas ON ventas.idventas=cxc.idventas WHERE cxc.fecha BETWEEN '" . date("Y-m-d") . " 00:00:00' AND '" . date("Y-m-d") . " 23:00:00' GROUP BY cxc.idcxc");
                  foreach ($Auto as $row) {
                    $totalVENTAS += $row[0];
                    $totalVENTAST += $row[1];
                  ?>
                    <tr>
                      <td><?= $row['fecha'] ?></td>
                      <td><?= $row['folio'] ?></td>
                      <td><?= $row['tipo'] ?></td>
                      <td align="right">$ <?= number_format($row[0] + $row[1], 2) ?></td>
                      <td><?
                          $Auto = $consulta->query(" SELECT * FROM ventas_detalle WHERE idventas=" . $row['idventas']);
                          foreach ($Auto as $det) {
                            echo $det['productos'] . " ";
                          }
                          ?></td>
                    </tr>
                  <?
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="ingreso" role="tabpanel" aria-labelledby="pills-profile-tab">
            ingreso
          </div>
          <div class="tab-pane fade" id="transferencia" role="tabpanel" aria-labelledby="pills-contact-tab">
            transferencia
          </div>
          <div class="tab-pane fade" id="caja" role="tabpanel" aria-labelledby="pills-contact-tab">
            caja
          </div>
          <div class="tab-pane fade" id="extracion" role="tabpanel" aria-labelledby="pills-contact-tab">

          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="col-12" style="text-align: center;">
          <div class="row" style="padding-top: 20px; padding-bottom: 20px;">
            <div class="col-12 text-uppesrcase align-self-center">
              <label for="Importe"><b>Totales del Corte</b></label>
            </div>
          </div>
          <div class="row">
            <div class="col-12 text-uppesrcase align-self-center">
              <table class="table table-hover table-sm">
                <thead>
                  <tr>
                    <td align="left"><b>Extracion En Efectivo</b> </td>
                    <td align="right">$ <?= number_format($totalRETIRO, 2) ?></td>
                  </tr>
                  <tr>
                    <td align="left"><b>Ingresos En Efectivo</b> </td>
                    <td align="right">$ <?= number_format($totalINGRESO, 2) ?></td>
                  </tr>
                  <tr>
                    <td align="left"><b>Total en Caja</b> </td>
                    <td align="right">$ <?= number_format((($totalINGRESO + $totalVENTAS) - $totalRETIRO), 2) ?></td>
                  </tr>
                  <tr>
                    <td align="left"><b>Total Transeferencia</b> </td>
                    <td align="right">$ <?= number_format($totalVENTAST, 2) ?></td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-1"></div>

    </div>
    <!-- jQuery -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!-- Your custom scripts (optional) -->
    <script type="text/javascript"></script>

</body>
<script>
  $(document).ready(function(e) {
    $(document).on('click', '#boton', function() {


    });

  });
</script>

</html>