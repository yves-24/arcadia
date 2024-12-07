<?php
// Démarrer la session et inclure la connexion à la base de données
session_start();
include '../php/connection.php';
require '../vendor/autoload.php'; // Pour MongoDB

use MongoDB\Client;

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: ../login.php');
    exit;
}

try {
    // Connexion à MongoDB
    $uri = "mongodb+srv://arcadia:arcadia1@cluster0.kvuaz.mongodb.net/zoo_arcadia?retryWrites=true&w=majority&tls=true&tlsAllowInvalidCertificates=true";
    $client = new Client($uri);
    $database = $client->selectDatabase('zoo_arcadia');
    $collection = $database->selectCollection('consultations');

    // Récupération des consultations depuis MongoDB
    $consultations = $collection->find([], ['limit' => 5]);

    $most_consulted_animals_mongo = [];
    foreach ($consultations as $consultation) {
        $most_consulted_animals_mongo[] = [
            'animal_name' => $consultation['animal_name'],
            'consultations' => $consultation['consultations']
        ];
    }

    // Récupération des statistiques principales depuis MySQL
    $total_habitats = $pdo->query("SELECT COUNT(*) AS total FROM habitat")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_animals = $pdo->query("SELECT COUNT(*) AS total FROM animal")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_users = $pdo->query("SELECT COUNT(*) AS total FROM utilisateur WHERE role IN ('employe', 'veterinaire')")->fetch(PDO::FETCH_ASSOC)['total'];
    $total_services = $pdo->query("SELECT COUNT(*) AS total FROM service")->fetch(PDO::FETCH_ASSOC)['total'];

} catch (Exception $e) {
    $error = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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


    <!-- Dashboard Content -->
    <div class="container mt-4">
        <h1>Tableau de bord - Zoo Arcadia</h1>

        <!-- Affichage des erreurs -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Statistiques principales -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Habitats</h5>
                        <p class="card-text"><?= $total_habitats ?> Habitats</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Animaux</h5>
                        <p class="card-text"><?= $total_animals ?> Animaux</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs</h5>
                        <p class="card-text"><?= $total_users ?> Utilisateurs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Services</h5>
                        <p class="card-text"><?= $total_services ?> Services</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des consultations depuis MongoDB -->
        <h2>Top 5 des Animaux les Plus Consultés (MongoDB)</h2>
        <ul class="list-group mb-4">
            <?php foreach ($most_consulted_animals_mongo as $animal): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($animal['animal_name']) ?>
                    <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($animal['consultations']) ?> consultations</span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <footer class="bg-dark text-center text-white py-3 mt-4">
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>
</body>
</html>
