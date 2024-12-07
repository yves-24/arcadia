<?php
include '../php/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $role = 'administrateur';

    try {
        $query = $pdo->prepare("INSERT INTO utilisateur (email, password, role) VALUES (:email, :password, :role)");
        $query->execute([
            ':email' => $email,
            ':password' => $hashed_password,
            ':role' => $role
        ]);
        echo "Administrateur ajouté avec succès !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Administrateur</title>
</head>
<body>
    <form action="" method="POST">
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
