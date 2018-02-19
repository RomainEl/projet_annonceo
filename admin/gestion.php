<?php

require_once('../inc/init.php');

 /*if ( !estConnecteEtAdmin() )
{
    header('location:'.RACINE_SITE.'index.php');
} */    

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Back-office</title>
<!-- liens css -->
<link rel="stylesheet" href="<?= RACINE_SITE.'inc/css/bootstrap.min.css' ?>">
<link rel="stylesheet" href="<?= RACINE_SITE.'inc/css/style.css' ?>">
</head>
<body>
    <header>
    <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?= RACINE_SITE.'index.php' ?>">Annonceo</a>
      </div>
  
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="<?= RACINE_SITE.'admin/gestion_annonces.php' ?>">Gestion annonces</a></li>
          <li><a href="<?= RACINE_SITE.'admin/gestion_categories.php' ?>">Gestion categories</a></li>
          <li><a href="<?= RACINE_SITE.'admin/gestion_commentaires.php' ?>">Gestion commentaires</a></li>
          <li><a href="<?= RACINE_SITE.'admin/gestion_membre.php' ?>">Gestion membres</a></li>
          <li><a href="<?= RACINE_SITE.'admin/gestion_notes.php' ?>">Gestion notes</a></li>
          <li><a href="<?= RACINE_SITE.'admin/stats.php' ?>">Statistiques</a></li>

        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
</header>

<?php
require_once ('../inc/footer.php');