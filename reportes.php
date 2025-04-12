<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes</title>
  <link rel="stylesheet" href="Vistas/reportes.css">
</head>
<body>

  <!-- Barra de navegación -->
  <nav class="navbar">
    <ul>
      <li><a href="dashboard.php">Inicio</a></li>
      <li><a href="profile.php">Perfil de Usuario</a></li>
      <li><a href="reportes.php">Reportes</a></li>
      <li><a href="chat.php">Chat</a></li>
      <li><a href="index.php">Salir</a></li>
    </ul>
  </nav>

  <div class="content">
    <h1>Bienvenido al apartado de Reportes</h1>
    <p>Este es tu panel de control. Aquí puedes ver la actividad.</p>
    
    <!-- Ventana de Reportes -->
    <div class="report-box">
      <h2>Reportes</h2>
      <div class="report-item">
        <p><strong>Cantidad de Usuarios Registrados:</strong> <span id="user-count">5</span></p>
      </div>
      <div class="report-item">
        <p><strong>Cantidad de Post Registrados:</strong> <span id="post-count">2</span></p>
      </div>
    </div>
  </div>

  <script src="Controladores/script.js"></script>

</body>
</html>
