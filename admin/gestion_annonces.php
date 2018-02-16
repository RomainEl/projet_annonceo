<?php

require_once('../inc/init.php');
require_once('../inc/header.php');

if ( !estConnecteEtAdmin() )
{
    header('location:../connexion.php'); // si pas admin, ouste ! va voir ailleurs
    exit();
}

// Supression 
if ( isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_annonce']))
{
    $result = $pdo->prepare("SELECT photo FROM annonce WHERE id_annonce=:id_annonce"); 
    $result->execute(array(
        'id_annonce' => $_GET['id_annonce']));
    $photo_a_supprimer = $result -> fetch(PDO::FETCH_ASSOC);
    $chemin_photo = $_SERVER['DOCUMENT_ROOT'] . $photo_a_supprimer['photo'];

    if ( !empty($photo_a_supprimer['photo']) && file_exists($chemin_photo)){
        unlink($chemin_photo);
    }

    $result = $pdo->prepare("DELETE from annonce WHERE id_annonce=:id_annonce");
    $result->execute(array('id_annonce' => $_GET['id_annonce']));

    echo '<div class="alert alert-success">L\'annonce a bien été supprimée !</div>';
    $_GET['action'] = 'affichage' ; 
}

// Onglet affichage ajout/modif
echo '<ul class="nav nav-tabs">
                <li><a href="?action=affichage">Affichage des produits</a></li>
                <li><a href="?action=ajout">Ajouter un produit</a></li>
            </ul>';

        
// Enregistrement du produit en base de donnée

if ( $_POST )
{
    $photo_bdd='';
    if (isset($_POST["photo_actuelle"]))
    {
        $photo_bdd=$_POST['photo_actuelle'];
    }

    // ajouter des controles sur le format, la taille et l'extension de l'image. 

    if (!empty($_FILES['photo']['name']))
    {
        $nom_photo = $_POST['reference']. '-' . $_FILES['photo']['name'];
        $photo_bdd = RACINE_SITE . 'photo/' . $nom_photo;   
        $photo_dossier = $_SERVER['DOCUMENT_ROOT']. $photo_bdd;

        copy($_FILES['photo']['tmp_name'], $photo_dossier);
    }

    // On enregistre le produit en base

    executeRequete('REPLACE INTO produit VALUES (:id_produit,:reference,:categorie,:titre,:description,:couleur,:taille,:public,:photo,:prix,:stock)',
                    array('id_produit' => $_POST['id_produit'],
                          'reference' => $_POST['reference'],
                          'categorie' => $_POST['categorie'],
                          'titre' => $_POST['titre'],
                          'description' => $_POST['description'],
                          'couleur' => $_POST['couleur'],
                          'taille' => $_POST['taille'],
                          'public' => $_POST['public'],
                          'photo' => $photo_bdd,
                          'prix' => $_POST['prix'],
                          'stock' => $_POST['stock']
                          
                    ));
                    $contenu .= '<div class="alert alert-success>La team rocket s\'envole vers d\'autres cieux</div>';
                    $_GET['action'] = 'affichage';
}

if ( (isset($_GET['action']) && $_GET['action']=='affichage') || !isset($_GET['action']) )
{
    // affichage des produits
    echo "<h3>Affichage des produits :</h3>";


    $pdo->exec('USE annonceo');

    $resul = $pdo->query('SELECT * FROM produit');

    echo '<table class="table table-hover"><tr>';

    $nbcolonnes = $resul->columnCount();
    for( $i=0; $i < $nbcolonnes; $i++){
        $infoscolonne = $resul->getColumnMeta($i);
            echo '<th>' . $infoscolonne['name'].'</th>';

    }
    echo '<th colspan="2">Actions</th>';

    echo "</tr>";

    while ($ligne = $resul->fetch(PDO::FETCH_ASSOC))
    {
        echo "<tr>";
            foreach ($ligne as $indice => $information) {
                    if ( ($indice=='photo') && $information != '')
                    {
                        $information= '<img class="img-thumbnail" src="'.$information.'"alt="'.$ligne['titre'].'">';
                    }
                    echo "<td>$information</td>";
            }
            echo '<td><a href="?action=modifier&id_annonce=' . $ligne['id_annonce'] . '"> [Modifier] </a></td>';
            echo '<td><a href="?action=suppression&id_annonce=' . $ligne['id_annonce'] . '" onclick="return(confirm(\'Etes vous certain de vouloir supprimer ce produit : ' .$ligne['titre']. ' ?\'))">[Supprimmer]</a></td>';
        echo "</tr>";
    }
    echo "</table>";
}
// echo $contenu;

/**
 * je veux afficher un formulaire : - vide si je fais "ajout"
 *                                  - pré rempli si je fais "modifier" sur un produit
 * 
 * */

if ( isset($_GET['action']) && ( $_GET['action']=='ajout' ||  $_GET['action']=='modifier' ) ):

    if ( !empty($_GET['id_produit']) )
    {
        $resul = executeRequete("SELECT * FROM produit WHERE id_produit= :id_produit", array('id_produit' => $_GET['id_produit']));
        $produit_actuel = $resul->fetch(PDO::FETCH_ASSOC);
    }
?>
    <h3>Formulaire d'ajout ou de modification</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" id="id_produit" name='id_produit' value="<?= $produit_actuel['id_produit'] ?? 0 ?>">
        
        <label for="reference">Référence</label>
        <br>
        <input type="text" name="reference" id="reference" value="<?= $produit_actuel['reference'] ?? ''?>">
        <br>
        <label for="categorie">Catégorie</label>
        <br>
        <input type="text" name="categorie" id="categorie" value="<?= $produit_actuel['categorie'] ?? ''?>">
        <br>
        <label for="categorie">Description</label>
        <br>
        <textarea name="description" id="description"><?= $produit_actuel['description'] ?? ''?></textarea>
        <br>
        <label for="titre">Titre</label>
        <br>
        <input type="text" name="titre" id="titre" value="<?= $produit_actuel['titre'] ?? ''?>">
        <br>
        <label for="couleur">Couleur</label>
        <br>
        <input type="text" name="couleur" id="couleur" value="<?= $produit_actuel['taille'] ?? ''?>">
        <br>
        <label for="taille">Taille</label>
        <br>
        <select name="taille" id="taille">
            <option <?= (isset($produit_actuel['taille']) && $produit_actuel['taille']= 'S') ? 'selected' : '' ?> value="S">S</option>
            <option <?= (isset($produit_actuel['taille']) && $produit_actuel['taille']= 'M') ? 'selected' : '' ?> value="M">M</option>
            <option <?= (isset($produit_actuel['taille']) && $produit_actuel['taille']= 'L') ? 'selected' : '' ?> value="L">L</option>
            <option <?= (isset($produit_actuel['taille']) && $produit_actuel['taille']= 'XL') ? 'selected' : '' ?> value="XL">XL</option>
        </select>
        <br>
        <br>
        <label for="photo">Photo</label>
        <input type="file" name="photo" id="photo">
        <?php 
            if ( isset($produit_actuel['photo']))
            {
                echo '<p>Vous pouvez uploader une nouvelle photo </p>';
                echo '<img class="img-thumbnail" src="'.$produit_actuel['photo'].'" alt="'.$produit_actuel['titre'].'">';
                echo '<input type="hidden" name="photo_actuelle" value="'.$produit_actuel['photo'].'">';
                // cet input permet de remplir $_POST sur un indice "photo_actuelle" la valeur de l'url de la photo stockée en base. Ainsi, si on ne
                // charge pas de nouvelle photo, l'url actuelle sera remise en base
            }
    
        ?>
    
        <br>
        <label for="public">Public</label>
        <br>
        <select name="public">
            <option <?= (isset($produit_actuel['taille']) && $produit_actuel['taille']=='m') ? 'selected' : ''?> value="m">m</option>
            <option <?= (isset($produit_actuel['taille']) && $produit_actuel['taille']=='f') ? 'selected' : ''?> value="f">f</option>
            <option <?= (isset($produit_actuel['taille']) && $produit_actuel['taille']=='mixte') ? 'selected' : ''?> value="mixte">mixte</option>
        </select>
        <br>
    
        <label for="prix">Prix</label>
        <input class="form-control" type="text" name="prix" id="prix" value="<?= $produit_actuel['prix'] ?? '' ?>">
        <br>
    
        <label for="stock">Stock</label>
        <input class="form-control" type="text" name="stock" id="stock" value="<?= $produit_actuel['stock'] ?? '' ?>">
        <br>
    

        <input id='bouton1' type='submit' value='Ajouter le produit' class='btn btn-primary btn-block'/>
        
    </form>
<?php
endif;
require_once('../inc/footer.php');

?>