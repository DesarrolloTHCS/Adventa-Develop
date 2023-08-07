document.addEventListener("DOMContentLoaded", function () {
    // Función para cargar los datos JSON en la tabla
    function loadTableData(data) {
      const tbody = document.querySelector("#products_list tbody");
      tbody.innerHTML = ""; // Limpiar contenido anterior
  
      data.forEach(item => {
        const row = `
          <tr>
            <td><input type="checkbox" class="rowCheckbox"></td>
            <td>${item.id}</td>
            <td>${item.descripcion}</td>
            <td>${item.marca}</td>
            <td>${item.categoria}</td>
            <td>${item.precio}</td>
            <td>${item.precioMinimo}</td>
            <td>${item.existencia}</td>
            <td><input type="number" class="form-control cantidadProductos" value="${item.cantidadProductos}"></td>
            <td><input type="number" class="form-control cantidadExcedente" value="${item.cantidadExcedente}"></td>
          </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
      });
    }
  
    // Simulación de datos JSON (reemplazar con tus datos y lógica de fetch)
    const jsonData = [
      {
        id: 1,
        descripcion: 'Producto A',
        marca: 'Marca A',
        categoria: 'Categoría A',
        precio: 100,
        precioMinimo: 80,
        existencia: 50,
        cantidadProductos: 0,
        cantidadExcedente: 0
      },
      // ... Otros datos ...
    ];
  
    // Cargar datos iniciales en la tabla
    loadTableData(jsonData);
  
    // Función para manejar el evento de selección/deselección de todos los checkbox
    const selectAllCheckbox = document.querySelector("#selectAll");
    selectAllCheckbox.addEventListener("change", function () {
      const rowCheckboxes = document.querySelectorAll(".rowCheckbox");
      rowCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
      });
    });
  
    // Función para exportar los datos de la tabla a CSV
    const exportCSVButton = document.querySelector("#exportCSV");
    exportCSVButton.addEventListener("click", function () {
      const csvContent = "data:text/csv;charset=utf-8," + encodeURIComponent(getTableCSV());
      const link = document.createElement("a");
      link.setAttribute("href", csvContent);
      link.setAttribute("download", "tabla.csv");
      link.click();
    });
  
    // Función para obtener los datos de la tabla en formato CSV
    function getTableCSV() {
      const rows = [];
      const headers = Array.from(document.querySelectorAll("#products_list th")).map(th => th.textContent);
      rows.push(headers.join(","));
  
      const tableRows = document.querySelectorAll("#products_list tr");
      tableRows.forEach(row => {
        const rowData = Array.from(row.querySelectorAll("td")).map(td => td.textContent);
        rows.push(rowData.join(","));
      });
  
      return rows.join("\n");
    }
  
    // Agregar funcionalidad de paginación (requiere implementación adicional)
  
    // Agregar funcionalidad de búsqueda (requiere implementación adicional)
  
    // Agregar funcionalidad de importar CSV (requiere implementación adicional)
  
    // Función para levantar la orden y obtener los valores como JSON
    const createOrderButton = document.querySelector("#createOrder");
    createOrderButton.addEventListener("click", function () {
      const orderData = [];
      const selectedCheckboxes = document.querySelectorAll(".rowCheckbox:checked");
      selectedCheckboxes.forEach(checkbox => {
        const row = checkbox.closest("tr");
        const id = row.querySelector("td:nth-child(2)").textContent;
        const cantidadProductos = row.querySelector(".cantidadProductos").value;
        const cantidadExcedente = row.querySelector(".cantidadExcedente").value;
        orderData.push({
          id: id,
          cantidadProductos: cantidadProductos,
          cantidadExcedente: cantidadExcedente
        });
      });
  
      const orderJSON = JSON.stringify(orderData);
      console.log(orderJSON); // Aquí puedes enviar los datos al servidor o realizar otras acciones
    });
  });