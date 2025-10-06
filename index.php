<?php
require_once 'functions.php';
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <title>Module connexion - Accueil</title>
</head>
<body>
  <div class="container">
    <nav class="nav">
      <a href="index.php">Accueil</a>
      <?php if(!is_logged()): ?>
        <a href="inscription.php">Inscription</a>
        <a href="connexion.php">Connexion</a>
      <?php else: ?>
        <a href="profil.php">Profil</a>
        <?php if(is_admin()): ?><a href="admin.php">Admin</a><?php endif; ?>
        <a href="deconnexion.php">Déconnexion</a>
      <?php endif; ?>
    </nav>

    <h1>Bienvenue</h1>
    <p class="small">Exercice module connexion — connecte-toi ou crée un compte.</p>

    <?php if(is_logged()): ?>
      <div class="success">Connecté en tant que <strong><?php echo sanitize($_SESSION['user']['login']); ?></strong></div>
    <?php else: ?>
      <div class="small">Tu n'es pas connecté.</div>
    <?php endif; ?>
  </div>
</body>
</html>
