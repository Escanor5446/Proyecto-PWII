document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const username = document.getElementById('register-username').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const birthday = document.getElementById('register-birthday').value;
            const tipoUsuario = document.getElementById('user-type').value;
            const imagenInput = document.getElementById('user-image');
            const Imagen = imagenInput.files[0];

            let hasErrors = false;

            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden.');
                hasErrors = true;
            }

            if (password.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres.');
                hasErrors = true;
            }

            if (!/[A-Z]/.test(password)) {
                alert('La contraseña debe contener al menos una letra mayúscula.');
                hasErrors = true;
            }

            if (!/\d/.test(password)) {
                alert('La contraseña debe contener al menos un número.');
                hasErrors = true;
            }

            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                alert('La contraseña debe contener al menos un carácter especial.');
                hasErrors = true;
            }

            if (!/\S+@\S+\.\S+/.test(email)) {
                alert('El formato del correo electrónico no es válido.');
                hasErrors = true;
            }

            const birthdayDate = new Date(birthday);
            const cutoffDate = new Date('2010-01-01');
            if (isNaN(birthdayDate.getTime())) {
                alert('Por favor, introduce una fecha de nacimiento válida.');
                hasErrors = true;
            } else if (birthdayDate >= cutoffDate) {
                alert('La fecha de nacimiento debe ser anterior al 1 de enero de 2010.');
                hasErrors = true;
            }

            if (!hasErrors) {
                const formData = new FormData();
                formData.append('Usuario', username);
                formData.append('correo', email);
                formData.append('contrasena', password);
                formData.append('fecha', birthday);
                formData.append('tipo_usuario', tipoUsuario);
                
                if (Imagen) {
                    formData.append('imagen', Imagen);
                }

                fetch('Data/registrobd.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    if (data.includes('registrado exitosamente')) {
                        window.location.href = 'index.php';
                    }
                })
                .catch(error => {
                    console.error('Error al enviar los datos al servidor:', error);
                    alert('Ocurrió un error al registrar el usuario.');
                });
            }
        });
    }
});