<?php
$host = 'localhost'; // ou 127.0.0.1
$user = 'root'; // votre nom d'utilisateur MySQL
$password = ''; // votre mot de passe MySQL, souvent vide par défaut
$database = 'sondage'; // le nom de votre base de données

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>

