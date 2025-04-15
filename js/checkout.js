document.addEventListener("DOMContentLoaded", () => {
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  const summary = document.getElementById("order-summary");
  const user = JSON.parse(localStorage.getItem("user") || "null");

  if (cart.length === 0) {
    summary.innerHTML = "<p>Không có sản phẩm nào trong giỏ hàng.</p>";
    return;
  }

  let total = 0;
  let html = "<h3>Đơn hàng:</h3><ul>";
  cart.forEach((item) => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;
    html += `<li>${item.name} x ${
      item.quantity
    } = ${itemTotal.toLocaleString()}₫</li>`;
  });
  html += `</ul><strong>Tổng cộng: ${total.toLocaleString()}₫</strong>`;
  summary.innerHTML = html;

  document.getElementById("checkout-form").addEventListener("submit", (e) => {
    e.preventDefault();

    const fullname = document.getElementById("fullname").value;
    const phone = document.getElementById("phone").value;
    const address = document.getElementById("address").value;

    fetch("api/place_order.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        fullname,
        phone,
        address,
        cart,
        user_id: user?.id || null,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          alert("Đặt hàng thành công!");
          localStorage.removeItem("cart");
          window.location.href = "index.html";
        } else {
          alert("Có lỗi xảy ra khi đặt hàng.");
          console.error(data.message);
        }
      });
  });
});
