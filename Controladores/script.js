showChat('chat1');

function createPostElement(post) { // Changed to accept 'post' object
  const postDiv = document.createElement('div');
  postDiv.classList.add('post');
  postDiv.innerHTML = `
      <div class="post-header">
          <div class="author-info">
              <img src="Imagenes/descarga.jpg" alt="Foto de perfil" class="profile-img">
              <span class="author">${post.usuario}</span>
          </div>
          <span class="date">${post.fecha_publicacion}</span>
      </div>
      <p class="post-text">${post.contenido}</p>
      <div class="post-footer">
          <button class="like-button">Ã›Ã› Like</button>
          <textarea class="comment-box" placeholder="Escribe un comentario..."></textarea>
          <button class="comment-button">Ã›Ã› Comentar</button>
      </div>
  `;
  return postDiv;
}

document.getElementById('postForm').addEventListener('submit', function(event) {
  event.preventDefault();

  const postContent = document.getElementById('postContent').value;

  fetch('Data/publicar_post.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'content=' + encodeURIComponent(postContent),
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          const postsContainer = document.getElementById('posts-container');
          const newPostElement = createPostElement(data.post); // Use the 'post' object
          postsContainer.prepend(newPostElement);
          document.getElementById('postContent').value = '';
      } else {
          alert('Hubo un error al publicar: ' + data.error);
      }
  })
  .catch(error => console.error('Error:', error));
});