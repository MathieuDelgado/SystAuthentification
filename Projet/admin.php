<?php 
// Nécessaire pour utiliser les variables de session
session_start();

// Inclusion du fichier qui contrôle si l'adresse IP a changé (anti vol de session)
require_once 'security.php';

require 'bdd.php';

$response = $bdd->prepare("SELECT id, name, pseudo, email FROM users");

$response->execute(array());


$accounts = $response->fetchAll(PDO::FETCH_ASSOC);
$response->closeCursor();
if(count($accounts) == 0){
    $error = 'Aucun compte à afficher';
    
}

// require 'bdd.php';
// $response = $bdd->prepare("DELETE FROM users WHERE  ");
// $response->execute(array(
//     'pseudo' =>$_POST['suppr']
// ));

//$account = $response->fetch(PDO::FETCH_ASSOC);

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
    <?php include 'menu.php';  ?>
    <table>
    <tr>
        <th>id</th>
        <th>nom</th>
        <th>pseudo</th>
        <th>email</th>
    </tr>
    <?php
        // Pour chaque fruit dans $fruits, on crée une nouvelle ligne dans le tableau
    if(!isset($error)){  
        echo "<form action='delete.php' method='POST'>";
       
        foreach($accounts as $account){
            echo '
            <tr>
                <td>' . ($account['id']) . ' </td>
                <td>' . htmlspecialchars($account['name']) . '</td>
                <td>' . htmlspecialchars($account['pseudo']) . '</td>
                <td>' . htmlspecialchars($account['email']) . '</td>
                <td>' ?> 
                <a href="delete.php"><input type="submit" value="Terminate"></a> 
                 <?php
                              
                 '</td>
           </tr>';
        }

    }else{
            echo '<p style="color:red;">' . $error . '</p>';
        }

        ?>
    </table>

</body>
</html>