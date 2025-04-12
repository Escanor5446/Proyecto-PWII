<?php

session_start();

if(!isset($_SESSION['usuario'])){
    echo'
    <script>
    alert("Inicia sesion");
    window.location = "index.php";
    </script>';
    session_destroy();
    die();
}

require_once 'Models/Users.php';
$conn = require 'Models/Users.php';

if (!$conn) {
    die("Error: No se pudo establecer la conexión a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'publicar') {
    $contenido = $_POST["postContent"];
    $usuario = $_SESSION['usuario'];

    $sql = "INSERT INTO publicaciones (usuario, contenido, fecha_publicacion) VALUES ('$usuario', '$contenido', NOW())";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        $sql_select = "SELECT id, usuario, contenido, fecha_publicacion FROM publicaciones WHERE id = $last_id";
        $result = $conn->query($sql_select);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = array(
                'status' => 'success',
                'id' => $row['id'],
                'usuario' => htmlspecialchars($row["usuario"]),
                'contenido' => htmlspecialchars($row["contenido"]),
                'fecha_publicacion' => htmlspecialchars($row["fecha_publicacion"])
            );
            echo json_encode($response);
        } else {
            $response = array('status' => 'error', 'message' => 'Error al obtener la publicación.');
            echo json_encode($response);
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Error al publicar: ' . $conn->error);
        echo json_encode($response);
    }
    $conn->close();
    exit();
}

$sql_obtener_posts = "SELECT id, usuario, contenido, fecha_publicacion FROM publicaciones ORDER BY fecha_publicacion DESC";
$result_posts = $conn->query($sql_obtener_posts);
$conn->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="Vistas/Dash.css">
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

<div class="dashboard">
    <div class="create-post-bar">
        <form id="postForm">
        <h2>Publica Tu Estado :</h2>
            <textarea id="postContent" name="postContent" placeholder="Escribe tu publicación"></textarea>
            <button type="submit">Publicar</button>
        </form>
    </div>

    <div id="posts-container">
        <h2></h2>
        <?php
        if ($result_posts->num_rows > 0) {
            while($row = $result_posts->fetch_assoc()) {
                echo '<div class="post" data-post-id="' . $row['id'] . '">';
                echo '<div class="post-header">';
                echo '<span class="post-author">' . htmlspecialchars($row["usuario"]) . '</span>';
                echo '<span class="post-date"> - ' . htmlspecialchars($row["fecha_publicacion"]) . '</span>';
                echo '</div>';
                echo '<p class="post-content">' . htmlspecialchars($row["contenido"]) . '</p>';
                echo '<div class="post-actions">';
                echo '<button class="like-btn" data-post-id="' . $row['id'] . '">❤️ Me gusta</button>';
                echo '<button class="comment-btn" data-post-id="' . $row['id'] . '">💬 Comentar</button>';
                echo '</div>';
                echo '<div class="comment-section" style="display: none;">';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>Aún no hay publicaciones.</p>";
        }
        ?>
    </div>
</div>

<script src="Controladores/publicaciones.js"></script>
</body>
</html>