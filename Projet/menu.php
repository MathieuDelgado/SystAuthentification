<nav> 
    <ul>
    <?php 
        // Si on est pas connecté, on affiche le bouton de connexion, sinon ona ffiche les boutons "mon compte" et "deconnexion"
        if(!isset($_SESSION['account'])){
    ?>
    
        <li><a href="inscription.php">Inscription</a></li>
        <li><a href="login.php">Login</a></li>
<?php
} else {
    ?>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
<?php
}
//TODO si ADMIN = 1 afficher l'onglet admin du menu
?>
<li><a href="admin.php">Administration</a></li>
    </ul>
</nav>