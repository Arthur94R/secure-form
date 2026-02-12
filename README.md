# Projet formulaire sécurisé en PHP

Projet simple de connexion avec PHP et SQLite.

## Fonctionnalités
- 1 formulaire de connexion
    - 1 logo
    - 1 champ identifiant
    - 1 champ mot de passe
    - 3 boutons
        - Reset : remise à zéro des champs
        - Ok : Message ok ou error
        - Ajout compte : possibilité d'ajouter un identifiant en plus
- 1 formulaire d'ajout de compte
    - Exigences de sécurité du mot de passe lors de sa création
    - 1 champ identifiant
    - 1 champ mot de passe
    - 1 champ confirmation de mot de passe
    - 2 boutons
        - Créer
        - Retour vers le formulaire de connexion initial
- Base de données SQLite
- Protection token CSRF
- un style.css généré avec IA pour un projet esthétiquement correct

## Installation
1. Cloner le repository
2. Lancer : `php -S localhost:8000`
3. Ouvrir : `http://localhost:8000`

## Compte par défaut
- Identifiant : `admin`
- Mot de passe : `Admin@12345678`