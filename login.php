<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    
    $db = new SQLite3('users.db');
    $stmt = $db->prepare('SELECT * FROM users WHERE identifiant = :identifiant AND mdp = :mdp');
    $stmt->bindValue(':identifiant', $identifiant, SQLITE3_TEXT);
    $stmt->bindValue(':mdp', $mdp, SQLITE3_TEXT);
    
    $result = $stmt->execute();
    $user = $result->fetchArray();
    
    if ($user) {
        $_SESSION['message'] = "OK - Connexion réussie !";
    } else {
        $_SESSION['message'] = "ERROR - Identifiant ou mot de passe incorrect";
    }
    
    header('Location: index.php');
    exit();
}
?>