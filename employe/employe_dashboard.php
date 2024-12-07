<?php
// Démarrer la session et inclure la connexion à la base de données
session_start();
include '../php/connection.php';

// Vérifier si l'utilisateur est un employé
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employe') {
    header('Location: ../login.php');
    exit;
}

// Gestion des actions sur les avis
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'valider') {
        $query = $pdo->prepare("UPDATE avis SET valide = 1 WHERE id = :id");
    } elseif ($action === 'invalider') {
        $query = $pdo->prepare("DELETE FROM avis WHERE id = :id");
    }
    $query->execute([':id' => $id]);
    header('Location: employe_dashboard.php');
    exit;
}

// Ajouter un suivi de nourriture
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_nourriture'])) {
    $animal_id = intval($_POST['animal_id']);
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $quantite = $_POST['quantite'];

    $query = $pdo->prepare("INSERT INTO nourriture (animal_id, date, heure, quantite) VALUES (:animal_id, :date, :heure, :quantite)");
    $query->execute([
        ':animal_id' => $animal_id,
        ':date' => $date,
        ':heure' => $heure,
        ':quantite' => $quantite
    ]);
    $message = "Nourriture ajoutée avec succès.";
}

// Récupérer les avis non validés
$query_avis = $pdo->query("SELECT * FROM avis WHERE valide = 0");
$avis = $query_avis->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les animaux
$query_animaux = $pdo->query("SELECT id, prenom FROM animal");
$animaux = $query_animaux->fetchAll(PDO::FETCH_ASSOC);

// Récupérer l'historique des suivis de nourriture
$query_nourriture = $pdo->query("SELECT n.*, a.prenom FROM nourriture n JOIN animal a ON n.animal_id = a.id ORDER BY n.date DESC, n.heure DESC");
$nourritures = $query_nourriture->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Espace Employé</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="../logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Espace Employé</h1>

    <!-- Message de confirmation -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Gestion des avis -->
    <div class="card mb-4">
        <div class="card-header">Gestion des Avis</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Commentaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($avis)): ?>
                        <?php foreach ($avis as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['nom']) ?></td>
                                <td><?= htmlspecialchars($a['commentaire']) ?></td>
                                <td>
                                    <a href="employe_dashboard.php?action=valider&id=<?= $a['id'] ?>" class="btn btn-success btn-sm">Valider</a>
                                    <a href="employe_dashboard.php?action=invalider&id=<?= $a['id'] ?>" class="btn btn-danger btn-sm">Invalider</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Aucun avis en attente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ajout de suivi de nourriture -->
    <div class="card mb-4">
        <div class="card-header">Ajouter un Suivi de Nourriture</div>
        <div class="card-body">
            <form action="employe_dashboard.php" method="POST">
                <div class="mb-3">
                    <label for="animal_id" class="form-label">Animal</label>
                    <select name="animal_id" id="animal_id" class="form-select" required>
                        <option value="">-- Choisir un animal --</option>
                        <?php foreach ($animaux as $animal): ?>
                            <option value="<?= $animal['id'] ?>"><?= htmlspecialchars($animal['prenom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="heure" class="form-label">Heure</label>
                    <input type="time" name="heure" id="heure" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="quantite" class="form-label">Quantité (kg)</label>
                    <input type="number" step="0.01" name="quantite" id="quantite" class="form-control" required>
                </div>
                <button type="submit" name="add_nourriture" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Historique des suivis de nourriture -->
    <div class="card">
        <div class="card-header">Historique des Suivis de Nourriture</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Quantité (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($nourritures)): ?>
                        <?php foreach ($nourritures as $nourriture): ?>
                            <tr>
                                <td><?= htmlspecialchars($nourriture['prenom']) ?></td>
                                <td><?= htmlspecialchars($nourriture['date']) ?></td>
                                <td><?= htmlspecialchars($nourriture['heure']) ?></td>
                                <td><?= htmlspecialchars($nourriture['quantite']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Aucun suivi disponible.</td>
                        </tr>
                    <?php endif; ?>
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
