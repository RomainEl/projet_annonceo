<?php

require_once('../inc/init.php');

//calcul moyennes

$somme_notes = 0;
$i = 0;
foreach($note as $cle=>$valeur)
{
    $i++;
    $somme_notes+=$valeur;// équivaut a $somme_notes = $somme_notes + $valeur
}
$moyenne = $somme_notes / $i;

$contenu .= '<h2>TOP 5 des membres les mieux notés</h2>
                <ul class="list-group">';
$a = 1;
while($a <= 5)
{
    $contenu .= '<li class="list-group-item">'.$a.'-'..'<span class="badge">'.$moyenne.' étoiles basé sur '.$i.' avis</span>';
    $a++;
}


require_once('../inc/header.php');

