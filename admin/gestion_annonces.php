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
                <li><a href="?action=affichage">Affichage des annonces</a></li>
            </ul>';

        
// Enregistrement du produit en base de donnée

if ( $_POST )
{
    // On enregistre le produit en base

    $titre = htmlspecialchars($_POST['titre']);	
    $desc_c = htmlspecialchars($_POST['desc_c'], ENT_QUOTES);
    $desc_l = htmlspecialchars($_POST['desc_l'], ENT_QUOTES);
    $prix = htmlspecialchars($_POST['prix'], ENT_QUOTES);
    $pays = htmlspecialchars($_POST['pays'], ENT_QUOTES);
    $ville = htmlspecialchars($_POST['ville'], ENT_QUOTES);
    $adresse = htmlspecialchars($_POST['adresse'], ENT_QUOTES);
    $cp = htmlspecialchars($_POST['cp'], ENT_QUOTES);
    
    $photo_bdd='';
    if (isset($_POST["photo"]))
    {
        $photo_bdd=$_POST['photo']; 
    }

    // ajouter des controles sur le format, la taille et l'extension de l'image. 

    if (!empty($_FILES['photo']['name']))
    {
        $nom_photo = $_FILES['photo']['name'];
        $photo_bdd = '/php/projet_annonceo/photo/'. $nom_photo;   
        $photo_dossier = $_SERVER['DOCUMENT_ROOT']. $photo_bdd;

        copy($_FILES['photo']['tmp_name'], $photo_dossier);
    }

    $photo_bdd2='';
    if (isset($_POST["photo2"]))
    {
        $photo_bdd2=$_POST['photo2'];
    }

    // ajouter des controles sur le format, la taille et l'extension de l'image. 

    if (!empty($_FILES['photo2']['name']))
    {
        $nom_photo = $_FILES['photo2']['name'];
        $photo_bdd2 = '/php/projet_annonceo/photo/'. $nom_photo;   
        $photo_dossier = $_SERVER['DOCUMENT_ROOT']. $photo_bdd2;

        copy($_FILES['photo2']['tmp_name'], $photo_dossier);
    }
    $photo_bdd3='';
    if (isset($_POST["photo3"]))
    {
        $photo_bdd3=$_POST['photo3'];
    }

    // ajouter des controles sur le format, la taille et l'extension de l'image. 

    if (!empty($_FILES['photo3']['name']))
    {
        $nom_photo = $_FILES['photo3']['name'];
        $photo_bdd3 = '/php/projet_annonceo/photo/'. $nom_photo;   
        $photo_dossier = $_SERVER['DOCUMENT_ROOT']. $photo_bdd3;

        copy($_FILES['photo3']['tmp_name'], $photo_dossier);
    }
    $photo_bdd4='';
    if (isset($_POST["photo4"]))
    {
        $photo_bdd4=$_POST['photo4'];
    }

    // ajouter des controles sur le format, la taille et l'extension de l'image. 

    if (!empty($_FILES['photo4']['name']))
    {
        $nom_photo = $_FILES['photo4']['name'];
        $photo_bdd4 = '/php/projet_annonceo/photo/'. $nom_photo;   
        $photo_dossier = $_SERVER['DOCUMENT_ROOT']. $photo_bdd4;

        copy($_FILES['photo4']['tmp_name'], $photo_dossier);
    }

    $photo_bdd5='';
    if (isset($_POST["photo5"]))
    {
        $photo_bdd5=$_POST['photo5'];
    }

    // ajouter des controles sur le format, la taille et l'extension de l'image. 

    if (!empty($_FILES['photo5']['name']))
    {
        $nom_photo = $_FILES['photo5']['name'];
        $photo_bdd5 = '/php/projet_annonceo/photo/'. $nom_photo;   
        $photo_dossier = $_SERVER['DOCUMENT_ROOT']. $photo_bdd5;

        copy($_FILES['photo5']['tmp_name'], $photo_dossier);
    }
    
    $req1 = $pdo->prepare('REPLACE INTO photo VALUES (NULL,:photo1,:photo2,:photo3,:photo4,:photo5)');
    $req1->execute(array(
        'photo1' => $photo_bdd,
        'photo2' => $photo_bdd2,
        'photo3' => $photo_bdd3,
        'photo4' => $photo_bdd4,
        'photo5' => $photo_bdd5
    ));
    $id_photo = $pdo->lastInsertId();

    $req = $pdo->prepare("REPLACE INTO annonce VALUES (:id_annonce,:titre,:description_courte,:description_longue,:prix,:photo,:pays,:ville,:adresse,:code_postal,:membre_id,:photo_id,:categorie_id, NOW())");
    $req->execute(array('id_annonce' => $_POST['id_annonce'],
                        'titre' => $titre,
                        'description_courte' => $desc_c,
                        'description_longue' => $desc_l,
                        'prix' => $prix,
                        'photo' => $photo_bdd,
                        'pays' => $pays,
                        'ville' => $ville,
                        'adresse' => $adresse,
                        'code_postal' => $cp,
                        'membre_id' => $_SESSION['membre']['id_membre'],
                        'photo_id' => $id_photo,
                        'categorie_id' => $_POST['cat']                           
                    ));
                    $contenu .= '<div class="alert alert-success>La team rocket s\'envole vers d\'autres cieux</div>';
                    $_GET['action'] = 'affichage';
}

if ( (isset($_GET['action']) && $_GET['action']=='affichage') || !isset($_GET['action']) )
{
    // affichage des produits
    echo "<h3>Affichage des produits :</h3>";


    $pdo->exec('USE annonceo');

    $resul = $pdo->query('SELECT * FROM annonce');

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
                    if ($indice == 'membre_id')
                    {
                        $information = '<a href="gestion_membre.php">'.$ligne['membre_id'].'</a>';
                    } 
                    if ($indice == 'categorie_id')
                    {
                        $information = '<a href="gestion_categories.php">'.$ligne['categorie_id'].'</a>';
                    } 
                    if ( ($indice=='photo') && $information != '')
                    {
                        $information= '<img class="img-thumbnail" src="'.$ligne['photo'].'"alt="'.$ligne['titre'].'">';
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

if ( isset($_GET['action']) && ($_GET['action']=='modifier') ):

    if ( !empty($_GET['id_annonce']) )
    {
        $result = $pdo->prepare("SELECT * FROM annonce WHERE id_annonce= :id_annonce");

        $result->execute(array('id_annonce' => $_GET['id_annonce']));
        $annonce_actuelle = $result->fetch(PDO::FETCH_ASSOC);
    }
   
?>
    <h3>Formulaire de modification</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>Ajouter votre annonce</legend>
            <input type="hidden" id="id_annonce" name='id_annonce' value="<?= $annonce_actuelle['id_annonce'] ?? 0 ?>">
                <label for="titre" >Titre de l'annonce</label>
                <br>
                <input  type="text" id="titre" name='titre' value="<?= $annonce_actuelle['titre'] ?>">
                <br>
                <label for="desc_c" >Decription courte</label>
                <br>
                <textarea name="desc_c" id="desc_c" cols="40" rows="5"><?= $annonce_actuelle['description_courte'] ?></textarea>
                <br>
                <label for="desc_l" >Decription Longue</label>
                <br>
                <textarea name="desc_l" id="desc_l" cols="40" rows="10"><?= $annonce_actuelle['description_longue'] ?></textarea>
                <br>
                <label for="prix">Prix</label>
                <br>
                <input type="text" id="prix" name='prix' value="<?= $annonce_actuelle['prix'] ?>">
                <br>
                <label for="photo">Photo principale</label>
                <br>
                <input type="file" name="photo" id="photo">
                <br>
                <label for="photo2">Photo 2</label>
                <br>
                <input type="file" name="photo2" id="photo2">
                <br>
                <label for="photo3">Photo 3</label>
                <br>
                <input type="file" name="photo3" id="photo3">
                <br>
                <label for="photo4">Photo 4</label>
                <br>
                <input type="file" name="photo4" id="photo4">
                <br>
                <label for="photo5">Photo 5</label>
                <br>
                <input type="file" name="photo5" id="photo5">
                <br>
                <label for="pays">Pays</label>
                <br>
                <input type="text" id="pays" name='pays' value="<?= $annonce_actuelle['pays'] ?>">
                <br>
                <label for="ville">Ville</label>
                <br>
                <input type="text" id="ville" name='ville' value="<?= $annonce_actuelle['ville'] ?>">
                <br>
                <label for="cp">Code Postal</label>
                <br>
                <input type="text" id="cp" name='cp' value="<?= $annonce_actuelle['code_postal'] ?>">
                <br>
                <label for="adresse" >Adresse</label>
                <br>
                <textarea name="adresse" id="adresse" cols="40" rows="5"><?= $annonce_actuelle['titre'] ?></textarea>
                <br>
                <label for="cat">Catégorie</label>
                <br>
                    <select name="cat" id="cat">
                        <?php
                            $categories = $pdo->query('SELECT * FROM categorie');
                            while ( $categorie = $categories->fetch(PDO::FETCH_ASSOC))
                                {
                                    echo '<option value="'.$categorie['id_categorie'].'">'.$categorie['titre'].'</option>';
                                
                                }
                        ?>
                    </select>
                    <br>
                
                <input type="submit" value="Modifier mon annonce" class="btn btn-success">

        </fieldset>
    </form>
    
<?php
endif;
require_once('../inc/footer.php');

?>