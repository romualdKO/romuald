<?php
session_start(); // Démarrer la session pour accéder aux informations utilisateur
require 'db_connection.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté, sinon redirection vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $created_by = $_SESSION['user_id'];
    $options = $_POST['options'];

    // Vérifier que le titre, la description et les options sont valides
    if (empty($title) || empty($description) || empty($options) || count($options) < 2) {
        echo "Erreur : un titre, une description et au moins deux options de réponse sont requis.";
        exit();
    }

    // Démarrer une transaction pour garantir que toutes les opérations réussissent ou échouent ensemble
    $conn->begin_transaction();

    try {
        // Insérer le sondage dans la table 'polls'
        $stmt = $conn->prepare("INSERT INTO polls (title, description, created_by) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $description, $created_by);
        $stmt->execute();
        $poll_id = $stmt->insert_id; // Récupérer l'ID du sondage inséré

        // Insérer les options de réponse dans la table 'poll_options'
        $stmt_option = $conn->prepare("INSERT INTO poll_options (poll_id, option_text) VALUES (?, ?)");
        foreach ($options as $option_text) {
            if (!empty(trim($option_text))) {
                $stmt_option->bind_param("is", $poll_id, $option_text);
                $stmt_option->execute();
            }
        }

        // Confirmer la transaction
        $conn->commit();
        echo "Sondage et options créés avec succès."; // Message de succès

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $conn->rollback();
        echo "Erreur : " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Sondage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Créer un Sondage</h2>
    <form action="create_poll.php" method="POST">
        <label for="title">Titre du sondage:</label>
        <input type="text" name="title" id="title" required><br><br>

        <label for="description">Description du sondage:</label>
        <input type="text" name="description" id="description" required><br><br>

        <label for="option1">Option 1:</label>
        <input type="text" name="options[]" id="option1" required><br><br>

        <label for="option2">Option 2:</label>
        <input type="text" name="options[]" id="option2" required><br><br>

        <input type="submit" value="Créer le sondage">
    </form>
</body>
</html>
