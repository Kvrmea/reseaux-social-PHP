<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");

// Récupérer les messages principaux (sans parent_id)
$sql = "SELECT messages.id, messages.contenu, users.username, messages.date_publication,
        (SELECT COUNT(*) FROM likes WHERE likes.message_id = messages.id) AS like_count
        FROM messages 
        JOIN users ON messages.user_id = users.id 
        WHERE messages.parent_id IS NULL
        ORDER BY messages.date_publication DESC";
$stmt = $pdo->query($sql);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour récupérer les réponses d'un message
function getReplies($pdo, $message_id) {
    $stmt = $pdo->prepare("SELECT messages.id, messages.contenu, users.username, messages.date_publication 
                           FROM messages 
                           JOIN users ON messages.user_id = users.id 
                           WHERE messages.parent_id = :message_id 
                           ORDER BY messages.date_publication ASC");
    $stmt->execute(['message_id' => $message_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>



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
        <aside class="sidebar">
            <div class="logo">
                <img src="img/logo.png" alt="Logo H">
            </div>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="#"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="#"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="settings.php"><i class="fas fa-user"></i> Profil</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </aside>

        <!-- Contenu principal -->
        <main class="main-content">
            <form action="post.php" method="POST">
                <textarea name="contenu" placeholder="Que voulez-vous partager ?" rows="4" required></textarea>
                 <button type="submit">Publier</button>
            </form>


            <div class="feed">
                <?php foreach ($messages as $message): ?>
                    <div class="post" id="post-<?php echo $message['id']; ?>">
                        <div class="post-header">
                            <img src="img/profile-pic.jpg" alt="Profile Picture" class="profile-pic">
                            <span class="username"><?php echo htmlspecialchars($message['username']); ?></span>
                            <span class="post-date"><?php echo $message['date_publication']; ?></span>
                        </div>
                        <div class="post-body">
                            <p><?php echo htmlspecialchars($message['contenu']); ?></p>
                        </div>
                        <div class="post-footer">
                            <button class="like-button" data-message-id="<?php echo $message['id']; ?>">
                                <i class="fas fa-heart"></i> <span class="like-count"><?php echo $message['like_count']; ?></span>
                            </button>
                            <!-- Bouton pour afficher les réponses -->
                            <button class="reply-button" data-message-id="<?php echo $message['id']; ?>">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>

                        <!-- Formulaire de réponse -->
                        <div class="reply-form" style="display:none;">
                            <textarea name="reply" placeholder="Votre réponse..." rows="3"></textarea>
                            <button class="submit-reply" data-message-id="<?php echo $message['id']; ?>">Envoyer</button>
                        </div>

                        <!-- Affichage des réponses -->
                        <div class="replies">
                            <?php
                            // Récupérer les réponses pour chaque message
                            $sql_replies = "SELECT replies.contenu, users.username 
                                            FROM replies 
                                            JOIN users ON replies.user_id = users.id 
                                            WHERE replies.message_id = :message_id";
                            $stmt_replies = $pdo->prepare($sql_replies);
                            $stmt_replies->execute(['message_id' => $message['id']]);
                            $replies = $stmt_replies->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($replies as $reply): ?>
                                <div class="reply">
                                    <span class="username"><?php echo htmlspecialchars($reply['username']); ?></span>
                                    <p><?php echo htmlspecialchars($reply['contenu']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <!-- Barre latérale droite (facultatif) -->
        <aside class="right-sidebar">
            <h3>Qui suivre</h3>
            <ul>
                <li><a href="#">Utilisateur A</a></li>
                <li><a href="#">Utilisateur B</a></li>
            </ul>
        </aside>
    </div>

<script src="script.js"></script>
</body>
</html>