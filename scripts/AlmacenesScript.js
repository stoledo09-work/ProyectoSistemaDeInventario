document.addEventListener("DOMContentLoaded", function () {
  const btnAddWarehouse = document.getElementById("btnAddWarehouse");
  const addWarehouseModal = document.getElementById("addWarehouseModal");
  const closeAddModal = document.getElementById("closeAddModal");

  const editWarehouseModal = document.getElementById("editWarehouseModal");
  const closeEditModal = document.getElementById("closeEditModal");

  const deleteWarehouseModal = document.getElementById("deleteWarehouseModal");
  const closeDeleteModal = document.getElementById("closeDeleteModal");

  btnAddWarehouse.addEventListener("click", function () {
    addWarehouseModal.showModal();
  });

  closeAddModal.addEventListener("click", function () {
    addWarehouseModal.close();
    addWarehouseLocation.style.height = "50px";
  });

  closeEditModal.addEventListener("click", function () {
    editWarehouseModal.close();
    editWarehouseLocation.style.height = "50px";
  });

  closeDeleteModal.addEventListener("click", function () {
    deleteWarehouseModal.close();
  });

  document.querySelectorAll(".btnEdit").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const name = this.closest("tr").children[1].innerText;
      const location = this.closest("tr").children[2].innerText;

      document.getElementById("editWarehouseId").value = id;
      document.getElementById("editWarehouseName").value = name;
      document.getElementById("editWarehouseLocation").value = location;

      document.getElementById("editWarehouseIdText").textContent = `Editar Almacén: ${id}`;

      editWarehouseModal.showModal();
    });
  });

  document.querySelectorAll(".btnDelete").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;

      document.getElementById("deleteWarehouseId").value = id;

      document.getElementById("deleteWarehouseIdText").textContent = `Eliminar Almacén: ${id}`;

      deleteWarehouseModal.showModal();
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
