<?php
use Views\Layout\Layout;
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
                <table id="products_table" class="table table-bordered table-striped text-center">
                  <thead>
                    <tr>
                      <th scope="col" class=" col-1">Selcc.</th>
                      <th scope="col" class=" col-1">No.Producto</th>
                      <th scope="col" class=" col-1">Descripción</th>
                      <th scope="col" class=" col-2">Marca</th>    
                      <th scope="col" class=" col-1">Stock</th>
                      <th scope="col" class=" col-2">Selecciona cantidad</th>
                      <th scope="col" class=" col-2">Excedente</th>
                    </tr>
                  </thead>
                  
                  <tbody>
                    <tr>
                        <td><input type="checkbox" class="form-control" name="" id=""></td>
                        <td>1</td>
                        <td class="truncate" style="font-size: 12px;">Lorem ipsum dolor, sit amet consectetur.adipisicing elit. Dolores pariatur omnis commodi mollitia laudantium qui?</td>
                        <td>Lorem</td>
                        <td>100</td>
                        <td><input type="number" class="form-control" min=0 id=""></td>
                        <td><input type="number" class="form-control" min=0 id=""></td>
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
                <textarea class="summernote" name="" id="summernote"></textarea>
              </div>
              </div>
      </div>
      </section>
    <!-- /.content -->

<?php
Layout::layoutFooter();
?>
<script src="assets/js/data_table/products_table.js"></script>