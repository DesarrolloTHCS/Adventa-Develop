document.addEventListener("DOMContentLoaded", function () {
  const apiUrl = 'app/api/api-products.php';
  let productsData = [];
  let currentPage = 1;
  let itemsPerPage = 10;

  const fetchData = async () => {
      try {
          const response = await fetch(apiUrl);
          productsData = await response.json();
          renderTable();
      } catch (error) {
          console.error("Error fetching data:", error);
      }
  };

  const renderTable = () => {
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;

      const paginatedData = productsData.slice(startIndex, endIndex);
      const tableBody = document.querySelector('#productTable tbody');
      tableBody.innerHTML = '';

      paginatedData.forEach((product, index) => {
          const row = `
              <tr>
                  <td><input type="checkbox" class="productCheckbox"></td>
                  <td>${index + 1}</td>
                  <td>${product.descripcion}</td>
                  <td>${product.marca}</td>
                  <td>${product.categoria}</td>
                  <td>${product.precio}</td>
                  <td>${product.precio_minimo}</td>
                  <td>${product.existencia}</td>
                  <td><input type="number" class="form-control form-control-sm cantidadProducto" value="0"></td>
                  <td><input type="number" class="form-control form-control-sm cantidadExcedente" value="0"></td>
              </tr>
          `;
          tableBody.insertAdjacentHTML('beforeend', row);
      });

      const totalPages = Math.ceil(productsData.length / itemsPerPage);
      renderPagination(totalPages);
  };

  const renderPagination = (totalPages) => {
      const pagination = document.querySelector('#pagination');
      pagination.innerHTML = '';

      for (let i = 1; i <= totalPages; i++) {
          const listItem = document.createElement('li');
          listItem.classList.add('page-item');
          if (i === currentPage) {
              listItem.classList.add('active');
          }
          const link = document.createElement('a');
          link.classList.add('page-link');
          link.href = '#';
          link.textContent = i;
          link.dataset.page = i;
          listItem.appendChild(link);
          pagination.appendChild(listItem);
      }
  };

  const exportSelected = () => {
      const selectedProducts = [];

      const productCheckboxes = document.querySelectorAll('.productCheckbox:checked');
      productCheckboxes.forEach(checkbox => {
          const row = checkbox.closest('tr');
          const index = parseInt(row.querySelector('td:nth-child(2)').textContent) - 1;
          const cantidadProducto = parseInt(row.querySelector('.cantidadProducto').value);
          const cantidadExcedente = parseInt(row.querySelector('.cantidadExcedente').value);

          const product = {
              ...productsData[index],
              cantidadProducto: cantidadProducto,
              cantidadExcedente: cantidadExcedente
          };
          selectedProducts.push(product);
      });

      return selectedProducts;
  };

  document.querySelector('#selectAll').addEventListener('change', function () {
      const productCheckboxes = document.querySelectorAll('.productCheckbox');
      productCheckboxes.forEach(checkbox => {
          checkbox.checked = this.checked;
      });
  });

  document.querySelector('#itemsPerPage').addEventListener('change', function () {
      itemsPerPage = parseInt(this.value);
      currentPage = 1;
      renderTable();
  });

  document.querySelector('#pagination').addEventListener('click', function (e) {
      if (e.target && e.target.classList.contains('page-link')) {
          e.preventDefault();
          currentPage = parseInt(e.target.dataset.page);
          renderTable();
      }
  });

  fetchData();

  document.querySelector('#exportCsv').addEventListener('click', function () {
    const selectedData = exportSelected();
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Descripción,Marca,Categoría,Precio,Precio Mínimo,Existencia,Cantidad Productos,Cantidad Excedente\n";

    selectedData.forEach(product => {
        const row = [
            product.descripcion,
            product.marca,
            product.categoria,
            product.precio,
            product.precio_minimo,
            product.existencia,
            product.cantidadProducto || "",
            product.cantidadExcedente || ""
        ];
        csvContent += row.join(",") + "\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "productos.csv");
    document.body.appendChild(link);
    link.click();
});

document.querySelector('#importCsv').addEventListener('click', function () {
    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.accept = ".csv";
    fileInput.style.display = "none";
    
    document.body.appendChild(fileInput);
    
    fileInput.addEventListener('change', async function (event) {
        const file = event.target.files[0];
        
        if (file) {
            try {
                const fileContent = await file.text();
                const lines = fileContent.split('\n');
                
                const importedData = [];
                
                for (let i = 1; i < lines.length; i++) {
                    const columns = lines[i].split(',');
                    
                    if (columns.length >= 7) {
                        const product = {
                            descripcion: columns[0],
                            marca: columns[1],
                            categoria: columns[2],
                            precio: columns[3],
                            precio_minimo: columns[4],
                            existencia: columns[5],
                            cantidadProducto: columns[6],
                            cantidadExcedente: columns[7]
                        };
                        
                        importedData.push(product);
                    }
                }
                
                productsData = importedData;
                currentPage = 1;
                renderTable();
            } catch (error) {
                console.error("Error al importar CSV:", error);
            }
        }
        
        document.body.removeChild(fileInput);
    });
    
    fileInput.click();
});

});

