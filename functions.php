<?php
// Générer un token CSRF
function genererTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Vérifier le token CSRF
function verifierTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Valider mot de passe RGPD
function validerMotDePasse($mdp) {
    $erreurs = [];
    
    if (strlen($mdp) < 12) {
        $erreurs[] = "Le mot de passe doit contenir au moins 12 caractères";
    }
    
    if (!preg_match('/[A-Z]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins une majuscule";
    }
    
    if (!preg_match('/[a-z]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins une minuscule";
    }
    
    if (!preg_match('/[0-9]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins un chiffre";
    }
    
    if (!preg_match('/[^a-zA-Z0-9]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins un caractère spécial (!@#$%^&*...)";
    }
    
    return $erreurs;
}
?>