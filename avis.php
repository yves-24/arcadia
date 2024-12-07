<?php
include 'php/connection.php';

$message = '';

// Ajouter un avis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $commentaire = htmlspecialchars($_POST['commentaire']);

    $query = $pdo->prepare("INSERT INTO avis (nom, email, commentaire) VALUES (:nom, :email, :commentaire)");
    $query->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':commentaire' => $commentaire
    ]);
    $message = "Merci pour votre avis ! Il sera publié après validation.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laisser un Avis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Zoo Arcadia</a>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Laisser un Avis</h1>

    <!-- Message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Formulaire d'avis -->
    <form action="avis.php" method="POST">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="commentaire" class="form-label">Commentaire</label>
            <textarea name="commentaire" id="commentaire" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>

<footer class="bg-dark text-center text-white py-3 mt-4">
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
