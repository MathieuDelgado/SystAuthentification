<?php 

try{
    $bdd = new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root','');
} catch(Exception $e){
    die('Erreur de connexion à la BDD : ' . $e->getMessage());
}


$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);