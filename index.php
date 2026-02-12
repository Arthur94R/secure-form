<?php
session_start();
require_once 'functions.php';

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

// Générer le token CSRF
$csrf_token = genererTokenCSRF();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>TP1 - Secure Form</h1>
        
        <img src="logo_paris8_noir.png" alt="Logo" width="200" height="100" class="logo">
        
        <form method="POST" action="login.php" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <label>Identifiant :</label>
            <input type="text" name="identifiant" id="identifiant" required>
            
            <label>Mot de passe :</label>
            <input type="password" name="mdp" id="mdp" required>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message <?php echo (strpos($_SESSION['message'], 'OK') !== false) ? 'message-success' : 'message-error'; ?>">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                </div>
                <?php 
                unset($_SESSION['message']);
                ?>
            <?php endif; ?>
            
            <div class="buttons">
                <button type="button" onclick="document.getElementById('loginForm').reset()">Reset</button>
                <button type="submit">OK</button>
                <a href="ajout.php"><button type="button">Ajout compte</button></a>
            </div>
        </form>
    </div>
</body>
</html>