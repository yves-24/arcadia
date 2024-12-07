<?php
// Démarrer la session
session_start();
include '../php/connection.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Préparer la requête SQL
        $query = $pdo->prepare("SELECT * FROM utilisateur WHERE username = :username");
        $query->execute([':username' => $username]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe
        if ($user && $password === $user['password']) { // Vérifier le mot de passe (adapter si vous utilisez un hash)
            $_SESSION['user'] = $user;

            // Redirection selon le rôle
            if ($user['role'] === 'administrateur') {
                header('Location: admin_dashboard.php');
            } elseif ($user['role'] === 'veterinaire') {
                header('Location: ../vet/vet_dashboard.php');
            } elseif ($user['role'] === 'employe') {
                header('Location: ../employe/employe_dashboard.php');
            }
            exit;
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Connexion Administrateur</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="admin_login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</body>
</html>
