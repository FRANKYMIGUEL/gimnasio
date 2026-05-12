<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Softsimbiosis</title>
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

<?
include("menu.php");
?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
            <BR>
            <div class="row">
                <div class="col-sm-4">
                    <div class="card bg-success">
                        <div class="card-body text-light">
                            <h5 class="card-title"><i class="bi bi-person-check"></i> Clientes Activos</h5>
                            <p class="card-text text-center">
                                <button type="button" class="btn btn-primary">
                                Activos <span class="badge text-bg-secondary" id="activos">0</span>
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card bg-info">
                    <div class="card-body text-light">
                        <h5 class="card-title"><i class="bi bi-cash-coin"></i> Ingresos Ventas</h5>
                        <p class="card-text text-center">
                            <button type="button" class="btn btn-primary">
                            Activos <span class="badge text-bg-secondary" id="activos">0</span>
                            </button>
                        </p>
                    </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card bg-primary">
                    <div class="card-body text-light">
                        <h5 class="card-title"><i class="bi bi-credit-card-2-front"></i> Ingresos por Membresias (Dia)</h5>
                        <p class="card-text text-center">
                            <button type="button" class="btn btn-success">
                            $ <span class="badge text-bg-secondary" id="activos">0.00</span>
                            </button>
                        </p>
                    </div>
                    </div>
                </div>
            </div>
            <BR>
            <div class="row">
                <div class="col-sm-4">
                    <div class="card bg-danger">
                    <div class="card-body">
                        <h5 class="card-title text-light"><i class="bi bi-binoculars"></i> Pendientes de Pago</h5>
                        <p class="card-text text-center">
                            <button type="button" class="btn btn-primary">
                            # <span class="badge text-bg-secondary" id="activos">0</span>
                            </button>
                        </p>
                    </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card bg-dark">
                    <div class="card-body text-light">
                        <h5 class="card-title"><i class="bi bi-person-lines-fill"></i> Asistencias Dia</h5>
                        <p class="card-text text-center">
                            <button type="button" class="btn btn-primary">
                            <span class="badge text-bg-secondary" id="activos">0</span>
                            </button>
                        </p>
                    </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card bg-secondary">
                    <div class="card-body text-light">
                        <h5 class="card-title"><i class="bi bi-clipboard-heart"></i> Clientes Sin Membresia Activa</h5>
                        <p class="card-text text-center">
                            <button type="button" class="btn btn-primary">
                             <span class="badge text-bg-secondary" id="activos">0</span>
                            </button>
                        </p>
                    </div>
                    </div>
                </div>
            </div>
       </div>
   </div>
</div>
