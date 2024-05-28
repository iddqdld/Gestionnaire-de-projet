<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

$idProjet = $_GET["projet"];

$query = "SELECT * FROM projet WHERE id = :idProjet";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
$stmt->execute();
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

if ($projet == null) {
    header('Location: projets.php');
    exit();
}

$query = "SELECT * FROM tache WHERE projet_id = :idProjet ORDER BY date_debut ASC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
$stmt->execute();
$taches = $stmt->fetchAll(PDO::FETCH_BOTH);

$contributions = array();
$utilisateurs = array();
foreach ($taches as $tache) {
    $query = "SELECT * FROM contribution WHERE tache_id = :idTache";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idTache', $tache['id'], PDO::PARAM_STR);
    $stmt->execute();
    $contributions[$tache['id']] = $stmt->fetchAll(PDO::FETCH_BOTH);

    foreach ($contributions[$tache['id']] as $contribution) {
        $query = "SELECT * FROM utilisateur WHERE id = :idUtilisateur";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idUtilisateur', $contribution['utilisateur_id'], PDO::PARAM_STR);
        $stmt->execute();
        $utilisateurs[$contribution['utilisateur_id']] = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tâches</title>

        <!-- line awesome cdn link  -->
        <link rel="stylesheet" href="line-awesome-1.3.0/css/line-awesome.min.css">
        <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="jquery-ui-bootstrap-jquery-ui-bootstrap-71f2e47/css/custom-theme/jquery-ui-1.9.2.custom.css">

        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
        <script src="jquery-ui-bootstrap-jquery-ui-bootstrap-71f2e47/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script>
            $(function(){
                $("#bandeau").load("bandeau.php");
            });
        </script>

    </head>

    <body>
        <nav  id="bandeau" class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        </nav>
        <main class="container">
            <h2><i class="las la-cog"></i><?php echo $projet['titre'] ?></h2>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="projet.php?projet=<?php echo $projet['id'] ?>">informations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Tâches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="commentaires.php?projet=<?php echo $projet['id'] ?>">Commentaires</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="documents.php?projet=<?php echo $projet['id'] ?>">Documents</a>
                </li>
            </ul>
            <div class="mt-2">
                <a class="btn btn-primary" href="creerTache.php?projet=<?php echo $projet['id'] ?>"><i class="las la-plus"></i> Tâche</a>
            </div>
            <div class="mt-2">
                <?php if (count($taches) == 0) : ?>
                    aucune tâche
                <?php else : ?>
                    <div class="row border-bottom d-flex align-items-center">
                        <div class="col-1">
                            Description
                        </div>
                        <div class="col-2">
                            Contributions
                        </div>
                        <div class="col">
                            <div class="d-flex justify-content-between">
                                <div><?php echo date_format(date_create($projet['date_debut']),"d/m/Y") ?></div>
                                <div><?php echo date_format(date_create($projet['date_fin_prevue']),"d/m/Y") ?></div>
                            </div>
                        </div>
                        <div class="col-1">
                        </div>
                    </div>
                    <?php foreach ($taches as $tache) : ?>
                        <div class="row d-flex align-items-center mt-1">
                            <div title="<?php echo $tache['description'] ?>"  class="col-1 text-truncate">
                                <?php echo $tache['description'] ?>
                            </div>
                            <div class="col-2">
                                <div class="d-flex justify-content-between">
                                    <?php if (count($contributions[$tache['id']]) == 0) : ?>
                                        <div><small>aucune contribution</small></div>
                                    <?php else : ?>
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle" type="button" id="contribution<?php echo $tache['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                contributions : <?php echo count($contributions[$tache['id']]) ?>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="contribution<?php echo $tache['id'] ?>">
                                                <?php foreach ($contributions[$tache['id']] as $contribution) : ?>
                                                    <li><a title="supprimer la contribution" class="dropdown-item" href="supprimerContribution.php?tache=<?php echo $contribution['tache_id'] ?>&utilisateur=<?php echo $contribution['utilisateur_id'] ?>"><?php echo $utilisateurs[$contribution['utilisateur_id']]['prenom'].' '.$utilisateurs[$contribution['utilisateur_id']]['nom'].' ('.$contribution['role'].')' ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    <a title="ajouter une contribution à la tâche" class="btn btn-sm btn-primary" href="creerContribution.php?tache=<?php echo $tache['id'] ?>"><i class="las la-plus"></i></a>
                                </div>
                            </div>
                            <div class="col">
                                <div title="tâche <?php if ($tache['statut'] == 1) : ?>initialisée<?php elseif ($tache['statut'] == 2) : ?>en cours<?php elseif ($tache['statut'] == 3) : ?>terminée<?php endif; ?> du <?php echo date_format(date_create($tache['date_debut']),"d/m/Y") ?> au <?php echo date_format(date_create($tache['date_fin_prevue']),"d/m/Y") ?>" class="border <?php if ($tache['statut'] == 1) : ?>border-primary<?php elseif ($tache['statut'] ==  2) : ?>border-danger<?php elseif ($tache['statut'] ==  3) : ?>border-success<?php endif; ?>" style="height:20px;width:<?php echo (round((strtotime($tache['date_fin_prevue'])-strtotime($tache['date_debut']))/(strtotime($projet['date_fin_prevue'])-strtotime($projet['date_debut']))*100, 0)) ?>%;margin-left:<?php echo (round((strtotime($tache['date_debut'])-strtotime($projet['date_debut']))/(strtotime($projet['date_fin_prevue'])-strtotime($projet['date_debut']))*100,0)) ?>%"></div>
                            </div>
                            <div class="col-1">
                                <a title="modifier la tâche" class="btn btn-sm btn-light" href="modifierTache.php?tache=<?php echo $tache['id'] ?>"><i class="las la-pen"></i></a>
                                <a title="supprimer la tâche" class="btn btn-sm btn-danger" href="supprimerTache.php?tache=<?php echo $tache['id'] ?>"><i class="las la-trash"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </body>
</html>
