<?php
// Démarrer la session et inclure la connexion à la base de données
session_start();
include '../php/connection.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: ../login.php');
    exit;
}

// Initialiser les variables
$rapports = [];
$error = null;

try {
    // Vérifiez si la colonne `details` existe
    $query = $pdo->query("
        SELECT r.id, a.prenom AS animal, r.etat, r.nourriture, r.details AS details, r.date AS date_creation
        FROM rapport_veterinaire r
        JOIN animal a ON r.animal_id = a.id
        ORDER BY r.date DESC
    ");
    $rapports = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des rapports : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports Vétérinaires - Administrateur</title>
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

    <!-- Contenu principal -->
    <div class="container mt-4">
        <h1 class="mb-4">Rapports Vétérinaires</h1>

        <!-- Afficher une erreur si nécessaire -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Tableau des rapports vétérinaires -->
        <div class="card">
            <div class="card-header">Liste des Rapports</div>
            <div class="card-body">
                <?php if (empty($rapports)): ?>
                    <p>Aucun rapport vétérinaire trouvé.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Animal</th>
                                <th>État de santé</th>
                                <th>Nourriture donnée</th>
                                <th>Détails</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rapports as $rapport): ?>
                                <tr>
                                    <td><?= htmlspecialchars($rapport['id']) ?></td>
                                    <td><?= htmlspecialchars($rapport['animal']) ?></td>
                                    <td><?= htmlspecialchars($rapport['etat']) ?></td>
                                    <td><?= htmlspecialchars($rapport['nourriture']) ?></td>
                                    <td><?= htmlspecialchars($rapport['details'] ?? 'Non spécifié') ?></td>
                                    <td><?= htmlspecialchars($rapport['date_creation']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-center text-white py-3 mt-4">
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

