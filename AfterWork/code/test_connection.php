<?php
require 'connexion.php'; // Inclure le fichier de connexion
// Test de la connexion
if ($pdo) {
   echo "Connexion réussie à la base de données.";
} else {
   echo "Échec de la connexion à la base de données.";
}
