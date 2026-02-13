<?php
// Configuration de la base de données MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'secure_form');
define('DB_USER', 'root');
define('DB_PASS', 'root');

function getDB() {
    try {
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        $connexion = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $pdo = new PDO($connexion, DB_USER, DB_PASS, $options);
        
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

// Créer la base de données et la table si elles n'existent pas
function initDB() {
    try {
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        // Connexion sans spécifier de base de données
        $connexion = "mysql:host=" . DB_HOST;
        $pdo = new PDO($connexion, DB_USER, DB_PASS, $options);
        
        // Créer la base de données si elle n'existe pas
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE " . DB_NAME);
        
        // Créer la table users
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            identifiant VARCHAR(50) UNIQUE NOT NULL,
            mdp VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        // Vérifier s'il y a des utilisateurs
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        
        // Créer le compte admin par défaut
        if ($count == 0) {
            $mdp_hash = password_hash('Admin@12345678', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (identifiant, mdp) VALUES (?, ?)");
            $stmt->execute(['admin', $mdp_hash]);
        }
        
    } catch (PDOException $e) {
        die("Erreur d'initialisation de la base de données : " . $e->getMessage());
    }
}
?>