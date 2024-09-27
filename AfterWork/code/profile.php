<?php
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "afterwork"; 
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
   die("Échec de la connexion: " . $conn->connect_error);
}

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
   die("Veuillez vous connecter pour accéder à cette page.");
}

// Traitement du formulaire de réservation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $bar_id = $_POST['bar_id'];
   $user_id = $_SESSION['user_id']; // Assurez-vous que l'utilisateur est connecté
   $reservation_date = $_POST['reservation_date'];
   $reservation_time = $_POST['reservation_time'];
   $number_of_people = $_POST['number_of_people'];
   $sql = "INSERT INTO reservations (user_id, bar_id, reservation_date, reservation_time, number_of_people, status)
           VALUES (?, ?, ?, ?, ?, 'Pending')";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("iissi", $user_id, $bar_id, $reservation_date, $reservation_time, $number_of_people);
   if ($stmt->execute()) {
       echo "<p>Réservation effectuée avec succès.</p>";
   } else {
       echo "<p>Erreur lors de la réservation: " . $stmt->error . "</p>";
   }
   $stmt->close();
}

// Récupérer les réservations de l'utilisateur
$user_id = $_SESSION['user_id'];
$sql = "SELECT r.*, b.name AS bar_name FROM reservations r JOIN bars b ON r.bar_id = b.bar_id WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Profil de l'utilisateur</title>
<link rel="stylesheet" href="styles.css">
<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Permet d'étendre le body pour remplir tout l'écran */
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    header.banner {
        background-color: rgba(39, 21, 73, 0.9);
        color: white;
        text-align: center;
        padding: 20px;
    }
    main {
        flex: 1; /* Permet à main de prendre l'espace disponible entre le header et le footer */
    }
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: rgba(39, 21, 73, 0.8);
        color: white;
    }
    footer {
        background-color: rgba(39, 21, 73, 0.9);
        color: white;
        text-align: center;
        padding: 10px;
        position: relative;
        bottom: 0;
        width: 100%;
    }
</style>
</head>
<body>

<header class="banner">
    <h1>Vos Réservations</h1>
</header>

<main>
    <h2>Liste de vos réservations</h2>
    <table>
        <tr>
            <th>Bar</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Nombre de personnes</th>
            <th>Statut</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($reservation = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['bar_name']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['number_of_people']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Aucune réservation trouvée.</td>
            </tr>
        <?php endif; ?>
    </table>
</main>



</body>

<footer>
    <div class="footer-content">
        <a href="afterwork.php" id="footer-logo-link">
            <img src="logot.png" alt="Logo Afterwork" id="footer-logo">
        </a>
        <p>&copy; 2024 Projet Afterwork pour le Workshop EPSI Lille. Tous droits réservés.</p>
        <p>Retrouvez le code source de ce projet sur <a href="https://github.com/Louispeka/AfterWork" target="_blank">Github</a>.</p>
    </div>
</footer>

</html>
<?php
$stmt->close();
$conn->close();
?>
