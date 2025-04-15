document.addEventListener("DOMContentLoaded", () => {
  fetch("api/get_orders.php")
    .then((res) => res.json())
    .then((data) => {
      const tbody = document.getElementById("order-list");
      data.forEach((order) => {
        const row = document.createElement("tr");
        row.innerHTML = `
                    <td>${order.id}</td>
                    <td>${order.fullname}</td>
                    <td>${order.phone}</td>
                    <td>${order.address}</td>
                    <td>${order.total_price.toLocaleString()}₫</td>
                    <td>${order.created_at}</td>
                    <td><button onclick="viewDetail(${
                      order.id
                    })">Chi tiết</button></td>
                `;
        tbody.appendChild(row);
      });
    });
});

function viewDetail(orderId) {
  fetch("api/get_order_detail.php?id=" + orderId)
    .then((res) => res.json())
    .then((data) => {
      let html = `<h2>Chi tiết đơn hàng #${orderId}</h2>`;
      html += `<ul>`;
      data.forEach((item) => {
        html += `<li>${item.product_name} x ${item.quantity} = ${(
          item.price * item.quantity
        ).toLocaleString()}₫</li>`;
      });
      html += `</ul>`;
      document.getElementById("order-detail").innerHTML = html;
    });
}
