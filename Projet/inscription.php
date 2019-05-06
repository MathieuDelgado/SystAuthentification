<?php 

// Nécessaire pour utiliser les variables de session
session_start();

// Inclusion du fichier qui contrôle si l'adresse IP a changé (anti vol de session)
require_once 'security.php';
//appel des variables
if(
    isset($_POST['name']) &&
    isset($_POST['firstname']) &&
    isset($_POST['pseudo']) &&
    isset($_POST['sex']) &&
    isset($_POST['birthdate']) &&
    isset($_POST['uPassword']) && 
    isset($_POST['confirmPassword']) &&
    isset($_POST['email']) 

){

    //bloc de verif
    
    if(!preg_match('#^[a-z]{2,100}$#i', $_POST['name'])){
        $errors[] = 'nom pas bon';
    }

    if(!preg_match('#^[a-z]{2,100}$#i', $_POST['firstname'])){
        $errors[] = 'prénom pas bon';
    }

    if(!preg_match('#^[a-z]{2,100}$#i', $_POST['pseudo'])){
        $errors[] = 'pseudo pas bon';
    }

    if(!preg_match('#^m|f$#i', $_POST['sex'])){
        $errors[] = 'sex pas bon';
    }

    if(!preg_match('#^((\d{2}(([02468][048])|([13579][26]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|([1-2][0-9])))))|(\d{2}(([02468][1235679])|([13579][01345789]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|(1[0-9])|(2[0-8]))))))(\s(((0?[1-9])|(1[0-2]))\:([0-5][0-9])((\s)|(\:([0-5][0-9])\s))([AM|PM|am|pm]{2,2})))?$#', $_POST['birthdate'])){
        $errors[] = 'date invalide';
    }

    /*TODO remettre le nombre de caracatère possible a 8 minimum*/if(!preg_match('#^.{2,300}$#', $_POST['uPassword'])){
        $errors[] = 'Password pas bon';
    }

    if($_POST['confirmPassword'] != $_POST['uPassword']){
        $errors[] = 'Confirmation password pas bon';
    }

    if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
        $errors[] = 'email pas bon';
    }



    if(!isset($errors)){ 

        $userRight = 2;

        require 'bdd.php';

        $response= $bdd->prepare("INSERT INTO users(admin, name, firstname, pseudo, sex, birthdate, inscription_date, password, email) VALUES(:uAdmin, :uName, :firstname, :pseudo, :sex, :birthdate, :inscription_date, :uPassword, :email)");
 
        $response->execute(array(
            'uAdmin'=> $userRight,
            'uName' => $_POST['name'],
            'firstname' => $_POST['firstname'],
            'pseudo' => $_POST['pseudo'],
            'sex' => $_POST['sex'],
            'birthdate' => $_POST['birthdate'],
            'inscription_date' => date('Y-m-d H:i:s'),
            'uPassword' => password_hash($_POST['uPassword'], PASSWORD_BCRYPT),  
            'email' => $_POST['email']
        ));
            // Si la dernière requête a affecté au moins une ligne, alors le compte a bien été créé, sinon erreur
            if($response->rowCount() > 0){
                $success="Vous avez bien envoyé le formulaire";
                $_SESSION['account']['pseudo'] = $_POST['pseudo'];
                $_SESSION['account']['birthdate'] = $_POST['birthdate'];
                $_SESSION['account']['email'] = $_POST['email'];
                $_SESSION['account']['ip'] = $_SERVER['REMOTE_ADDR'];
            } else {
                $errors[] = 'Problème avec la BDD';
            }

            // Fermeture de la requête
            $response->closeCursor();

 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inscription</title>
</head>
<body>
 <?php
    include 'menu.php';
    // Si $success existe, c'est que le formulaire a été traité sans erreur, donc on affiche le message de succès. Sinon dans le else, on affiche le formulaire (du coup le formulaire sera caché si le $success existe)
    if(isset($success)){
        echo '<p style="color:green;">' . $success . '</p>';


    } else {
        ?>
    <form action="inscription.php" method="POST" >
        <input type="text" name="name" placeholder="name">
        <input type="text" name="firstname" placeholder="firstname">
        <input type="text" name="pseudo" placeholder="pseudo">
        <input type="text" name="sex" placeholder="sex(m/f)">
        <input type="date" name="birthdate" placeholder="birthdate">
        <input type="text" name="uPassword" placeholder="uPassword">
        <input type="text" name="confirmPassword" placeholder="confirmPassword">
        <input type="text" name="email" placeholder="email">
        <input type="submit">
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