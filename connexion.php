<?php
// connexion.php
require_once 'functions.php';

$errors = [];
$info = null;

// si déjà connecté => rediriger
if (is_logged()) {
    header('Location: profil.php');
    exit;
}

// message après inscription
if (isset($_GET['registered'])) {
    $info = "Inscription réussie. Connecte-toi.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $errors[] = "Remplis login et mot de passe.";
    } else {
        $user = get_user_by_login($pdo, $login);
        if (!$user) {
            $errors[] = "Identifiants incorrects.";
        } else {
            // Support pour utilisateurs récents (hashés) et pour l'admin inséré en clair dans SQL d'exemple
            $stored = $user['password'];
            $ok = false;
            if (password_verify($password, $stored)) {
                $ok = true;
            } else {
                // compat plain-text (uniquement pour l'exemple/admin)
                if ($stored === $password) {
                    $ok = true;
                }
            }

            if ($ok) {
                // créer session user
                unset($user['password']); // ne pas stocker le hash en session
                $_SESSION['user'] = $user;
                header('Location: profil.php');
                exit;
            } else {
                $errors[] = "Identifiants incorrects.";
            }
        }
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <title>Connexion</title>
</head>
<body>
<div class="container">
  <a href="index.php" class="small">← Retour</a>
  <h1>Connexion</h1>

  <?php if ($info): ?>
    <div class="success"><?php echo sanitize($info); ?></div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="alert"><?php foreach ($errors as $e) echo "<div>".sanitize($e)."</div>"; ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <label>Login
      <input type="text" name="login" required>
    </label>
    <label>Mot de passe
      <input type="password" name="password" required>
    </label>
    <input type="submit" value="Se connecter">
  </form>
</div>
</body>
</html>
