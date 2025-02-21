<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Non autorisé");
}

$pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");

// Récupérer l'ID de l'utilisateur et du message
$user_id = $_SESSION['user_id'];
$message_id = $_POST['message_id'];

// Vérifier si l'utilisateur a déjà liké ce message
$stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = :user_id AND message_id = :message_id");
$stmt->execute(['user_id' => $user_id, 'message_id' => $message_id]);

if ($stmt->rowCount() > 0) {
    // Si déjà liké, on retire le like
    $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = :user_id AND message_id = :message_id");
    $stmt->execute(['user_id' => $user_id, 'message_id' => $message_id]);
    echo json_encode(['status' => 'unliked']);
} else {
    // Sinon, on ajoute le like
    $stmt = $pdo->prepare("INSERT INTO likes (user_id, message_id) VALUES (:user_id, :message_id)");
    $stmt->execute(['user_id' => $user_id, 'message_id' => $message_id]);
    echo json_encode(['status' => 'liked']);
}
?>
