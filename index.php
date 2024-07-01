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

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventario_db";

if (!isset($_SESSION['almacenes'], $_SESSION['productos'], $_SESSION['inventario'])) {
  try {
    // Conexión a la base de datos utilizando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cargar datos de la tabla almacenes
    $stmt = $conn->prepare("SELECT id, nombre, ubicacion FROM almacenes");
    $stmt->execute();
    $almacenes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $almacenes[] = new Almacen($row['id'], $row['nombre'], $row['ubicacion']);
    }
    $_SESSION['almacenes'] = $almacenes;

    // Depuración: Imprimir los datos cargados de almacenes
    // echo "Almacenes cargados: <br>";
    // foreach ($almacenes as $almacen) {
    //   echo "ID: " . $almacen->id . ", Nombre: " . $almacen->nombre . ", Ubicación: " . $almacen->ubicacion . "<br>";
    // }

    // Cargar datos de la tabla productos
    $stmt = $conn->prepare("SELECT id, nombre, precio, descripcion FROM productos");
    $stmt->execute();
    $productos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $productos[] = new Producto($row['id'], $row['nombre'], $row['precio'], $row['descripcion']);
    }
    $_SESSION['productos'] = $productos;

    // Depuración: Imprimir los datos cargados de productos
    // echo "Productos cargados: <br>";
    // foreach ($productos as $producto) {
    //   echo "ID: " . $producto->id . ", Nombre: " . $producto->nombre . ", Precio: " . $producto->precio . ", Descripción: " . $producto->descripcion . "<br>";
    // }

    // Cargar datos de la tabla inventario
    $stmt = $conn->prepare("SELECT almacen_id, producto_id, cantidad FROM inventario");
    $stmt->execute();
    $inventario = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $inventario[] = new Inventario($row['almacen_id'], $row['producto_id'], $row['cantidad']);
    }
    $_SESSION['inventario'] = $inventario;

    // Depuración: Imprimir los datos cargados de inventario
    // echo "Inventario cargado: <br>";
    // foreach ($inventario as $inv) {
    //   echo "Almacen ID: " . $inv->almacen . ", Producto ID: " . $inv->producto . ", Cantidad: " . $inv->cantidad . "<br>";
    // }

    // echo "Datos cargados en las variables de sesión.";
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

$conn = null; // Cierra la conexión
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Inventario</title>
  <link rel="stylesheet" type="text/css" href="styles/styles.css">
  <link rel="stylesheet" type="text/css" href="styles/stylesIndex.css">
</head>

<body>
  <?php include 'components/navbar.php'; ?>

  <header>
    <h1>Sistema de Inventario</h1>
    <h3>Menú Principal</h3>
  </header>

  <main>
    <ul>
      <li><a class="btn" href="almacenes.php">Gestión de Almacenes</a></li>
      <li><a class="btn" href="productos.php">Gestión de Productos</a></li>
      <li><a class="btn" href="inventario.php">Gestión de Inventario</a></li>
      <li><a class="btn" href="exit.php">Salir</a></li>
    </ul>
  </main>

  <?php include 'components/footer.php'; ?>
</body>

</html>
