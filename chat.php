<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat 1 a 1</title>
  <link rel="stylesheet" href="Vistas/chat.css">
</head>
<body>

  <nav class="navbar">
    <ul>
      <li><a href="dashboard.php">Inicio</a></li>
      <li><a href="profile.php">Perfil de Usuario</a></li>
      <li><a href="reportes.php">Reportes</a></li>
      <li><a href="chat.php">Chat</a></li>
      <li><a href="index.php">Salir</a></li>
    </ul>
  </nav>

  <div class="chat-container">
    <div class="sidebar">
        <div class="user" id="user1" onclick="showChat('chat1')">
            <img src="https://akamai.sscdn.co/tb/letras-blog/wp-content/uploads/2024/02/40e9a7c-Frases_-Canserbero-thumb-150x150.png" alt="Usuario 1">
            <span>Canserbero</span>
        </div>
        <div class="user" id="user2" onclick="showChat('chat2')">
            <img src="https://i.pinimg.com/236x/6a/07/ba/6a07ba333669a2868959939bab9e7e11.jpg" alt="Usuario 2">
            <span>Sasuke</span>
        </div>
        <div class="user" id="user3" onclick="showChat('chat3')">
            <img src="https://i.pinimg.com/originals/61/6c/63/616c63d7f50573de28ff4843167f6068.jpg" alt="Usuario 3">
            <span>Luffy</span>
        </div>
    </div>

    <div class="chat-area">
        <div class="chat" id="chat1">
            <div class="messages">
                <div class="message received">
                    <p>¡Hola, cómo estás?</p>
                </div>
                <div class="message sent">
                    <p>¡Bien, gracias! ¿Y tú?</p>
                </div>
            </div>
            <textarea placeholder="Escribe un mensaje..."></textarea>
        </div>

        <div class="chat" id="chat2">
            <div class="messages">
                <div class="message received">
                    <p>¿Cómo va todo?</p>
                </div>
                <div class="message sent">
                    <p>Todo bien, ¿y tú?</p>
                </div>
            </div>
            <textarea placeholder="Escribe un mensaje..."></textarea>
        </div>

        <div class="chat" id="chat3">
            <div class="messages">
                <div class="message received">
                    <p>¡Hey! ¿Qué tal?</p>
                </div>
                <div class="message sent">
                    <p>Todo tranquilo, ¿y tú?</p>
                </div>
            </div>
            <textarea placeholder="Escribe un mensaje..."></textarea>
        </div>
    </div>
</div>

<script>
    function showChat(chatId) {
        const chats = document.querySelectorAll('.chat');
        chats.forEach(chat => {
            chat.style.display = 'none';
        });

        const selectedChat = document.getElementById(chatId);
        selectedChat.style.display = 'block';
    }

    window.onload = function() {
        showChat('chat1');
    };
</script>
</body>
</html>
