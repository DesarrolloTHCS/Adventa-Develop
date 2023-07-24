/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * URL: Establece la URL base del proyecto
 */
const URL_PROYECT = getURL();

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * URL: Establece la URL de las imagenes de los productos
 */
const URL_IMAGES_PRODUCT = getURL() + "imagenes/products/";

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * URL: Establece la URL sobre detalles de los productos
 */
const URL_PRODUCTS_DETAILS =
  getURL() + "views/products/ProductDetails?product_id=";

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * setUrls: Establece la URL base del proyecto
 * @param {*} input
 * @param {*} feedback
 * @returns
 */

function getURL() {
  let baseURLPath = "Adventa-Develop/";
  var loc = window.location;
  var baseURL =
    loc.protocol + "//" + loc.hostname + (loc.port ? ":" + loc.port : "") + "/";
  return baseURL + baseURLPath;
}

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * parseResponse: Convierte la respuesta del servidor en un objeto JSON
 * @param {*} input
 * @param {*} feedback
 * @returns
 */

function parseResponse(response) {
  try {
    return response.text().then((data) => {
      let body;
      try {
        body = JSON.parse(data);
      } catch (error) {
        body = null;
      }
      return { status: response.status, body: body };
    });
  } catch (error) {
    console.log(error);
  }
}

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * formToJson: Convierte los datos de un formulario en un objeto JSON
 * @param {*} form
 * @returns
 */

function formToJson(form) {
  var formData = new FormData(form);
  var jsonData = {};

  formData.forEach(function (value, key) {
    jsonData[key] = value;
  });

  return JSON.stringify(jsonData);
}

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * populteForm: Llena los campos de un formulario con los datos de un objeto JSON
 * @param {*} form
 * @param {*} data
 */
function populteForm(form, data) {
  for (var i = 0; i < form.length; i++) {
    for (let key in data) {
      if (form[i].name == key) {
        form[i].value = data[key];
      }
    }
  }
}

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * numberFormat: Formatea un numero
 * @param {*} number
 * @param {*} decimals
 * @param {*} decimalSeparator
 * @param {*} thousandsSeparator
 * @returns
 * */
function numberFormat(number, decimals, decimalSeparator, thousandsSeparator) {
  decimals = decimals !== undefined ? decimals : 2;
  decimalSeparator = decimalSeparator || ".";
  thousandsSeparator = thousandsSeparator || ",";

  let parts = number.toFixed(decimals).split(".");
  let integerPart = parts[0].replace(
    /\B(?=(\d{3})+(?!\d))/g,
    thousandsSeparator
  );
  let decimalPart = parts[1] || "";

  let newNumber = integerPart + decimalSeparator + decimalPart;
  return newNumber;
}

/**
 * 
 */
$(document).ready(function() {
  $('#summernote').summernote({
    height: 150,   //set editable area's height
    codemirror: { // codemirror options
    theme: 'monokai'
    }
  });
});

