<?php
// profil.php
require_once 'functions.php';

if (!is_logged()) {
    header('Location: connexion.php');
    exit;
}

$user = get_user_by_id($pdo, (int)$_SESSION['user']['id']);
if (!$user) {
    // session corrompue
    session_destroy();
    header('Location: connexion.php');
    exit;
}

$errors = [];
$success = null;

// CSRF simple
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Requête invalide.";
    } else {
        $login  = trim($_POST['login'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $nom    = trim($_POST['nom'] ?? '');
        $pass   = $_POST['password'] ?? '';
        $pass2  = $_POST['password_confirm'] ?? '';

        if ($login === '' || $prenom === '' || $nom === '') {
            $errors[] = "Login/prénom/nom requis.";
        }

        // vérifier login unique (sauf si c'est le même user)
        $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE login = :login AND id != :id');
        $stmt->execute([':login' => $login, ':id' => $user['id']]);
        if ($stmt->fetch()) {
            $errors[] = "Ce login est déjà utilisé.";
        }

        if ($pass !== '' && $pass !== $pass2) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        if (empty($errors)) {
            if ($pass !== '') {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE utilisateurs SET login = :login, prenom = :prenom, nom = :nom, password = :password WHERE id = :id');
                $stmt->execute([
                    ':login' => $login,
                    ':prenom' => $prenom,
                    ':nom' => $nom,
                    ':password' => $hash,
                    ':id' => $user['id']
                ]);
            } else {
                $stmt = $pdo->prepare('UPDATE utilisateurs SET login = :login, prenom = :prenom, nom = :nom WHERE id = :id');
                $stmt->execute([
                    ':login' => $login,
                    ':prenom' => $prenom,
                    ':nom' => $nom,
                    ':id' => $user['id']
                ]);
            }
            // mettre à jour session
            $updated = get_user_by_id($pdo, (int)$user['id']);
            unset($updated['password']);
            $_SESSION['user'] = $updated;
            $success = "Profil mis à jour.";
            // recharger les données pour affichage
            $user = $updated;
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
  <title>Profil</title>
</head>
<body>
<div class="container">
  <a href="index.php" class="small">← Accueil</a>
  <h1>Mon profil</h1>

<?php if ($errors): ?>
    <div class="alert">
        <?php foreach ($errors as $e) echo "<div>".sanitize($e)."</div>"; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success">
        <?php echo sanitize($success); ?>
    </div>
<?php endif; ?>
    <!-- novalidate si on veut gérer la validation soi-même (JS ou PHP) -->
  <form method="post" novalidate>

  <!-- vérifier que le formulaire vient bien du site et pas d’un site tiers -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <label>Login
      <input type="text" name="login" value="<?php echo sanitize($user['login']); ?>" required maxlength="255">
    </label>
    <label>Prénom
      <input type="text" name="prenom" value="<?php echo sanitize($user['prenom']); ?>" required maxlength="255">
    </label>
    <label>Nom
      <input type="text" name="nom" value="<?php echo sanitize($user['nom']); ?>" required maxlength="255">
    </label>
    <hr>
    <p class="small">Laissez les champs mot de passe vides si vous ne voulez pas le modifier.</p>
    <label>Nouveau mot de passe
      <input type="password" name="password">
    </label>
    <label>Confirmer nouveau mot de passe
      <input type="password" name="password_confirm">
    </label>

    <input type="submit" value="Enregistrer">
  </form>
</div>
</body>
</html>
