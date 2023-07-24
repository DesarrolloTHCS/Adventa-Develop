<?php
use Views\Layout\Layout;
require_once '../vendor/autoload.php';

Layout::layoutHeader();
?>
 <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Inicio</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Dashboad</li>
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
                Inicio
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="products_table" class="table table-bordered table-striped text-center">
                  <thead>
                    <tr>
                      <th scope="col" class=" col-1">No.Producto</th>
                      <th scope="col" class=" col-1">Categoria</th>
                      <th scope="col" class=" col-2">Titulo</th>    
                      <th scope="col" class=" col-1">Estado</th>
                      <th scope="col" class=" col-2">Fecha</th>
                      <th scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              
          
            </div>
            <!-- /.card -->
            </div>
            
          
          </div>
        </div>
        <!-- /.col-->
        <div class="col-12">
              <div class="mb-3">
                <label for="" class="form-label">Descripción</label>
                <textarea class="summernote" name="" id="summernote" rows="3" readonly></textarea>
              </div>
              </div>
      </div>
      </section>
    <!-- /.content -->

<?php
Layout::layoutFooter();
?>
<script src="assets/js/data_table/products_table.js"></script>