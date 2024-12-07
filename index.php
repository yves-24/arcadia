





<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'php/connection.php';

// Récupérer les habitats depuis la base de données
$query_habitats = $pdo->query("SELECT * FROM habitat");
$habitats = $query_habitats->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les services depuis la base de données
$query_services = $pdo->query("SELECT * FROM service");
$services = $query_services->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les avis validés depuis la base de données
$query_avis = $pdo->query("SELECT pseudo, commentaire FROM avis WHERE valide = 1");
$avis = $query_avis->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Zoo Arcadia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
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
                <!-- Ajouter un bouton pour la connexion -->
                <li class="nav-item">
                    <a class="btn btn-primary" href="login.php">Connexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

  <!-- Section Présentation -->
  <header class="text-center bg-light py-5">
    <div class="container">
      <h1>Bienvenue au Zoo Arcadia</h1>
      <p>Explorez nos habitats naturels et découvrez nos animaux fascinants.</p>
      <img src="images/zoo.jpg" alt="Photo du Zoo" class="img-fluid rounded">
    </div>
  </header>

  <!-- Section Habitats -->
  <section id="habitats" class="py-5">
    <div class="container">
      <h2>Nos Habitats</h2>
      <div class="row">
        <?php foreach ($habitats as $habitat): ?>
        <div class="col-md-4">
          <div class="card">
            <img src="<?= $habitat['image_url'] ?>" class="card-img-top" alt="<?= htmlspecialchars($habitat['nom']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($habitat['nom']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($habitat['description']) ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Section Services -->
  <section id="services" class="py-5 bg-light">
    <div class="container">
      <h2>Nos Services</h2>
      <div class="row">
        <?php foreach ($services as $service): ?>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($service['nom']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($service['description']) ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Section Avis -->
  <section id="avis" class="py-5">
    <div class="container">
      <h2>Ce que disent nos visiteurs</h2>
      <div class="row">
        <?php foreach ($avis as $a): ?>
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($a['pseudo']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($a['commentaire']) ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="text-center">
        <a href="avis.php" class="btn btn-primary">Laisser un avis</a>
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
<a href="increment_consultation.php?animal_id=65bcd12f45a67e12345f6789&animal_name=Médor">
    Voir Médor
</a>











