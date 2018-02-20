<?php

require_once('inc/init.php');

if(!estConnecte())
{
    header('location:connexion.php');
    exit;
}


if($_POST){

    $result = $pdo->prepare("REPLACE INTO membre VALUES(:id_membre,:pseudo,:mdp,:nom,:prenom,:telephone,:email,:civilite,:statut,NOW()");
    
    $params = $_POST['mdp'];
    if(!empty($params))
    {
        $result->execute(array('id_membre'=>$_POST['id_membre'],
                            'pseudo'=>$_POST ['pseudo'],
                            'mdp' =>$_POST['mdp'],
                            'nom'=> $params,
                            'prenom'=>$_POST['prenom'],
                            'telephone'=>$_POST['telephone'],
                            'email'=>$_POST['email'],
                            'civilite'=>$_POST['civilite'],
                            'statut'=>$_POST['statut']));
    }
    else{
        $result->execute(array('id_membre'=>$_POST['id_membre'],
                            'pseudo'=>$_POST ['pseudo'],
                            'mdp' =>$_POST['mdp'],
                            'nom'=> $_SESSION['membre']['mdp'],
                            'prenom'=>$_POST['prenom'],
                            'telephone'=>$_POST['telephone'],
                            'email'=>$_POST['email'],
                            'civilite'=>$_POST['civilite'],
                            'statut'=>$_POST['statut']));
    }
    

    $contenu.= '<div class="alert alert-success">Vos informations ont été modifiées</div>
    <div><h3>Vos informations de profil</h3>
    <p>Pseudo : '.$_POST['pseudo'].'</p>
    <p>Email : '.$_POST['email'].'</p>
    <p>Nom, Prénom : '.$_POST['nom'].', '.$_POST['prenom'].'</p>
    <p>Téléphone: '.$_POST['telephone'].'</p>
    <p>Civilité: '.$_POST['civilite'].'</p></div>
    <a href="?action=modifier" class="btn btn-default" type="submit">Modifier vos informations</a>';                         

}

if (!isset($_GET['action']) || $_GET['action'] != 'modifier')
{
    $contenu .= '<h2>Bonjour '.ucfirst($_SESSION['membre']['pseudo']).'</h2>';

    if($_SESSION['membre']['statut'] == 1 )
    {
        $contenu.= '<p>Vous êtes connecté en tant qu\'administrateur</p>';

        $contenu.= '<div><h3>Vos informations de profil</h3>
            <p>Pseudo : '.$_SESSION['membre']['pseudo'].'</p>
            <p>Email : '.$_SESSION['membre']['email'].'</p>
            <p>Nom, Prénom : '.$_SESSION['membre']['nom'].', '.$_SESSION['membre']['prenom'].'</p>
            <p>Téléphone: '.$_SESSION['membre']['telephone'].'</p>
            <p>Civilité: '.$_SESSION['membre']['civilite'].'</p></div>
            <a href="?action=modifier" class="btn btn-default" type="submit">Modifier vos informations</a>';
    }
    else{
        $contenu.= '<div><h3>Vos informations de profil</h3>
        <p>Pseudo : '.$_SESSION['membre']['pseudo'].'</p>
        <p>Email : '.$_SESSION['membre']['email'].'</p>
        <p>Nom, Prénom : '.$_SESSION['membre']['nom'].', '.$_SESSION['membre']['prenom'].'</p>
        <p>Téléphone: '.$_SESSION['membre']['telephone'].'</p>
        <p>Civilité: '.$_SESSION['membre']['civilite'].'</p></div>
        <a href="?action=modifier" class="btn btn-default" type="submit">Modifier vos informations</a>';
    }
}


require_once('inc/header.php');
echo $contenu;
if (isset($_GET['action']) && $_GET['action'] == 'modifier'){
    if(!$_POST){
?>

<h2>Modifier vos données</h2>
<form novalidate class="form-horizontal" method="post" action="">
    <div class="form-group">
        <input type="hidden" name="id_membre" id="id_membre" value="<?= $_SESSION['membre']['id_membre'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" class="form-control" id="pseudo" placeholder="Pseudo" value="<?= $_SESSION['membre']['pseudo'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="mdp">Password</label>
        <input type="password" name="mdp" class="form-control" id="mdp" placeholder="Password" value="<?= $_SESSION['membre']['mdp'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom" value="<?= $_SESSION['membre']['nom'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="prenom">Prenom</label>
        <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Prenom" value="<?= $_SESSION['membre']['prenom'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label for="telephone">Téléphone</label>
        <input type="text" name="telephone" id="telephone" class="form-control" placeholder="Téléphone" value="<?= $_SESSION['membre']['telephone'] ?? '' ?>">
    </div>
    <div class="form-group">
        <div class="col-sm-1">
            <label for="nom">Civilité</label>
        </div>
        <div class="col-sm-4">
            <input type="radio" name="civilite" class="form-control" value="f" <?=((isset($_SESSION['membre']['civilite']) 
               && $_SESSION['membre']['civilite'] == 'f')) ? 'checked' : ''?>>Femme
            <input type="radio" name="civilite" class="form-control" value="m" <?=((isset($_SESSION['membre']['civilite']) 
            && $_SESSION['membre']['civilite'] == 'm')) || !isset($_SESSION['membre']['civilite']) ? 'checked' : ''?>>Homme
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="<?= $_SESSION['membre']['email'] ?? '' ?>">
    </div>
    <div class="form-group">
        <input type="hidden" name="statut" id="statut" value="<?= $_SESSION['membre']['statut'] ?? '' ?>">
    </div>

    <button type="submit" class="btn btn-default">Valider</button>
</form>
<?php
    }
}
require_once('inc/footer.php');
?>
