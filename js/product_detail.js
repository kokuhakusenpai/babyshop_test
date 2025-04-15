const params = new URLSearchParams(window.location.search);
const id = params.get("id");

fetch(`api/get_product.php?id=${id}`)
  .then((res) => res.json())
  .then((product) => {
    const container = document.getElementById("product-detail");

    if (product.error) {
      container.innerHTML = `<p>${product.error}</p>`;
      return;
    }

    container.innerHTML = `
            <img src="${product.image}" width="200">
            <h2>${product.name}</h2>
            <p>${product.description}</p>
            <strong>${parseFloat(product.price).toLocaleString()}₫</strong>
        `;

    document.getElementById("add-to-cart").onclick = () => {
      let cart = JSON.parse(localStorage.getItem("cart") || "[]");

      const found = cart.find((item) => item.id == product.id);
      if (found) {
        found.quantity += 1;
      } else {
        cart.push({
          id: product.id,
          name: product.name,
          price: product.price,
          image: product.image,
          quantity: 1,
        });
      }

      localStorage.setItem("cart", JSON.stringify(cart));
      alert("Đã thêm vào giỏ hàng!");
    };
  });
