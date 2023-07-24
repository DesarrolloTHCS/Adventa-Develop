

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * validateName: Valida que el nombre solo contenga caracteres alfabéticos
 * @param {*} input
 * @param {*} feedback
 * @returns
 */
function validateName(input, feedback) {
    try {
      // Validate name
      if (/^[a-zA-Z ]+$/.test(input.value) == false) {
        input.classList.add("is-invalid");
        feedback.textContent =
          "Solo puede contener caracteres alfabéticos";
        return false;
      } else {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");
        feedback.textContent =
          "Válido";
          return true;
      }
    } catch (error) {
      console.log(error);
    }
  }

  /**
   * author:Alfredo Segura <pixxo2010@gmail.com>
   * validatePassword: Valida que la contraseña tenga entre 8 y 16 caracteres, al menos una letra mayúscula y un caracter especial
   * @param {*} input
   * @param {*} feedback
   * @returns
   */
  function validatePassword(input, feedback) {
    try {
      const longitudMinima = 8;
      const longitudMaxima = 16;
      const caracteresEspeciales = /[/*\-_$!]/;
  
      if (
        input.value.length < longitudMinima ||
        input.value.length > longitudMaxima
      ) {
        input.classList.add("is-invalid");
        feedback.textContent = "La contraseña debe tener entre 8 y 16 caracteres";
        return false;
      }
  
      if (!/[A-Z]/.test(input.value)) {
        input.classList.add("is-invalid");
        feedback.textContent =
          "La contraseña debe tener al menos una letra mayúscula";
        return false;
      }
  
      if (!caracteresEspeciales.test(input.value)) {
        input.classList.add("is-invalid");
        feedback.textContent =
          "La contraseña debe tener al menos un caracter especial como: /*-_$!";
        return false;
      }

      input.classList.remove("is-invalid");
      input.classList.add("is-valid");  
      feedback.textContent =
          "Contraseña válida";
    } catch (error) {
      console.log(error);
    };
  }
  
  /**
   * author:Alfredo Segura <pixxo2010@gmail.com>
   * validateEmail: Valida que el email tenga el formato correcto
   * @param {*} input
   * @param {*} feedback
   * @returns
   */
  function validateEmail(input, feedback) {
  try {
    if(input.value==""){
      input.classList.add("is-invalid");
      feedback.textContent =
        "Por favor, introduce una dirección de correo electrónico válida";
      return false;
    }
    // Validate email
    if (/^\S+@\S+\.\S+$/.test(input.value) == false || input.value == "") {
      input.classList.add("is-invalid");
      feedback.textContent =
        "Por favor, introduce una dirección de correo electrónico válida";
      return false;
    }
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
    feedback.textContent =
        "Correo electrónico válido";
  } catch (error) {
    console.log(error);
  };
  }
  
  /**
   * author:Alfredo Segura <pixxo2010@gmail.com>
   * validatePhone: Valida que el teléfono solo contenga caracteres numéricos
   * @param {*} input
   * @param {*} feedback
   * @returns
   */
  function validatePhone(input, feedback) {
    try {
      
    // Validate phone
    if (/^\+?\d+$/.test(input.value) == false) {
      input.classList.add("is-invalid");
      feedback.textContent = "Por favor, introduce un número de teléfono válido";
      return false;
    }
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
    feedback.textContent = "Teléfono válido	";
  } catch (error) {
    console.log(error);
  };
  }
  
  /**
   * author:Alfredo Segura <pixxo2010@gmail.com>
   * validateMessage: Valida que el mensaje no esté vacío
   * @param {*} input
   * @param {*} feedback
   * @returns
   */
  function validateMessage(input, feedback) {
    try{
    // Validate message
    if (input.value == "") {
      input.classList.add("is-invalid");
      feedback.textContent = "Por favor, introduce un mensaje";
      return false;
    }
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
    feedback.textContent = "Gracias por tu mensaje";

  }catch (error) {
    console.log(error);
  };
  }

  function validateRFC(input,feedback) {
    try {
      if(input.value === ""){
        input.classList.add("is-invalid");
        feedback.textContent = "Por favor, introduce el RFC";
        return;  
      }
      // Sanitizar el input
      rfc = input.value.trim().toUpperCase(); // Eliminar espacios al inicio y final y convertir a mayúsculas
    
      // Patrón de validación para RFC
      var pattern = /^[A-Z]{4}\d{6}[A-Z0-9]{3}$/;
    
      // Validar el RFC utilizando el patrón
      if (pattern.test(rfc) === false) {
      input.classList.add("is-invalid");
      feedback.textContent = "RFC no valido";
      return false;
      }  
      input.classList.remove("is-invalid");
      input.classList.add("is-valid");
      feedback.textContent =
      "RFC válido";
       // El RFC es válido
    } catch (error) {
      console.log(error);
      return false; // El RFC es inválido
    }
  }

  /**
   * author:Alfredo Segura <pixxo2010@gmail.com>
   * validateUserName: Valida que el nombre de usuario solo contenga caracteres alfanuméricos, guiones bajos y guiones medios
   * @param {*} input 
   * @param {*} feedback 
   * @returns 
   */
  function validateUserName(input, feedback) {
    try {
    // Sanitizar el input
    username = input.value.trim(); // Eliminar espacios al inicio y final
    
    // Patrón de validación para el nombre de usuario
    var pattern = /^[a-zA-Z0-9_\-]+$/;
    
    // Validar el nombre de usuario utilizando el patrón
    if (pattern.test(username) === false) {
      input.classList.add("is-invalid");
      feedback.textContent = "El nombre de usuario contiene caracteres no permitidos";
      return false; // El nombre de usuario contiene caracteres no permitidos
    }
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
    feedback.textContent =
    "Usuario válido";
     // El nombre de usuario es válido
  }catch (error) {
    console.log(error);
    return false; // return false; // El nombre de usuario es inválido
  }
}

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * validateSex: Valida que el sexo sea 1 o 2
 * @param {*} input 
 * @param {*} feedback 
 * @returns 
 */
function validateSex(input, feedback) {
  // Validar si el valor es 1 o 2
  if (input.value === '1' ||  input.value === '2') {
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
    feedback.textContent ="Sexo válido"
  } else {
    input.classList.add("is-invalid");
    feedback.textContent = "Por favor, selecciona una opción";
    return false; // El valor no es válido
  }
}

/**
 * 
 * @param {*} search 
 * @returns 
 */
function validateSearch(search) {
  // Eliminar espacios en blanco al inicio y al final
  search = search.trim();

  // Eliminar etiquetas HTML y JavaScript de forma segura
  search = search.replace(/[&<>"'`]/g, '');

  // Validar longitud mínima y máxima del término de búsqueda
  let maxLength = 50;
  if (search.length < maxLength) {
  // Devolver el valor sanitizado y validado
  return search;     
  }

}



