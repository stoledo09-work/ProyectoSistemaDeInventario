<!-- footer.php -->
<!DOCTYPE html>
<html lang="es">

<head>
  <style>
    .footer {
      background-color: #3586ff;
      color: white;
      /* padding: 1em; */
      text-align: center;
      position: fixed;
      width: 100%;
      height: 80px;
      bottom: 0;
      left: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .footer .footer-links {
      display: flex;
      justify-content: center;
      gap: 1em;
    }

    .footer .footer-links a {
      color: white;
      text-decoration: none;
      font-size: 1em;
    }

    .footer .footer-links a:hover {
      text-decoration: underline;
    }

    .footer p {
      color: #fff;
      margin: 15px 0 10px 0;
      font-size: 1rem;
      font-weight: 300;
    }

    .wave {
      position: absolute;
      top: -50px;
      left: 0;
      width: 100%;
      height: 50px;
      background: url("https://i.ibb.co/wQZVxxk/wave.png");
      background-size: 1000px 50px;
    }

    .wave#wave1 {
      z-index: 1000;
      opacity: 1;
      bottom: 0;
      animation: animateWaves 4s linear infinite;
    }

    .wave#wave2 {
      z-index: 999;
      opacity: 0.5;
      bottom: 10px;
      animation: animate 4s linear infinite !important;
    }

    .wave#wave3 {
      z-index: 1000;
      opacity: 0.2;
      bottom: 15px;
      animation: animateWaves 3s linear infinite;
    }

    .wave#wave4 {
      z-index: 999;
      opacity: 0.7;
      bottom: 20px;
      animation: animate 3s linear infinite;
    }

    @keyframes animateWaves {
      0% {
        background-position-x: 1000px;
      }

      100% {
        background-positon-x: 0px;
      }
    }

    @keyframes animate {
      0% {
        background-position-x: -1000px;
      }

      100% {
        background-positon-x: 0px;
      }
    }
  </style>
</head>

<body>
  <footer class="footer">
    <div class="waves">
      <div class="wave" id="wave1"></div>
      <div class="wave" id="wave2"></div>
      <div class="wave" id="wave3"></div>
      <div class="wave" id="wave4"></div>
    </div>
    <div class="footer-links">
      <a href="index.php">Menú</a>
      <a href="inventario.php">Inventario</a>
      <a href="almacenes.php">Almacenes</a>
      <a href="productos.php">Productos</a>
    </div>
    <p>©2024 AS Systems | Todos los derechos reservados</p>
  </footer>
</body>

</html>