let form_login = document.getElementById("form-login");
let input_email=document.getElementById("singin_email");
let input_password=document.getElementById("singin_password");
let invalid_feedback=document.querySelectorAll(".valid-register");
let alert_login=document.querySelector(".alert-login");

try {
        form_login.addEventListener("submit", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if(validateEmail(input_email,invalid_feedback[0])==false){return};
        if(validatePassword(input_password,invalid_feedback[1])==false){return}; 
        console.log(formToJson(form_login));
        sendNotification(form_login);
      });

  } catch (error) {
    console.log(error);
  }

  function sendNotification(form) {
    fetch(`${URL_PROYECT}app/api/api-login`, {
      method: "POST",
      body: formToJson(form)
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
            title: "Bienvenido",
            toast: true,
            showConfirmButton: false,
            timer: 1500,
          });
          setTimeout(() => {
          window.location.href = `${URL_PROYECT}views/index.php`;
          }, 2500);
        }else{
          
          Swal.fire({
            position: "top-end",
            icon: "error",
            title: `Error al inciar sesion`,
            toast: true,            
            showConfirmButton: false,
            timer: 1500,
          });
        }
      });
  }

  
  
