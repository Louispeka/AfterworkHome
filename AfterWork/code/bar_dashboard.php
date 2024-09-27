<?php
session_start();
// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['bar_username'])) {
   header("Location: bar_login.php"); // Redirige vers la page de connexion si non connecté
   exit();
}

// Connexion à la base de données
$host = 'localhost';
$db = 'afterwork';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
   die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer l'ID du bar connecté
$bar_username = $_SESSION['bar_username'];
$sql_bar = "SELECT b.bar_id FROM bars b JOIN bar_accounts ba ON b.bar_id = ba.bar_id WHERE ba.username = ?";
$stmt = $conn->prepare($sql_bar);
$stmt->bind_param("s", $bar_username);
$stmt->execute();
$result = $stmt->get_result();
$bar = $result->fetch_assoc();
$bar_id = $bar['bar_id'];

// Récupérer les réservations associées au bar
$sql_reservations = "SELECT r.reservation_id, u.first_name, u.last_name, r.reservation_date, r.reservation_time, r.number_of_people, r.status
                    FROM reservations r
                    JOIN users u ON r.user_id = u.user_id
                    WHERE r.bar_id = ?";
$stmt = $conn->prepare($sql_reservations);
$stmt->bind_param("i", $bar_id);
$stmt->execute();
$reservations = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tableau de Bord du Bar</title>
<link rel="stylesheet" href="style.css">
<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Permet d'étendre le body pour remplir tout l'écran */
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    header {
        background-color: rgba(39, 21, 73, 0.9);
        color: white;
        text-align: center;
        padding: 20px;
    }
    main {
        flex: 1; /* Permet à main de prendre l'espace disponible entre le header et le footer */
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.8); /* Fond légèrement blanc pour le contenu */
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
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
    #footer-logo {
        max-width: 100px; /* Ajustez cette valeur selon vos besoins */
        height: auto; /* Garde les proportions de l'image */
    }
</style>
</head>
<body>
    <header>
        <h1>Tableau de Bord du Bar</h1>
    </header>

    <main>
        <h2>Réservations</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Nombre de personnes</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reservation = $reservations->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['reservation_id']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['number_of_people']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                    <td>
                        <?php if ($reservation['status'] == 'Pending'): ?>
                            <form action="bar_dashboard.php" method="POST" style="display:inline;">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit">Accepter</button>
                            </form>
                            <form action="bar_dashboard.php" method="POST" style="display:inline;">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit">Annuler</button>
                            </form>
                        <?php else: ?>
                            <span>Action non disponible</span>
                        <?php endif; ?>
                        <form action="bar_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <?php
    // Traitement de la validation, de l'annulation ou de la suppression de la réservation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $reservation_id = $_POST['reservation_id'];
        $action = $_POST['action'];

        if ($action == 'approve') {
            $update_sql = "UPDATE reservations SET status = 'Confirmed' WHERE reservation_id = ?";
        } else if ($action == 'cancel') {
            $update_sql = "UPDATE reservations SET status = 'Canceled' WHERE reservation_id = ?";
        } else if ($action == 'delete') {
            $update_sql = "DELETE FROM reservations WHERE reservation_id = ?";
        }

        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $reservation_id);
        if ($update_stmt->execute()) {
            echo "Réservation mise à jour avec succès.";
            header("Refresh:0"); // Rafraîchir la page pour voir les changements
        } else {
            echo "Erreur lors de la mise à jour de la réservation : " . $update_stmt->error;
        }
        $update_stmt->close();
    }
    ?>


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
