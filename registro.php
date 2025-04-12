<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="Vistas/registro.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Registrarse</h2>
            <form action="Data/registrobd.php" method="POST" id="registerForm">
                <label for="register-username">Nombre de usuario:</label>
                <input type="text" id="register-username" name="Usuario" required>

                <label for="register-email">Correo electrónico:</label>
                <input type="email" id="register-email" name="correo" required>

                <label for="register-password">Contraseña:</label>
                <input type="password" id="register-password" name="contrasena" required>

                <label for="confirm-password">Confirmar contraseña:</label>
                <input type="password" id="confirm-password" required>


                <label for="register-birthday">Fecha de nacimiento:</label>
                <input type="date" id="register-birthday" name="fecha" required>

                <label for="user-type">Tipo de usuario:</label>
                <select id="user-type" name="tipo_usuario" >
                    <option value="1">Admin</option>
                    <option value="2">Usuario</option>
                </select>

                <label for="user-image">Imagen de perfil:</label>
                <input type="file" id="user-image" name="imagen" accept="image/*">

                <button type="submit">Registrarse</button>

                <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión aquí</a></p>
            </form>
        </div>
    </div>
    <script src="Controladores/Registro.js"></script>
</body>
</html>
