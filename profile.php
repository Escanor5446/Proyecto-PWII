<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capadb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$usuario_nombre_sesion = $_SESSION['usuario'];

$sql = "SELECT ID, correo, FechaNac, Imagen FROM usuarios WHERE NombreUsua = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_nombre_sesion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $usuario_id = $row['ID'];
    $nombre = $usuario_nombre_sesion;
    $email = $row['correo'];
    $fecha_nacimiento = $row['FechaNac'];
    $imagen_nombre = $row['Imagen'];
} else {
    echo "Error: No se encontraron los datos del usuario.";
    exit();
}

$stmt->close();
$conn->close();

$image_folder = "Imagenes/";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="Vistas/Perfil.css">
</head>
<body>

    <nav class="navbar">
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="profile.php">Perfil de Usuario</a></li>
            <li><a href="reportes.php">Reportes</a></li>
            <li><a href="chat.php">Chat</a></li>
            <li><a href="Data/cerrar_session.php">Salir</a></li>
        </ul>
    </nav>

    <div class="profile-container">
        <div class="profile-card">

            <div class="foto-perfil">
                <?php
                $image_folder = __DIR__ . "/Imagenes/";  // Ruta completa del servidor al directorio
                if (!empty($imagen_nombre) && file_exists($image_folder . $imagen_nombre)) {
                    $image_src = "/Data/Imagenes/" . $imagen_nombre; // Ruta relativa a profile.php
                    echo '<img src="' . $image_src . '" alt="Foto de perfil">';
                } else {
                    echo '<img src="../Imagenes/descarga.jpg" alt="Foto de perfil">';
                }
                ?>
            </div>

            <h2 class="profile-name"><?php echo $nombre; ?></h2>
            <p class="profile-birthday">Correo: <?php echo $email; ?></p>
            <p class="profile-birthday">Fecha de Nacimiento: <?php echo $fecha_nacimiento; ?></p>

            <a href="editar_perfil.php" class="edit-btn">Modificar Datos</a>

        </div>
    </div>

    <script src="Controladores/script.js"></script>
</body>
</html>