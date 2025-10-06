<?php
// config.php
declare(strict_types=1);

session_start();

$DB_HOST = '127.0.0.1';
$DB_NAME = 'moduleconnexion';
$DB_USER = 'root';
$DB_PASS = ''; // à adapter

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // en production, logger plutôt que d'afficher
    die("Erreur BDD : " . $e->getMessage());
}
