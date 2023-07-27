$(document).ready(function () {
  var url_string = URL_PROYECT + "app/api/api-sinube.php";
  var table = $("#products_table")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      dom: "Bfrtipl",
      lengthMenu: [10, 25, 50, 100],
      iDisplayLength: 10,
      searching: true,
      lengthChange: false,
      colReorder: true,
      buttons: [
        "copy",
        "csv",
        "excel",
        "pdf",
        "print",
        "colvis",
        {
          extend: "csv",
          action: function (e, dt, node, config) {
            $("#miArchivoInput").trigger("click");
          },
        },
      ],
      ajax: {
        url: url_string,
        type: "post",
        dataType: "json",
        dataSrc: function (data) {
          // Muestra la data recibida en la consola del navegador
          console.log(data);

          // Devuelve la data sin procesar (sin cambios) para que DataTables la muestre en la tabla
          return data.result;
        },
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
      responsive: true,
      bInfo: true,
      autoWidth: true,
      language: {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo: "Mostrando un total de _TOTAL_ registros",
        sInfoEmpty: "Mostrando un total de 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix: "",
        sSearch: "Buscar:",
        sUrl: "",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "Siguiente",
          sPrevious: "Anterior",
        },
        oAria: {
          sSortAscending:
            ": Activar para ordenar la columna de manera ascendente",
          sSortDescending:
            ": Activar para ordenar la columna de manera descendente",
        },
      },
      columnDefs: [
        {
          targets: 0,
          orderable: false,
          className: "dt-body-center",
          render: function (data, type, full, meta) {
            return '<input type="checkbox" data-id-product="' + data + '">';
          },
        },
      ],
      columns: [
        { data: "producto" }, // Asocia la propiedad 'descripcion' del objeto al segundo campo de la tabla
        {
          data: "descripcion",
          render: function (data, type, row) {
            // Renderiza el primer campo de entrada para cada fila
            return (
              '<a href="#" data-toggle="modal" data-target="#modal-lg">' +
              data +
              "</a>"
            );
          },
        }, // Asocia la propiedad 'descripcion' del objeto al segundo campo de la tabla
        { data: "precio" }, // Asocia la propiedad 'precio' del objeto al cuarto campo de la tabla
        { data: "precioMinimo" }, // Asocia la propiedad 'precioMinimo' del objeto al quinto campo de la tabla
        { data: "rfc" }, // Asocia la propiedad 'rfc' del objeto al sexto campo de la tabla
        { data: "sucursal" }, // Asocia la propiedad 'sucursal' del objeto al séptimo campo de la tabla
        { data: "existencias" }, // Asocia la propiedad 'existencias' del objeto al tercer campo de la tabla
        {
          data: null,
          render: function (data, type, row) {
            // Renderiza el primer campo de entrada para cada fila
            return (
              '<input type="number" class="form-control" name="" value="0" min=0 max="' +
              data.existencias +
              '">'
            );
          },
        },
        {
          data: null,
          render: function (data, type, row) {
            // Renderiza el primer campo de entrada para cada fila
            return '<input type="number" class="form-control" value="0" min=0>';
          },
        },
      ],
    })
    .DataTable()
    .buttons()
    .container()
    .appendTo("#products_table .col-md-6:eq(0)");

  // Evento draw.dt para truncar el texto en la columna "descripcion" después de cada redibujado
  table.on("draw.dt", function () {
    table.cells(".truncate-column").render(function (data, type, row) {
      if (type === "display" && data.length > 50) {
        return data.substr(0, 50) + "...";
      }
      return data;
    }, "display");
  });|
  $("#summernote").summernote({
    height: 150, //set editable area's height
    codemirror: {
      // codemirror options
      theme: "monokai",
    },
  });

  $("#miArchivoInput").on("change", function (e) {
    var file = e.target.files[0];
    table.clear().draw();
    Papa.parse(file, {
      header: true,
      complete: function (results) {
        var data = results.data;
        // Eliminar filas de encabezado (si es necesario)
        data.shift();

        // Creamos un arreglo con objetos que contienen los datos de cada fila del archivo CSV
        var newData = data.map(function (row) {
          return {
            producto: row.producto,
            descripcion: row.descripcion,
            precio: row.precio,
            precioMinimo: row.precioMinimo,
            rfc: row.rfc,
            sucursal: row.sucursal,
            existencias: row.existencias,
            cantidad1:
              '<input type="number" class="form-control" value="' +
              (row.cantidad1 || 0) +
              '" min="0">',
            cantidad2:
              '<input type="number" class="form-control" value="' +
              (row.cantidad2 || 0) +
              '" min="0">',
          };
        });

        // Agregamos los nuevos datos a la tabla utilizando rows.add() y redibujamos la tabla
        table.rows.add(newData).draw();
      },
    });
  });
});
