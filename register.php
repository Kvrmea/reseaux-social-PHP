<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form action="register.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>

<?php
$pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        echo "Nom d'utilisateur ou email déjà utilisé.";
    } else {
        // Sécuriser le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        
        if ($stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ])) {
            // Redirection vers la page de connexion après inscription réussie
            header("Location: login.php");
            exit; // S'assurer que le script s'arrête après la redirection
        } else {
            echo "Une erreur est survenue lors de l'inscription.";
        }
    }
}
?>
