# TP1 - Secure Form

Projet de formulaire sécurisé en PHP avec MySQL et protection CSRF.

---

## 🚀 Installation rapide

### Prérequis
- **XAMPP**, **WAMP** ou **MAMP** installé
- **Apache** et **MySQL** activés

---

### Étapes d'installation

#### 1. Placer les fichiers au bon endroit

**XAMPP (Windows)** :
```
C:\xampp\htdocs\secure-form\
```

**WAMP (Windows)** :
```
C:\wamp64\www\secure-form\
```

**MAMP (Mac/Windows)** :
```
/Applications/MAMP/htdocs/secure-form/
```
ou
```
C:\MAMP\htdocs\secure-form\
```

#### 2. Démarrer les serveurs

- Ouvrir **XAMPP/WAMP/MAMP**
- Démarrer **Apache**
- Démarrer **MySQL**
- Vérifier que les deux voyants sont **verts**

#### 3. Accéder au site

**XAMPP/WAMP** :
```
http://localhost/secure-form/
```

**MAMP** :
```
http://localhost:8888/secure-form/
```
*(le port peut varier selon votre configuration MAMP)*

#### 4. Première connexion

La base de données `secure_form` et la table `users` seront créées **automatiquement** au premier lancement.

**Compte par défaut** :
- **Identifiant** : `admin`
- **Mot de passe** : `Admin@12345678`

---

## ⚙️ Configuration

### Configuration MySQL par défaut (fonctionne avec XAMPP/WAMP/MAMP)

Le fichier `config.php` est configuré avec les paramètres par défaut :

```php
DB_HOST = 'localhost'
DB_USER = 'root'
DB_PASS = 'root'     // MAMP utilise 'root', XAMPP/WAMP utilisent ''
DB_NAME = 'secure_form'
```

### Si vous avez des identifiants MySQL différents

Modifiez le fichier **config.php** :

```php
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
```

**Note** : 
- **MAMP** utilise par défaut : `root` / `root`
- **XAMPP/WAMP** utilisent par défaut : `root` / `` (vide)

---

## 🔐 Fonctionnalités de sécurité

### Protection CSRF (Cross-Site Request Forgery)
- Token unique généré pour chaque session
- Vérification du token à chaque soumission de formulaire
- Protection contre les attaques de type CSRF

### Validation des mots de passe
- Minimum **12 caractères**
- Au moins **1 majuscule**
- Au moins **1 minuscule**
- Au moins **1 chiffre**
- Au moins **1 caractère spécial** (!@#$%^&*...)

### Sécurité de la base de données
- **Hashage des mots de passe** avec bcrypt (algorithme PASSWORD_DEFAULT)
- **Requêtes préparées PDO** pour prévenir les injections SQL
- **Validation côté serveur** de toutes les données

---

## 📁 Structure du projet

```
secure-form/
├── index.php              # Page de connexion
├── login.php              # Traitement de la connexion
├── ajout.php              # Page de création de compte
├── config.php             # Configuration base de données
├── functions.php          # Fonctions de sécurité (CSRF, validation)
├── style.css              # Styles CSS
├── logo_paris8_noir.png   # Logo
└── README.md              # Ce fichier
```

---

## 🎯 Utilisation

### Page de connexion (index.php)
- Formulaire avec identifiant et mot de passe
- Bouton **Reset** : réinitialise les champs
- Bouton **OK** : soumet le formulaire
- Bouton **Ajout compte** : redirige vers la page de création

### Création de compte (ajout.php)
- Formulaire avec identifiant, mot de passe et confirmation
- Validation en temps réel des critères RGPD
- Affichage des erreurs détaillées si le mot de passe ne respecte pas les critères
- Protection contre les doublons d'identifiants

### Traitement de connexion (login.php)
- Vérification du token CSRF
- Vérification de l'identifiant et du mot de passe
- Message de succès ou d'erreur
- Redirection vers la page de connexion

---

## ⚠️ Dépannage

### Erreur "No such file or directory" ou "Can't connect"
**Cause** : MySQL n'est pas démarré ou mal configuré

**Solution** :
1. Vérifier que MySQL est bien démarré (voyant vert dans XAMPP/WAMP/MAMP)
2. Vérifier les identifiants dans `config.php`
3. Pour **MAMP** : le mot de passe par défaut est `'root'` et non `''` (vide)

### Erreur 404 Not Found
**Cause** : Les fichiers ne sont pas au bon endroit

**Solution** :
1. Vérifier que les fichiers sont dans le bon dossier (`htdocs` ou `www`)
2. Vérifier l'URL utilisée (port 8888 pour MAMP par défaut)
3. Pour MAMP : vérifier le Document Root dans Preferences → Web Server

### Page blanche
**Cause** : Erreur PHP non affichée

**Solution** :
1. Activer l'affichage des erreurs dans `php.ini` : `display_errors = On`
2. Redémarrer Apache
3. Consulter les logs Apache

### Erreur "Class 'PDO' not found"
**Cause** : Extension PDO MySQL non activée

**Solution** :
1. Ouvrir `php.ini`
2. Décommenter : `extension=pdo_mysql` (enlever le `;` devant)
3. Redémarrer Apache