<?php
session_start();
// Connexion à la base de données
$host = 'localhost'; 
$db = 'afterwork'; 
$user = 'root'; 
$pass = ''; 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
   die("Échec de la connexion : " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $username = $_POST['username'];
   $password = $_POST['password']; 
   // Vérification de l'utilisateur
   $sql = "SELECT * FROM bar_accounts WHERE username = ? LIMIT 1"; 
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $username);
   $stmt->execute();
   $result = $stmt->get_result();
   if ($result->num_rows > 0) {
       $bar_info = $result->fetch_assoc();
       // Vérifiez le mot de passe
       if (password_verify($password, $bar_info['password_hash'])) {
           $_SESSION['bar_username'] = $username; // Mettez à jour la session
           header("Location: bar_dashboard.php"); // Rediriger vers le tableau de bord
           exit();
       } else {
           echo "Nom d'utilisateur ou mot de passe incorrect.";
       }
   } else {
       echo "Nom d'utilisateur ou mot de passe incorrect.";
   }
}
$conn->close();
?>