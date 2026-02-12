<?php
session_start();

// On créer la base de données
$db = new SQLite3('users.db');
$db->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    identifiant TEXT UNIQUE NOT NULL,
    mdp TEXT NOT NULL
)');

// On créer un compte par défaut
$result = $db->query('SELECT COUNT(*) as count FROM users');
$row = $result->fetchArray();
if ($row['count'] == 0) {
    $mdp_hash = password_hash('Admin@12345678', PASSWORD_DEFAULT);
    $db->exec("INSERT INTO users (identifiant, mdp) VALUES ('admin', '$mdp_hash')");
}
?>

<!-- On créer un formualire simple-->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h1>TP1-secureForm</h1>
    
    <img src="logo_paris8_noir.png" alt="Logo" width="200" height="100">
    
    <form method="POST" action="login.php" id="loginForm">
        <p>
            <label>Identifiant :</label><br>
            <input type="text" name="identifiant" id="identifiant" required>
        </p>
        
        <p>
            <label>Mot de passe :</label><br>
            <input type="password" name="mdp" id="mdp" required>
        </p>
        
        <!-- Affichage du message ok ou error (login.php) -->
        <?php if (isset($_SESSION['message'])): ?>
            <p><strong><?php echo $_SESSION['message']; ?></strong></p>
            <?php 
            unset($_SESSION['message']);
            ?>
        <?php endif; ?>
        
        <p>
            <button type="button" onclick="document.getElementById('loginForm').reset()">Reset</button>
            <button type="submit">OK</button>
            <a href="ajout.php"><button type="button">Ajout compte</button></a>
        </p>
    </form>
</body>
</html>