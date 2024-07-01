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

function actualizarTablasAlCerrarSesion($conn, &$almacenes, &$productos, &$inventario)
{
  try {
    // Iniciar transacción para asegurar integridad de los datos
    $conn->beginTransaction();

    // Eliminar todos los registros de la tabla almacenes
    $stmt = $conn->prepare("DELETE FROM almacenes");
    $stmt->execute();

    // Insertar nuevos registros en la tabla almacenes
    $stmt = $conn->prepare("INSERT INTO almacenes (id, nombre, ubicacion) VALUES (:id, :nombre, :ubicacion)");
    foreach ($almacenes as $almacen) {
      $stmt->bindValue(':id', $almacen->id);
      $stmt->bindValue(':nombre', $almacen->nombre);
      $stmt->bindValue(':ubicacion', $almacen->ubicacion);
      $stmt->execute();
    }

    // Eliminar todos los registros de la tabla productos
    $stmt = $conn->prepare("DELETE FROM productos");
    $stmt->execute();

    // Insertar nuevos registros en la tabla productos
    $stmt = $conn->prepare("INSERT INTO productos (id, nombre, precio, descripcion) VALUES (:id, :nombre, :precio, :descripcion)");
    foreach ($productos as $producto) {
      $stmt->bindValue(':id', $producto->id);
      $stmt->bindValue(':nombre', $producto->nombre);
      $stmt->bindValue(':precio', $producto->precio);
      $stmt->bindValue(':descripcion', $producto->descripcion);
      $stmt->execute();
    }

    // Eliminar todos los registros de la tabla inventario
    $stmt = $conn->prepare("DELETE FROM inventario");
    $stmt->execute();

    // Insertar nuevos registros en la tabla inventario
    $stmt = $conn->prepare("INSERT INTO inventario (almacen_id, producto_id, cantidad) VALUES (:almacen_id, :producto_id, :cantidad)");
    foreach ($inventario as $inv) {
      $stmt->bindValue(':almacen_id', $inv->almacen);
      $stmt->bindValue(':producto_id', $inv->producto);
      $stmt->bindValue(':cantidad', $inv->cantidad);
      $stmt->execute();
    }

    // Confirmar transacción
    $conn->commit();

    // Limpiar las variables de sesión
    unset($_SESSION['almacenes']);
    unset($_SESSION['productos']);
    unset($_SESSION['inventario']);

    // echo "Datos actualizados correctamente al cerrar la sesión.";
  } catch (PDOException $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
  }
}

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventario_db";

try {
  // Conexión a la base de datos utilizando PDO
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Verificar si la sesión está iniciada y las variables de sesión existen
  if (isset($_SESSION['almacenes'], $_SESSION['productos'], $_SESSION['inventario'])) {
    // Llamar a la función para actualizar las tablas al cerrar la sesión
    actualizarTablasAlCerrarSesion($conn, $_SESSION['almacenes'], $_SESSION['productos'], $_SESSION['inventario']);
  }

  // Destruir la sesión
  session_destroy();
  // echo "Sesión cerrada correctamente.";
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

$conn = null; // Cierra la conexión
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adiós!</title>
  <link rel="stylesheet" type="text/css" href="styles/styles.css">
  <link rel="stylesheet" type="text/css" href="styles/stylesExit.css">
</head>

<body>
  <div id="goodBye">
    <h1>Adiós!</h1>
    <h3>Gracias por usar nuestro Sistema de Inventario!</h3>
    <p>Convierte tu sistema en el AS de tu empresa!</p>
    <p>AS Systems ®</p>
  </div>
</body>

</html>
