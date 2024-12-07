<!DOCTYPE html>
<html lang="en">

<head>
    <title>php et mysql</title>
    <meta charset="UTF-8"/>
 </head>
 
    <body>
    <?php
        $serveur = "localhost";
        $login = "root";
        $pass = "root";

        try{
        $connexion = new PDO("mysql:host=$serveur;dbname=test", $login, $pass);
        $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 'connexion à la base de données réussie';
        }
        
        catch(PDOException $e){
            echo 'Echec de la connexion : ' .$e->getMessage();
        }
    ?>  
</body>
</html>