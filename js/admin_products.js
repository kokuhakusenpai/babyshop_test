document.addEventListener("DOMContentLoaded", () => {
  loadProducts();

  document
    .getElementById("product-form")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const id = document.getElementById("product-id").value;
      const name = document.getElementById("product-name").value;
      const price = document.getElementById("product-price").value;
      const image = document.getElementById("product-image").value;
      const description = document.getElementById("product-description").value;

      const data = { id, name, price, image, description };

      fetch("api/save_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      })
        .then((res) => res.json())
        .then((result) => {
          alert(result.message);
          this.reset();
          loadProducts();
        });
    });
});

function loadProducts() {
  fetch("api/get_products.php")
    .then((res) => res.json())
    .then((data) => {
      const tbody = document.getElementById("product-list");
      tbody.innerHTML = "";
      data.forEach((p) => {
        const row = document.createElement("tr");
        row.innerHTML = `
                    <td>${p.name}</td>
                    <td>${parseInt(p.price).toLocaleString()}₫</td>
                    <td><img src="${p.image}" width="50"></td>
                    <td>${p.description}</td>
                    <td>
                        <button onclick='editProduct(${JSON.stringify(
                          p
                        )})'>Sửa</button>
                        <button onclick='deleteProduct(${p.id})'>Xoá</button>
                    </td>
                `;
        tbody.appendChild(row);
      });
    });
}

function editProduct(product) {
  document.getElementById("product-id").value = product.id;
  document.getElementById("product-name").value = product.name;
  document.getElementById("product-price").value = product.price;
  document.getElementById("product-image").value = product.image;
  document.getElementById("product-description").value = product.description;
}

function deleteProduct(id) {
  if (!confirm("Bạn có chắc chắn muốn xoá?")) return;
  fetch("api/delete_product.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id }),
  })
    .then((res) => res.json())
    .then((result) => {
      alert(result.message);
      loadProducts();
    });
}
