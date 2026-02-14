// js/cart.js - Versión corregida para manejar precios grandes

document.addEventListener("DOMContentLoaded", function () {
  // Determinar la URL base
  const isLocal =
    window.location.hostname === "localhost" ||
    window.location.hostname === "127.0.0.1";
  const baseUrl = isLocal ? "/student025/shop" : "https://remotehost.es/student025/shop";
  function formatPrice(number) {
    // Convertir a número y formatear con separador de miles
    const num = parseFloat(number);
    if (isNaN(num)) return '0.00';
    
    // Formatear con separador de miles (punto) y decimales (coma) para formato europeo
    // O usar formato internacional con coma para miles y punto para decimales
    return num.toLocaleString('es-ES', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  }

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
            
            // Verificar si el carrito está vacío
            const tbody = document.querySelector('table tbody');
            const rows = tbody.querySelectorAll('tr:not(.empty-cart-row)');
            
            if(rows.length === 0) {
              tbody.innerHTML = '<tr class="empty-cart-row"><td colspan="6">Tu carrito está vacío</td></tr>';
              document.getElementById('checkout-btn').style.display = 'none';
            }
          } else {
            row.querySelector(".quantity-cell").textContent = data.quantity;
            row.querySelector(".subtotal-cell").textContent = "€" + formatPrice(data.subtotal);
          }

          document.getElementById("cart-total").textContent = "€" + formatPrice(data.total);
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