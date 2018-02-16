<?php
// Session
session_start();

// Connexion
$pdo = new PDO(
        'mysql:host=localhost;dbname=annonceo',
        'root',
        '',
        array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ));

// Chemin du site
define('RACINE_SITE','/projet_annonceo/');

$contenu='';
$contenu_gauche='';
$contenu_droite='';

// Inclusion fichier de fonctions
require_once('fonction.php');

?>