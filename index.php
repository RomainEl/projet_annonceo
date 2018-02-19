<?php 

require_once('inc/init.php');
require_once('inc/header.php');

// Génération des catégories pour alimenter le contenu gauche
$categories = $pdo->prepare("SELECT DISTINCT titre, id_categorie FROM categorie ORDER BY titre");
$categories->execute(array());

$contenu_gauche .= '<p class="lead">Catégories</p>';
    
             
$contenu_gauche .= '<form method="post" action="" >
                        <select name="categorie">';
                            while ( $cat = $categories->fetch(PDO::FETCH_ASSOC))
                                {
                                    $contenu_gauche .= '<option value="'.$cat['id_categorie'].'">'.$cat['titre'].'</option>';
                                
                                }
$contenu_gauche .=  '</select>';

$villes = $pdo->prepare("SELECT DISTINCT ville FROM annonce ORDER BY ville");
$villes->execute(array());

$contenu_gauche .= '<p class="lead">Villes</p>';
    
             
$contenu_gauche .='<select name="ville">';
                            while ( $ville = $villes->fetch(PDO::FETCH_ASSOC))
                                {
                                    $contenu_gauche .= '<option value="'.$ville['ville'].'">'.$ville['ville'].'</option>';
                                
                                }
$contenu_gauche .=                    '</select>';

$contenu_gauche .= '<p class="lead">Membres</p>';

    $membres = $pdo->prepare("SELECT DISTINCT prenom, id_membre FROM membre ORDER BY prenom");
    $membres->execute(array());
    
$contenu_gauche .=          '<select name="membre">';
                            while ( $membre = $membres->fetch(PDO::FETCH_ASSOC))
                                {
                                    $contenu_gauche .= '<option value="'.$membre['id_membre'].'">'.$membre['prenom'].'</option>';
                                
                                }
$contenu_gauche .=           '</select>';


$contenu_gauche .=    '<p class="lead">Prix</p>';


$contenu_gauche .= '<input type="submit" name = "submit" class="btn btn-pripary">';    



$contenu_gauche .= '</form>';


// Partie droite 




$contenu_droite .= '<h2>Annonces</h2>';

$donnees = $pdo->prepare('SELECT a.*,m.prenom FROM annonce a, membre m WHERE a.membre_id=m.id_membre');
$donnees->execute(array());


while ( $annonce = $donnees->fetch(PDO::FETCH_ASSOC))
{

    $contenu_droite .=  '<div class="thumbnail vignette">
                <a href="fiche_annonce.php?id_annonce='.$annonce['id_annonce'].'">
                <img src="'.$annonce['photo'].'" class="img-responsive"></a>
                <div class="caption">
                    <h4 class="pull-right">'.$annonce['prix'].' €</h4>
                    <h4><a href="fiche_annonce.php?id_annonce='.$annonce['id_annonce'].'">'.$annonce['titre'].'</a></h4>
                    <p>'.$annonce['description_courte'].'</p>
                    <p>'.$annonce['pays'].'-'.$annonce['ville'].'</p>
                    <p>Annonce proposée par '.$annonce['prenom'].'
                </div>
            </div>';        
}

?>

<div class="row">
    <div class="col-md-3">
        <?= $contenu_gauche ?>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?= $contenu_droite ?>
        </div>
    </div>
</div>
<?php
require_once('inc/footer.php');


