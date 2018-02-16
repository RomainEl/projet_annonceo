<?php
require_once('../inc/init.php');
require_once('../inc/header.php');
$ajout = false;
if ( $_POST && !empty($_POST))  
{
    $ajout = true; 
    $req = $pdo->prepare('INSERT INTO categorie VALUES (NULL, :titre, :motscles)');
    $req->execute(array(
        'titre' => htmlspecialchars($_POST['titre']),
        'motscles' => htmlspecialchars($_POST['motscles'])
    ));
    
}

if ($ajout)
{
    echo '<div class="alert alert-success">La catégorie a bien été ajoutée !</div>';
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
        echo '<td><a href="?action=modifier&id_produit=' . $ligne['id_categorie'] . '"> [Modifier] </a></td>';
        echo '<td><a href="?action=suppression&id_produit=' . $ligne['id_categorie'] . '" onclick="return(confirm(\'Etes vous certain de vouloir supprimer cette catégorie : ' .$ligne['titre']. ' ?\'))">[Supprimmer]</a></td>';
    echo "</tr>";
}
echo "</table>";



?>

<h3>Ajouter une catégorie</h3>

<form action="" method='post'>
<br>
<label for="titre">Titre de la catégorie</label>
<br>
<input type="text" name="titre" id='titre'>
<br>
<label for="motscles">Mots clés associés</label>
<br>
<textarea name="motscles" id="motscles" cols="30" rows="10"></textarea>
<br>
<input type="submit" id='submit' class='btn btn-success'>

</form>




<?php
require_once('../inc/footer.php');

?>