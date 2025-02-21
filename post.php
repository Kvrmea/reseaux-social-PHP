<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Non autorisé");
}

$pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");

$user_id = $_SESSION['user_id'];
$contenu = $_POST['contenu'];
$parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : null;

$stmt = $pdo->prepare("INSERT INTO messages (user_id, contenu, parent_id) VALUES (:user_id, :contenu, :parent_id)");
$stmt->execute([
    'user_id' => $user_id,
    'contenu' => $contenu,
    'parent_id' => $parent_id
]);

header("Location: index.php");
exit;
?>
