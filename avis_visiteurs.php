<?php
include 'php/connection.php';

// Récupérer les avis validés
$query = $pdo->query("SELECT nom, commentaire FROM avis WHERE valide = 1");
$avis = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis des Visiteurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Zoo Arcadia</a>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Avis des Visiteurs</h1>

    <!-- Bouton pour laisser un avis -->
    <div class="mb-3 text-end">
        <a href="avis.php" class="btn btn-primary">Laisser un avis</a>
    </div>

    <!-- Liste des avis -->
    <div class="card">
        <div class="card-header">Ce que nos visiteurs disent de nous</div>
        <div class="card-body">
            <ul class="list-group">
                <?php if (!empty($avis)): ?>
                    <?php foreach ($avis as $a): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($a['nom']) ?> :</strong>
                            <p><?= htmlspecialchars($a['commentaire']) ?></p>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">Aucun avis disponible pour le moment.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<footer class="bg-dark text-center text-white py-3 mt-4">
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
