<?php
include_once ("lib/functions.php");
spl_autoload_register('load'); //auto chargement des classes
$gestiongroupe = new Groupes();
$utilisateurs = new Utilisateurs();
$tableau = new Tableau();
$search = new Search();
$messagegroupe = $gestiongroupe->ajoutergroupe();
$messageutilisateur = $utilisateurs->ajouterutilisateurs();
list($lignes,$list,$userid) = $tableau->lister();
$messagesupprimer = $tableau->supprimer($userid);
$messagetoutsupprimer = $tableau->toutsupprimer();

?>
<!DOCTYPE html>
<html>
<head>
    <!-- ==========================
    	Meta Tags
    =========================== -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ==========================
    	Title
    =========================== -->
    <title>Régnié Lionel - Test technique</title>

    <!-- ==========================
    	Favicons
    =========================== -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">

    <!-- ==========================
    	Fonts
    =========================== -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

    <!-- ==========================
    	CSS
    =========================== -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css">

    <!-- ==========================
    	PLUGINS
    =========================== -->
    <link href="assets/plugins/Datepicker/datepicker3.css" rel="stylesheet" type="text/css">

    <!-- ==========================
    	JS
    =========================== -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<h1 class="title">Régnié Lionel -  Test technique</h1>
<div class="container">
    <?php echo $messagegroupe.$messageutilisateur.$messagesupprimer.$messagetoutsupprimer;?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#addgroup">Ajouter un groupe</button>
            <div id="addgroup" class="collapse">
                <form method="post" action="">
                   <table class="table table-bordered table-hover">
                       <thead>
                       <tr>
                           <th class="groupe" colspan="2">Ajouter un groupe</th>
                       </tr>
                       </thead>
                       <tbody>
                       <tr>
                           <td>Nom du Groupe</td>
                           <td><input type="text" name="groupname" class="form-control" required></td>
                       </tr>
                       <tr>
                           <td colspan="2"> <button type="submit" name="addgroup" class="btn btn-block btn-primary">Ajouter</button></td>
                       </tr>
                       </tbody>
                   </table>
                </form>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#adduser">Ajouter un utiisateur</button>
            <div id="adduser" class="collapse">
                <form method="post" action="">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="groupe" colspan="2">Ajouter un utilisateur</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Nom</td>
                            <td><input type="text" name="nom" class="form-control" placeholder="Nom..." required></td>
                        </tr>
                        <tr>
                            <td>Prénom</td>
                            <td><input type="text" name="prenom" class="form-control" placeholder="Prénom..." required></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type="email" name="email" class="form-control" placeholder="Email..." required></td>
                        </tr>
                        <tr>
                            <td>Date d'anniversaire</td>
                            <td>
                                <div class="input-group margin-bottom-20 date" data-provide="datepicker" data-date-format="yyyy/mm/dd" data-date-end-date="0d" data-date-start-date="1900/01/01" data-date-today-highlight="true">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="age" readonly required>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Groupe</td>
                            <td><?php echo $utilisateurs->displaygroupes();?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><button type="submit" name="adduser" class="btn btn-block btn-primary">Ajouter</button></td>
                        </tr>
                        <tr>
                            <td colspan="2"><button type="reset" class="btn btn-block btn-danger">Reset</button></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="navbar navbar-inverse">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">Rechercher</a>
                        <div class="pull-right">
                            <form class="navbar-form" action="index.php" method="post">
                                <?php echo $search->listgroup();?>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search" name="searchinput">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit" name="search"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <?php echo (isset($_POST["search"]))? '<a class="btn btn-danger" href="index.php">Annuler la recherche</a>': "";?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form method="post" action="">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nom du groupe</th>
                        <th>Nom/Prénom</th>
                        <th>Email</th>
                        <th>Actions</th>
                        <th><input type="checkbox" class="checkboxmaster"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    echo $lignes;
                    if(!empty($list)){
                    ?>
                    <tr>
                        <td colspan="5"><button type="submit" name="deleteall" class="btn btn-block btn-danger">Supprimer les utilisateurs cochés</button></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>

        </div>

    </div>

</div>
<?php echo $list;?>
<!-- ==========================
    JS
=========================== -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/plugins/Datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $('.checkboxmaster').click(function() {
        if(this.checked) {
            $('.checkboxchild').each(function () {
                $(this).prop('checked', true);
            });
        }else{
            $('.checkboxchild').each(function () {
                $(this).prop('checked', false);
            });
        }
    });
</script>
</body>
</html>