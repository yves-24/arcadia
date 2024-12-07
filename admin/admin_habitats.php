<?php
// Démarrer la session et inclure la connexion à la base de données
session_start();
include '../php/connection.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: ../index.php');
    exit;
}

// Ajouter un habitat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_habitat'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $image = '';

    // Gestion des fichiers d'images
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = 'images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image);
    }

    try {
        $query = $pdo->prepare("INSERT INTO habitat (nom, description, image_url) VALUES (:nom, :description, :image)");
        $query->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':image' => $image
        ]);
        $message = "Habitat ajouté avec succès !";
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Supprimer un habitat
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    try {
        $query = $pdo->prepare("DELETE FROM habitat WHERE id = :id");
        $query->execute([':id' => $id]);
        header('Location: admin_habitats.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer les habitats existants
$query_habitats = $pdo->query("SELECT * FROM habitat");
$habitats = $query_habitats->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Habitats</title>
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

    <!-- Gestion des Habitats -->
    <div class="container mt-4">
        <h1>Gestion des Habitats</h1>

        <!-- Messages -->
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Ajouter un Habitat -->
        <div class="card mb-4">
            <div class="card-header">Ajouter un Habitat</div>
            <div class="card-body">
                <form action="admin_habitats.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image (optionnel)</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <button type="submit" name="add_habitat" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>

        <!-- Liste des Habitats -->
        <div class="card">
            <div class="card-header">Liste des Habitats</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($habitats as $habitat): ?>
                            <tr>
                                <td><?= $habitat['id'] ?></td>
                                <td><?= htmlspecialchars($habitat['nom']) ?></td>
                                <td><?= htmlspecialchars($habitat['description']) ?></td>
                                <td>
                                    <?php if ($habitat['image_url']): ?>
                                        <img src="../<?= $habitat['image_url'] ?>" alt="Image" width="100">
                                    <?php else: ?>
                                        Aucun
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="admin_habitats.php?delete_id=<?= $habitat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet habitat ?')">Supprimer</a>
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
