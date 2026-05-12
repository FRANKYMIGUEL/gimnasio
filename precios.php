<?

include("inc/conectar.php");



  session_start();

  if(isset($_SESSION['usuario'])==false){

       header('Location: ../');

    }else{

      $consulta=null;

    }

?>



<!DOCTYPE html>



<html>

<head>

</head>

<body>

  <?php include("menu.php") ?>



<br>

<div class="col-md-offset-3">

  <div class="row">

    <div class="container-fluid">

      <div class="col-md-12">

        <table id="tablaPrecios" class="table table-striped table-bordered table-responsive" style="width:100%;">

            <thead>

                <tr>

                  <td>Acciones</td>

                    <td>ID</td>

                    <td>Codigo</td>

                    <td>Codigo Barras</td>

                    <td>Nombre</td>

                    <td>Costo</td>

                    <td>Lista 1</td>

                    <td>Lista 2</td>

                    <td>Lista 3</td>

                    <td>Lista 4</td>

                    <td>Lista 5</td>

                    <td>Lista 6</td>



                </tr>

            </thead>

            <tbody></tbody>

            <tfoot>

                <tr>

                  <td>Acciones</td>

                    <td>ID</td>

                    <td>Codigo</td>

                    <td>Codigo Barras</td>

                    <td>Nombre</td>

                    <td>Costo</td>

                    <td>Lista 1</td>

                    <td>Lista 2</td>

                    <td>Lista 3</td>

                    <td>Lista 4</td>

                    <td>Lista 5</td>

                    <td>Lista 6</td>

                </tr>

            </tfoot>

        </table>

      </div>

    </div>  

  </div>

</div> 



















<!--MODAL PARA MODIFICAR LOS PRECIOS-->

<div id="editarPrecioProductoModal" class="modal fade" role="dialog">



  <div class="modal-dialog modal-lg">



    <!-- Contenido del Modal-->



    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Modificar Precios</h4>

      </div>

      <div class="modal-body">

        <input type="text" id="idProducto" disabled style="display: none;">



      <div class="row">

        <div class="container-fluid">



          <div id="datosPreciosProductos"></div>

        </div>



      </div>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

         <button type="button" class="btn btn-primary modificarPrecio" data-dismiss="modal">Modificar</button>

      </div>

    </div>

  </div>

</div>

</body>

</html>