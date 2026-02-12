document.addEventListener("DOMContentLoaded", function () {
  // Determinar la URL base: localhost usa rutas relativas, todo lo demás usa remotehost
  const isLocal =
    window.location.hostname === "localhost" ||
    window.location.hostname === "127.0.0.1";
  const baseUrl = isLocal ? "/student025/shop" : "https://remotehost.es/student025/shop";

  function updateCart(productId, action) {
    const row = document.querySelector(`tr[data-product-id="${productId}"]`);
    if (!row) return;

    const buttons = row.querySelectorAll("button");
    // Deshabilitar botones mientras se procesa
    buttons.forEach((btn) => (btn.disabled = true));

    const formData = new FormData();
    formData.append("product_id", productId);
    formData.append("action", action);

    fetch(`${baseUrl}/backend/ajax/update_cart.php`, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          if (data.quantity <= 0) {
            row.remove();
            // ... resto de tu lógica de carrito vacío
          } else {
            row.querySelector(".quantity-cell").textContent = data.quantity;
            row.querySelector(".subtotal-cell").textContent =
              "€" + parseFloat(data.subtotal).toFixed(2);
          }
          document.getElementById("cart-total").textContent =
            "€" + parseFloat(data.total).toFixed(2);
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((error) => console.error("Error:", error))
      .finally(() => {
        buttons.forEach((btn) => (btn.disabled = false));
      });
  }

  document.querySelectorAll(".btn-add-cart").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const productId = this.getAttribute("data-product-id");
      updateCart(productId, "add");
    });
  });

  document.querySelectorAll(".btn-remove-cart").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const productId = this.getAttribute("data-product-id");
      updateCart(productId, "remove");
    });
  });
});
