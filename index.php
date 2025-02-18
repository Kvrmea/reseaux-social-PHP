<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// $pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");

// // Récupérer les messages avec le pseudo de l’auteur
// $sql = "SELECT messages.contenu, users.username, messages.date_publication 
//         FROM messages 
//         JOIN users ON messages.user_id = users.id 
//         ORDER BY messages.date_publication DESC";

// $stmt = $pdo->query($sql);
// $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
// ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réseau Social H</title>
    <link rel="stylesheet" href="style/index.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Barre latérale -->
        <aside class="sidebar">
            <div class="logo">
                <img src="img/logo.png" alt="Logo H">
            </div>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="#">Notifications</a></li>
                <li><a href="#">Messages</a></li>
                <li><a href="#">Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </aside>

        <!-- Contenu principal -->
        <main class="main-content">
            <div class="post-form">
                <textarea placeholder="Que voulez-vous partager ?" rows="4" id="new-post"></textarea>
                <button id="post-button">Publier</button>
            </div>

            <div class="feed">
                <!-- Exemple de publication dans le fil d'actualités -->
                <div class="post">
                    <div class="post-header">
                        <img src="img/profile-placeholder.jpg" alt="Profile Pic">
                        <span class="username">Nom d'utilisateur</span>
                    </div>
                    <div class="post-body">
                        <p>Voici un message que l'utilisateur a publié.</p>
                    </div>
                    <div class="post-footer">
                        <button><i class="fas fa-heart"></i> J'aime</button>
                        <button><i class="fas fa-comment"></i> Répondre</button>
                        <button><i class="fas fa-share"></i> Partager</button>
                    </div>
                </div>

                <!-- Autres publications ici -->
            </div>
        </main>

        <!-- Barre latérale droite (facultatif) -->
        <aside class="right-sidebar">
            <h3>Qui suivre</h3>
            <ul>
                <li><a href="#">Utilisateur A</a></li>
                <li><a href="#">Utilisateur B</a></li>
            </ul>
        </aside>
    </div>

</body>
</html>