<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $messageId = $_POST['message_id'];
        $content = $_POST['content'];
        $userId = $_SESSION['user_id'];

        // Insérer la réponse dans la base de données
        $stmt = $pdo->prepare("INSERT INTO replies (message_id, user_id, contenu) VALUES (?, ?, ?)");
        $stmt->execute([$messageId, $userId, $content]);

        // Récupérer le nom d'utilisateur pour afficher dans la réponse
        $stmt_user = $pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt_user->execute([$userId]);
        $user = $stmt_user->fetch();

        // Répondre avec succès
        echo json_encode(['status' => 'success', 'username' => $user['username'], 'content' => $content]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur de la base de données: ' . $e->getMessage()]);
    }
}
?>