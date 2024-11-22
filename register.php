<?php
session_start();
require 'db_connection.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
    } else {
        echo "Erreur: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            </ul>
        </nav>
    </header>
    <h1>SONDAGE SCHOOL</h1>

    <h2>Inscription</h2>
    <form action="register.php" method="post">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà inscrit? <a href="login.php">Connectez-vous ici</a>.</p>
    <button onclick="window.history.back();">Retour</button> <!-- Bouton pour revenir -->
    <footer>
        <p>&copy; 2024 Mon Application de Sondages. Tous droits réservés.</p>
        <p><a href="contact.php">Contactez-nous</a></p>
    </footer>
</body>
</html>