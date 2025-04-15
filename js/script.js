document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("product-list");

  if (container) {
    fetch("api/get_products.php")
      .then((res) => res.json())
      .then((products) => {
        products.forEach((p) => {
          const item = document.createElement("div");
          item.className = "product";
          item.innerHTML = `
                      <a href="product_detail.html?id=${p.id}">
                          <img src="${p.image}" alt="${p.name}">
                          <h3>${p.name}</h3>
                          <p>${parseFloat(p.price).toLocaleString()}₫</p>
                      </a>
                  `;
          container.appendChild(item);
        });
      });
  }
});

//lọc sản phẩm theo danh mục
function filterByCategory() {
  const category = document.getElementById("category-select").value;

  fetch("api/filter_products.php?category=" + encodeURIComponent(category))
    .then(res => res.json())
    .then(data => displayProducts(data));
}
  //tim kiếm sản phẩm theo danh mục
  function searchProducts() {
    const keyword = document.getElementById("search-input").value.trim();

    fetch("api/search_products.php?keyword=" + encodeURIComponent(keyword))
      .then((res) => res.json())
      .then((data) => {
        displayProducts(data);
      });
  }

  //hien thị sản phẩm theo danh mục
  function displayProducts(products) {
    const container = document.getElementById("product-container");
    container.innerHTML = "";

    products.forEach((p) => {
      const div = document.createElement("div");
      div.className = "product";
      div.innerHTML = `
  <img src="${p.image}" alt="${p.name}">
  <h3>${p.name}</h3>
  <p>${parseInt(p.price).toLocaleString()}₫</p>
`;
      container.appendChild(div);
    });
  }
  function handleSearchKey(e) {
    if (e.key === "Enter") {
      searchProducts();
    }
  }