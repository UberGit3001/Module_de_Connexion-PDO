# Module Connexion PDO

Ce projet est un module PHP de gestion d'utilisateurs (inscription, connexion, profil, administration) utilisant PDO pour l'accès à une base de données MySQL.

## Fonctionnalités

- Inscription d'utilisateurs avec mot de passe sécurisé (hashé)
- Connexion et déconnexion
- Modification du profil utilisateur
- Page d'administration (liste des utilisateurs, accès réservé à l'admin)
- Protection CSRF sur les formulaires
- Sécurisation des entrées/sorties (sanitization)
- Interface responsive simple (CSS)

## Structure

- `index.php` : Accueil, navigation
- `inscription.php` : Formulaire d'inscription
- `connexion.php` : Formulaire de connexion
- `profil.php` : Gestion du profil utilisateur
- `admin.php` : Liste des utilisateurs (admin uniquement)
- `deconnexion.php` : Déconnexion
- `functions.php` : Fonctions utilitaires (auth, sécurité, accès BDD)
- `config.php` : Connexion PDO à la base de données
- `moduleconnexion.sql` : Script SQL pour créer la base et la table
- `style.css` : Styles

## Installation

1. Importer la base de données avec [`moduleconnexion.sql`](moduleconnexion.sql)
2. Configurer l'accès BDD dans [`config.php`](config.php)
3. Placer les fichiers sur un serveur web avec PHP et MySQL
4. Accéder à `index.php` via le navigateur

**Admin par défaut** :  
Login : `admin`  
Mot de passe : `admin` (modifiable dans la BDD)

## Sécurité

- Les nouveaux mots de passe sont stockés hashés (password_hash)
- L'admin d'exemple est en clair pour la démonstration (à modifier en production)
- Les entrées utilisateurs sont filtrées et échappées

## Auteur

Projet d'exercice PHP - Module Connexion (PDO)