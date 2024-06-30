<!-- navbar.php -->
<!DOCTYPE html>
<html lang="es">

<head>
  <style>
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #0056b3;
      padding: 1em;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
    }

    .items-left {
      display: flex;
      align-items: center;
    }

    .navbar .title {
      font-size: 1.5em;
      font-weight: bold;
    }

    .navbar .nav-links {
      display: flex;
      gap: 1em;
      margin-left: 150px;
    }

    .navbar .nav-links a {
      color: white;
      text-decoration: none;
      font-size: 1em;
    }

    .navbar .nav-links a:hover {
      text-decoration: underline;
    }

    .navbar .logout a {
      color: white;
      text-decoration: none;
      font-size: 1em;
    }

    .navbar .logout a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="items-left">
      <div class="title">Sistema de Inventario</div>
      <div class="nav-links">
        <a href="index.php">Menú</a>
        <a href="inventario.php">Inventario</a>
        <a href="almacenes.php">Almacenes</a>
        <a href="productos.php">Productos</a>
      </div>
    </div>
    <div class="logout">
      <a href="exit.php">Salir</a>
    </div>
  </nav>
</body>

</html>