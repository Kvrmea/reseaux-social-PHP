<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <form action="login.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>

    <?php
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=reseau_social;charset=utf8", "root", "");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie, tu peux rediriger vers une page d'accueil ou de profil
            echo "Connexion réussie ! Bienvenue, " . htmlspecialchars($user['username']) . ".";
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    ?>
</body>
</html>
