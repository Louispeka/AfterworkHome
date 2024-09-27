<?php
session_start(); // Démarrer la session

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

// Gestion de l'inscription
if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hachage du mot de passe

    $sql = "INSERT INTO users (first_name, last_name, email, password_hash) VALUES ('$first_name', '$last_name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Inscription réussie !";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// Gestion de la connexion
if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Vérifiez le mot de passe
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['user_id']; // Enregistrez l'ID utilisateur dans la session
            $_SESSION['first_name'] = $row['first_name']; // Enregistrez le prénom dans la session
            header("Location: afterwork.php"); // Rediriger vers la même page
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy(); // Détruire la session
    header("Location: afterwork.php"); // Rediriger vers la page d'accueil
    exit();
}

// Vérifiez si l'utilisateur est connecté
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : null;

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers le fichier CSS -->
    <title>AfterWork Home</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        input { margin-bottom: 10px; display: block; }
        button { padding: 10px; background-color: rgba(39, 21, 73, 0.9); color: white; border: none; cursor: pointer; }
        button:hover { background-color: rgba(39, 21, 73, 1); }
        .header { display: flex; justify-content: space-between; align-items: center; width: 100%; }
        .logo { font-size: 24px; padding-right: 120px; }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .profile-link { text-decoration: none; color: black; padding: 12px 16px; display: block; }
        .profile-link:hover { background-color: #f1f1f1; }

        /* Réduction de l'espace entre le haut et la bannière */
        .welcome-banner {
            margin-top: 0;
            padding: 20px;
            background-color: rgba(39, 21, 73, 0.9);
            color: white;
            text-align: center;
        }

        .bar-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    <div class="header">
        <div class="dropdown">
            <button class="dropbtn" id="barButton">Si vous êtes un bar, cliquez ici</button>
        </div>
        <a href="afterwork.php" id="logo-link">
            <img src="logoblanc.png" alt="Logo Afterwork" id="logo" class="logo">
        </a>
        <div class="dropdown">
            <?php if ($first_name): ?>
                <button class="dropbtn"><?php echo htmlspecialchars($first_name); ?></button>
                <div class="dropdown-content">
                    <a class="profile-link" href="profile.php">Mon Profil</a>
                    <a class="profile-link" href="?logout=1">Déconnexion</a>
                </div>
            <?php else: ?>
                <button class="dropbtn">Connexion</button>
                <div class="dropdown-content">
                    <a class="profile-link" href="#loginModal" onclick="document.getElementById('loginModal').style.display='block'">Se Connecter</a>
                    <a class="profile-link" href="#registerModal" onclick="document.getElementById('registerModal').style.display='block'">Créer un Compte</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>



<!-- Formulaire d'inscription -->
<div id="registerModal" style="display:none;">
    <h2>Créer un Compte</h2>
    <form method="POST" action="">
        <input type="text" name="first_name" placeholder="Prénom" required>
        <input type="text" name="last_name" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="register">S'inscrire</button>
    </form>
    <button onclick="document.getElementById('registerModal').style.display='none'">Fermer</button>
</div>

<!-- Formulaire de connexion -->
<div id="loginModal" style="display:none;">
    <h2>Se Connecter</h2>
    <form method="POST" action="">
        <input type="email" name="login_email" placeholder="Email" required>
        <input type="password" name="login_password" placeholder="Mot de passe" required>
        <button type="submit" name="login">Se Connecter</button>
    </form>
    <button onclick="document.getElementById('loginModal').style.display='none'">Fermer</button>
</div>

<!-- Formulaire de connexion pour les bars -->
<div id="barLoginModal" style="display:none;">
    <div class="modal-content">
        <span id="closeBarModal" style="cursor:pointer;">&times;</span>
        <h2>Connexion Bar</h2>
        <form action="bar_login.php" method="POST">
            <label for="bar_username">Nom d'utilisateur :</label>
            <input type="text" id="bar_username" name="username" required>
            <label for="bar_password">Mot de passe :</label>
            <input type="password" id="bar_password" name="password" required>
            <button type="submit">Connexion</button>
        </form>
    </div>
</div>

<script>
    // Script pour ouvrir et fermer les modals
    document.getElementById('barButton').onclick = function() {
        document.getElementById('barLoginModal').style.display = "block";
    }
    
    document.getElementById('closeBarModal').onclick = function() {
        document.getElementById('barLoginModal').style.display = "none";
    }

    // Gestion des modals de connexion et d'inscription
    document.querySelectorAll('.profile-link').forEach(link => {
        link.onclick = function() {
            const targetModal = this.getAttribute('href').substring(1); // Récupère l'ID du modal
            document.getElementById(targetModal).style.display = "block";
        }
    });

    // Ferme le modal si l'utilisateur clique en dehors
    window.onclick = function(event) {
        if (event.target == document.getElementById('barLoginModal')) {
            document.getElementById('barLoginModal').style.display = "none";
        }
        if (event.target == document.getElementById('loginModal')) {
            document.getElementById('loginModal').style.display = "none";
        }
        if (event.target == document.getElementById('registerModal')) {
            document.getElementById('registerModal').style.display = "none";
        }
    }
</script>

<div class="welcome-banner">
    <h1>Bienvenue à L'afterwork Home</h1>
</div>

<div class="project-description">
    <p>
        Bienvenue sur Afterwork Home ! Ce projet a pour but de vous aider à trouver des bars pour vos soirées détentes. 
        Ici, vous pouvez réserver votre table dans l'un des bars partenaires directement en ligne. 
        Naviguez ci-dessous pour découvrir les établissements disponibles et faites votre réservation en quelques clics !
    </p>
</div>

<div class="bar-container">
    <a href="bar_details.php?id=1">
        <img src="zebulonr.jpg" alt="Bar Zebulon" class="bar-image">
        <div class="bar-name">Bar Zebulon</div> 
    </a>
</div>

<div class="bar-container">
    <a href="bar_details.php?id=2">
        <img src="beerstrot.jpg" alt="Le Beerstro" class="bar-image">
        <div class="bar-name">Le Beerstro</div> 
    </a>
</div>

<div class="bar-container">
    <a href="bar_details.php?id=3">
        <img src="peat.jpg" alt="Le Peacock" class="bar-image">
        <div class="bar-name">Le Peacock</div> 
    </a>
</div>

<div class="bar-container">
    <a href="bar_details.php?id=3">
        <img src="helt.jpg" alt="Helter Skelter" class="bar-image">
        <div class="bar-name">Helter Skelter</div> 
    </a>
</div>



<script src="script.js"></script>
</body>
<footer>
    <div class="footer-content">
        <a href="afterwork.php" id="footer-logo-link">
            <img src="logot.png" alt="Logo Afterwork" id="footer-logo">
        </a>
        <p>&copy; 2024 Projet Afterwork pour le Workshop EPSI Lille. Tous droits réservés.</p>
        <p>Retrouvez le code source de ce projet sur <a href="https://github.com/Louispeka/AfterworkHome" target="_blank">Github</a>.</p>
    </div>
</footer>

</html>


