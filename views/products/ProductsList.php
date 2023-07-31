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
              
              <input type="file" id="miArchivoInput" class="form-control d-none" accept=".csv">

                <table id="products_table" class="table table-bordered table-striped text-center">
                  <thead>
                    <tr>
                      <th scope="col" class=" col-1">Selcc. <input type="checkbox"></th>
                      <th scope="col" class=" col-1"></th>
                      <th scope="col" class=" col-1">Descripcion</th>
                      <th scope="col" class=" col-1">Marca</th>
                      <th scope="col" class=" col-2">Categoría</th>    
                      <th scope="col" class=" col-1">Precio</th>
                      <th scope="col" class=" col-2">Precio_Minimo</th>
                      <th scope="col" class=" col-2">Existencia</th>
                      <th scope="col" class=" col-1">Cantidad_Productos</th>
                      <th scope="col" class=" col-1">Cantidad_Excedente</th>
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
              <h4 class="modal-title">Producto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="body_modal_detail">
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
<script src="../../public/library/papaparse/papaparse.min.js"></script>
<script src="../../public/library/papaparse/xlxs.min.js"></script>
<script src="../assets/js/import/import_cvs.js"></script>
<script src="../assets/js/data_table/products_table.js"></script>