<?php
require 'vendor/autoload.php';

use MongoDB\Client;

// Chaîne de connexion avec mot de passe mis à jour
$uri = "mongodb+srv://arcadia:arcadia1@cluster0.kvuaz.mongodb.net/zoo_arcadia?retryWrites=true&w=majority&tls=true&tlsAllowInvalidCertificates=true";

try {
    // Connexion au client MongoDB
    $client = new Client($uri);
    $database = $client->selectDatabase('zoo_arcadia');
    $collection = $database->selectCollection('consultations');

    // Récupération des paramètres depuis l'URL
    $animal_id = $_GET['animal_id'] ?? null;
    $animal_name = $_GET['animal_name'] ?? null;

    if ($animal_id && $animal_name) {
        // Mise à jour ou insertion du document avec l'incrément du compteur
        $result = $collection->updateOne(
            ['animal_id' => $animal_id],
            [
                '$inc' => ['consultations' => 1],
                '$setOnInsert' => ['animal_name' => $animal_name]
            ],
            ['upsert' => true]
        );

        echo "Consultation mise à jour avec succès!";
    } else {
        echo "Erreur : Paramètres manquants.";
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
