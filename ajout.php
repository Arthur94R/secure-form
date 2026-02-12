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
    <title>Ajout de compte sécurisé</title>
</head>
<body>
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
        <!-- Token CSRF caché -->
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
        <p>
            <label>Identifiant :</label><br>
            <input type="text" name="identifiant" value="<?php echo htmlspecialchars($_POST['identifiant'] ?? ''); ?>" required>
        </p>
        
        <p>
            <label>Mot de passe :</label><br>
            <input type="password" name="mdp" required>
        </p>
        
        <p>
            <label>Confirmer le mot de passe :</label><br>
            <input type="password" name="mdpConfirm" required>
        </p>
        
        <?php if (!empty($erreurs)): ?>
            <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">
                <strong>ERREURS :</strong>
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?php echo htmlspecialchars($erreur); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <p>
            <a href="index.php"><button type="button">Retour</button></a>
            <button type="submit">Créer</button>
        </p>
    </form>
</body>
</html>