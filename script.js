// Gestion des clics sur les boutons "like"
document.querySelectorAll(".like-button").forEach(button => {
    button.addEventListener("click", function() {
        let messageId = this.getAttribute("data-message-id");
        let likeCountSpan = this.querySelector(".like-count");

        fetch("like.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "message_id=" + messageId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "liked") {
                likeCountSpan.textContent = parseInt(likeCountSpan.textContent) + 1;
            } else {
                likeCountSpan.textContent = parseInt(likeCountSpan.textContent) - 1;
            }
        });
    });
});

// Gestion des clics sur les boutons "réponse"
document.addEventListener("DOMContentLoaded", function() {
    // Afficher ou cacher le formulaire de réponse
    document.querySelectorAll(".reply-button").forEach(button => {
        button.addEventListener("click", function() {
            const messageId = this.getAttribute("data-message-id");
            const replyForm = document.querySelector(`#post-${messageId} .reply-form`);
            replyForm.style.display = replyForm.style.display === "none" ? "block" : "none";
        });
    });

    // Envoi d'une réponse
    document.querySelectorAll(".submit-reply").forEach(button => {
        button.addEventListener("click", function() {
            const messageId = this.getAttribute("data-message-id");
            const replyContent = document.querySelector(`#post-${messageId} .reply-form textarea`).value;

            if (replyContent.trim()) {
                fetch("reply.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `message_id=${messageId}&content=${encodeURIComponent(replyContent)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        // Ajouter la nouvelle réponse sous le post
                        const repliesContainer = document.querySelector(`#post-${messageId} .replies`);
                        const newReply = document.createElement("div");
                        newReply.classList.add("reply");
                        newReply.innerHTML = `<span class="username">${data.username}</span><p>${data.content}</p>`;
                        repliesContainer.appendChild(newReply);

                        // Vider le champ du formulaire
                        document.querySelector(`#post-${messageId} .reply-form textarea`).value = '';
                    }
                });
            }
        });
    });
});


