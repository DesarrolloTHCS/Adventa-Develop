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

                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php
Layout::layoutFooter();
?>

