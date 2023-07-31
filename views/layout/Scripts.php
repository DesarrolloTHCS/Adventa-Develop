<?php

namespace Views\Layout;

use App\Traits\App;

class Scripts
{
    use App;
    static function scripts()
    {
        
        $path = self::PATH_LIBRARIES;
        $path_dist = self::PATH_DIST;
        $path_assets = self::PATH_ASSETS;
        $html = <<<HTML
        <!-- jQuery -->
<script src="{$path}jquery/jquery.min.js"></script>

<!-- Bootstrap 4 -->
<script src="{$path}bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="{$path}bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="{$path}datatables/jquery.dataTables.min.js"></script>
<script src="{$path}datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{$path}datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{$path}datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{$path}datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{$path}datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{$path}jszip/jszip.min.js"></script>
<script src="{$path}pdfmake/pdfmake.min.js"></script>
<script src="{$path}pdfmake/vfs_fonts.js"></script>
<script src="{$path}datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{$path}datatables-buttons/js/buttons.print.min.js"></script>
<script src="{$path}datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="{$path_dist}js/adminlte.min.js"></script>
<!-- Summernote -->
<script src="{$path}summernote/summernote-bs4.min.js"></script>
<!-- ChartJS -->
<script src="{$path}chart.js/Chart.min.js"></script>
<!-- SwetAlert2 -->
<script src="{$path}sweetalert2/sweetalert2.min.js"></script>
<script src="{$path_assets}js/app.js"></script>


HTML;
        echo $html;
    }
}
