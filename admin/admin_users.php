<?php
session_start();
include '../php/connection.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO utilisateur (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);
}

if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
}

$users = $pdo->query("SELECT * FROM utilisateur")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Gestion des utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Admin - Zoo Arcadia</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="admin_services.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_habitats.php">Habitats</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_animals.php">Animaux</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_users.php">Utilisateurs</a></li>
					<li class="nav-item"><a class="nav-link" href="admin_reports.php">Rapports Vétérinaires</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="../logout.php">Déconnexion</a></li>

                </ul>
            </div>
        </div>
    </nav>


</head>
<body>
    <div class="container mt-4">
        <h1>Gestion des utilisateurs</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role">Rôle</label>
                <select name="role" class="form-control">
                    <option value="employe">Employé</option>
                    <option value="veterinaire">Vétérinaire</option>
                </select>
            </div>
            <button type="submit" name="add_user" class="btn btn-primary">Ajouter</button>
        </form>

        <h2>Liste des utilisateurs</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="admin_users.php?delete_id=<?= $user['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

