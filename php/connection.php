<?php
$host = '127.0.0.1'; // Adresse du serveur MySQL
$dbname = 'comaojtn_aracadiap'; // Nom de la base de données
$username = 'comaojtn_arcadiap1988'; // Nom d'utilisateur MySQL
$password = 'arcadiap1988arcadiap1988'; // Mot de passe MySQL
$port = '3306'; // Port MySQL (3306 par défaut)

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
