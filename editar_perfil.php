<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
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
    $nombre_actual = $usuario_nombre_sesion;
    $email_actual = $row['correo'];
    $fecha_nacimiento_actual = $row['FechaNac'];
    $imagen_actual = $row['Imagen'];
} else {
    echo "Error: No se encontraron los datos del usuario.";
    exit();
}
$stmt->close();

$error_message = "";
$success_message = "";
$password_error_message = "";
$password_success_message = "";
$image_error_message = "";
$image_success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_perfil'])) {
    $nuevo_nombre = trim($_POST['nombre']);
    $nuevo_email = trim($_POST['email']);
    $nueva_fecha_nacimiento = $_POST['fecha_nacimiento'];

    if (empty($nuevo_nombre) || empty($nuevo_email) || empty($nueva_fecha_nacimiento)) {
        $error_message .= "Por favor, completa todos los campos.<br>";
    }
    if (!filter_var($nuevo_email, FILTER_VALIDATE_EMAIL)) {
        $error_message .= "El formato del correo electrónico no es válido.<br>";
    }

    if (empty($error_message)) {
        $sql_update = "UPDATE usuarios SET NombreUsua = ?, correo = ?, FechaNac = ? WHERE ID = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssi", $nuevo_nombre, $nuevo_email, $nueva_fecha_nacimiento, $usuario_id);

        if ($stmt_update->execute()) {
            $success_message = "Datos del perfil actualizados correctamente.";
            if ($nuevo_nombre !== $usuario_nombre_sesion) {
                $_SESSION['usuario'] = $nuevo_nombre;
            }
            header("Location: profile.php?mensaje=perfil_actualizado");
            exit();
        } else {
            $error_message = "Error al actualizar los datos del perfil: " . $stmt_update->error;
        }
        $stmt_update->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_password'])) {
    $password_nuevo = $_POST['password_nuevo'];
    $password_confirmar = $_POST['password_confirmar'];

    if (empty($password_nuevo) || empty($password_confirmar)) {
        $password_error_message .= "Por favor, ingresa la nueva contraseña y su confirmación.<br>";
    } elseif ($password_nuevo !== $password_confirmar) {
        $password_error_message .= "La nueva contraseña y la confirmación no coinciden.<br>";
    } elseif (strlen($password_nuevo) < 8) {
        $password_error_message .= "La nueva contraseña debe tener al menos 8 caracteres.<br>";
    } elseif (!preg_match('/[A-Z]/', $password_nuevo)) {
        $password_error_message .= "La nueva contraseña debe contener al menos una letra mayúscula.<br>";
    } elseif (!preg_match('/\d/', $password_nuevo)) {
        $password_error_message .= "La nueva contraseña debe contener al menos un número.<br>";
    }

    if (empty($password_error_message)) {
        $sql_update_password = "UPDATE usuarios SET contrasena = ? WHERE ID = ?";
        $stmt_update_password = $conn->prepare($sql_update_password);
        $stmt_update_password->bind_param("si", $password_nuevo, $usuario_id);

        if ($stmt_update_password->execute()) {
            $password_success_message = "Contraseña actualizada correctamente.";
            header("Location: profile.php?mensaje=password_actualizado");
            exit();
        } else {
            $password_error_message = "Error al actualizar la contraseña: " . $stmt_update_password->error;
        }
        $stmt_update_password->close();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_imagen'])) {
    if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] == UPLOAD_ERR_OK) {
        $nombre_archivo = $_FILES['nueva_imagen']['name']; 
        $tipo_archivo = $_FILES['nueva_imagen']['type'];
        $tamano_archivo = $_FILES['nueva_imagen']['size'];
        $ruta_temporal = $_FILES['nueva_imagen']['tmp_name'];
        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));

        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $tamano_maximo = 2 * 1024 * 1024; 

        if (!in_array($tipo_archivo, $tipos_permitidos)) {
            $image_error_message .= "Error: Solo se permiten archivos JPEG, PNG o GIF.<br>";
        }

        if ($tamano_archivo > $tamano_maximo) {
            $image_error_message .= "Error: El tamaño del archivo excede el límite de 2MB.<br>";
        }

        if (empty($image_error_message)) {
            $nombre_archivo_unico = $nombre_archivo;
            $ruta_destino = 'Imagenes/' . $nombre_archivo_unico;

            if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
                $sql_update_imagen = "UPDATE usuarios SET Imagen = ? WHERE ID = ?";
                $stmt_update_imagen = $conn->prepare($sql_update_imagen);
                $stmt_update_imagen->bind_param("si", $nombre_archivo_unico, $usuario_id);

                if ($stmt_update_imagen->execute()) {
                    $image_success_message = "Imagen de perfil actualizada correctamente.";
                    header("Location: profile.php?mensaje=imagen_actualizada");
                    exit();
                } else {
                    $image_error_message = "Error al actualizar el nombre del archivo en la base de datos: " . $stmt_update_imagen->error;
                }
                $stmt_update_imagen->close();
            } else {
                $image_error_message = "Error al mover el archivo al servidor.";
            }
        }
    } else {
        $image_error_message = "Error: No se ha seleccionado ningún archivo.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="Vistas/ModificarU.css">
    <style>
       
    </style>
</head>

<body>

    <nav class="navbar">
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="profile.php">Perfil de Usuario</a></li>
            <li><a href="reportes.html">Reportes</a></li>
            <li><a href="chat.html">Chat</a></li>
            <li><a href="Data/cerrar_session.php">Salir</a></li>
        </ul>
    </nav>

    <div class="profile-container">
        <div class="edit-form">
            <h2>Editar Datos del Perfil</h2>

            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <?php if (!empty($success_message)) : ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="nombre">Nombre de Usuario:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre_actual); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email_actual); ?>" required>
                </div>

                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($fecha_nacimiento_actual); ?>" required>
                </div>

                <div class="form-group">
                    <button type="submit" name="actualizar_perfil">Guardar Cambios</button>
                    <a href="profile.php">Cancelar</a>
                </div>
            </form>

            <div class="password-section">
                <h3>Cambiar Contraseña</h3>

                <?php if (!empty($password_error_message)) : ?>
                    <p class="error-message"><?php echo $password_error_message; ?></p>
                <?php endif; ?>

                <?php if (!empty($password_success_message)) : ?>
                    <p class="success-message"><?php echo $password_success_message; ?></p>
                <?php endif; ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="password_nuevo">Nueva Contraseña:</label>
                        <input type="password" id="password_nuevo" name="password_nuevo" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmar">Confirmar Nueva Contraseña:</label>
                        <input type="password" id="password_confirmar" name="password_confirmar" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="actualizar_password">Cambiar Contraseña</button>
                    </div>
                </form>
            </div>

            <div class="image-section">
                <h3>Cambiar Imagen de Perfil</h3>

                <?php if (!empty($image_error_message)) : ?>
                    <p class="error-message"><?php echo $image_error_message; ?></p>
                <?php endif; ?>

                <?php if (!empty($image_success_message)) : ?>
                    <p class="success-message"><?php echo $image_success_message; ?></p>
                <?php endif; ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nueva_imagen">Seleccionar Nueva Imagen:</label>
                        <input type="file" id="nueva_imagen" name="nueva_imagen" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="actualizar_imagen">Actualizar Imagen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>