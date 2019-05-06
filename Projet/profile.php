<?php 

// Nécessaire pour utiliser les variables de session
session_start();

// Inclusion du fichier qui contrôle si l'adresse IP a changé (anti vol de session)
require_once 'security.php';

//appel des variables
if (
    isset($_POST['title']) &&
    isset($_POST['subject']) &&
    isset($_POST['text']) &&
    isset($_SESSION)
){
   //bloc de controle

   //verifiaction titre
   if(!preg_match('#^.{2,300}$#', $_POST['title'])){
       $errors[] = 'incorrect title';
   }

   //verification sujet
   if(!preg_match('#^.{2,300}$#', $_POST['subject'])){
       $errors[] = 'incorrect subject';
   }

   //verifiaction texte
   if(!preg_match('#^.{8,10000}$#', $_POST['text'])){
       $errors[] = 'invalid text';
   }

   //gestion des erreurs
   if(!isset($errors)){

    //si pas d'erreur on contact la bdd pour y inclure l'article
    require 'bdd.php';
    //on recupère l'id lié au pseudo de la session
    $response = $bdd->prepare("SELECT id FROM users WHERE pseudo = :pseudo");
    $response-> execute(array(
        'pseudo' => $_SESSION['account']['pseudo']
    ));
    $pseudoId = $response->fetch(PDO::FETCH_ASSOC);

    //on inclu l'article a la bdd
    $response= $bdd->prepare("INSERT INTO articles (title, subject, text, publication_date, author) VALUES(:title, :subject, :text, :publication_date, :author)");

    $response->execute(array(
        'title' => $_POST['title'],
        'subject' => $_POST['subject'],
        'text' => $_POST['text'],
        'publication_date' => date('Y-m-d H:i:s'),
        'author' => $pseudoId['id']
    ));
        // Si la dernière requête a affecté au moins une ligne, alors le compte a bien été créé, sinon erreur
        if($response->rowCount() > 0){
            $success = true;
        } else {
            $errors[] = 'Problème avec la BDD';
        }

        // Fermeture de la requête
        $response->closeCursor();

       $success = 'your article has been created !';

   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
</head>
<body>

<?php 

include 'menu.php';

echo '<p>Hello and welcome, ' . htmlspecialchars($_SESSION['account']['pseudo']) . ' you are born in ' . htmlspecialchars($_SESSION['account']['birthdate']) . '.</p>
 <p> your email is: ' . htmlspecialchars($_SESSION['account']['email']) . '</p>';

?>

    <h1>Write an article</h1>
    <form action="profile.php" method="POST">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" placeholder="Title" require>
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" placeholder="Subject" require>
        <label for="text">Text</label>
        <input type="text" name="text" id="text" placeholder="Your text..." require>
        <input type="submit" value="Create my article !">
    </form>
</body>
</html>