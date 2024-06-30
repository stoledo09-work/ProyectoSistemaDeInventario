<?php
session_start();

class Almacen
{
  public $id;
  public $nombre;
  public $ubicacion;

  function __construct($id, $nombre, $ubicacion)
  {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->ubicacion = $ubicacion;
  }
}
class Producto
{
  public $id;
  public $nombre;
  public $precio;
  public $descripcion;

  function __construct($id, $nombre, $precio, $descripcion)
  {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->precio = $precio;
    $this->descripcion = $descripcion;
  }
}
class Inventario
{
  public $almacen;
  public $producto;
  public $cantidad;

  public function __construct($almacen, $producto, $cantidad)
  {
    $this->almacen = $almacen;
    $this->producto = $producto;
    $this->cantidad = $cantidad;
  }
}

if (!isset($_SESSION['almacenes'])) {
  $_SESSION['almacenes'] = [];
}
if (!isset($_SESSION['productos'])) {
  $_SESSION['productos'] = array();
}
if (!isset($_SESSION['inventario'])) {
  $_SESSION['inventario'] = [];
}

if (isset($_POST['addProduct'])) {
  $almacen = $_POST['almacen'];
  $producto = $_POST['producto'];
  $cantidad = $_POST['cantidad'];

  $_SESSION['lastAlmacenId'] = $almacen;
  $_SESSION['lastAlmacenNombre'] = getAlmacenNombreById($almacen);

  foreach ($_SESSION['inventario'] as &$item) {
    if ($item->almacen == $almacen && $item->producto == $producto) {
      $item->cantidad += $cantidad;
      header("Location: inventario.php");
      exit();
    }
  }

  $_SESSION['inventario'][] = new Inventario($almacen, $producto, $cantidad);
  header("Location: inventario.php");
  exit();
}

if (isset($_POST['editProduct'])) {
  $idAlmacen = $_POST['almacen'];
  $idProducto = $_POST['producto'];
  $cantidad = $_POST['cantidad'];

  $_SESSION['lastAlmacenId'] = $idAlmacen;
  $_SESSION['lastAlmacenNombre'] = getAlmacenNombreById($idAlmacen);

  foreach ($_SESSION['inventario'] as &$item) {
    if ($item->almacen == $idAlmacen && $item->producto == $idProducto) {
      $item->cantidad = $cantidad; // Actualizar la cantidad
      header("Location: inventario.php");
      exit();
    }
  }
}

if (isset($_POST['deleteProduct'])) {
  $idAlmacen = $_POST['almacen'];
  $idProducto = $_POST['producto'];

  $_SESSION['lastAlmacenId'] = $idAlmacen;
  $_SESSION['lastAlmacenNombre'] = getAlmacenNombreById($idAlmacen);

  foreach ($_SESSION['inventario'] as $key => $item) {
    if ($item->almacen == $idAlmacen && $item->producto == $idProducto) {
      unset($_SESSION['inventario'][$key]); // Eliminar el producto del inventario
      $_SESSION['inventario'] = array_values($_SESSION['inventario']); // Reindexar el array
      header("Location: inventario.php");
      exit();
    }
  }
}

function contarProductosPorAlmacen($almacenId)
{
  $contador = 0;
  foreach ($_SESSION['inventario'] as $producto) {
    if ($producto->almacen == $almacenId) {
      $contador += $producto->cantidad;
    }
  }
  return $contador;
}

function getAlmacenNombreById($id)
{
  foreach ($_SESSION['almacenes'] as $almacen) {
    if ($almacen->id == $id) {
      return $almacen->nombre;
    }
  }
  return null;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario por Almacén</title>
  <link rel="stylesheet" type="text/css" href="styles/styles.css">
  <link rel="stylesheet" type="text/css" href="styles/stylesInventario.css">
</head>

<body>
  <?php include 'components/navbar.php'; ?>

  <header>
    <h1>Gestión de Inventario por Almacén</h1>
    <!-- <a href="index.php" class="btnBack">Volver al Menú Principal</a> -->
  </header>

  <main>
    <div class="warehouse-grid">
      <?php foreach ($_SESSION['almacenes'] as $almacen): ?>
        <div class="warehouse-card" data-id="<?php echo $almacen->id; ?>" data-nombre="<?php echo $almacen->nombre; ?>">
          <h2><?php echo $almacen->nombre; ?></h2>
          <p>ID: <?php echo $almacen->id; ?></p>
          <p>Productos: <?php echo contarProductosPorAlmacen($almacen->id); ?></p>
          <p>Ubicación: <?php echo $almacen->ubicacion; ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <dialog id="productListModal">
      <div class="productListModalHeader">
        <h2 id="productListModalTitle">Productos en Almacén:</h2>
        <div>
          <button id="btnAddProduct">Agregar Producto</button>
          <button type="button" id="closeProductListModal">Cerrar</button>
        </div>
      </div>
      <table id="productTable">
        <thead>
          <tr>
            <th class="col-id">ID</th>
            <th class="col-name">Nombre</th>
            <th class="col-price">Precio</th>
            <th class="col-quantity">Cantidad</th>
            <!-- <th>Almacén</th> -->
            <th class="col-buttons">Acciones</th>
          </tr>
        </thead>
        <tbody id="productTableBody">
        </tbody>
      </table>
    </dialog>

    <dialog id="addProductModal">
      <form id="addProductForm" method="POST" action="inventario.php">
        <h2 id="addProductModalTitle">Agregar Producto en Almacén:</h2>

        <input type="hidden" id="addProductAlmacen" name="almacen">

        <label for="producto">Producto:</label>
        <select id="producto" name="producto" required>
          <option value="" disabled selected>Seleccionar producto</option>
          <?php foreach ($_SESSION['productos'] as $producto): ?>
            <option value="<?php echo $producto->id; ?>"><?php echo $producto->id . ' - ' . $producto->nombre; ?></option>
          <?php endforeach; ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" required>

        <div class="form-buttons">
          <input type="submit" name="addProduct" value="Agregar Producto">
          <button type="button" id="closeAddProductModal">Cancelar</button>
        </div>
      </form>
    </dialog>

    <dialog id="editProductModal">
      <form id="editProductForm" method="POST" action="inventario.php">
        <h2 id="editProductModalTitle">Editar Producto:</h2>

        <input type="hidden" id="editProductId" name="producto">
        <input type="hidden" id="editProductAlmacen" name="almacen">

        <label for="editProductCantidad">Cantidad:</label>
        <input type="number" id="editProductCantidad" name="cantidad" required>

        <div class="form-buttons">
          <input type="submit" name="editProduct" value="Actualizar Producto">
          <button type="button" id="closeEditProductModal">Cancelar</button>
        </div>
      </form>
    </dialog>

    <dialog id="deleteProductModal">
      <form id="deleteProductForm" method="POST" action="inventario.php">
        <h2 id="deleteProductModalTitle">Eliminar Producto:</h2>

        <p>¿Estás seguro de que deseas eliminar este producto?</p>

        <input type="hidden" id="deleteProductAlmacen" name="almacen">
        <input type="hidden" id="deleteProductId" name="producto">

        <div class="form-buttons">
          <input class="confirmDelete" type="submit" name="deleteProduct" value="Confirmar">
          <button class="cancelDelete" type="button" id="closeDeleteProductModal">Cancelar</button>
        </div>
      </form>
    </dialog>
  </main>

  <script src="scripts/InventarioScript.js"></script>
  <script>
    const lastAlmacenId = <?php echo isset($_SESSION['lastAlmacenId']) ? json_encode($_SESSION['lastAlmacenId']) : 'null'; ?>;
    const lastAlmacenNombre = <?php echo isset($_SESSION['lastAlmacenNombre']) ? json_encode($_SESSION['lastAlmacenNombre']) : 'null'; ?>;

    if (lastAlmacenId && lastAlmacenNombre) {
      currentAlmacenId = lastAlmacenId;
      currentAlmacenNombre = lastAlmacenNombre;
      productListModalTitle.textContent = `Productos en Almacén: ${lastAlmacenNombre} - ${lastAlmacenId}`;
      fetchProducts(lastAlmacenId);
      <?php unset($_SESSION['lastAlmacenId']); ?>
      <?php unset($_SESSION['lastAlmacenNombre']); ?>
      productListModal.showModal();
    }

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

    document.getElementById("btnAddProduct").addEventListener("click", function () {
      if (currentAlmacenId && currentAlmacenNombre) {
        document.getElementById("addProductAlmacen").value = currentAlmacenId;

        addProductModalTitle.textContent = `Agregar Producto en Almacén: ${currentAlmacenId}`;

        addProductModal.showModal();
      }
    });
  </script>

  <?php include 'components/footer.php'; ?>
</body>

</html>