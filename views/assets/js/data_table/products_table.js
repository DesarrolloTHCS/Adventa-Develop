//document.addEventListener("DOMContentLoaded",function () {
try {
  $("#summernote").summernote({
    tabsize: 2,
    minHeight: 120,
    maxHeight: 250,
    toolbar: [
      ["style", ["style"]],
      ["font", ["bold", "underline", "clear"]],
      ["color", ["color"]],
      ["para", ["ul", "ol", "paragraph"]],
      ["view", ["fullscreen"]],
    ],
  });

  const URL_PRODUCTS = URL_PROYECT + "app/api/api-products.php";

  function updateTableData() {
    fetch(URL_PRODUCTS + "?type=products", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then(parseResponse)
      .then((data) => {
        if (data.status === 200) {
          console.log(data.body.result);
          loadTableData(data.body.result);
        } else {
          console.log("Error");
        }
      })
      .catch((error) => console.log(error));
  }

  // Función para cargar los datos JSON en la tabla
  function loadTableData(data) {
    let tbody = document.querySelector("#products_list tbody");
    tbody.innerHTML = ""; // Limpiar contenido anterior de la tabla
    data.forEach((item) => {
      console.log(tbody);
      let row = `
          <tr>
            <td><input type="checkbox" class="rowCheckbox"></td>
            <td>${item.id_product}</td>
            <td><a href="#" onClick="productDetail(${item.id_product})" data-toggle="modal" data-target="#modal-detail-product">${item.description_product}</a></td>
            <td>${item.brand_product}</td>
            <td>${item.category_product}</td>
            <td>${item.price_sinube}</td>
            <td>${item.price_minimum_sinube}</td>
            <td>${item.existence_product}</td>`;

      if (item.existence_product == 0) {
        row += `    
              <td><input type="number" class="form-control cantidadProductos" value="" disabled></td>  
              <td><input type="number" class="form-control cantidadExcedente" value=""></td>
              `;
      } else {
        row += `    
            <td><input type="number" class="form-control cantidadProductos" value="${item.cantidadProductos}"></td>
            <td><input type="number" class="form-control cantidadExcedente" value="${item.cantidadExcedente}"></td>
          </tr>
        `;
      }
      tbody.insertAdjacentHTML("beforeend", row);
    });
  }

  // Función para manejar el evento de selección/deselección de todos los checkbox
  const selectAllCheckbox = document.querySelector("#selectAll");
  selectAllCheckbox.addEventListener("change", function () {
    const rowCheckboxes = document.querySelectorAll(".rowCheckbox");
    rowCheckboxes.forEach((checkbox) => {
      checkbox.checked = selectAllCheckbox.checked;
    });
  });

  // Función para exportar los datos de la tabla a CSV
  const exportCSVButton = document.querySelector("#exportCSV");
  exportCSVButton.addEventListener("click", function () {
    const csvContent =
      "data:text/csv;charset=utf-8," + encodeURIComponent(getTableCSV());
    const link = document.createElement("a");
    link.setAttribute("href", csvContent);
    link.setAttribute("download", "tabla.csv");
    link.click();
  });

  // Función para obtener los datos de la tabla en formato CSV
  function getTableCSV() {
    const rows = [];
    const headers = Array.from(document.querySelectorAll("#products_list th"))
      .slice(1)
      .map((th) => th.textContent);
    rows.push(headers.join(","));

    const tableRows = document.querySelectorAll("#products_list tr");
    tableRows.forEach((row) => {
      const rowData = Array.from(row.querySelectorAll("td"))
        .slice(1)
        .map((td) => {
          const cellText = td.textContent.trim();
          // Si la celda contiene comas, rodearla con comillas dobles
          return cellText.includes(",") ? `"${cellText}"` : cellText;
        });

      // Check if rowData contains only whitespace
      const isBlankRow = rowData.every((cell) => cell.trim() === "");

      if (!isBlankRow) {
        rows.push(rowData.join(","));
      }
    });

    return rows.join("\n");

    // Agregar funcionalidad de paginación (requiere implementación adicional)
    function setupPagination(totalPages, currentPage) {
      const pagination = document.querySelector("#pagination");
      pagination.innerHTML = "";

      for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement("li");
        const link = document.createElement("a");
        link.href = "#";
        link.textContent = i;
        li.appendChild(link);
        if (i === currentPage) {
          li.classList.add("active");
        }
        pagination.appendChild(li);

        link.addEventListener("click", function () {
          loadTableDataPaginated(jsonData, i); // Debes implementar esta función
          setupPagination(totalPages, i);
        });
      }
    }
  }
  // Agregar funcionalidad de búsqueda (requiere implementación adicional)
  const searchInput = document.querySelector("#searchInput");
  searchInput.addEventListener("input", function () {
    const searchTerm = searchInput.value.toLowerCase();
    const filteredData = jsonData.filter((item) => {
      return (
        item.descripcion.toLowerCase().includes(searchTerm) ||
        item.marca.toLowerCase().includes(searchTerm) ||
        item.categoria.toLowerCase().includes(searchTerm)
      );
    });
    loadTableData(filteredData);
  });

  // Agregar funcionalidad de importar CSV usando Papaparse
  const importCSVButton = document.querySelector("#importCSV");
  importCSVButton.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      Papa.parse(file, {
        complete: function (results) {
          const importedData = results.data.filter((row) => !isRowEmpty(row)); // Eliminar filas vacías
          console.log(importedData);
          populateTableWithImportedData(importedData);
        },
        header: true, // Indicar que la primera fila contiene encabezados
      });
    }
  });
  function isRowEmpty(row) {
    return Object.values(row).every((value) => value.trim() === "");
  }

  function populateTableWithImportedData(importedData) {
    const tbody = document.querySelector("#products_list tbody");
    tbody.innerHTML = ""; // Limpiar contenido anterior de la tabla
    importedData.forEach((columns) => {
      const row = `
    <tr>
      <td><input type="checkbox" class="rowCheckbox"></td>
      <td>${columns["ID"]}</td>
      <td><a href="#" onClick="productDetail(${columns["ID"]})" data-toggle="modal" data-target="#modal-detail-product">${columns["Descripción"]}</a></td>
      <td>${columns["Marca"]}</td>
      <td>${columns["Categoría"]}</td>
      <td>${columns["Precio"]}</td>
      <td>${columns["Precio Mínimo"]}</td>
      <td>${columns["Existencia"]}</td>
      <td><input type="number" class="form-control cantidadProductos" value="${columns["Cantidad Productos"]}"></td>
      <td><input type="number" class="form-control cantidadExcedente" value="${columns["Cantidad Excedente"]}"></td>
    </tr>
  `;
      tbody.insertAdjacentHTML("beforeend", row);
    });
  }

  // Función para levantar la orden y obtener los valores como JSON
  const createOrderButton = document.querySelector("#createOrder");
  createOrderButton.addEventListener("click", function () {
    const orderData = [];
    const selectedCheckboxes = document.querySelectorAll(
      ".rowCheckbox:checked"
    );
    selectedCheckboxes.forEach((checkbox) => {
      const row = checkbox.closest("tr");
      const id = row.querySelector("td:nth-child(2)").textContent;
      const cantidadProductos = row.querySelector(".cantidadProductos").value;
      const cantidadExcedente = row.querySelector(".cantidadExcedente").value;
      orderData.push({
        id: id,
        cantidadProductos: cantidadProductos,
        cantidadExcedente: cantidadExcedente,
      });
    });
    const orderJSON = JSON.stringify(orderData);
    productOrder(orderJSON);
  });
} catch (error) {
  console.log(error);
}
//});

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
          body_modal.innerHTML = body;
        });
      } else {
        throw new Error("Error en la petición");
      }
    });
}

let imageThumbs = document.querySelectorAll(".product-image-thumb");
imageThumbs.forEach(function (imageThumb) {
  imageThumb.addEventListener("click", function () {
    const imageElement = this.querySelector("img");
    const productImage = document.querySelector(".product-image");
    const activeThumb = document.querySelector(".product-image-thumb.active");

    productImage.src = imageElement.getAttribute("src");

    if (activeThumb) {
      activeThumb.classList.remove("active");
    }

    this.classList.add("active");
  });
});

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

async function productOrder(data) {
  const URL_ORDERS = URL_PROYECT + "app/api/api-orders.php";
  await fetch(URL_ORDERS + "?type=order", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: data,
  })
    .then(parseResponse)
    .then((data) => {
      updateTableData();
      console.log(data);
    });
}
