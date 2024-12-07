<?php
// Démarrer la session et inclure la connexion à la base de données
session_start();
include '../php/connection.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: ../index.php');
    exit;
}

// Ajouter un service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    try {
        $query = $pdo->prepare("INSERT INTO service (nom, description) VALUES (:nom, :description)");
        $query->execute([
            ':nom' => $nom,
            ':description' => $description
        ]);
        $message = "Service ajouté avec succès !";
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Supprimer un service
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    try {
        $query = $pdo->prepare("DELETE FROM service WHERE id = :id");
        $query->execute([':id' => $id]);
        header('Location: admin_services.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer les services existants
$query_services = $pdo->query("SELECT * FROM service");
$services = $query_services->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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

    <!-- Gestion des Services -->
    <div class="container mt-4">
        <h1>Gestion des Services</h1>

        <!-- Messages -->
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Ajouter un Service -->
        <div class="card mb-4">
            <div class="card-header">Ajouter un Service</div>
            <div class="card-body">
                <form action="admin_services.php" method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="add_service" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>

        <!-- Liste des Services -->
        <div class="card">
            <div class="card-header">Liste des Services</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= $service['id'] ?></td>
                                <td><?= htmlspecialchars($service['nom']) ?></td>
                                <td><?= htmlspecialchars($service['description']) ?></td>
                                <td>
                                    <a href="admin_services.php?delete_id=<?= $service['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce service ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-center text-white py-3 mt-4">
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
