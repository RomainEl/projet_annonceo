<?php

require_once('inc/init.php');
$inscription= false;
if($_POST){
    //Je poste mon formulaire d'inscription

    //controles sur les champs
    $champs_vides = 0;
    foreach ($_POST as $indice => $valeur)
    {
        if(empty($valeur))
        {
            $champs_vides++;
        }
    }
        if($champs_vides > 0)
        {
            $contenu .= '<div class="alert alert-danger">Il y a '.$champs_vides.' information(s) manquante(s)</div>';
        }
    

    //vérifier qu'une chaine contient des caractères autorisés
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]{3,15}$#',$_POST['pseudo']);
    $verif_telephone = preg_match('#[0-9]{10}$#',$_POST['telephone']); 
    //expression régulière
    /*
        -je délimite l'expression par le symbole # debut et fin
        - ^ signifie "commence par tout ce qui suit"
        - $ signifie "finit par tout ce qui précède"
        - [] pour délimiter les intervalles (ici de a à z, de A à Z, de 0 à 9, et on ajoute ".","_" ou "-")
        - le + pour dire que la chaine peut faire de 1 à n caractères
            + équivalent de {1,}
            ? équivalent de {0,1}
            * équivalent de {0,}
            {5} 5 précisément
            {3,15} de 3 à 15 caractères
    */
    if (!$verif_caractere){
        $contenu .= '<div class="alert alert-danger">Le pseudo doit contenir 3 à 15 caractères (lettres de a à Z, chiffres de 0 à 9, _.-)</div>';
    }

    if (!$verif_telephone){
        $contenu .= '<div class="alert alert-danger">Le numéro de téléphone doit contenir 10 chiffres de 0 à 9</div>';
    }

    if($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f'){
        $contenu .= '<div class= alert alert-danger">De quel genre etes vous donc?</div>';
    }


    //Astuce de controle d'email avec filter_var
    if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
    {
        $contenu .= '<div class="alert alert-danger">Adresse mail invalide</div>';
    }

    //si tout va bien
    //je controle que le pseudo n'existe pas déjà dans la table
    //sinon j'invite l'internaute à changer de pseudo

    $membre = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo",array('pseudo' => $_POST['pseudo']));
    if($membre -> rowCount() > 0)
    {
        $contenu .= '<div class= alert alert-danger">Ce pseudonyme est déjà pris. Merci d\'en choisir un autre</div>';
    }

    //si tout va bien 
    //j'insère le nouveau membre dans la table membre (avec statut = 0)
    //je mets $inscription à true
    if(empty($contenu))
    {
        $result= $pdo->prepare("INSERT INTO membre VALUES (NULL,:pseudo,:mdp,:nom,:prenom,:telephone,:email,:civilite,0,NOW())");

        $result->execute(array('pseudo' => $_POST['pseudo'],
                'mdp' => MD5($_POST['mdp']),
                'nom' => $_POST['nom'], 
                'prenom'=> $_POST['prenom'],
                'telephone' => $_POST['telephone'],
                'email'=> $_POST['email'],
                'civilite'=> $_POST['civilite']
                ));
        $contenu .= '<div class="alert alert-success">Vous êtes incrit à notre site. <a href="connexion.php">Cliquez ici pour vous connecter</a></div>';
        $inscription = true;
    }

}
require_once('inc/header.php');

echo $contenu;
if(!$inscription){
?>
<h2>Formulaire d'inscription</h2>
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
    <button type="submit" class="btn btn-default">S'inscrire</button>
</form>

<?php
}

require_once('inc/footer.php');