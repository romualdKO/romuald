<?php
session_start(); // Démarrer la session pour accéder aux informations utilisateur
require 'db_connection.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté, sinon redirection vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['poll_id'])) {
    die('Sondage non spécifié.'); // Arrête le script si l'ID du sondage n'est pas fourni
}

$poll_id = $_GET['poll_id'];

// Récupérer le titre du sondage
$poll_query = "SELECT title, description, is_locked, unlock_date FROM polls WHERE id = ?";
$stmt = $conn->prepare($poll_query);
$stmt->bind_param("i", $poll_id);
$stmt->execute();
$poll_result = $stmt->get_result();
$poll = $poll_result->fetch_assoc();

if (!$poll) {
    die('Sondage introuvable.'); // Arrête le script si le sondage n'existe pas
}

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

// Vérification de la sécurité des résultats
if ($poll['is_locked']) {
    $today = date('Y-m-d');

    // Si la date de déblocage est atteinte, on débloque automatiquement les résultats
    if ($poll['unlock_date'] && $poll['unlock_date'] <= $today) {
        $stmt = $conn->prepare("UPDATE polls SET is_locked = 0 WHERE id = ?");
        $stmt->execute([$poll_id]);
        echo "<p>Les résultats sont maintenant débloqués.</p>";
    } else {
        echo "<p>Les résultats de ce sondage sont sécurisés. Revenez le {$poll['unlock_date']} pour les voir.</p>";
        exit;
    }
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

$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($poll['title']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($poll['title']); ?></h1>
    <p><?php echo htmlspecialchars($poll['description']); ?></p>

    <!-- Formulaire pour répondre au sondage -->
    <form method="POST" action="">
        <!-- Boucle pour afficher chaque option comme choix radio -->
        <?php foreach ($options as $option): ?>
            <div>
                <input type="radio" name="option_id" value="<?php echo $option['id']; ?>" required>
                <label><?php echo htmlspecialchars($option['option_text']); ?></label>
            </div>
        <?php endforeach; ?>
        <button type="submit">Soumettre votre réponse</button>
    </form>

    <p><a href="view_polls.php">Retour aux sondages</a>.</p>
    
    <footer>
        <p>&copy; 2024 Mon Application de Sondages. Tous droits réservés.</p>
        <p><a href="contact.php">Contactez-nous</a></p>
    </footer>
</body>
</html>
