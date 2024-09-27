<?php
$host = 'localhost';
$db = 'afterwork';
$user = 'root';
$pass = '';

try {
   $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
   // Configure PDO pour afficher les erreurs
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   echo "Connexion réussie à la base de données."; // Message de succès
} catch (PDOException $e) {
   echo "Échec de la connexion : " . $e->getMessage(); // Message d'erreur
}
