<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - H</title>
    <link rel="stylesheet" href="style/style-login.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
</head>
<body>
    <div class="background-container"></div>

    <div class="auth-container">
        <header style="text-align: center; margin-bottom: 30px;">
            <img src="img/logo.png" alt="logo H" style="width: 100px; height: auto;">
        </header>
        <h1>Se connecter</h1>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>


<?php
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Rechercher l'utilisateur dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        // Utilisateur authentifié, démarrer la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

