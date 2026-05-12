<?php

if ($_POST["funcion"] == "DataListCodigos") {
    include("inc/conectar.php");
    $nombre = $_POST['nombre'];
    $buscar = $consulta->query("SELECT * FROM productos WHERE inactivo is null");
    if ($buscar) {
        foreach ($buscar as $fila) {
            if ($fila['idproductos'] == 0) {
                // code...
                // echo "No";
            } else {
                // code...
                echo "<option value ='$fila[codigo]-$fila[nombre]'>";
            }
        }
    } else {
        echo "Problemas en la consulta";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <title>Tienda Navideña Corona</title>

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
<style>
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_asc_disabled:before,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_desc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:before {
        bottom: .5em;
    }
</style>

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
                                        <span class="input-group-text" id="basic-addon3"><img src="img/lupa.png" width="20" height="20" alt="" sizes="" srcset=""></span>
                                    </div>
                                    <input type="text" list="datosCodigos" class="form-control" id="codigo" aria-describedby="basic-addon3">
                                    <datalist id="datosCodigos" active>
                                        <!-- 
                                        <?
                                        $buscar = $consulta->query("SELECT * FROM productos WHERE inactivo is null");

                                        foreach ($buscar as $fila) {
                                            if ($fila['idproductos'] == 0) {
                                            } else {
                                        ?>
                                                <option value='$fila[codigo]'>
                                            <?
                                            }
                                        }
                                            ?>
                                    </datalist> -->
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <label for="basic-url">Cantidad</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><img src="img/gato.png" width="20" height="20" alt="" sizes="" srcset=""></span>
                                    </div>
                                    <input type="text" class="form-control" id="cantidad" aria-describedby="basic-addon3">
                                </div>
                            </div>

                            <div class="col-sm-2"><br>
                                <button type="submit" class="btn btn-primary btn-sm" id="generar"> <Strong>Generar</Strong> <i class="fas fa-barcode"></i></button>
                                <button type="submit" style="display:none" class="btn btn-danger btn-sm" id="btn_limpio"> <Strong>limpiar</Strong> <i class="fas fa-broom"></i></button>

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
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="center"><br><br>
                <div class="card">
                    <div class="card-header">
                        Vista Previa
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">cantidad</th>
                                    <th scope="col">Diseño</th>
                                    <th scope="col">opciones</th>
                                </tr>
                            </thead>
                            <tbody id="mytable">

                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="row">

                </div>



            </div>
            <div class="col-sm-2"></div>

        </div>

    </div>


</body>
<script>
    $(document).ready(function() {

        Carga_Codigos();

        function Carga_Codigos() {
            $.ajax({
                type: "POST",
                url: "<?= $_SERVER["PHP_SELF"] ?>",
                data: ({
                    funcion: "DataListCodigos"
                }),
                dataType: "html",
                async: false,
                success: function(msg) {
                    //alert(msg);
                    $("#datosCodigos").html(msg);
                    // btn_generar();


                }
            });
        }
        $("#codigo").focus();
        $(document).on('click', '#generar', function() {
            if ($("#codigo").val() == '') {
                alertify.alert("Ingresa una valor Correcto");
                $("#codigo").focus().select();
                return false;
            }
            generarCodigo();
            $("#card1").show(300);
            // $("#btn_limpio").show(200);
            //$("#generar").hide();
            //   $('#codigo').attr("disabled", true);
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
            var i = 0;
            i++;
            if (i == "") {
                i = "1";
            }
            var e = 0;
            e++;
            if (e == "") {
                e = "1";
            }
          
            var codigog = 'prueba';
            var codigo1 = $("#codigo").val();
            var split = codigo1.split("-");
            var codigo =split[0];
            var producto =split[1];
           // alert(codigo);
          //  return false;
            var num = Math.round(Math.random() * (10000 - 1) + 1);
            var codigoV = '<img data-value="" id="genCodigo' + num + '" data-text="" class="codigo" />';
            var cantidad = $("#cantidad").val();
            var fila = '<tr class="valor_columna " id="row' + i + '"><td class="Producto_fin">' +
                producto + '</td><td class="codigo_prod">' +
                codigo + '</td><td class="cantidad_fin">' +
                cantidad + '</td><td class="codigo_barras">' +
                codigoV + '</td><td><button type="button" name="remove" id="' +
                i + '"class="btn btn-danger btn-sm btn_editar">Eliminar <i class="far fa-edit"></i></button></td></tr>'; //esto seria lo que contendria la fila

            $('#mytable').append(fila);
            $("#adicionados").text(""); //esta instruccion limpia el div adicioandos para que no se vayan acumulando
            var nFilas = $("#mytable tr").length;
            JsBarcode("#genCodigo" + num, {
                format: "pharmacode",
                lineColor: "#0aa",
                width: 2,
                height: 10,
                displayValue: true
            });

            $('#genCodigo' + num).attr("data-value", codigo);
            $('#genCodigo' + num).attr("data-text", codigo);
            JsBarcode(".codigo").init();
            $("#codigo").val("");
            $("#cantidad").val("");
            $("#codigo").focus();


        }

        function imprimir() {
            
            var Detalle = new Array();
                    var cnt = 0;
                    var valores = "";
                    $(".valor_columna").each(function() {
                            var codigo = $(this).find(".codigo_prod").text();
                            var cantidad = $(this).find(".cantidad_fin").text();
                           
                            var espacio = "-";

                            //alert(espacio);
                            Detalle[cnt] = Array(codigo, cantidad);
                            //alert(Detalle[cnt]);
                            cnt++;

                            
                    });
                    var codigo = $("#codigo").val();
                            var bob = window.open('', '_new');
                            bob.location = "codigo_masa.php?codigo=" + Detalle;
                            window.location = "codigobarras.php";
        }

    });
    $(document).on('click', '.btn_editar', function() {

        $(this).parent().parent().remove(); //borra la fila
        //limpia el para que vuelva a contar las filas de la tabla
        $("#adicionados").text("");
        var nFilas = $("#mytable tr").length;
    });
    $(document).ready(function() {
        $('#selectedColumn').DataTable({
            "aaSorting": [],
            columnDefs: [{
                orderable: false,
                targets: 3
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>


</html>