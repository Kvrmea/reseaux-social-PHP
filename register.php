<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - H</title>
    <link rel="stylesheet" href="style/register.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="background-container"></div>

    <div class="auth-container">
        <header style="text-align: center; margin-bottom: 30px;">
            <img src="img/logo.png" alt="logo H" style="width: 100px; height: auto;">
        </header>
        <h1>Rejoignez notre Réseau Social H !</h1>
        <p>Remplissez le formulaire pour créer votre compte.</p>

        <form action="register.php" method="POST">
            <div style="position: relative; margin-bottom: 15px;">
                <i class="fas fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Nom d'utilisateur" required>
            </div>

            <div style="position: relative; margin-bottom: 15px;">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Votre email" required>
            </div>

            <div style="position: relative; margin-bottom: 15px;">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Mot de passe" required>
            </div>

            <button type="submit">S'inscrire</button>
        </form>
    </div>

        <?php if (isset($error_message)): ?>
            <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <p style="text-align: center;">Déjà un compte ? <a href="login.php" style="color: #3498db;">Se connecter</a></p>
    </div>
</body>
</html>



<?php
session_start();

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
        $error_message = "Nom d'utilisateur ou email déjà utilisé.";
    } else {
        // Sécuriser le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insérer l'utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        $_SESSION['username'] = $username;
        header('Location: login.php'); // Rediriger vers la page de connexion après l'inscription
        exit;
    }
}
?>

