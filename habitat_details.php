<?php
// Inclure le fichier de connexion à la base de données MySQL
include 'php/connection.php';

// Inclure le chargement automatique de Composer pour MongoDB
require 'vendor/autoload.php';

use MongoDB\Client;

// Connexion à MongoDB
$uri = "mongodb+srv://arcadia:arcadia1@cluster0.kvuaz.mongodb.net/zoo_arcadia?retryWrites=true&w=majority&tls=true&tlsAllowInvalidCertificates=true";
$client = new Client($uri);
$database = $client->selectDatabase('zoo_arcadia');
$collection = $database->selectCollection('consultations');

// Vérifier si un ID d'habitat est passé dans l'URL
if (!isset($_GET['id'])) {
    die("Erreur : aucun habitat spécifié.");
}

$habitat_id = (int) $_GET['id'];

// Récupérer les informations de l'habitat
$query_habitat = $pdo->prepare("SELECT * FROM habitat WHERE id = :id");
$query_habitat->execute(['id' => $habitat_id]);
$habitat = $query_habitat->fetch(PDO::FETCH_ASSOC);

if (!$habitat) {
    die("Erreur : habitat non trouvé.");
}

// Récupérer les animaux associés à cet habitat
$query_animaux = $pdo->prepare("SELECT * FROM animal WHERE habitat_id = :habitat_id");
$query_animaux->execute(['habitat_id' => $habitat_id]);
$animaux = $query_animaux->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les détails de l'animal si l'ID de l'animal est passé en paramètre
$selected_animal = null;
if (isset($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];

    // Récupérer les détails de l'animal depuis MySQL
    $query_animal = $pdo->prepare("SELECT * FROM animal WHERE id = :animal_id");
    $query_animal->execute(['animal_id' => $animal_id]);
    $selected_animal = $query_animal->fetch(PDO::FETCH_ASSOC);

    // Incrémenter le compteur de consultations dans MongoDB
    if ($selected_animal) {
        $animal_name = $selected_animal['prenom'];

        $collection->updateOne(
            ['animal_id' => $animal_id],
            ['$inc' => ['consultations' => 1], '$setOnInsert' => ['animal_name' => $animal_name]],
            ['upsert' => true]
        );
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Habitat : <?= htmlspecialchars($habitat['nom']) ?> - Zoo Arcadia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">Zoo Arcadia</a>
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

  <!-- Section Habitat -->
  <section class="py-5">
    <div class="container">
      <h1><?= htmlspecialchars($habitat['nom']) ?></h1>
      <p><?= htmlspecialchars($habitat['description']) ?></p>
      <img src="<?= htmlspecialchars($habitat['image_url']) ?>" alt="<?= htmlspecialchars($habitat['nom']) ?>" class="img-fluid rounded">
    </div>
  </section>

  <!-- Section Animaux -->
  <section class="py-5 bg-light">
    <div class="container">
      <h2>Animaux dans cet habitat</h2>
      <div class="row">
        <?php foreach ($animaux as $animal): ?>
        <div class="col-md-4">
          <div class="card mb-3">
            <img src="<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($animal['prenom']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($animal['prenom']) ?> (<?= htmlspecialchars($animal['race']) ?>)</h5>
              <p class="card-text">État : <?= htmlspecialchars($animal['etat']) ?></p>
              <a href="?id=<?= $habitat_id ?>&animal_id=<?= $animal['id'] ?>" class="btn btn-primary">Voir l'Animal</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Section Détails de l'Animal Sélectionné -->
  <?php if ($selected_animal): ?>
  <section class="py-5">
    <div class="container">
      <h2>Détails de l'Animal</h2>
      <div class="card">
        <div class="card-body">
          <h3><?= htmlspecialchars($selected_animal['prenom']) ?></h3>
          <p><strong>Race :</strong> <?= htmlspecialchars($selected_animal['race']) ?></p>
          <p><strong>État de Santé :</strong> <?= htmlspecialchars($selected_animal['etat']) ?></p>
          <img src="<?= htmlspecialchars($selected_animal['image_url']) ?>" alt="<?= htmlspecialchars($selected_animal['prenom']) ?>" class="img-fluid rounded">
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- Footer -->
  <footer class="bg-dark text-center text-white py-3">
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

