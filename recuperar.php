<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="Vistas/Login.css">
</head>
<body>
    <div class="login-container">
        <div class="form-container">
            <h2>Recuperar Contraseña</h2>
            <form id="recoverForm">
                <label for="recover-email">Correo electrónico:</label>
                <input type="email" id="recover-email" required>

                <button type="submit">Recuperar Contraseña</button>

                <p><a href="index.php">Volver al inicio de sesión</a></p>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('recoverForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('recover-email').value;

            alert(`Se ha enviado un enlace de recuperación a ${email}`);
            window.location.href = 'index.php'; 
        });
    </script>
</body>
</html>
