<?php
require_once('../inc/init.php');
require_once('../inc/header.php');
if ( $_POST && !empty($_POST))  
{
    $req = $pdo->prepare('REPLACE INTO categorie VALUES (:id_categorie, :titre, :motscles)');
    $req->execute(array(
        'id_categorie' => $_POST['id_categorie'],
        'titre' => htmlspecialchars($_POST['titre']),
        'motscles' => htmlspecialchars($_POST['motscles'])
    ));
    
}
/*
{
    echo '<div class="alert alert-success">La catégorie a bien été ajoutée !</div>';
}
*/

// Supression 
if ( isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_categorie']))
{

    $result = $pdo->prepare("DELETE from categorie WHERE id_categorie=:id_categorie");
    $result->execute(array('id_categorie' => $_GET['id_categorie']));

    echo '<div class="alert alert-success">La catégorie a bien été supprimée !</div>';
    $_GET['action'] = 'affichage' ; 
}
?>


<?php

echo "<h3>Catégories :</h3>";


$pdo->exec('USE annonceo');

$resul = $pdo->query('SELECT * FROM categorie');

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
        echo '<td><a href="?action=modifier&id_categorie=' . $ligne['id_categorie'] . '"> [Modifier] </a></td>';
        echo '<td><a href="?action=suppression&id_categorie=' . $ligne['id_categorie'] . '" onclick="return(confirm(\'Etes vous certain de vouloir supprimer cette catégorie : ' .$ligne['titre']. ' ?\'))">[Supprimmer]</a></td>';
    echo "</tr>";
}
echo "</table>";

// modification

if ( isset($_GET['action']) && ($_GET['action']=='modifier') ):

    if ( !empty($_GET['id_categorie']) )
    {
        $result = $pdo->prepare("SELECT * FROM categorie WHERE id_categorie= :id_categorie");

        $result->execute(array('id_categorie' => $_GET['id_categorie']));
        $actegorie_actuelle = $result->fetch(PDO::FETCH_ASSOC);
    }
   
?>
    <h3>Formulaire de modification</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>Modifier une catégorie</legend>
            <p>Pour ajouter une catégorie, donnez lui un ID non-existant</p>
            <label for="id">ID</label>
            <br>
            <input type="text" id="id_categorie" name='id_categorie' value="<?= $actegorie_actuelle['id_categorie'] ?? 0 ?>">
            <br>
            <label for="titre">Titre</label>
            </br>
            <input  type="text" id="titre" name='titre' value="<?= $actegorie_actuelle['titre'] ?>">
            <br>
            <label for="motscles" >Mots clés</label>
            <br>
            <textarea name="motscles" id="motscles" cols="40" rows="5"><?= $actegorie_actuelle['motscles'] ?></textarea>
            <br>
            <input type="submit" id='submit' class='btn btn-success' value = "modifier">
        </fieldset>
    </form>
<?php

endif;

?>


<?php
require_once('../inc/footer.php');

?>