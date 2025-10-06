<?php

// ==============================================
// admin.php — Page d'administration du site
// ==============================================

// Inclure le fichier des fonctions (connexion PDO, sécurité, etc.)
require_once 'functions.php';

// -------------------------------------------------
// Vérifier si l'utilisateur connecté est administrateur
// -------------------------------------------------
// Si l'utilisateur n'est pas admin, renvoyer le code 403 (accès interdit)
// Afficher un message d'erreur et arrêter le script

if (!is_admin()) {
    http_response_code(403);
    echo "Accès interdit.";
    exit;
}

// ------------------------------------------------------
// Récupérer tous les utilisateurs de la base de données
// ------------------------------------------------------
// Préparer et exécuter une requête simple (pas de paramètre, donc pas besoin de prepare())
// Trier les utilisateurs par identifiant croissant

$stmt = $pdo->query('SELECT id, login, prenom, nom FROM utilisateurs ORDER BY id ASC');

// Récupérer tous les résultats sous forme de tableau
// Par défaut, fetchAll() renvoie un tableau associatif + numérique
// Pour plus de clarté, préciser PDO::FETCH_ASSOC (optionnel mais recommandé)
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// $users = $stmt->fetchAll();

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <!-- Définir l'encodage et le comportement responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Lier la feuille de style principale -->
  <link rel="stylesheet" href="style.css">
  <title>Admin - Liste utilisateurs</title>
</head>
<body>
<div class="container">

     <!-- Lien pour retourner à la page d'accueil -->
  <a href="index.php" class="small">← Retour</a>

  <!-- Titre principal de la page -->
  <h1>Administration</h1>
  <p class="small">Utilisateur admin : affichage de tous les utilisateurs</p>

  <!-- Tableau listant les utilisateurs -->
  <table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Login</th>
        <th>Prénom</th>
        <th>Nom</th>
       
    </tr>
    </thead>
    <tbody>
        <!-- Parcourir chaque utilisateur et afficher ses informations -->
      <?php foreach ($users as $u): ?>
      <tr>
        <!-- Sécuriser les affichages avec sanitize() -->
        <td><?php echo (int)$u['id']; ?></td>
        <td><?php echo sanitize($u['login']); ?></td>
        <td><?php echo sanitize($u['prenom']); ?></td>
        <td><?php echo sanitize($u['nom']); ?></td>

      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
