<?php

require_once('../inc/init.php');

//calcul moyennes

$result = $pdo->prepare("SELECT m.pseudo,n.membre_id1,AVG(n.note),COUNT(n.note) FROM note n,membre m  WHERE m.id_membre=n.membre_id1 GROUP BY membre_id1 ORDER BY AVG(n.note) DESC LIMIT 5");

$result->execute(array());

$contenu .= '<h2>Top 5 des utilisateurs les mieux notés</h2>
                <ul class="list-group">';

while($moyenne= $result->fetch(PDO::FETCH_ASSOC)){
    //var_dump($moyenne);
    $contenu .= '
                <li class="list-group-item">
                    <span class="badge">'.$moyenne['AVG(n.note)'].' étoiles basé sur '.$moyenne['COUNT(n.note)'].' avis</span>'
                    .$moyenne['pseudo'].'
                </li>';
}
$contenu .= '</ul>';

// catégories avec le plus d'annonces

$result = $pdo->prepare("SELECT c.titre,COUNT(a.categorie_id) FROM categorie c,annonce a WHERE a.categorie_id=c.id_categorie ORDER BY COUNT(a.categorie_id) DESC");

$result->execute(array());

$contenu.= '<h2>Top 5 des catégories avec le plus d\'annonces<h2>
                <ul class="list-group">';

while($annonce=$result->fetch(PDO::FETCH_ASSOC))
{
    $contenu .=' <li class="list-group-item">
                    <span class="badge">'.$annonce['COUNT(a.categorie_id)'].'</span>'
                    .$annonce['titre'].'
                </li>';
}
$contenu .= '</ul>';

// membres les plus actifs

$result = $pdo->prepare("SELECT m.pseudo,a.id_annonce,COUNT(a.membre_id) FROM membre m, annonce a WHERE a.membre_id=m.id_membre ORDER BY COUNT(a.membre_id) DESC");

$result->execute(array());

$contenu.= '<h2>Top 5 des membres les plus actifs<h2>
                <ul class="list-group">';

while($actif=$result->fetch(PDO::FETCH_ASSOC))
{
    $contenu .= '<li class="list-group-item">
                    <span class="badge">'.$actif['COUNT(a.membre_id)'].'</span>'
                    .$actif['pseudo'].'
                </li>';
}
$contenu .= '</ul>';
require_once('../inc/header.php');
echo $contenu;

require_once('../inc/footer.php');