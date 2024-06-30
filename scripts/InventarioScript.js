document.addEventListener("DOMContentLoaded", function () {
  const warehouseCards = document.querySelectorAll(".warehouse-card");

  const productListModal = document.getElementById("productListModal");
  const productListModalTitle = document.getElementById("productListModalTitle");
  const productTableBody = document.getElementById("productTableBody");
  const closeProductListModal = document.getElementById("closeProductListModal");

  const addProductModal = document.getElementById("addProductModal");
  const addProductModalTitle = document.getElementById("addProductModalTitle");
  const closeAddProductModal = document.getElementById("closeAddProductModal");

  const editProductModal = document.getElementById("editProductModal");
  const editProductModalTitle = document.getElementById("editProductModalTitle");
  const closeEditProductModal = document.getElementById("closeEditProductModal");

  const deleteProductModal = document.getElementById("deleteProductModal");
  const deleteProductModalTitle = document.getElementById("deleteProductModalTitle");
  const closeDeleteProductModal = document.getElementById("closeDeleteProductModal");

  let currentAlmacenId = null;
  let currentAlmacenNombre = null;

  warehouseCards.forEach((card) => {
    card.addEventListener("click", function () {
      const almacenId = this.getAttribute("data-id");
      const almacenNombre = this.getAttribute("data-nombre");
      currentAlmacenId = almacenId;
      currentAlmacenNombre = almacenNombre;

      productListModalTitle.textContent = `Productos en Almacén: ${almacenNombre} - ${almacenId}`;

      fetchProducts(almacenId);
      productListModal.showModal();
    });
  });

  document.getElementById("btnAddProduct").addEventListener("click", function () {
    if (currentAlmacenId && currentAlmacenNombre) {
      document.getElementById("addProductAlmacen").value = currentAlmacenId;

      addProductModalTitle.textContent = `Agregar Producto en Almacén: ${currentAlmacenId}`;

      addProductModal.showModal();
    }
  });

  closeProductListModal.addEventListener("click", function () {
    productListModal.close();
  });

  closeAddProductModal.addEventListener("click", function () {
    addProductModal.close();
  });

  closeEditProductModal.addEventListener("click", function () {
    editProductModal.close();
  });

  closeDeleteProductModal.addEventListener("click", function () {
    deleteProductModal.close();
  });

  function fetchProducts(almacenId) {
    productTableBody.innerHTML = ""; // Clear current table content
    fetch(`fetch/fetch_products.php?almacen=${almacenId}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        data.forEach((product) => {
          const row = document.createElement("tr");
          row.innerHTML = `
                    <td>${product.id}</td>
                    <td>${product.nombre}</td>
                    <td>${product.precio}</td>
                    <td>${product.cantidad}</td>
                    <td class="col-buttons">
                        <div class="table-buttons">
                            <button class="btnEdit" data-id="${product.id}" data-almacen="${product.almacen}" data-cantidad="${product.cantidad}">Editar</button>
                            <button class="btnDelete" data-id="${product.id}"  data-almacen="${product.almacen}">Eliminar</button>
                        </div>
                    </td>
                `;
          productTableBody.appendChild(row);
        });

        const editButtons = document.querySelectorAll(".btnEdit");
        const deleteButtons = document.querySelectorAll(".btnDelete");

        editButtons.forEach((button) => {
          button.addEventListener("click", function () {
            const productId = this.getAttribute("data-id");
            const almacenId = this.getAttribute("data-almacen");
            const cantidad = this.getAttribute("data-cantidad");

            document.getElementById("editProductId").value = productId;
            document.getElementById("editProductAlmacen").value = almacenId;
            document.getElementById("editProductCantidad").value = cantidad;

            editProductModalTitle.textContent = `Editar Producto: ${productId}`;

            editProductModal.showModal();
          });
        });

        deleteButtons.forEach((button) => {
          button.addEventListener("click", function () {
            const productId = this.getAttribute("data-id");
            const almacenId = this.getAttribute("data-almacen");

            document.getElementById("deleteProductId").value = productId;
            document.getElementById("deleteProductAlmacen").value = almacenId;

            deleteProductModalTitle.textContent = `Eliminar Producto: ${productId}`;

            deleteProductModal.showModal();
          });
        });
      })
      .catch((error) => {
        console.error("Error fetching products:", error);
      });
  }
});
