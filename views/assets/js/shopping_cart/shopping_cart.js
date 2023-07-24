// Agregar producto al carrito
function addToCart(producto_id) {
  let cart_count = document.getElementById("cart_count");
  let url = location.protocol + "//" + location.host;
  let url_add_to_cart = `${url}/shop_thcs_nativo/app/api/api-shopping-cart.php`;

  fetch(url_add_to_cart, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "add",
      product_id: producto_id,
    }),
  })
    .then(parseResponse)
    .then((data) => {
      if (data.status == 200) {
        if (data.body != null || data.body != undefined) {
          let nav_cart = document.getElementById("nav_cart");
          let body = ``;
          let total_order = 0;
          let cart_total_price = document.querySelector(".cart-total-price");
          data.body.result.forEach((element) => {
            total_order += element.quantity * element.product[0].sinube_price;
            body += itemListCart(element);
          });
          cart_count.innerHTML = data.body.total;
          nav_cart.innerHTML = body;
          cart_total_price.innerHTML =
            "$" + numberFormat(total_order, 2, ".", ",");
        }
      }
    })
    .catch((error) => {
      console.error("Error:", error.message);
    });
}

// Eliminar producto del carrito
function deleteToCart(product_id) {
  let nav_cart = document.getElementById("nav_cart");
  let url = location.protocol + "//" + location.host;
  let url_get_to_cart = `${url}/shop_thcs_nativo/app/api/api-shopping-cart.php`;
  fetch(url_get_to_cart, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "delete",
      product_id: product_id,
    }),
  })
    .then(parseResponse)
    .then((data) => {
      if (data.status == 200) {
        if (data.body != null || data.body != undefined) {
          let nav_cart = document.getElementById("nav_cart");
          let body = ``;
          let total_order = 0;
          let cart_total_price = document.querySelector(".cart-total-price");
          cart_count.innerHTML = data.body.total ?? 0;

          data.body.result.forEach((element) => {
            total_order += element.quantity * element.product[0].sinube_price;
            body += itemListCart(element);
          });
          nav_cart.innerHTML = body;
          cart_total_price.innerHTML =
            "$ " + numberFormat(total_order, 2, ".", ",");
        }
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// Eliminar producto del carrito
function updateShoppingList(product_id, quantity) {
    let url = location.protocol + "//" + location.host;
    let url_get_to_cart = `${url}/shop_thcs_nativo/app/api/api-shopping-cart.php`;
    fetch(url_get_to_cart, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        action: "update_shopping_list",
        product_id: product_id,
        quantity: quantity,
      }),
    })
      .then(parseResponse)
      .then((data) => {
        if (data.status == 200) {
          if (data.body != null || data.body != undefined) {
            console.log(data.body);
            let nav_cart = document.getElementById("nav_cart");
            let subtotal= document.getElementById("subtotal");
            let total_col=document.querySelectorAll(".total-col");
            let list_cart = ``;
            let list_shopping = ``;
            let total_order = 0;
            let cart_total_price = document.querySelector(".cart-total-price");
            let increment=0;
            cart_count.innerHTML = data.body.total ?? 0;
  
            data.body.result.shopping_list.forEach((element) => {
              total_order += element.quantity * element.product[0].sinube_price;
              list_cart += itemListCart(element);
              total_col[increment].innerHTML="$ "+numberFormat((element.quantity * element.product[0].sinube_price),2,".",",");
              list_shopping += itemShoppingList(element);
              increment++;
            });

            nav_cart.innerHTML = list_cart;
            cart_total_price.innerHTML =
              "$ " + numberFormat(total_order, 2, ".", ",");
            subtotal.innerHTML="$ "+numberFormat(total_order,2,".",",");
          }
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

// Función para actualizar el texto del precio
function addProductQuantity(inputPrice) {
    const liElement = inputPrice.parentElement;
    const precioElement = liElement.querySelector(".precio");
    const productoId = inputPrice.dataset.productId;
    const valorActual = parseInt(inputPrice.value);
    updateShoppingList(productoId, valorActual);
}

function itemListCart(element){
    
    total_order = numberFormat((element.quantity * element.product[0].sinube_price),2,".",",");
    html=`<div class="product">
    <div class="product-cart-details">
        <h4 class="product-title">
            <a href="product.html">${element.product[0].product_name}</a>
        </h4>
        <span class="cart-product-info">
            <span class="cart-product-qty">${element.quantity}</span>
            x ${total_order}
        </span>
    </div><!-- End .product-cart-details -->

    <figure class="product-image-container">
        <a href="product.html" class="product-image">
            <img src="${URL_IMAGES_PRODUCT+element.product[0].product_image1}" alt="product">
        </a>
    </figure>
    <a href="#" class="btn-remove" onClick="deleteToCart(${element.id_product})" title="Remove Product"><i class="icon-close"></i></a>
</div><!-- End .product -->`;
    return html;
}

function itemShoppingList(element){
    
    total=numberFormat(element.quantity * element.product[0].sinube_price,2,".",",")
    html=`<tr>
    <td class="product-col">
        <div class="product">
            <figure class="product-media">
                <a href="#">
                    <img src="${URL_IMAGES_PRODUCT+element.product[0].product_image1}" alt="Product image">
                </a>
            </figure>

            <h3 class="product-title">
                <a href="#">${element.product[0].product_name}</a>
            </h3><!-- End .product-title -->
        </div><!-- End .product -->
    </td>
    <td class="price-col price_shopping_list">${element.product[0].sinube_price}</td>
    <td class="quantity-col">
        <div class="cart-product-quantity">
            <input type="number" class="form-control"
            data-product-id="${element.product[0].id_product}"
            onChange="addProductQuantity(this)"                                                                        
            value="${element.quantity}" min="1" max="${element.product[0].sinube_inventory}" step="0" data-decimals="0" required>
        </div><!-- End .cart-product-quantity -->
    </td>
    <td class="total-col">$ ${total}</td>
    <td class="remove-col"><button class="btn-remove" onClick="deleteToCart(${element.product[0].id_product})"><i class="icon-close"></i></button></td>
</tr>`;
    return html;
}