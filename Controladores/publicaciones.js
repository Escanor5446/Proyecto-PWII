document.addEventListener('DOMContentLoaded', function() {
    const postForm = document.getElementById('postForm');
    const postContentInput = document.getElementById('postContent');
    const postsContainer = document.getElementById('posts-container');

    postForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const postContent = postContentInput.value.trim();
        if (postContent === '') {
            alert('Por favor, escribe algo antes de publicar.');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'publicar');
        formData.append('postContent', postContent);

        fetch('dashboard.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const newPost = createPostElement(data);
                postsContainer.insertBefore(newPost, postsContainer.firstChild);
                postContentInput.value = '';
            } else {
                alert('Error al publicar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            alert('Ocurrió un error al intentar publicar.');
        });
    });

    postsContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('like-btn')) {
            const postId = event.target.dataset.postId;
            console.log('Me gusta en la publicación:', postId);
        } else if (event.target.classList.contains('comment-btn')) {
            const postId = event.target.dataset.postId;
            console.log('Comentar en la publicación:', postId);
        }
    });

    function createPostElement(postData) {
        const post = document.createElement('div');
        post.classList.add('post');
        post.dataset.postId = postData.id; 
        post.innerHTML = `
            <div class="post-header">
                <span class="post-author">${postData.usuario}</span>
                <span class="post-date"> - ${postData.fecha_publicacion}</span>
            </div>
            <p class="post-content">${postData.contenido}</p>
            <div class="post-actions">
                <button class="like-btn" data-post-id="${postData.id}">❤️ Me gusta</button>
                <button class="comment-btn" data-post-id="${postData.id}">💬 Comentar</button>
            </div>
            <div class="comment-section" style="display: none;">
                </div>
        `;
        return post;
    }

});