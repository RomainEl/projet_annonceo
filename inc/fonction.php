<?php

function estConnecte(){


    if ( isset($_SESSION['membre']) ){
        return true;
    }  
    else {
        return false;
    }
   
}

function estConnecteEtAdmin(){
     
    if ( estConnecte() && $_SESSION['membre']['statut']==1){
        return true;
    }
    else {
        return false;
    }
}


function executeRequete($sql, $params=array() ){
    if ( !empty($params) )
    {
        foreach( $params as $indice => $param )
        {
            $params[$indice] = htmlspecialchars($param, ENT_QUOTES);
        }
    }

    global $pdo;

    $r = $pdo->prepare($sql);
    $r->execute($params);

    if ( !empty($r->errorInfo()[2]) ){
        die('<p>Erreur recontrée LOSER !'.$r->errorInfo()[2].'</p>');
    }

    return $r; // On retourne l'objet issu de la requète à l'endroit ou la fonction a été appelée. 

}


?>