<?php
// functions.php

// Activer le typage strict pour renforcer la sécurité et éviter les conversions implicites
declare(strict_types=1);

// Inclure le fichier de configuration (connexion à la base de données, constantes, etc.)
require_once 'config.php';

// -------------------------------
// Fonction : sanitize()
// -------------------------------
// Nettoyer une chaîne de caractères avant de l'afficher ou de l'enregistrer
// Supprimer les espaces inutiles et convertir les caractères spéciaux en entités HTML
function sanitize(string $s): string {
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}

// -------------------------------
// Fonction : is_logged()
// -------------------------------
// Vérifier si un utilisateur est connecté
// Retourner true si la clé 'user' existe dans la session, sinon false
function is_logged(): bool {
    return !empty($_SESSION['user']);
}

// -------------------------------
// Fonction : is_admin()
// -------------------------------
// Vérifier si l'utilisateur connecté est un administrateur
// Retourner true si l'utilisateur est connecté et que son login est "admin"
function is_admin(): bool {
    return is_logged() && isset($_SESSION['user']['login']) && $_SESSION['user']['login'] === 'admin';
}

// -------------------------------
// Fonction : get_user_by_login()
// -------------------------------
// Récupérer un utilisateur en fonction de son login
// Préparer la requête SQL pour éviter les injections
// Exécuter la requête avec le paramètre ":login"
// Retourner les données de l'utilisateur sous forme de tableau associatif ou false si aucun résultat
function get_user_by_login(PDO $pdo, string $login): array|false {
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE login = :login');
    $stmt->execute([':login' => $login]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// -------------------------------
// Fonction : get_user_by_id()
// -------------------------------
// Récupérer un utilisateur en fonction de son identifiant (id)
// Préparer la requête SQL avec un paramètre nommé ":id"
// Exécuter la requête avec la valeur de l'identifiant
// Retourner le résultat sous forme de tableau associatif ou false si aucun utilisateur trouvé
function get_user_by_id(PDO $pdo, int $id): array|false {
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
