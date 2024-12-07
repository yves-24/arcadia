<?php
require 'vendor/autoload.php';

use MongoDB\Client;

// Connexion à MongoDB
$uri = "mongodb+srv://arcadia:<arcadia>@cluster0.kvuaz.mongodb.net/zoo_arcadia?retryWrites=true&w=majority";
$client = new Client($uri);
$database = $client->selectDatabase('zoo_arcadia');
$collection = $database->selectCollection('consultations');

// ID de l'animal (Remplacez par un ID réel)
$animal_id = "65bcd12f45a67e12345f6789";
$animal_name = "Médor";

try {
    // Incrémenter le compteur de consultations de l'animal
    $result = $collection->updateOne(
        ['animal_id' => $animal_id],
        ['$inc' => ['consultations' => 1], '$set' => ['animal_name' => $animal_name]],
        ['upsert' => true]
    );

    echo "Consultation mise à jour avec succès !";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
