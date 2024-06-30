<?php
session_start();

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

if (!isset($_SESSION['productos'])) {
  $_SESSION['productos'] = array();
}

if (isset($_POST['addProduct'])) {
  $id = $_POST['id'];
  $nombre = $_POST['nombre'];
  $precio = $_POST['precio'];
  $descripcion = $_POST['descripcion'];

  foreach ($_SESSION['productos'] as $producto) {
    if ($producto->id == $id) {
      $_SESSION['existing_product'] = [
        'id' => $id,
        'nombre' => $nombre,
        'precio' => $precio,
        'descripcion' => $descripcion
      ];
      header("Location: productos.php");
      exit();
    }
  }

  $_SESSION['productos'][] = new Producto($id, $nombre, $precio, $descripcion);
  header("Location: productos.php");
  exit();
}

if (isset($_POST['editProduct'])) {
  $id = $_POST['id'];
  $nombre = $_POST['nombre'];
  $precio = $_POST['precio'];
  $descripcion = $_POST['descripcion'];

  foreach ($_SESSION['productos'] as &$producto) {
    if ($producto->id == $id) {
      $producto->nombre = $nombre;
      $producto->precio = $precio;
      $producto->descripcion = $descripcion;
      break;
    }
  }
  header("Location: productos.php");
  exit();
}

if (isset($_POST['deleteProduct'])) {
  $id = $_POST['id'];

  foreach ($_SESSION['productos'] as $key => $producto) {
    if ($producto->id == $id) {
      if (checkExistenciaInventario($id) == "No") {
        unset($_SESSION['productos'][$key]);
        $_SESSION['productos'] = array_values($_SESSION['productos']);
      } else if (checkExistenciaInventario($id) == "Si") {
        $_SESSION['errorMessage'] = "No se puede eliminar un producto existente en el inventario.";
      }
      break;
    }
  }

  header("Location: productos.php");
  exit();
}

if (isset($_POST['updateExistingProduct'])) {
  $id = $_POST['id'];
  $nombre = $_POST['nombre'];
  $precio = $_POST['precio'];
  $descripcion = $_POST['descripcion'];

  foreach ($_SESSION['productos'] as &$producto) {
    if ($producto->id == $id) {
      $producto->nombre = $nombre;
      $producto->precio = $precio;
      $producto->descripcion = $descripcion;
      break;
    }
  }
  unset($_SESSION['existing_product']);
  header("Location: productos.php");
  exit();
}

function checkExistenciaInventario($productoId)
{
  $existe = "No";
  if (isset($_SESSION['inventario'])) {
    foreach ($_SESSION['inventario'] as $item) {
      if ($item->producto == $productoId) {
        $existe = "Si";
        break;
      }
    }
  }
  return $existe;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Productos</title>
  <link rel="stylesheet" type="text/css" href="styles/styles.css">
  <link rel="stylesheet" type="text/css" href="styles/stylesProductos.css">
</head>

<body>
  <?php include 'components/navbar.php'; ?>

  <header>
    <h1>Gestión de Productos</h1>
    <!-- <a href="index.php" class="btnBack">Volver al Menú Principal</a> -->
    <button id="btnAddProduct">Agregar Producto</button>
  </header>

  <main>
    <?php if (!empty($_SESSION['errorMessage'])): ?>
      <div id="errorMessageModal" class="errorModal">
        <div class="errorModalContent">
          <span class="closeErrorModal" id="closeErrorMessageModal">&times;</span>
          <p>
            <?php echo $_SESSION['errorMessage'];
            unset($_SESSION['errorMessage']); ?>
          </p>
        </div>
      </div>
    <?php endif; ?>

    <table id="productTable">
      <thead>
        <tr>
          <th class="col-id">ID</th>
          <th class="col-name">Nombre</th>
          <th class="col-price">Precio</th>
          <th class="col-description">Descripción</th>
          <th class="col-exist">Presente en Inventario</th>
          <th class="col-buttons">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['productos'] as $producto): ?>
          <tr>
            <td><?php echo $producto->id; ?></td>
            <td><?php echo $producto->nombre; ?></td>
            <td><?php echo $producto->precio; ?></td>
            <td><?php echo $producto->descripcion; ?></td>
            <td><?php echo checkExistenciaInventario($producto->id); ?></td>
            <td class="col-buttons">
              <div class="table-buttons">
                <button class="btnEdit" data-id="<?php echo $producto->id; ?>">Editar</button>
                <button class="btnDelete" data-id="<?php echo $producto->id; ?>">Eliminar</button>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Modal Agregar Producto -->
    <dialog id="addProductModal">
      <form id="addProductForm" method="POST" action="productos.php">
        <h2>Agregar Producto</h2>

        <label for="id">ID:</label>
        <input type="number" id="id" name="id" placeholder="Ingrese el ID del producto" required>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingrese el nombre del producto" required>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" id="precio" name="precio" placeholder="Ingrese el precio del producto"
          required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" placeholder="Ingrese la descripción del producto"
          required></textarea>

        <div class="form-buttons">
          <input type="submit" name="addProduct" value="Agregar Producto">
          <button type="button" id="closeAddModal">Cancelar</button>
        </div>
      </form>
    </dialog>

    <!-- Modal Editar Producto -->
    <dialog id="editProductModal">
      <form id="editProductForm" method="POST" action="productos.php">
        <h2 id="editProductIdText">Editar Producto:</h2>
        <input type="hidden" id="editProductId" name="id">

        <label for="editProductName">Nombre:</label>
        <input type="text" id="editProductName" name="nombre" required>

        <label for="editProductPrice">Precio:</label>
        <input type="number" id="editProductPrice" name="precio" step="0.01" required>

        <label for="editProductDescription">Descripción:</label>
        <textarea id="editProductDescription" name="descripcion" required></textarea>

        <div class="form-buttons">
          <input type="submit" name="editProduct" value="Actualizar Producto">
          <button type="button" id="closeEditModal">Cancelar</button>
        </div>
      </form>
    </dialog>

    <!-- Modal Eliminar Producto -->
    <dialog id="deleteProductModal">
      <form id="deleteProductForm" method="POST" action="productos.php">
        <h2 id="deleteProductIdText">Eliminar Producto:</h2>

        <p>¿Estás seguro de que deseas eliminar este producto?</p>

        <input type="hidden" id="deleteProductId" name="id">

        <div class="form-buttons">
          <input class="confirmDelete" type="submit" name="deleteProduct" value="Confirmar">
          <button class="cancelDelete" type="button" id="closeDeleteModal">Cancelar</button>
        </div>
      </form>
    </dialog>

    <!-- Modal Producto Existente -->
    <dialog id="existingProductModal">
      <form id="existingProductForm" method="POST" action="productos.php">
        <h2 id="existingProductIdText">Producto Existente:</h2>

        <p>¿Desea actualizar los datos del producto?</p>

        <input type="hidden" id="existingProductId" name="id">
        <input type="hidden" id="existingProductName" name="nombre">
        <input type="hidden" id="existingProductPrice" name="precio">
        <input type="hidden" id="existingProductDescription" name="descripcion">

        <div class="form-buttons">
          <input type="submit" name="updateExistingProduct" value="Actualizar Datos">
          <button type="button" id="closeExistingProductModal">Cancelar</button>
        </div>
      </form>
    </dialog>
  </main>

  <script src="scripts/ProductosScript.js"></script>

  <?php if (isset($_SESSION['existing_product'])): ?>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        var product = <?php echo json_encode($_SESSION['existing_product']); ?>;

        document.getElementById("existingProductId").value = product.id;
        document.getElementById("existingProductName").value = product.nombre;
        document.getElementById("existingProductPrice").value = product.precio;
        document.getElementById("existingProductDescription").value = product.descripcion;

        document.getElementById("existingProductIdText").textContent = `Producto Existente: ${product.id}`;

        document.getElementById("existingProductModal").showModal();

        document.getElementById("closeExistingProductModal").addEventListener("click", function () {
          document.getElementById("existingProductModal").close();
        });
      });
      <?php unset($_SESSION['existing_product']); ?>
    </script>
  <?php endif; ?>

  <?php include 'components/footer.php'; ?>
</body>

</html>