<?php
require 'vendor/autoload.php';

use MongoDB\Client;

// Remplacez par le mot de passe réel
$uri = "mongodb+srv://arcadia:<arcadia>@cluster0.kvuaz.mongodb.net/zoo_arcadia?retryWrites=true&w=majority";

$options = [
    'tls' => true,
    'tlsAllowInvalidCertificates' => false, // Assurez-vous que les certificats sont valides
    'serverSelectionTryOnce' => false,
    'serverSelectionTimeoutMS' => 5000
];

try {
    $client = new Client($uri, $options);
    $database = $client->selectDatabase('zoo_arcadia');
    $collection = $database->selectCollection('consultations');

    echo "Connexion réussie à MongoDB!";
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

