<?php
use Views\Layout\Layout;
use Views\Products\ProductsDetails;
require_once '../../vendor/autoload.php';

Layout::layoutHeader();
?>
 <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Lista de produtos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Lista de productos</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Lista de productos
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
              
              <input type="file" id="miArchivoInput" class="form-control" accept=".csv">

                <table id="products_table" class="table table-bordered table-striped text-center">
                  <thead>
                    <tr>
                      <th scope="col" class=" col-1">Selcc. <input type="checkbox"></th>
                      <th scope="col" class=" col-1">Descripcion</th>
                      <th scope="col" class=" col-1">Descripción</th>
                      <th scope="col" class=" col-2">Marca</th>    
                      <th scope="col" class=" col-1">Stock</th>
                      <th scope="col" class=" col-2">Selecciona cantidad</th>
                      <th scope="col" class=" col-2">Stock</th>
                      <th scope="col" class=" col-1">Seleccion de producto</th>
                      <th scope="col" class=" col-1">Cantidad Excedente</th>
                    </tr>
                  </thead>                  
                  <tbody>
                    <tr>
                    </tr>
                  </tbody>
                </table>
              </div>
            <!-- /.card -->
            </div>
          </div>
        </div>
        <!-- /.col-->
        <div class="col-12">
              <div class="mb-3">
                <label for="" class="form-label">Observaciones</label>
                <textarea class="summernote" name="obeservation" id="summernote"></textarea>
              </div>
              </div>
      </div>
      </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Proeductos:</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <?php 
               ProductsDetails::productsDetails();
               ?>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
<?php
Layout::layoutFooter();
?>
<script src="../assets/js/data_table/products_table.js"></script>