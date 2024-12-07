<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        include_once("visiteur.class.php");
        
        $visiteur1 = new Visiteur;
        $visiteur2 = new Visiteur;
      
        $visiteur1->set_prenom("Pierre");
        $visiteur2->set_prenom("Victor");
      
        echo "Bonjour". $visiteur1->get_prenom()."<br>";
        echo "Bonjour". $visiteur2->get_prenom()."<br>";
      ?>
</body>
</html>