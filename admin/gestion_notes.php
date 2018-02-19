<?php

require_once('../inc/init.php');

$pdo->exec("USE annonceo");


$result = $pdo->query("SELECT * FROM note");

// suppression 
if ( isset($_GET['action']) && $_GET['action'] == "suppression" && !empty($_GET['id_note'] ))
{
    $result= $pdo->prepare('DELETE FROM note WHERE id_note= :id_note');
    $result->execute(array('id_note' => $_GET['id_note']));
    $contenu .= '<div class="alert alert-success">La note a été supprimée</div>';
}

if($_POST)
{
    $res=$pdo->prepare('REPLACE INTO note VALUES(:id_note,:membre_id1,:membre_id2,:note,:avis,:date_enregistrement)');
    $res->execute(array(
        'id_note'=>$_POST['id_note'],
        'membre_id1' =>$_POST['membre_id1'],
        'membre_id2'=>$_POST['membre_id2'],
        'note'=>$_POST['note'],
        'avis'=>$_POST['avis'],
        'date_enregistrement'=>$_POST['date_enregistrement']));
    $contenu .= '<div class="alert alert-success">La note a été modifiée</div>';
    $_GET['action'] = '';
}

if (!isset($_GET['action']) || $_GET['action'] != 'modifier')
{
    $contenu .= "<table class='table table-hover'><tr>";

    // Générer les en-têtes de colonnes

    $nbcol = $result->columnCount();
    for ($i=0;$i<$nbcol;$i++){
        $infocol = $result->getColumnMeta($i);
        //getColumnMeta envoie les informations d'une colonne comme son type, son nom, sa longueur. Dans notre exemple, c'est l'index 'name' qui nous intéresse. 
        $contenu .= '<th>';
        $contenu .= $infocol['name'];
        $contenu .= '</th>';

    
    }

    $contenu .= '<th colspan="2">Actions</th>';
    $contenu .= '</tr>';

    // Parcours des enregistrements

    while ($ligne = $result->fetch(PDO::FETCH_ASSOC)){
        $contenu .= '<tr>';
            foreach($ligne as $index => $value){
                if( $index == 'membre_id1'){
                    $contenu .= '<td class="text-center"><a href="gestion_membre.php">'.$value.'</a></td>';
                }
                elseif ( $index == 'membre_id2'){
                    $contenu .= '<td class="text-center"><a href="gestion_membre.php">'.$value.'</a></td>'; 
                }
                else{
                    $contenu .= '<td class="text-center">'.$value.'</td>';
                }
                
                
            }

        /*
        if ($ligne['id_membre'] != $_SESSION['membre']['id_membre'])
        {
        $type_action = ($ligne['statut'] == 0 ? 'Promouvoir' : 'Dégrader');
                $contenu .= '<td><a href="?action=changestatut&id_membre='.$ligne['id_membre'].'">'.$type_action.'<a></td>';    
                
                $contenu .= '<td><a href="?action=suppression&id_membre='.$ligne['id_membre'].'"onclick="return(confirm(\'Etes vous sûr de vouloir kicker : '.$ligne['nom'].' ?\'))">Supprimer<a></td>';   
                $contenu .= '</tr>';
        } */
        $contenu .= '<td><a href="?action=modifier&id_note='.$ligne['id_note'].'">Modifier</a></td>';
        $contenu .= '<td><a href="?action=suppression&id_note='.$ligne['id_note'].'"onclick="return(confirm(\'Etes vous sûr de vouloir supprimer cette note ?\'))">Supprimer</a></td>';
        $contenu .= '</tr>';
    }

    $contenu .= '</table>';
    $contenu .= '<p class="text-center">Nombre de notes : '.$result->rowCount().'</p>';

}

require_once('../inc/header.php');
echo $contenu;

// modification
if ( isset($_GET['action']) && $_GET['action'] == "modifier" && !empty($_GET['id_note'])){
        
    $result=$pdo->prepare("SELECT * FROM note WHERE id_note=:id_note");
    $result->execute(array('id_note'=>$_GET['id_note']));
    $note_a_modif = $result->fetch(PDO::FETCH_ASSOC);
    ?>
    <h3>Formulaire de modération de note</h3>
    <form method="post" action="">
        <div class="form-group">
            <input type="hidden" id="id_note" name="id_note" value="<?= $note_a_modif['id_note']?>">
        </div>
        <div class="form-group">
            <input type="hidden" id="membre_id1" name="membre_id1" value="<?= $note_a_modif['membre_id1']?>">
        </div>
        <div class="form-group">
            <input type="hidden" id="membre_id2" name="membre_id2" value="<?= $note_a_modif['membre_id2']?>">
        </div>
        <div class="form-group">
            <select>
                <option <?php (isset($note_a_modif['note']) && $note_a_modif['note']== '1') ? 'selected' : '' ?> value="1"><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></option>
                <option <?php (isset($note_a_modif['note']) && $note_a_modif['note']== '2') ? 'selected' : '' ?> value="2"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></option>
                <option <?php (isset($note_a_modif['note']) && $note_a_modif['note']== '3') ? 'selected' : '' ?> value="3"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></option>
                <option <?php (isset($note_a_modif['note']) && $note_a_modif['note']== '4') ? 'selected' : '' ?> value="4"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></option>
                <option <?php (isset($note_a_modif['note']) && $note_a_modif['note']== '5') ? 'selected' : '' ?> value="5"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></option>
            </select>
        </div>
        <div class="form-group">
            <textarea name="avis" id="avis"><?= $note_a_modif['avis'] ?></textarea>
        </div>
        <div class="form-group">
            <input type="hidden" id="date_enregistrement" name="date_enregistrement" value="<?= $note_a_modif['date_enregistrement']?>">
        </div>
        <input type="submit" class="btn btn-primary" value="Modifier">
    </form>
<?php
}
//}
// change statut

/*if ( isset($_GET['action']) && $_GET['action'] == "changestatut" && !empty($_GET['id_membre'] ))
{
    if ( $_GET["id_membre"] != $_SESSION["membre"]['id_membre'])
    {
        $result = executeRequete('SELECT statut FROM membre WHERE id_membre=:id_membre', array('id_membre' => $_GET['id_membre']));
        $membre = $result->fetch(PDO::FETCH_ASSOC);
        $newstatut = ($membre['statut'] == 0) ? 1 : 0;

        executeRequete('UPDATE membre SET statut=:newstatut WHERE id_membre=:id_membre', array('id_membre' => $_GET['id_membre'],
                                                                                               'newstatut' => $newstatut));
        $contenu .= '<div class="alert alert-success">Le statut du membre a bien été modifié</div>';
    }
}
*/
require_once('../inc/footer.php');

