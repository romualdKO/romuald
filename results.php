if ($poll) {
    echo "<h1>{$poll['question']}</h1>";

    // Vérifier si les résultats sont verrouillés
    if ($poll['is_locked']) {
        $today = date('Y-m-d');
        
        // Si la date de déblocage est atteinte, on débloque automatiquement les résultats
        if ($poll['unlock_date'] && $poll['unlock_date'] <= $today) {
            $stmt = $pdo->prepare("UPDATE polls SET is_locked = 0 WHERE id = ?");
            $stmt->execute([$poll_id]);
            echo "<p>Les résultats sont maintenant débloqués.</p>";
        } else {
            echo "<p>Les résultats de ce sondage sont sécurisés. Revenez le {$poll['unlock_date']} pour les voir.</p>";
        }
    }

    // Afficher les résultats du sondage uniquement si ils ne sont pas verrouillés
    if (!$poll['is_locked'] || ($poll['unlock_date'] && $poll['unlock_date'] <= $today)) {
        // Afficher les résultats
        $stmt = $pdo->prepare("SELECT option_text, vote_count FROM poll_options WHERE poll_id = ?");
        $stmt->execute([$poll_id]);
        $options = $stmt->fetchAll();

        foreach ($options as $option) {
            echo "<p>{$option['option_text']}: {$option['vote_count']} votes</p>";
        }
    }

    // Lien pour répondre au sondage, affiché quel que soit l'état de verrouillage
    echo '<p><a href="respond_poll.php?poll_id=' . $poll_id . '">Répondre au sondage</a></p>';
    echo '<p>Vous pouvez toujours répondre au sondage, même si les résultats sont verrouillés.</p>'; // Message informatif

} else {
    echo "Sondage non trouvé.";
}


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats du Sondage</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Charge Chart.js pour le graphique -->
</head>
<body>
    <h1>Résultats pour le sondage : <?php echo htmlspecialchars($poll['title']); ?></h1> <!-- Affiche le titre du sondage -->

    <canvas id="pollChart" width="400" height="400"></canvas> <!-- Canvas pour le graphique des résultats -->

    <script>
        // Récupère les données PHP dans des variables JavaScript
        var options = <?php echo json_encode($options); ?>;
        var votes = <?php echo json_encode($votes); ?>;

        // Création du graphique à barres avec Chart.js
        var ctx = document.getElementById('pollChart').getContext('2d');
        var pollChart = new Chart(ctx, {
            type: 'bar', // Définit le type de graphique (ici un graphique à barres)
            data: {
                labels: options, // Les options du sondage sont les étiquettes
                datasets: [{
                    label: 'Nombre de votes',
                    data: votes, // Les votes pour chaque option
                    backgroundColor: [ // Couleurs de fond des barres
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [ // Couleurs des bordures des barres
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1 // Épaisseur des bordures
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // La valeur de l'axe Y commence à zéro
                    }
                }
            }
        });
    </script>

    <p><a href="view_polls.php">Retour aux sondages</a>.</p> <!-- Lien pour retourner à la page des sondages -->
    <footer>
        <p>&copy; 2024 Mon Application de Sondages. Tous droits réservés.</p>
        <p><a href="contact.php">Contactez-nous</a></p> <!-- Lien vers la page de contact -->
    </footer>
</body>
</html>
