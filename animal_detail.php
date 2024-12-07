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

// Vérifier si l'ID de l'animal est passé dans l'URL
if (!isset($_GET['animal_id'])) {
    die("Erreur : aucun animal spécifié.");
}

$animal_id = $_GET['animal_id'];

// Récupérer les informations de l'animal depuis MySQL
$query_animal = $pdo->prepare("SELECT * FROM animal WHERE id = :animal_id");
$query_animal->execute(['animal_id' => $animal_id]);
$animal = $query_animal->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    die("Erreur : animal non trouvé.");
}

// Incrémenter le compteur de consultations dans MongoDB
$animal_name = $animal['prenom'];
$collection->updateOne(
    ['animal_id' => $animal_id],
    ['$inc' => ['consultations' => 1], '$setOnInsert' => ['animal_name' => $animal_name]],
    ['upsert' => true]
);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Détails de l'Animal</h1>
        <div class="card">
            <img src="<?= htmlspecialchars($animal['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($animal['prenom']) ?>">
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($animal['prenom']) ?></h3>
                <p><strong>Race :</strong> <?= htmlspecialchars($animal['race']) ?></p>
                <p><strong>État de Santé :</strong> <?= htmlspecialchars($animal['etat']) ?></p>
            </div>
        </div>
        <a href="habitat_detail.php?id=<?= htmlspecialchars($_GET['habitat_id']) ?>" class="btn btn-secondary mt-3">Retour à l'Habitat</a>
    </div>

    <footer class="bg-dark text-center text-white py-3 mt-5">
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>
</body>
</html>
