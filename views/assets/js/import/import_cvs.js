function importFile() {
    const fileInput = document.getElementById('miArchivoInput');
    const file = fileInput.files[0];

    if (!file) {
      alert('Selecciona un archivo CSV o XLSX.');
      return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {
      const data = event.target.result;
      parseFile(data, file.name);
    };

    reader.onerror = function () {
      alert('Error al leer el archivo.');
    };

    reader.readAsBinaryString(file);
  }

  function parseFile(fileData, fileName) {
    if (fileName.toLowerCase().endsWith('.csv')) {
      parseCSV(fileData);
    } else if (fileName.toLowerCase().endsWith('.xlsx')) {
      parseXLSX(fileData);
    } else {
      alert('Tipo de archivo no compatible.');
    }
  }

  function parseCSV(csvData) {
    Papa.parse(csvData, {
      complete: function (result) {
        const jsonData = result.data;
        const jsonString = JSON.stringify(jsonData, null, 2);
        const cleanJsonString = jsonString.replace(/\\/g, "");
        showJson(JSON.parse(cleanJsonString));
      },
      header: true // Si el CSV tiene encabezados en la primera fila
    });
  }

  function parseXLSX(xlsxData) {
    const workbook = XLSX.read(xlsxData, { type: 'binary' });
    const firstSheetName = workbook.SheetNames[0];
    const worksheet = workbook.Sheets[firstSheetName];
    const jsonData = XLSX.utils.sheet_to_json(worksheet);
    const jsonString = JSON.stringify(jsonData, null, 2);
    const cleanJsonString = jsonString.replace(/\\/g, "");
    showJson(JSON.parse(cleanJsonString));
  }

  function showJson(jsonData) {
    console.log(jsonData); // Aquí tienes el JSON resultante
  }