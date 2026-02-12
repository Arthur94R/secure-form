<?php
session_start();
require_once 'functions.php';

$erreurs = [];

// Générer le token CSRF
$csrf_token = genererTokenCSRF();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier le token CSRF
    $csrf_token_post = $_POST['csrf_token'] ?? '';
    
    if (!verifierTokenCSRF($csrf_token_post)) {
        $erreurs[] = "Requête invalide (protection CSRF)";
    } else {
        $identifiant = $_POST['identifiant'] ?? '';
        $mdp = $_POST['mdp'] ?? '';
        $mdpConfirm = $_POST['mdpConfirm'] ?? '';
        
        if ($mdp !== $mdpConfirm) {
            $erreurs[] = "Les mots de passe ne correspondent pas";
        }

        $erreurs = array_merge($erreurs, validerMotDePasse($mdp));

        if (empty($erreurs)) {
            $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);
            
            $db = new SQLite3('users.db');
            $stmt = $db->prepare('INSERT INTO users (identifiant, mdp) VALUES (:identifiant, :mdp)');
            $stmt->bindValue(':identifiant', $identifiant, SQLITE3_TEXT);
            $stmt->bindValue(':mdp', $mdpHash, SQLITE3_TEXT);
            
            try {
                $stmt->execute();
                $_SESSION['message'] = "OK - Compte créé avec succès !";
                header('Location: index.php');
                exit();
            } catch (Exception $e) {
                $erreurs[] = "Cet identifiant existe déjà";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de compte sécurisé</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Créer un nouveau compte</h1>
        
        <h3>Exigences du mot de passe (RGPD) :</h3>
        <ul>
            <li>Minimum 12 caractères</li>
            <li>Au moins 1 majuscule</li>
            <li>Au moins 1 minuscule</li>
            <li>Au moins 1 chiffre</li>
            <li>Au moins 1 caractère spécial (!@#$%^&*...)</li>
        </ul>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <label>Identifiant :</label>
            <input type="text" name="identifiant" value="<?php echo htmlspecialchars($_POST['identifiant'] ?? ''); ?>" required>
            
            <label>Mot de passe :</label>
            <input type="password" name="mdp" required>
            
            <label>Confirmer le mot de passe :</label>
            <input type="password" name="mdpConfirm" required>
            
            <?php if (!empty($erreurs)): ?>
                <div class="erreurs">
                    <strong>ERREURS :</strong>
                    <ul>
                        <?php foreach ($erreurs as $erreur): ?>
                            <li><?php echo htmlspecialchars($erreur); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="buttons">
                <a href="index.php"><button type="button">Retour</button></a>
                <button type="submit">Créer</button>
            </div>
        </form>
    </div>
</body>
</html>