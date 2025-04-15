document.addEventListener("DOMContentLoaded", () => {
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  const container = document.getElementById("cart-items");
  const totalEl = document.getElementById("total");

  if (cart.length === 0) {
    container.innerHTML = "<p>Giỏ hàng đang trống.</p>";
    totalEl.innerText = "";
    return;
  }

  let total = 0;

  cart.forEach((item, index) => {
    const div = document.createElement("div");
    div.innerHTML = `
            <img src="${item.image}" width="80">
            <strong>${item.name}</strong> - 
            <input type="number" min="1" value="${
              item.quantity
            }" data-index="${index}" class="qty"> x 
            ${parseFloat(item.price).toLocaleString()}₫ = 
            <strong>${(item.quantity * item.price).toLocaleString()}₫</strong>
            <button data-index="${index}" class="remove">X</button>
            <hr>
        `;
    container.appendChild(div);
    total += item.quantity * item.price;
  });

  totalEl.innerText = `Tổng cộng: ${total.toLocaleString()}₫`;

  // Xử lý thay đổi số lượng
  document.querySelectorAll(".qty").forEach((input) => {
    input.addEventListener("change", (e) => {
      const index = e.target.dataset.index;
      cart[index].quantity = parseInt(e.target.value);
      localStorage.setItem("cart", JSON.stringify(cart));
      location.reload(); // refresh lại để cập nhật tổng
    });
  });

  // Xử lý xóa
  document.querySelectorAll(".remove").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const index = e.target.dataset.index;
      cart.splice(index, 1);
      localStorage.setItem("cart", JSON.stringify(cart));
      location.reload();
    });
  });
});
