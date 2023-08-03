$(document).ready(function () {
  var url = URL_PROYECT + "app/api/api-products.php";
  var table = $("#products_table").DataTable({
      aProcessing: true,
      aServerSide: true,
      searching: true,
      dom: '<"top"l<"float-right"f>>Brtip',
      lengthChange: true,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
      iDisplayLength: 10,
      colReorder: true,
      buttons: [
        "colvis",
        {
          extend: "csv",
          text: "Importar CSV",
          action: function (e, dt, node, config) {
            $("#miArchivoInput").trigger("click");
          },
        },
        [
          {
            extend: "csv",
            text: "Exportar CSV",
            exportOptions: {
              columns: [0, 2, 3, 4, 5, 6, 7, 8, 9],
              format: {
                body: function (data, row, column, node) {
                  // Si el nodo contiene un checkbox, obtenemos el valor del atributo "data-id-product"
                  if ($(node).find('input[type="checkbox"]').length > 0) {
                    return $(node)
                      .find('input[type="checkbox"]')
                      .data("id-product");
                  }
                  // Si el nodo contiene etiquetas HTML, devolvemos solo el texto visible
                  if ($(node).children().length > 0) {
                    return $(node).text();
                  }
                  return data;
                },
              },
            },
            filename: "productos", // Nombre del archivo CSV
          },
        ],
      ],
      ajax: {
        url: url + "?type=products",
        type: "GET",
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
        { data: "id_product" }, // Asocia la propiedad 'descripcion' del objeto al segundo campo de la tabla
        {
          data: "product_image_1",
          render: function (data, type, row) {
            // Renderiza el primer campo de entrada para cada fila
            return (
              '<img src="' +
              URL_PROYECT +
              "storage/images/products/" +
              data +
              '" alt="Product" width=52px heigth=52px>'
            );
          },
        },
        {
          data: "description_product",
          render: function (data, type, row, full) {
            id_product = row.id_product;
            // Renderiza el primer campo de entrada para cada fila
            return (
              '<a href="#" onClick="productDetail(' +
              id_product +
              ')" data-toggle="modal" data-target="#modal-lg">' +
              data +
              "</a>"
            );
          },
        }, // Asocia la propiedad 'descripcion' del objeto al segundo campo de la tabla
        { data: "brand_product" }, // Asocia la propiedad 'rfc' del objeto al sexto campo de la tabla
        { data: "category_product" }, // Asocia la propiedad 'precio' del objeto al cuarto campo de la tabla
        { data: "price_sinube" }, // Asocia la propiedad 'precio' del objeto al cuarto campo de la tabla
        { data: "price_minimum_sinube" }, // Asocia la propiedad 'precioMinimo' del objeto al quinto campo de la tabla
        { data: "existence_product" }, // Asocia la propiedad 'existencias' del objeto al tercer campo de la tabla
        {
          data: null,
          render: function (data, type, row) {
            // Renderiza el primer campo de entrada para cada fila
            return (
              '<input type="number" class="form-control" value="0" min=0 max="' +
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
    .buttons()
    .container()
    .appendTo("#products_table .col-md-6:eq(0)");

  // Personalizar los botones con clases de Bootstrap
  $(".buttons-colvis").addClass("btn-warning"); // Cambiar el color del botón Colvis (verde)
  $(".buttons-csv").addClass("btn-success"); // Cambiar el color del botón Exportar CSV (amarillo)

  $("#summernote").summernote({
    height: 150, //set editable area's height
    codemirror: {
      // codemirror options
      theme: "monokai",
    },
  });

  $("#miArchivoInput").on("change", function (e) {
    
    data=importFile(); //trae una arreglo de jsons
    
    // Limpiar la tabla antes de llenarla con los nuevos datos
    table=$("#products_table").DataTable().clear().draw();


        // Recorre el JSON y agrega las filas a la tabla
        for (let item in data) {
    console.log(item);
      table.row.add([
      item['Selcc'],
      item['Selcc'],
      item['Descripcion'],
      item['Marca'],
      item['Precio'],
      item['Precio_Minimo'],
      item['Existencia'],
      item['Cantidad_Productos'],
      item['Cantidad_Excedente']
      ]).draw(false); // draw(false) evita redibujar la tabla en cada iteración para mejor rendimiento
  }
  });


});

function productDetail(id_product) {
  fetch(
    `${URL_PROYECT}app/api/api-products.php?type=detail&id_product=${id_product}`,
    {
      method: "GET",
    }
  )
    .then(parseResponse)
    .then((response) => {
      if (response.status == 200) {
        let body_modal = document.getElementById("body_modal_detail");
        let body = ``;
        response.body.result.forEach((element) => {
          body = bodyModal(element);
        });

        body_modal.innerHTML = body;
      } else {
        throw new Error("Error en la petición");
      }
    });
}

function bodyModal(element) {
  body = `
        <!-- Main content -->
        <section class="content">
    
    <!-- Default box -->
    <div class="card card-solid">
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-sm-6">
            <h3 class="d-inline-block d-sm-none title-modal-product">${element.description_product}</h3>
            <div class="col-12">
              <img src="${URL_PROYECT}storage/images/products/${element.product_image_1}" class="product-image" alt="Product Image">
            </div>
            <div class="col-12 product-image-thumbs">
              <div class="product-image-thumb active"><img src="${URL_PROYECT}storage/images/products/${element.product_image_1}" alt="Product Image"></div>
              <div class="product-image-thumb" ><img src="${URL_PROYECT}storage/images/products/${element.product_image_1}" alt="Product Image"></div>
              <div class="product-image-thumb" ><img src="${URL_PROYECT}storage/images/products/${element.product_image_1}" alt="Product Image"></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <h3 class="my-3 title-modal-product">${element.description_product}</h3>
            <p>${element.description_product}</p>
            <hr>    
            
            <div class="bg-primary py-2 px-3 mt-4">
              <h2 class="mb-0">$ 
              ${element.price_sinube} mxn c/u
              </h2>
              <h4 class="mt-0">
                <small>Stock: ${element.existence_product}</small>
              </h4>
            </div>

            <div class="mt-4 product-share">
              <a href="https://es-la.facebook.com/DIODImx/" target=_blank class="text-gray">
                <i class="fab fa-facebook-square fa-2x"></i>
              </a>
              <a href="https://www.diodi.mx/" target=_blank class="text-gray">
              <i class="fa-solid fa-d fa-2x"></i>
              </a>
              <a href="mailto:ventas@diodi.mx" target=_blank class="text-gray">
                <i class="fas fa-envelope-square fa-2x"></i>
              </a>
            </div>
    
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
    
    </section>
    <!-- /.content -->
        `;
  return body;
}
