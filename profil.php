<?php

require_once('inc/init.php');

if(!estConnecte())
{
    header('location:connexion.php');
    exit;
}
if (!isset($_GET['action']) || $_GET['action'] != 'modifier')
{
    $contenu .= '<h2>Bonjour '.ucfirst($_SESSION['membre']['pseudo']).'</h2>';

    if($_SESSION['membre']['statut'] == 1 )
    {
        $contenu.= '<p>Vous êtes connecté en tant qu\'administrateur</p>';

        $contenu.= '<div><h3>Vos informations de profil</h3>
            <p>Email : '.$_SESSION['membre']['email'].'</p>
            <p>Nom, Prénom : '.$_SESSION['membre']['nom'].', '.$_SESSION['membre']['prenom'].'</p>
            <p>Téléphone: '.$_SESSION['membre']['telephone'].'</p>
            <p>Civilité: '.$_SESSION['membre']['civilite'].'</p></div>';
    }
    else{
        $contenu.= '<div><h3>Vos informations de profil</h3>
        <p>Email : '.$_SESSION['membre']['email'].'</p>
        <p>Nom, Prénom : '.$_SESSION['membre']['nom'].', '.$_SESSION['membre']['prenom'].'</p>
        <p>Téléphone: '.$_SESSION['membre']['telephone'].'</p>
        <p>Civilité: '.$_SESSION['membre']['civilite'].'</p></div>
        <a href="?action=modifier" class="btn btn-default" type="submit">Modifier vos informations</a>';
    }
}
require_once('inc/header.php');
echo $contenu;
if ($_GET['action'] == 'modifier'){
?>

<h2>Modifier vos données</h2>
<form novalidate class="form-horizontal" method="post" action="">
    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" class="form-control" id="pseudo" placeholder="Pseudo" value="<?= $_POST['pseudo'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="mdp">Password</label>
        <input type="password" name="mdp" class="form-control" id="mdp" placeholder="Password" value="<?= $_POST['password'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom" value="<?= $_POST['nom'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="prenom">Prenom</label>
        <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Prenom" value="<?= $_POST['prenom'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="telephone">Téléphone</label>
        <input type="text" name="telephone" id="telephone" class="form-control" placeholder="Téléphone" value="<?= $_POST['telephone'] ?? '' ?>">
    </div>
    <div class="form-group">
        <div class="col-sm-1">
            <label for="nom">Civilité</label>
        </div>
        <div class="col-sm-4">
            <input type="radio" name="civilite" class="form-control" value="f" <?=((isset($_POST['civilite']) 
               && $_POST['civilite'] == 'f')) ? 'checked' : ''?>>Femme
            <input type="radio" name="civilite" class="form-control" value="m" <?=((isset($_POST['civilite']) 
            && $_POST['civilite'] == 'm')) || !isset($_POST['civilite']) ? 'checked' : ''?>>Homme
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="<?= $_POST['email'] ?? '' ?>">
    </div>
    <button type="submit" class="btn btn-default">Valider</button>
</form>
<?php
}
require_once('inc/footer.php');
?>
