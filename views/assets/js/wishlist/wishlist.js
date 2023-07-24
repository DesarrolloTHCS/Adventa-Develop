/**
 * Author: Alfredo Segura Vara <pixxo2010@gmail.com>
 * Description: Añade productos a lista de deseos
 * @param {*} producto_id 
 */
function addWishlist(producto_id) {
  let url = location.protocol + "//" + location.host;
  let url_add_to_cart = `${url}/shop_thcs_nativo/app/api/api-wishlist.php`;
 
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
        let wishlist_count = document.getElementById("wishlist-count");
        let total_product_wishlist=0;
        if (data.body != null || data.body != undefined) {
          total_product_wishlist=data.body.result.length;
          wishlist_count.innerHTML=total_product_wishlist;
        }
      }
    })
    .catch((error) => {
      console.error("Error:", error.message);
    });
}


/**
 * Author: Alfredo Segura Vara <pixxo2010@gmail.com>
 * Description: Elimina productos de lista de deseos
 * @param {*} producto_id 
 */
function deleteWishlist(product_id) {

  let url = location.protocol + "//" + location.host;
  let url_get_to_cart = `${url}/shop_thcs_nativo/app/api/api-wishlist.php`;
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

      console.log(data);

      if (data.status == 200) {
        let wishlist_count = document.getElementById("wishlist-count");
        let wish_list = document.getElementById("wish-list");
        let total_product_wishlist=0;
        let body = ``;

        if (data.body != null || data.body != undefined) {
          total_product_wishlist=data.body.result.length;
          wishlist_count.innerHTML=total_product_wishlist;         

          data.body.result.forEach((element) => {
            body += itemWishList(element);
          });
          wish_list.innerHTML = body;
        }
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

/**
 * Author: Alfredo Segura Vara <pixxo2010@gmail.com>
 * Description: item de wishlist
 * @param {*} element 
 * @returns 
 */
function itemWishList(element){
  let html=`<tr>
  <td class="product-col">
    <div class="product">
      <figure class="product-media">
        <a href="${URL_PRODUCTS_DETAILS} ${element.product[0].id_product}">
          <img src="${URL_IMAGES_PRODUCT}${element.product[0].product_image1}" alt="Product image">
        </a>
      </figure>

      <h3 class="product-title">
        <a href="${URL_PRODUCTS_DETAILS} ${element.product[0].id_product}">${element.product[0].product_name}</a>
      </h3><!-- End .product-title -->
    </div><!-- End .product -->
  </td>
  <td class="price-col">$ ${element.product[0].sinube_price}</td>
  <td class="stock-col"><span class="in-stock ">Disponible</span></td>
  <td class="action-col">
    <button class="btn btn-block btn-outline-primary-2" onClick="addToCart(${element.product[0].id_product})"><i class="icon-cart-plus"></i>Añadir al carrito</button>
  </td>
  <td class="remove-col"><button class="btn-remove"><i class="icon-close" onClick="deleteWishlist(${element.product[0].id_product})"></i></button></td>
</tr>`;
        return html;
}