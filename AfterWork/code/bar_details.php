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

// Récupérer les informations du bar
$bar_id = $_GET['id'];
$sql = "SELECT * FROM bars WHERE bar_id = $bar_id";
$result = $conn->query($sql);
$bar = $result->fetch_assoc();

// Récupérer les horaires d'ouverture
$hours_sql = "SELECT * FROM bar_hours WHERE bar_id = $bar_id";
$hours_result = $conn->query($hours_sql);

// Récupérer les bières disponibles
$beers_sql = "SELECT * FROM beers WHERE bar_id = $bar_id";
$beers_result = $conn->query($beers_sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Bar</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Permet d'étendre le body pour remplir tout l'écran */
            font-family: Arial, sans-serif;
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
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Fond légèrement blanc pour le contenu */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 20px;
        }
        table.hours-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
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
        <h1 id="welcome-text"><?php echo htmlspecialchars($bar['name']); ?></h1>
        <p><?php echo htmlspecialchars($bar['address']); ?></p>
    </header>

    <main>
        <h2>Heures d'ouverture:</h2>
        <table class="hours-table">
            <tr>
                <th>Jour</th>
                <th>Heure d'ouverture</th>
                <th>Heure de fermeture</th>
                <th>Status</th>
            </tr>
            <?php while ($hours = $hours_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hours['day_of_week']); ?></td>
                    <td><?php echo htmlspecialchars($hours['opening_time']); ?></td>
                    <td><?php echo htmlspecialchars($hours['closing_time']); ?></td>
                    <td><?php echo htmlspecialchars($hours['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Bières Disponibles:</h2>
        <ul class="beer-list">
            <?php while ($beer = $beers_result->fetch_assoc()): ?>
                <li class="beer-item">
                    <strong><?php echo htmlspecialchars($beer['name']); ?></strong>
                    (<?php echo htmlspecialchars($beer['alcohol_content']); ?> %)
                </li>
            <?php endwhile; ?>
        </ul>

        <h2>Réservation</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="profile.php" class="reservation-form">
                <input type="hidden" name="bar_id" value="<?php echo $bar_id; ?>">
                <label for="reservation_date">Date:</label>
                <input type="date" name="reservation_date" required>
                <label for="reservation_time">Heure:</label>
                <input type="time" name="reservation_time" required>
                <label for="number_of_people">Nombre de personnes:</label>
                <input type="number" name="number_of_people" required>
                <button type="submit">Réserver</button>
            </form>
        <?php else: ?>
            <p>Veuillez vous <a href="afterwork.php">connecter</a> pour faire une réservation.</p>
        <?php endif; ?>
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
$conn->close();
?>
