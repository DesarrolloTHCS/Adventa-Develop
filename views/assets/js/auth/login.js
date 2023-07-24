let form_login = document.getElementById("form-login");
let input_email=document.getElementById("singin-email");
let input_password=document.getElementById("singin-password");
let invalid_feedback=document.querySelectorAll(".valid-register");
let alert_login=document.querySelector(".alert-login");
const BASEURL = getURL();
try {
        form_login.addEventListener("submit", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if(validateEmail(input_email,invalid_feedback[0])==false){return};        
        sendNotification(form_login);
      });

  } catch (error) {
    console.log(error);
  }

  function sendNotification(form) {
    fetch(`${BASEURL}app/api/api-login.php`, {
      method: "POST",
      body: new FormData(form),
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
            showConfirmButton: false,
            timer: 1500,
          });
          form.reset();
          alert_login.style.display = "none";
          data_user= response.body.result;
          window.location.href = `${BASEURL}views/dashboard.php?id=${response.body.result.id_user}`;
        }else{
          Swal.fire({
            position: "top-end",
            icon: "error",
            title: response.body.result.error ?? "Error al enviar el mensaje",
            showConfirmButton: false,
            timer: 1500,
          });
          alert_login.style.display = "block";
        }
      });
  }

  
  
