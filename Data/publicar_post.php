<?php
session_start();

include ("../Models/Users.php");
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = $_POST['content'];
    $usuario = $_SESSION['usuario']; 

    $content = htmlspecialchars(trim($content), ENT_QUOTES, 'UTF-8');
    $content = mysqli_real_escape_string($conexion, $content);

    if (empty($content)) {
        echo json_encode(['success' => false, 'error' => 'El contenido no puede estar vacío']);
        exit;
    }

    $query = "INSERT INTO publicaciones (usuario, contenido, fecha_publicacion) VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($conexion, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $content);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $post_id = mysqli_insert_id($conexion);

            $select_query = "SELECT id, usuario, contenido, fecha_publicacion FROM publicaciones WHERE id = ?";
            $select_stmt = mysqli_prepare($conexion, $select_query);
            mysqli_stmt_bind_param($select_stmt, "i", $post_id);
            mysqli_stmt_execute($select_stmt);
            $select_result = mysqli_stmt_get_result($select_stmt);
            $new_post = mysqli_fetch_assoc($select_result);

            echo json_encode(['success' => true, 'post' => $new_post]); 
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al insertar la publicación: ' . mysqli_error($conexion)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta: ' . mysqli_error($conexion)]);
    }

    mysqli_close($conexion);
} else {
    echo json_encode(['success' => false, 'error' => 'Solicitud no válida']);
}
?>