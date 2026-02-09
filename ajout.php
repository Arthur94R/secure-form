<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    
    $db = new SQLite3('users.db');
    $stmt = $db->prepare('INSERT INTO users (identifiant, mdp) VALUES (:identifiant, :mdp)');
    $stmt->bindValue(':identifiant', $identifiant, SQLITE3_TEXT);
    $stmt->bindValue(':mdp', $mdp, SQLITE3_TEXT);
    
    try {
        $stmt->execute();
        $_SESSION['message'] = "OK - Compte créé avec succès !";
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        $error = "ERROR - Cet identifiant existe déjà";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout de compte</title>
</head>
<body>
    <h1>Créer un nouveau compte</h1>
    
    <img src="logo_paris8_noir.png" alt="Logo" width="200" height="100">

    <form method="POST">
        <p>
            <label>Identifiant :</label><br>
            <input type="text" name="identifiant" required>
        </p>
        
        <p>
            <label>Mot de passe :</label><br>
            <input type="password" name="mdp" required>
        </p>
        
        <?php if (isset($error)): ?>
            <p><strong><?php echo $error; ?></strong></p>
        <?php endif; ?>
        
        <p>
            <a href="index.php"><button type="button">Retour</button></a>
            <button type="submit">Créer</button>
        </p>
    </form>
</body>
</html>