# Chatbox - Projet BTS SIO (Épreuve E6)

Application web de messagerie instantanée (Client Léger) développée en PHP et MySQL. 
Ce projet a été réalisé dans le cadre de la préparation à l'épreuve E6 du **BTS SIO (Option SLAM)**. L'objectif principal est de démontrer la maîtrise du développement web backend tout en appliquant les standards stricts de cybersécurité.

## 🚀 Fonctionnalités Principales

* **Messagerie Hybride :** Envoi de messages sur un canal public ("Général") ou en privé entre utilisateurs.
* **Base de Données Avancée :** Implémentation d'un **Trigger SQL** (`archivage_message`) pour automatiser la sauvegarde des messages supprimés dans une table d'archive.
* **Gestion des Sessions :** Authentification sécurisée et maintien de la session utilisateur.

## 🛡️ Cybersécurité (Prévention OWASP)

Le code a été renforcé pour contrer les vulnérabilités web les plus courantes :
* **Injections SQL :** Utilisation exclusive de requêtes préparées (`mysqli_prepare`).
* **Failles XSS :** Désinfection systématique des affichages via `htmlspecialchars` (avec le flag `ENT_QUOTES`).
* **Protection des Identifiants :** Hachage cryptographique des mots de passe en base de données (`password_hash`).
* **Contrôle d'accès :** Validation des droits d'action (ex: suppression de message) basée sur la session serveur, ignorant les paramètres d'URL.

## 🛠️ Stack Technique

* **Backend :** PHP 8.x
* **Base de données :** MariaDB / MySQL
* **Frontend :** HTML5, CSS3

## ⚙️ Installation (Environnement Local)

1. **Cloner le dépôt :**
   ```bash
   git clone https://github.com/zarathmgd/chatbox.git

2. **Préparer le serveur web :**
Placez le dossier du projet dans le répertoire de votre serveur web local (ex: htdocs pour XAMPP ou www pour WAMP).

3. **Initialiser la base de données :**

   - Créez une base de données nommée chatbox via phpMyAdmin ou un autre client SQL.
   - Importez le script chatbox.sql (situé à la racine du projet) pour générer les tables et le Trigger d'archivage.

4. **Lancer l'application :**
Accédez à l'application via votre navigateur : http://localhost/chatbox/connexion.php

Compte administrateur créer par défaut : **Pseudo : Admin / Mot de passe : Admin123456!**

## Projet développé par Zarath Mougamadou - BTS SIO. 
