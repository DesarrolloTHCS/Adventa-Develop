let search = document.getElementById("search");
let search_mobile= document.getElementById('mobile-search');
let form_search = document.getElementById("form-search");
let form_search_mobile = document.getElementById("form-search-mobile");
let container=document.querySelector(".content-result-search");
let container_mobile=document.getElementById("content-result-search-mobile");
let url=location.protocol+'//'+location.host;
let url_product_details=`${url}/shop_thcs_nativo/views/products/ProductDetails.php?id_product=`;



form_search.addEventListener("keyup", function (e) {
  e.preventDefault();
  let search_value = validateSearch(search.value);  
  if (search_value) {
    let form = formToJson(form_search);  
    navbarSearch(form,container);
  } else {
    let html = "";
    container.innerHTML = html;
  }
});

form_search_mobile.addEventListener("keyup", function (e) {
    e.preventDefault();
    e.stopPropagation();    
    
    let search_value = validateSearch(search_mobile.value);   
    
    if (search_value) {      
        let form_mobil = formToJson(form_search_mobile);
      navbarSearch(form_mobil,container_mobile);
    } else {
        container_mobile.innerHTML = "";
    }
  });
  

function navbarSearch(form,content_result) {
  try {
    fetch(`${BASEURL}app/api/api-search.php`, {
      method: "POST",
      body: form,
    })
      .then(parseResponse)
      .then((response) => {
        try{
        if (response.status = 200) {
          let data = response.body.result;
          let html = "";          
          data.forEach((item) => {              
                html += `<li><a href="${url_product_details}${item.id_product}" class="list-group-item list-group-item-action"><i class="icon-search"></i>${item.product_name}</a></li>`;
            }            
          );        
          content_result.innerHTML = html;          
        } else {
          console.log("sin resultados");
        }
    }catch(error){
        console.log(error);
    }
      });
    
  } catch (error) {
    console.log(error);
  }
}
