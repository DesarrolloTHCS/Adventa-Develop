<?php

use App\Controllers\Orders\OrdersController;
use Views\Layout\Layout;
use Views\Products\ProductsDetails;

require_once '../../vendor/autoload.php';
Layout::layoutHeader();
$id_user = $_SESSION['id_user'];
$orders = OrdersController::getOrdersByIdUser($id_user);
$html=<<<HTML
HTML;

?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1> Lista de ordenes</h1>
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
            Lista de ordenes
          </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <button id="exportCSV" class="btn btn-primary">
              <i class="fa-solid fa-download"></i> Exportar CSV</button>
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
            <div class="table-responsive">
            <table id="orders_list" class="table table-striped">
              <thead>
                <tr>
                  <th>
                    <input type="checkbox" id="selectAll">
                  </th>
                  <th>ID</th>
                  <th>Nota_venta</th>
                  <th>Cantidad_productos</th>
                  <th>Total</th>
                  <th>Total_sin_iva</th>
                  <th>IVA</th>
                  <th>Estado</th>
                  <th>Fecha de alta</th>
                  <th>Fecha de actualización</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (!empty($orders)){
                foreach ($orders as $key => $order) {
                  $html .= <<<HTML
            <tr>
            <td><input type="checkbox" class="rowCheckbox"></td>
            <td>{$order->id_order}</td>
            <td>
HTML;              
            
            $order->id_nota_venta_sinube !="" ? $html .= "<span class='badge badge-success'>Vendido</span>" : $html .= "<span class='badge badge-warning'>Pendiente</span>";
        $html .= <<<HTML
            </td>
            <td>{$order->quantity_products_order}</td>
            <td>{$order->total_coust_order}</td>
            <td>{$order->total_coust_out_vat_order}</td>
            <td>{$order->vat_order}</td>
            <td>
HTML;
              $order->status_order == 1 ? $html .= "<span class='badge badge-success'>Activo</span>" : $html .= "<span class='badge badge-warning'>Inactivo</span>";
        $html .= <<<HTML
            </td>
            <td>{$order->created_at}</td>
            <td>{$order->updated_at}</td>
            </tr>
HTML;
                }
              }else{
                $html .= <<<HTML
                <tr>
                <td colspan="10" class="text-center">No hay registros</td>
                </tr>
HTML;
              }
                echo $html;
                ?>
                <!-- Filas de la tabla serán agregadas dinámicamente aquí -->

              </tbody>
            </table>
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

<div class="modal fade" id="modal-detail-product">
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