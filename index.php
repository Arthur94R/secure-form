<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Initialiser la base de données
initDB();

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