<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier le token CSRF
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifierTokenCSRF($csrf_token)) {
        $_SESSION['message'] = "ERROR - Requête invalide (protection CSRF)";
        header('Location: index.php');
        exit();
    }
    
    $identifiant = $_POST['identifiant'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    
    $db = new SQLite3('users.db');
    $stmt = $db->prepare('SELECT * FROM users WHERE identifiant = :identifiant');
    $stmt->bindValue(':identifiant', $identifiant, SQLITE3_TEXT);
    
    $result = $stmt->execute();
    $user = $result->fetchArray();
    
    if ($user && password_verify($mdp, $user['mdp'])) {
        $_SESSION['message'] = "OK - Connexion réussie !";
    } else {
        $_SESSION['message'] = "ERROR - Identifiant ou mot de passe incorrect";
    }
    
    header('Location: index.php');
    exit();
}
?>