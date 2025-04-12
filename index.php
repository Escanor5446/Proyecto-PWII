<?php

session_start();
if(isset($_SESSION['usuario'])){
    header("location: dashboard.php");
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="Vistas/Login.css">
</head>
<body>
    <div class="login-container">
        <div class="form-container">
            <h2>Iniciar sesión</h2>
            <form action="Data/login_usuario.php" method="POST" id="loginForm">
                <label for="login-username">Nombre de usuario:</label>
                <input name="usuario" type="text" id="login-username" required>

                <label for="login-password">Contraseña:</label>
                <input name="password" type="password" id="login-password" required>

                <button type="submit">Iniciar sesión</button>

                <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
                <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p> 
            </form>
        </div>
    </div>

</body>
</html>
