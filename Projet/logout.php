<?php 

// Nécessaire pour utiliser les variables de session
session_start();

// Inclusion du fichier qui contrôle si l'adresse IP a changé (anti vol de session)
require_once 'security.php';





// Si on est connecté, on détruit account dans la session (les variables n'existant plus on considère cet état comme "déconnecté"), sinon on crée une erreur car la personne n'est pas connectée et ne peut donc pas se déconnecter
if(isset($_SESSION['account'])){
    unset($_SESSION['account']);
} else {
    $error = '<p style="color:red;">Vous devez être connecté pour accèder à cette page !</p>';
}

    // Si $error existe, on l'affiche, sinon on affiche le succès de la déconnexion
    if(isset($error)){
        echo $error;
    } else {
        echo '<p style="color:green;">Vous êtes bien déconnecté !</p>';
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php 

include 'menu.php';

?>
    
</body>
</html>