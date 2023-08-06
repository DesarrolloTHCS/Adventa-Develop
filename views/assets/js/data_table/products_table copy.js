


  const exportButton = document.getElementById("btn_sales_note");
  exportButton.addEventListener("click", function() {
    const table = document.getElementById("products_table"); // Reemplaza con el ID de tu tabla
    
    const dataTable = $(table).DataTable();
    // Obtén los datos de la tabla
var tableData = [];

// Recorre las filas de la tabla para recolectar los valores de los inputs
dataTable.rows().every(function () {
  var rowData = this.data(); // Datos de la fila actual
  var inputColumn1 = this.nodes().to$().find('input.input-product').val();
  var inputColumn2 = this.nodes().to$().find('input.input-excedent-product').val();

  // Agrega los valores y el número de fila al arreglo de datos
  tableData.push({
    id_product: rowData.id_product, // Supongo que esto identifica la fila
    input_product: inputColumn1,
    input_excedent_product: inputColumn2,
  });
});

    console.log(tableData);
  });



/**
 * Author: Alfredo Segura Vara <pixxo2010@gmail.com>
 * Description: OBTIENE EL DETALLE DEL PRODUCTO Y LOS MUESTRA EN EL MODAL
 * @param {} id_product 
 */

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
          const imageThumbs = document.querySelectorAll('.product-image-thumb');

          imageThumbs.forEach(function(imageThumb) {
            imageThumb.addEventListener('click', function() {
              const imageElement = this.querySelector('img');
              const productImage = document.querySelector('.product-image');
              const activeThumb = document.querySelector('.product-image-thumb.active');
        
              productImage.src = imageElement.getAttribute('src');
        
              if (activeThumb) {
                activeThumb.classList.remove('active');
              }
        
              this.classList.add('active');
            });
          });

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
              <div class="product-image-thumb" ><img src="${URL_PROYECT}storage/images/products/${element.product_image_2}" alt="Product Image"></div>
              <div class="product-image-thumb" ><img src="${URL_PROYECT}storage/images/products/${element.product_image_3}" alt="Product Image"></div>
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
