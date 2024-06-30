<?php
session_start();
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