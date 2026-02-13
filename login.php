<?php
session_start();
require_once 'config.php';
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
    
    $db = getDB();
    $stmt = $db->prepare('SELECT * FROM users WHERE identifiant = ?');
    $stmt->execute([$identifiant]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($mdp, $user['mdp'])) {
        $_SESSION['message'] = "OK - Connexion réussie !";
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['identifiant'];
    } else {
        $_SESSION['message'] = "ERROR - Identifiant ou mot de passe incorrect";
    }
    
    header('Location: index.php');
    exit();
}
?>