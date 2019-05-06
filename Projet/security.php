<?php

// A chaque chargement de page, ce fichier sera lu car il est inclu dans toutes les pages du site.

// Si on est connecté
if(isset($_SESSION['account'])){

    // On vérifie si l'adresse IP actuelle est la même que celle enregistrée en session lors de la connexion. Si celle ci diffère, alors on détruit la session pour éviter un vol de session
    if($_SESSION['account']['ip'] != $_SERVER['REMOTE_ADDR']){
        session_destroy();
    }

}
?>