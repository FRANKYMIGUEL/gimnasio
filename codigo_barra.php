<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<title>Tienda Navideña Corona</title>
	<link rel="stylesheet" href="css/all.css">
   
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="alertifyjs/css/alertify.css">
    <link rel="stylesheet" href="alertifyjs/css/themes/bootstrap.css">
    <script src="alertifyjs/alertify.js"></script>
    <script type="text/javascript" src="js/JsBarcode.all.min.js"></script>

</head>

<body>
    
<?
include("menu1.php");
?>
    <div class="container-fluid">
        <div class="row"><br><br>
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="center"><br><br>
                <div class="card">
                    <div class="card-header">
                        Generar Codigo de Barras
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <label for="basic-url">Ingrese Un Próducto o un Codigo</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fab fa-searchengin"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="codigo" aria-describedby="basic-addon3">
                                </div>
                            </div>
                            <div class="col-sm-2"><br>
                                <button type="submit" class="btn btn-primary btn-sm" id="generar"> <Strong>Generar</Strong> <i class="fas fa-barcode"></i></button>
                                <button type="submit" style="display:none" class="btn btn-danger btn-sm" id="btn_limpio"> <Strong>limpiar</Strong> <i class="fas fa-broom"></i></button>

                            </div>

                            <div class="col-sm-3">

                            </div>
                            <div class="col-sm-2"><br>
                                <button type="submit" class="btn btn-success btn-sm" style="display:none" id="imprimir"> <Strong>Imprimir</Strong> <i class="fas fa-print"></i></i></button>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">

                </div>



            </div>
            <div class="col-sm-2"></div>

        </div>
        <div class="row" style="display:none" id="card1">
            <div class="col-sm-3"></div>
            <div class="col-sm-6" align="center"><br><br>
                <div class="card">
                    <div class="card-header">
                        Vista Previa
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-1">

                            </div>
                            <div class="col-sm-10">
                                <img data-value="" id="genCodigo" data-text="" class="codigo" />

                            </div>

                            <div class="col-sm-1">

                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">

                </div>



            </div>
            <div class="col-sm-3"></div>

        </div>

    </div>

  
</body>
<script>
    $(document).ready(function() {
        $("#codigo").focus();
        $(document).on('click', '#generar', function() {
            if ($("#codigo").val() == '') {
                alertify.alert("Ingresa una valor Correcto");
                $("#codigo").focus().select();
                return false;
            }
            generarCodigo();
            $("#card1").show(300);
            $("#btn_limpio").show(200);
            $("#generar").hide();
            $('#codigo').attr("disabled", true);
            $("#imprimir").show(200);


        });
        $(document).on('click', '#btn_limpio', function() {

            $("#card1").hide();
            $("#generar").show(200);
            $("#btn_limpio").hide();
            $("#imprimir").hide();
            $("#codigo").val("");
            $('#codigo').attr("disabled", false);

        });
        $(document).on('click', '#imprimir', function() {
            var codigo = $("#codigo").val();
            alertify.confirm("Inicio de Imprecion", '¿Desea Iniciar con la imprecion del ticket??', function() {
                imprimir();
            }, function() {
                alertify.error('Cancelado')
            });


        });

        function generarCodigo() {
            var codigo = $("#codigo").val();
            $('#genCodigo').attr("data-value", codigo);
            $('#genCodigo').attr("data-text", codigo);
            JsBarcode(".codigo").init();
        }

        function imprimir() {
            var codigo = $("#codigo").val();
            var bob = window.open('', '_new');
            bob.location = "gen_codigo_barras.php?codigo=" + codigo;
            window.location = "codigo_barra.php";
        }

    });
</script>

</html>

