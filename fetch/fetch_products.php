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

if (isset($_GET['almacen'])) {
  $almacenId = $_GET['almacen'];
  $productosEnAlmacen = [];

  foreach ($_SESSION['inventario'] as $item) {
    if ($item->almacen == $almacenId) {
      $nombreProducto = fetchProductName($item->producto);
      $precioProducto = fetchProductPrice($item->producto);
      $productosEnAlmacen[] = [
        'id' => $item->producto,
        'nombre' => $nombreProducto,
        'precio' => $precioProducto,
        'cantidad' => $item->cantidad,
        'almacen' => $item->almacen
      ];
    }
  }

  header('Content-Type: application/json');
  echo json_encode($productosEnAlmacen);
  exit();
}

function fetchProductName($productId)
{
  foreach ($_SESSION['productos'] as $producto) {
    if ($producto->id == $productId) {
      return $producto->nombre;
    }
  }
  return "Producto no encontrado";
}

function fetchProductPrice($productId)
{
  foreach ($_SESSION['productos'] as $producto) {
    if ($producto->id == $productId) {
      return $producto->precio;
    }
  }
  return 0;
}
?>