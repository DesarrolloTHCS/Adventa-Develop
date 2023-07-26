
/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * register.js: Valida los campos del formulario de registro y envía el mensaje al correo del administrador
 */
let form_register = document.getElementById("form-register");
let form_update_register = document.getElementById("form-update-register");
let register_email = document.getElementById("register_email");
let register_password = document.getElementById("register_password");
let confirm_password = document.getElementById('register_confirm_password');
let register_policy = document.getElementById("register_policy");
let feedback_register = document.querySelectorAll(".valid-register");
let card_register = document.getElementById("card-register");
let card_login=document.getElementById("card-login");
const ID=new URLSearchParams(window.location.search).get("id");


if(form_update_register){
  form_update_register.addEventListener("submit", (event) => {
  event.preventDefault();
  event.stopPropagation();
  updateRegister(form_update_register);
});
}


if(form_register){
  form_register.addEventListener("submit", (event) => {
    try {
      event.preventDefault();
      event.stopPropagation();
      registerExpress(form_register);
    } catch (error) {
      console.log(error);
    }
  });
}

/**
 * author:Alfredo Segura <pixxo2010@gmai.com>
 * updateRegister: Valida los campos del formulario de actualización de registro y envía el mensaje al correo del administrador
 * @param {*} form 
 * @returns 
 */
function updateRegister(form){
    let name=document.getElementById("register_name");
    let paternal=document.getElementById("register_paternal");
    let maternal=document.getElementById("register_maternal");
    let rfc=document.getElementById("register_rfc");
    let sex=document.getElementById("register_sex");
    let phone=document.getElementById("register_phone");
    let cellPhone=document.getElementById("register_cellphone");
    let username=document.getElementById("register_username");
    try {
      if(validateName(name,feedback_register[0])==false){return};
      if(validateName(paternal,feedback_register[1])==false){return};
      if(validateName(maternal,feedback_register[2])==false){return};
      if(validateRFC(rfc,feedback_register[3])==false){return};
      if(validateSex(sex,feedback_register[4])==false){return};
      if(validatePhone(phone,feedback_register[5])==false){return};
      if(validatePhone(cellPhone,feedback_register[6])==false){return};
      if(validateUserName(username,feedback_register[7])==false){return};
      if(validateEmail(register_email, feedback_register[8])==false){return};  
      if (register_password.value=="" && confirm_password.value==""){
        try {
        let form_update = formToJson(form);
        userRegisterUpdate(form_update);        
      }catch (error) {
        console.log(error);
      }
      }      
    } catch (error) {
      console.log(error);
    }
  
}

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * registerExpress: Valida los campos del formulario de registro y envía el mensaje al correo del administrador
 * @param {*} form 
 * @returns 
 */
function registerExpress(form){
  try {
      if (validateEmail(register_email, feedback_register[2]) === false)
        return;
      if (
        validatePassword(register_password, feedback_register[3]) == false
      )
        return;
        if (
          validatePassword(confirm_password, feedback_register[4]) == false
        )
          return;
        let form_register = new FormData(form);
        form_register.append(
          "register_policy",
          register_policy.checked ? 1 : 0
        );
         
      sendNotificationRegister(form_register,"express");
    } catch (error) {
      console.log(error);
    }
}

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * sendNotification: Envia el mensaje al correo del administrador
 * @param {*} input
 * @param {*} feedback
 * @returns
 */
function userRegisterUpdate(form) {
  try {
    fetch(`${URL_PROYECT}app/api/api-update-user.php`, {
      method: "PUT",
      body: form,
    })
      .then(parseResponse)
      .then((response) => {
        if (response.status == "200") {
          const is_valid = document.querySelectorAll(".is-valid");
          is_valid.forEach((input) => {
            input.classList.remove("is-valid");
          });
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Mensaje enviado",
            toast: true,
            showConfirmButton: false,
            timer: 1500,
          });
          form.reset();
        } else {
          Swal.fire({
            position: "top-end",
            icon: "error",
            title: response.body.result.error ?? "Error al enviar el mensaje",
            toast: true,
            showConfirmButton: false,
            timer: 1500,
          });
          alert_login.classList.remove("is-invalid");
          alert_login.classList.add("is-invalid");
        }
      });
  } catch (error) {
    console.log(error);
  }
}

/**
 * author:Alfredo Segura <pixxo2010@gmail.com>
 * sendNotification: Envia el mensaje al correo del administrador
 * @param {*} form 
 * @param {*} $typeRegister 
 */
function sendNotificationRegister(form,$typeRegister) {
  try {
    fetch(`${URL_PROYECT}app/api/api-register.php?type-register=${$typeRegister}`, {
      method: "POST",
      body: form,
    })
      .then(parseResponse)
      .then((response) => {
        if (response.status == "200") {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Registro exitoso",
            toast: true,
            showConfirmButton: false,
            timer: 1500,
          });          

          const is_valid = document.querySelectorAll(".is-valid");
          is_valid.forEach((input) => {
            input.classList.remove("is-valid");
          });
          form.reset();

        } else {
          Swal.fire({
            position: "top-end",
            icon: "error",
            title: response.body.result.error ?? "Error al enviar el mensaje",
            toast: true,
            showConfirmButton: false,
            timer: 1500,
          });
          alert_login.classList.remove("is-invalid");
          alert_login.classList.add("is-invalid");
        }
      });
  } catch (error) {
    console.log(error);
  }
}

function showCardLogin(){
  card_login.style.display="block";
  card_register.style.display="none";
}

function showCardRegister(){
  card_login.style.display="none";
  card_register.style.display="block";
}



