<?php

require_once('inc/init.php');


// traitement
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    //session_destroy();
    unset($_SESSION['membre']);
    //pour conserver le panier à la déconnexion;
}
if(estConnecte()){
    header('location:index.php'); // renvoie un entete au client pour demander la page profil
    exit(); //puis quitte le script
}
if(estConnecteEtAdmin()){
    header('location:admin/gestion.php');
    exit();
}
if($_POST) // if (!empty($_POST))
{
    $motdepassecrypte = md5($_POST['mdp']); //je crypte le mot de passe saisi pour le comparer à la version cryptée du mdp enregistré en base
    
    // requete de selection pour vérifier que le membre existe et qu'il a saisi correctement ses identifiants
    $sql   ="SELECT * FROM membre WHERE pseudo=:pseudo AND mdp=:mdp";
    $resul = executeRequete($sql, array ('pseudo' => $_POST['pseudo'],
                                          'mdp'   => $motdepassecrypte
                                        ));

    if($resul->rowCount() == 1)
    {
        //si j'ai un résultat égal à 1 c'est que j'ai trouvé un membre qui a ce login et ce mdp
        $membre = $resul->fetch(PDO::FETCH_ASSOC);
        $_SESSION['membre'] = $membre;
        header('location:profil.php');
        exit();
    }
    else
    {
        $contenu .= '<div class="bg-danger">Erreur sur les identifiants</div>';
    }

}

require_once('inc/header.php');
echo $contenu;
?>
<!-- créer le formulaire de connexion -->
<h2 class="col-sm-offset-1">Connectez vous!</h2>
<form  class="form-horizontal" method="post" action="">
    <div class="form-group">
        <label for="pseudo" class="col-sm-1 col-sm-offset-4">Pseudo</label>
        <div class="col-sm-3">
            <input type="text"  class ="form-control" name="pseudo" id="pseudo" placeholder="Saisissez votre pseudo" required>
        </div>
    </div>

    <div class="form-group">
    <label for="mdp" class="col-sm-1 col-sm-offset-4">Mot de passe</label>
    <div class="col-sm-3">
        <input type="password" class="form-control" name="mdp" id="mdp" placeholder="***" required>
    </div>
    <br>
    <br>
    <br>
    <button type="submit" class=" col-sm-offset-6 btn btn-primary">Connexion</button>  
</form>

