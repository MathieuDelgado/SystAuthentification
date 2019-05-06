<?php 

// Nécessaire pour utiliser les variables de session
session_start();

// Inclusion du fichier qui contrôle si l'adresse IP a changé (anti vol de session)
require_once 'security.php';

// Appel des variables + on vérifie que le visiteur n'est pas déjà connecté.
if(
    isset($_POST['pseudo']) &&
    isset($_POST['uPassword']) &&
    isset($_POST['confirmPassword']) &&
    !isset($_SESSION['account'])
){
    if(!preg_match('#^[a-z-]{2,100}$#i', $_POST['pseudo'])){
        $errors[] = 'pseudo pas bon';
    }

    //TODO remettre le nombre de caracatère possible a 8 minimum
    //regex qui va bien : ^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$

    if(!preg_match('#^.{2,300}$#', $_POST['uPassword'])){
        $errors[] = 'Password pas bon';

    }

    require 'bdd.php';
    $response = $bdd->prepare("SELECT password, pseudo, birthdate, email FROM users WHERE pseudo = :pseudo ");
    $response->execute(array(
        'pseudo' =>$_POST['pseudo']
    ));

    $account = $response->fetch(PDO::FETCH_ASSOC);


    if(!password_verify( $_POST['uPassword'] , $account['password'])){
        $errors[] = 'erreur password';
    }

    if(!isset($errors)) {
        $success="Vous êtes connectés ! ";
        $_SESSION['account']['pseudo'] = $account['pseudo'];
        $_SESSION['account']['birthdate'] = $account['birthdate'];
        $_SESSION['account']['email'] = $account['email'];
        $_SESSION['account']['ip'] = $_SERVER['REMOTE_ADDR'];
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
<?php
    include 'menu.php';
// Si $success existe, c'est que le formulaire a été traité sans erreur, donc on affiche le message de succès. Sinon dans le else, on affiche le formulaire (du coup le formulaire sera caché si le $success existe)
if(isset($success)){
    echo '<p style="color:green;">' . $success . '</p>';
} else {
?>
    <form action="login.php" method="POST" >
        <input type="text" name="pseudo" placeholder="pseudo">
        <input type="text" name="uPassword" placeholder="uPassword">
        <input type="text" name="confirmPassword" placeholder="confirmPassword">
        <input type="submit" value="Login">
    </form>
<?php

}

// Si il y a des erreurs (test existance du tableau $errors), alors avec un foreach on affiche toutes les erreurs une par une.
if(isset($errors)){
    foreach($errors as $error){
        echo '<p style="color:red;">' . $error . '</p>';
    }
}

?>
</body>
</html>