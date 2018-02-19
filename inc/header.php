<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Annonceo</title>
<!-- liens css -->
<link rel="stylesheet" href="<?= RACINE_SITE.'inc/css/bootstrap.min.css' ?>">
<link rel="stylesheet" href="<?= RACINE_SITE.'inc/css/style.css' ?>">
<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

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
        <a class="navbar-brand" href="#">Brand</a>
      </div>
  
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="<?= RACINE_SITE.'contact.php' ?>">Contact</a></li>
          <li><a href="<?= RACINE_SITE.'qui_sommes_nous.php' ?>">Qui sommes nous ?</a></li>
        <?php

            if ( estConnecteEtAdmin() )
            {
                echo '<li><a href="'.RACINE_SITE.'admin/gestion.php">Gestion</a></li>';
            }

            if ( estConnecte() )
            {
                echo '<li><a href="'.RACINE_SITE.'ajout.php">Ajouter une annonce ! </a></li>';
            }

        ?>

        <form class="navbar-form navbar-left">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Rechercher">
          </div>
          <button type="submit" class="btn btn-default">Rechercher</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Espace membre <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="<?= RACINE_SITE.'connexion.php' ?>">Connexion</a></li>
              <li><a href="<?= RACINE_SITE.'inscription.php' ?>">Inscription</a></li>
              <li><a href="<?= RACINE_SITE.'connexion.php?action=deconnexion' ?>">Se déconnnecter</a></li>
            </ul>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
</header>
<div class="container main">
