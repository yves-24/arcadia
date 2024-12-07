<?php
// Démarrer la session et inclure la connexion à la base de données
session_start();
include '../php/connection.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: ../index.php');
    exit;
}

// Ajouter un animal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_animal'])) {
    $prenom = $_POST['prenom'];
    $race = $_POST['race'];
    $habitat_id = $_POST['habitat_id'];
    $etat = $_POST['etat'];
    $image = '';

    // Gestion des fichiers d'images
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = 'images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image);
    }

    try {
        $query = $pdo->prepare("INSERT INTO animal (prenom, race, habitat_id, etat, image_url) VALUES (:prenom, :race, :habitat_id, :etat, :image)");
        $query->execute([
            ':prenom' => $prenom,
            ':race' => $race,
            ':habitat_id' => $habitat_id,
            ':etat' => $etat,
            ':image' => $image
        ]);
        $message = "Animal ajouté avec succès !";
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Supprimer un animal
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    try {
        $query = $pdo->prepare("DELETE FROM animal WHERE id = :id");
        $query->execute([':id' => $id]);
        header('Location: admin_animals.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer les animaux existants
$query_animals = $pdo->query("SELECT animal.*, habitat.nom AS habitat_nom FROM animal INNER JOIN habitat ON animal.habitat_id = habitat.id");
$animals = $query_animals->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les habitats pour le formulaire d'ajout
$query_habitats = $pdo->query("SELECT id, nom FROM habitat");
$habitats = $query_habitats->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Animaux</title>
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

    <!-- Gestion des Animaux -->
    <div class="container mt-4">
        <h1>Gestion des Animaux</h1>

        <!-- Messages -->
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Ajouter un Animal -->
        <div class="card mb-4">
            <div class="card-header">Ajouter un Animal</div>
            <div class="card-body">
                <form action="admin_animals.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="race" class="form-label">Race</label>
                        <input type="text" class="form-control" id="race" name="race" required>
                    </div>
                    <div class="mb-3">
                        <label for="habitat_id" class="form-label">Habitat</label>
                        <select class="form-control" id="habitat_id" name="habitat_id" required>
                            <option value="">-- Choisir un habitat --</option>
                            <?php foreach ($habitats as $habitat): ?>
                                <option value="<?= $habitat['id'] ?>"><?= htmlspecialchars($habitat['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="etat" class="form-label">État</label>
                        <input type="text" class="form-control" id="etat" name="etat" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <button type="submit" name="add_animal" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>

        <!-- Liste des Animaux -->
        <div class="card">
            <div class="card-header">Liste des Animaux</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Prénom</th>
                            <th>Race</th>
                            <th>Habitat</th>
                            <th>État</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($animals as $animal): ?>
                            <tr>
                                <td><?= $animal['id'] ?></td>
                                <td><?= htmlspecialchars($animal['prenom']) ?></td>
                                <td><?= htmlspecialchars($animal['race']) ?></td>
                                <td><?= htmlspecialchars($animal['habitat_nom']) ?></td>
                                <td><?= htmlspecialchars($animal['etat']) ?></td>
                                <td>
                                    <?php if ($animal['image_url']): ?>
                                        <img src="../<?= $animal['image_url'] ?>" alt="Image" width="100">
                                    <?php else: ?>
                                        Aucun
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="admin_animals.php?delete_id=<?= $animal['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet animal ?')">Supprimer</a>
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
