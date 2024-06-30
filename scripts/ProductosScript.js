document.addEventListener("DOMContentLoaded", function () {
  const btnAddProduct = document.getElementById("btnAddProduct");
  const addProductModal = document.getElementById("addProductModal");
  const closeAddModal = document.getElementById("closeAddModal");

  const editProductModal = document.getElementById("editProductModal");
  const closeEditModal = document.getElementById("closeEditModal");

  const deleteProductModal = document.getElementById("deleteProductModal");
  const closeDeleteModal = document.getElementById("closeDeleteModal");

  btnAddProduct.addEventListener("click", function () {
    addProductModal.showModal();
  });

  closeAddModal.addEventListener("click", function () {
    addProductModal.close();
  });

  closeEditModal.addEventListener("click", function () {
    editProductModal.close();
  });

  closeDeleteModal.addEventListener("click", function () {
    deleteProductModal.close();
  });

  document.querySelectorAll(".btnEdit").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const row = this.closest("tr");
      const name = row.children[1].innerText;
      const price = row.children[2].innerText;
      const description = row.children[3].innerText;

      document.getElementById("editProductId").value = id;
      document.getElementById("editProductName").value = name;
      document.getElementById("editProductPrice").value = price;
      document.getElementById("editProductDescription").value = description;

      document.getElementById("editProductIdText").textContent = `Editar Producto: ${id}`;

      editProductModal.showModal();
    });
  });

  document.querySelectorAll(".btnDelete").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;

      document.getElementById("deleteProductId").value = id;

      document.getElementById("deleteProductIdText").textContent = `Eliminar Producto: ${id}`;

      deleteProductModal.showModal();
    });
  });

  const errorMessageModal = document.getElementById("errorMessageModal");
  if (errorMessageModal) {
    const closeErrorMessageModal = document.getElementById("closeErrorMessageModal");
    errorMessageModal.style.display = "block";

    closeErrorMessageModal.addEventListener("click", function () {
      errorMessageModal.style.display = "none";
    });
  }
});
