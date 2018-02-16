<?php

require_once('inc/init.php');

$sugg='';

if(isset($_GET['id_annonce']))
{
    $result= $pdo->prepare("SELECT * FROM annonce WHERE id_annonce=:id_annonce");
    $result->execute(array('id_annonce'=>$_GET['id_annonce']));

    if ($result->rowCount()== 0)
    {
        header('location:index.php');
    }

    $annonce= $resul->fetch(PDO::FETCH_ASSOC);

    $contenu .= '<div class="row">
        <div class="col-sm-12">
            <h2>'.$annonce['titre'].'</h2>
        </div>
    </div>';

    $contenu .= ''
}


require_once('inc/header.php');

?>