<?php
session_start(); // Démarre la session utilisateur
require 'db_connection.php'; // Connexion à la base de données

// Vérifie si un sondage est spécifié dans l'URL
if (!isset($_GET['poll_id'])) {
    die('Sondage non spécifié.'); // Arrête le script si l'ID du sondage n'est pas fourni
}

$poll_id = $_GET['poll_id'];

// Récupérer le titre du sondage
$poll_query = "SELECT title FROM polls WHERE id = ?";
$stmt = $conn->prepare($poll_query); // Prépare la requête pour éviter les injections SQL
$stmt->bind_param("i", $poll_id); // Lie l'ID du sondage
$stmt->execute();
$poll_result = $stmt->get_result();
$poll = $poll_result->fetch_assoc();

if (!$poll) {
    die('Sondage introuvable.'); // Arrête le script si le sondage n'existe pas
}

// Récupérer les options du sondage
$options_query = "SELECT id, option_text FROM poll_options WHERE poll_id = ?";
$stmt = $conn->prepare($options_query);
$stmt->bind_param("i", $poll_id);
$stmt->execute();
$options_result = $stmt->get_result();
$options = [];
while ($row = $options_result->fetch_assoc()) {
    $options[] = $row; // Stocke chaque option dans un tableau
}

$stmt->close(); // Ferme la requête

// Traitement de la soumission du formulaire de vote
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['option_id'])) {
    $option_id = $_POST['option_id']; // Récupère l'ID de l'option choisie

    // Insère le vote dans la base de données
    $insert_query = "INSERT INTO poll_votes (option_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ii", $option_id, $_SESSION['user_id']); // Associe l'option et l'utilisateur
    $stmt->execute();
    $stmt->close();

    // Redirige vers la page des sondages après le vote
    header('Location: view_polls.php');
    exit();
}

$conn->close(); // Ferme la connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répondre au Sondage</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers la feuille de style -->
</head>
<body>
    <h1><?php echo htmlspecialchars($poll['title']); ?></h1> <!-- Affiche le titre du sondage -->
    
    <!-- Formulaire pour répondre au sondage -->
    <form method="POST" action="">
        <!-- Boucle pour afficher chaque option comme choix radio -->
        <?php foreach ($options as $option): ?>
            <div>
                <input type="radio" name="option_id" value="<?php echo $option['id']; ?>" required> <!-- Option de réponse -->
                <label><?php echo htmlspecialchars($option['option_text']); ?></label> <!-- Texte de l'option -->
            </div>
        <?php endforeach; ?>
        <button type="submit">Soumettre votre réponse</button> <!-- Bouton de soumission -->
    </form>
    
    <p><a href="view_polls.php">Retour aux sondages</a>.</p> <!-- Lien pour retourner aux sondages -->
    
    <footer>
        <p>&copy; 2024 Mon Application de Sondages. Tous droits réservés.</p> <!-- Droits d'auteur -->
        <p><a href="contact.php">Contactez-nous</a></p> <!-- Lien de contact -->
    </footer>
</body>
</html>
