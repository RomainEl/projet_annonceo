<?php

require_once('../inc/init.php');

$pdo->exec("USE annonceo");


$result = $pdo->query("SELECT * FROM commentaire");

// suppression 
if ( isset($_GET['action']) && $_GET['action'] == "suppression" && !empty($_GET['id_commentaire'] ))
{
    $result= $pdo->prepare('DELETE FROM commentaire WHERE id_commentaire= :id_commentaire');
    $result->execute(array('id_commentaire' => $_GET['id_commentaire']));
    $contenu .= '<div class="alert alert-success">Le commentaire a été supprimé</div>';
}

if($_POST)
{
    $res=$pdo->prepare('REPLACE INTO commentaire VALUES(:id_commentaire,:membre_id,:annonce_id,:commentaire,:date_enregistrement)');
    $res->execute(array(
        'id_commentaire'=>$_POST['id_commentaire'],
        'membre_id' =>$_POST['membre_id'],
        'annonce_id'=>$_POST['annonce_id'],
        'commentaire'=>$_POST['commentaire'],
        'date_enregistrement'=>$_POST['date_enregistrement']));
    $contenu .= '<div class="alert alert-success">Le message a été modifié</div>';
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
                if( $index == 'membre_id'){
                    $contenu .= '<td class="text-center"><a href="gestion_membre.php">'.$value.'</a></td>';
                }
                elseif ( $index == 'annonce_id'){
                    $contenu .= '<td class="text-center"><a href="gestion_annonces.php">'.$value.'</a></td>'; 
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
        $contenu .= '<td><a href="?action=modifier&id_commentaire='.$ligne['id_commentaire'].'">Modifier</a></td>';
        $contenu .= '<td><a href="?action=suppression&id_commentaire='.$ligne['id_commentaire'].'"onclick="return(confirm(\'Etes vous sûr de vouloir supprimer ce message ?\'))">Supprimer</a></td>';
        $contenu .= '</tr>';
    }

    $contenu .= '</table>';
    $contenu .= '<p class="text-center">Nombre de commentaires : '.$result->rowCount().'</p>';

}

require_once('../inc/header.php');
echo $contenu;

// modification
if ( isset($_GET['action']) && $_GET['action'] == "modifier" && !empty($_GET['id_commentaire'])){
        
    $result=$pdo->prepare("SELECT * FROM commentaire WHERE id_commentaire=:id_commentaire");
    $result->execute(array('id_commentaire'=>$_GET['id_commentaire']));
    $commentaire_a_modif = $result->fetch(PDO::FETCH_ASSOC);
    ?>
    <h3>Formulaire de modération de commentaire</h3>
    <form method="post" action="">
        <div class="form-group">
            <input type="hidden" id="id_commentaire" name="id_commentaire" value="<?= $commentaire_a_modif['id_commentaire']?>">
        </div>
        <div class="form-group">
            <input type="hidden" id="membre_id" name="membre_id" value="<?= $commentaire_a_modif['membre_id']?>">
        </div>
        <div class="form-group">
            <input type="hidden" id="annonce_id" name="annonce_id" value="<?= $commentaire_a_modif['annonce_id']?>">
        </div>
        <div class="form-group">
            <textarea name="commentaire" id="commentaire"><?= $commentaire_a_modif['commentaire'] ?></textarea>
        </div>
        <div class="form-group">
            <input type="hidden" id="date_enregistrement" name="date_enregistrement" value="<?= $commentaire_a_modif['date_enregistrement']?>">
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
