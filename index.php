<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");

// Récupérer les messages avec le pseudo de l’auteur
$sql = "SELECT messages.contenu, users.username, messages.date_publication 
        FROM messages 
        JOIN users ON messages.user_id = users.id 
        ORDER BY messages.date_publication DESC";

$stmt = $pdo->query($sql);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réseau Social</title>
</head>
<body>
    <h1>Fil d'actualité</h1>

    <p>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?> !</p>
    <a href="logout.php">Se déconnecter</a>

    <?php foreach ($messages as $msg): ?>
        <div>
            <strong><?= htmlspecialchars($msg['username'] ?? 'Nom d\'utilisateur inconnu') ?></strong>
            <p><?= htmlspecialchars($msg['contenu']) ?></p>
            <small>Posté le <?= $msg['date_publication'] ?></small>
        </div>
        <hr>
    <?php endforeach; ?>
</body>
</html>