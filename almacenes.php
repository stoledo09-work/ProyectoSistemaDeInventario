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

if (!isset($_SESSION['inventario'])) {
  $_SESSION['inventario'] = [];
}

if (isset($_POST['addWarehouse'])) {
  $id = $_POST['id'];
  $nombre = $_POST['nombre'];
  $ubicacion = $_POST['ubicacion'];

  foreach ($_SESSION['almacenes'] as $almacen) {
    if ($almacen->id == $id) {
      $_SESSION['errorMessage'] = "El ID del almacén ya existe. ($id)";
      header("Location: almacenes.php");
      exit();
    }
  }

  $_SESSION['almacenes'][] = new Almacen($id, $nombre, $ubicacion);
  header("Location: almacenes.php");
  exit();
}

if (isset($_POST['editWarehouse'])) {
  $id = $_POST['id'];
  $nombre = $_POST['nombre'];
  $ubicacion = $_POST['ubicacion'];

  foreach ($_SESSION['almacenes'] as &$almacen) {
    if ($almacen->id == $id) {
      $almacen->nombre = $nombre;
      $almacen->ubicacion = $ubicacion;
      break;
    }
  }

  header("Location: almacenes.php");
  exit();
}

if (isset($_POST['deleteWarehouse'])) {
  $id = $_POST['id'];

  foreach ($_SESSION['almacenes'] as $key => $almacen) {
    if ($almacen->id == $id) {
      if (contarProductosPorAlmacen($id) == 0) {
        unset($_SESSION['almacenes'][$key]);
      } else {
        $_SESSION['errorMessage'] = "No se puede eliminar un almacén que tiene productos.";
      }
      break;
    }
  }

  header("Location: almacenes.php");
  exit();
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
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Almacenes</title>
  <link rel="stylesheet" type="text/css" href="styles/styles.css">
  <link rel="stylesheet" type="text/css" href="styles/stylesAlmacenes.css">
</head>

<body>
  <?php include 'components/navbar.php'; ?>

  <header>
    <h1>Gestión de Almacenes</h1>
    <!-- <a href="index.php" class="btnBack">Volver al Menú Principal</a> -->
    <button id="btnAddWarehouse">Agregar Almacén</button>
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

    <table id="warehouseTable">
      <thead>
        <tr>
          <th class="col-id">ID</th>
          <th class="col-name">Nombre</th>
          <th class="col-location">Ubicación</th>
          <th class="col-quantity">Cantidad de Productos</th>
          <th class="col-buttons">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['almacenes'] as $almacen): ?>
          <tr>
            <td><?php echo $almacen->id; ?></td>
            <td><?php echo $almacen->nombre; ?></td>
            <td><?php echo $almacen->ubicacion; ?></td>
            <td><?php echo contarProductosPorAlmacen($almacen->id); ?></td>
            <td class="col-buttons">
              <div class="table-buttons">
                <button class="btnEdit" data-id="<?php echo $almacen->id; ?>">Editar</button>
                <button class="btnDelete" data-id="<?php echo $almacen->id; ?>">Eliminar</button>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Modal Agregar Almacén -->
    <dialog id="addWarehouseModal">
      <form id="addWarehouseForm" method="POST" action="almacenes.php">
        <h2>Agregar Almacén</h2>

        <label for="addWarehouseId">ID:</label>
        <input type="number" id="addWarehouseId" name="id" required>

        <label for="addWarehouseName">Nombre:</label>
        <input type="text" id="addWarehouseName" name="nombre" required>

        <label for="addWarehouseLocation">Ubicación:</label>
        <!-- <input type="text" id="addWarehouseLocation" name="ubicacion" required> -->
        <textarea id="addWarehouseLocation" name="ubicacion" required></textarea>

        <div class="form-buttons">
          <input type="submit" name="addWarehouse" value="Agregar">
          <button type="button" id="closeAddModal">Cancelar</button>
        </div>
      </form>
    </dialog>

    <!-- Modal Editar Almacén -->
    <dialog id="editWarehouseModal">
      <form id="editWarehouseForm" method="POST" action="almacenes.php">
        <h2 id="editWarehouseIdText">Editar Almacén:</h2>

        <input type="hidden" id="editWarehouseId" name="id">

        <label for="editWarehouseName">Nombre:</label>
        <input type="text" id="editWarehouseName" name="nombre" required>

        <label for="editWarehouseLocation">Ubicación:</label>
        <textarea id="editWarehouseLocation" name="ubicacion" required></textarea>

        <div class="form-buttons">
          <input type="submit" name="editWarehouse" value="Actualizar">
          <button type="button" id="closeEditModal">Cancelar</button>
        </div>
      </form>
    </dialog>

    <!-- Modal Eliminar Almacén -->
    <dialog id="deleteWarehouseModal">
      <form id="deleteWarehouseForm" method="POST" action="almacenes.php">
        <h2 id="deleteWarehouseIdText">Eliminar Almacén:</h2>

        <p>¿Estás seguro de que deseas eliminar este almacén?</p>

        <input type="hidden" id="deleteWarehouseId" name="id">

        <div class="form-buttons">
          <input class="confirmDelete" type="submit" name="deleteWarehouse" value="Confirmar">
          <button class="cancelDelete" type="button" id="closeDeleteModal">Cancelar</button>
        </div>
      </form>
    </dialog>
  </main>

  <script src="scripts/AlmacenesScript.js"></script>

  <?php include 'components/footer.php'; ?>
</body>

</html>