<?php
// inscription.php
require_once 'functions.php';

$errors = [];
$success = null;

// CSRF token minimal
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérif token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Requête invalide.";
    }

    $login  = trim($_POST['login'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $nom    = trim($_POST['nom'] ?? '');
    $pass   = $_POST['password'] ?? '';
    $pass2  = $_POST['password_confirm'] ?? '';

    if ($login === '' || $prenom === '' || $nom === '' || $pass === '') {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if ($pass !== $pass2) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (strlen($login) > 255 || strlen($prenom) > 255 || strlen($nom) > 255) {
        $errors[] = "Champs trop longs.";
    }

    // Vérifier si login existe
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM utilisateurs WHERE login = :login');
        $stmt->execute([':login' => $login]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Le login est déjà pris.";
        }
    }

    if (empty($errors)) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $insert = $pdo->prepare('INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (:login, :prenom, :nom, :password)');
        $insert->execute([
            ':login' => $login,
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':password' => $hash
        ]);
        // redirection vers connexion
        header('Location: connexion.php?registered=1');
        exit;
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <title>Inscription</title>
</head>
<body>
<div class="container">
  <a href="index.php" class="small">← Retour</a>
  <h1>Inscription</h1>

  <?php if ($errors): ?>
    <div class="alert">
      <?php foreach ($errors as $e) echo "<div>".sanitize($e)."</div>"; ?>
    </div>
  <?php endif; ?>

  <form method="post" novalidate>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <label>Login
      <input type="text" name="login" value="<?php echo isset($login) ? sanitize($login) : ''; ?>" required maxlength="255">
    </label>
    <label>Prénom
      <input type="text" name="prenom" value="<?php echo isset($prenom) ? sanitize($prenom) : ''; ?>" required maxlength="255">
    </label>
    <label>Nom
      <input type="text" name="nom" value="<?php echo isset($nom) ? sanitize($nom) : ''; ?>" required maxlength="255">
    </label>
    <label>Mot de passe
      <input type="password" name="password" required>
    </label>
    <label>Confirmer le mot de passe
      <input type="password" name="password_confirm" required>
    </label>

    <input type="submit" value="S'inscrire">
  </form>
</div>
</body>
</html>
