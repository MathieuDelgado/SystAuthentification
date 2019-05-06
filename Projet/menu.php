<nav> 
    <ul>
    <?php 
    if(isset($_SESSION['account'])){
        include 'bdd.php';

        $response = $bdd->prepare("SELECT admin FROM users WHERE pseudo = :pseudo");
        $response-> execute(array(
            'pseudo' => $_SESSION['account']['pseudo']
        ));
        $adminStatut = $response->fetch(PDO::FETCH_ASSOC);

           if ($adminStatut['admin'] == 1){
    ?>
   <li><a href="admin.php">Administration</a></li>
   
    <?php 
            }
    }

    // Si on est pas connecté, on affiche le bouton de connexion, sinon ona ffiche les boutons "mon compte" et "deconnexion"
    if(!isset($_SESSION['account'])){

    ?>

    <li><a href="inscription.php">Inscription</a></li>
    <li><a href="login.php">Login</a></li>

    <?php
    }else {
    ?>

        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>

    <?php 
    }
    ?>

    </ul>
</nav>