<?php
// Inclure le fichier de connexion à la base de données
include 'php/connection.php';

// Récupérer les habitats depuis la base de données
$query_habitats = $pdo->query("SELECT * FROM habitat");
$habitats = $query_habitats->fetchAll(PDO::FETCH_ASSOC);

// Vérifiez que l'ID de l'animal est passé en paramètre
if (isset($_GET['animal_id'])) {
    $animal_id = intval($_GET['animal_id']);

    // Enregistrer l'interaction
    $query = $pdo->prepare("INSERT INTO interactions (type, cible_id) VALUES ('animal', :cible_id)");
    $query->execute([':cible_id' => $animal_id]);
}





?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Habitats - Zoo Arcadia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>





  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">Zoo Arcadia</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="habitats.php">Habitats</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Section Habitats -->
  <section class="py-5">
    <div class="container">
      <h1 class="mb-4">Nos Habitats</h1>
      <div class="row">
        <?php foreach ($habitats as $habitat): ?>
        <div class="col-md-4">
          <div class="card">
            <img src="<?= htmlspecialchars($habitat['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($habitat['nom']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($habitat['nom']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($habitat['description']) ?></p>
              <a href="habitat_details.php?id=<?= $habitat['id'] ?>" class="btn btn-primary">Voir les animaux</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-center text-white py-3">
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
