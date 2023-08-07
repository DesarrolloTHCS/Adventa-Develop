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
              <h1>Tabla con Funcionalidades</h1>
              <div class="form-inline mb-2">
                <label for="recordsPerPage">Registros por página:</label>
                <select id="recordsPerPage" class="form-control ml-2">
                  <option value="10">10</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="all">Todos</option>
                </select>
                <input type="text" id="searchInput" class="form-control ml-auto" placeholder="Buscar...">
              </div>
              <table id="products_list" class="table table-striped">
                <thead>
                  <tr>
                    <th>
                      <input type="checkbox" id="selectAll">
                    </th>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Precio Mínimo</th>
                    <th>Existencia</th>
                    <th>Cantidad Productos</th>
                    <th>Cantidad Excedente</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Filas de la tabla serán agregadas dinámicamente aquí -->
                </tbody>
              </table>
              <button id="exportCSV" class="btn btn-primary">Exportar CSV</button>
              <button id="importCSV" class="btn btn-secondary">Importar CSV</button>
              <button id="createOrder" class="btn btn-success">Levantar Orden</button>
              <nav aria-label="Page navigation">
                <ul id="pagination" class="pagination">
                  <!-- Elementos de paginación serán agregados dinámicamente aquí -->
                </ul>
              </nav>
          </div>
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