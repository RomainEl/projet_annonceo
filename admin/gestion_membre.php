

<?php
require_once('../inc/init.php');
require_once('../inc/header.php');

$pdo->exec("USE annonceo");

$nomtable="membre";

$result = $pdo->query("SELECT id_membre,pseudo,mdp,nom,prenom,telephone,email,civilite,statut,date_enregistrement FROM $nomtable");

echo "<table class='table table-hover'><tr>";

// Générer les en-têtes de colonnes

$nbcol = $result->columnCount();
for ($i=0;$i<$nbcol;$i++){
    $infocol = $result->getColumnMeta($i);
    //getColumnMeta envoie les informations d'une colonne comme son type, son nom, sa longueur. Dans notre exemple, c'est l'index 'name' qui nous intéresse. 
    echo '<th>';
    echo $infocol['name'];
    echo '</th>';

   
}

 echo '<th colspan="2">Actions</th>';
echo '</tr>';

// Parcours des enregistrements

while ($ligne = $result->fetch(PDO::FETCH_ASSOC)){
    echo '<tr>';
        foreach($ligne as $information){
            echo "<td class='text-center'> $information </td>";
            
        }

    
    if ($ligne['id_membre'] != $_SESSION['membre']['id_membre'])
    {
    $type_action = ($ligne['statut'] == 0 ? 'Promouvoir' : 'Dégrader');
            echo '<td><a href="?action=changestatut&id_membre='.$ligne['id_membre'].'">'.$type_action.'<a></td>';    
            
            echo '<td><a href="?action=suppression&id_membre='.$ligne['id_membre'].'"onclick="return(confirm(\'Etes vous sûr de vouloir kicker : '.$ligne['nom'].' ?\'))">Supprimer<a></td>';   
            echo '</tr>';
    }        
}

echo '</table>';
echo '<p class="text-center">Nombre de membres : '.$result->rowCount().'</p>';

// suppression 
if ( isset($_GET['action']) && $_GET['action'] == "suppression" && !empty($_GET['id_membre'] ))
{
    if ( $_GET["id_membre"] != $_SESSION["membre"]['id_membre'])
    {
        executeRequete('DELETE FROM membre WHERE id_membre= :id_membre', array('id_membre' => $_GET['id_membre']));
        echo '<div class="alert alert-success">Le membre a été supprimé</div>';
    }
}

// change statut

if ( isset($_GET['action']) && $_GET['action'] == "changestatut" && !empty($_GET['id_membre'] ))
{
    if ( $_GET["id_membre"] != $_SESSION["membre"]['id_membre'])
    {
        $result = executeRequete('SELECT statut FROM membre WHERE id_membre=:id_membre', array('id_membre' => $_GET['id_membre']));
        $membre = $result->fetch(PDO::FETCH_ASSOC);
        $newstatut = ($membre['statut'] == 0) ? 1 : 0;

        executeRequete('UPDATE membre SET statut=:newstatut WHERE id_membre=:id_membre', array('id_membre' => $_GET['id_membre'],
                                                                                               'newstatut' => $newstatut));
        echo '<div class="alert alert-success">Le statut du membre a bien été modifié</div>';
    }
}

require_once('../inc/footer.php');

?>