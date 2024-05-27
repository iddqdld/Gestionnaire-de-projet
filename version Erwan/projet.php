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

$query = "SELECT * FROM utilisateur WHERE id = :idUtilisateur";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idUtilisateur', $projet['responsable'], PDO::PARAM_STR);
$stmt->execute();
$responsable = $stmt->fetch(PDO::FETCH_ASSOC);
    

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Projet</title>

        <!-- line awesome cdn link  -->
        <link rel="stylesheet" href="line-awesome-1.3.0/css/line-awesome.min.css">
        <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="jquery-ui-bootstrap-jquery-ui-bootstrap-71f2e47/css/custom-theme/jquery-ui-1.9.2.custom.css">

        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="bootstrap-5.0.2-dist/js/bootstrap.js"></script>
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
                    <a class="nav-link active" aria-current="page" href="#">informations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="taches.php?projet=<?php echo $projet['id'] ?>">Tâches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="commentaires.php?projet=<?php echo $projet['id'] ?>">Commentaires</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="documents.php?projet=<?php echo $projet['id'] ?>">Documents</a>
                </li>
            </ul>
            <div class="row mt-2">
                <div class="col fs-5">
                    <?php echo $projet['description'] ?>
                </div>
                <div class="col-2">
                   <a title="modifier le projet" class="btn btn-sm btn-light" href="modifierProjet.php?projet=<?php echo $projet['id'] ?>"><i class="las la-pen"></i></a>
                    <a title="supprimer le projet" class="btn btn-sm btn-danger" href="supprimerProjet.php?projet=<?php echo $projet['id'] ?>"><i class="las la-trash"></i></a>
                </div>
            </div>
            <div class="mt-5">
                Date : du <?php echo date_format(date_create($projet['date_debut']),"d/m/Y") ?> au <?php echo date_format(date_create($projet['date_fin_prevue']),"d/m/Y") ?> (prévue)
            </div>
            <div class="mt-2">
                Responsable : <?php echo $responsable['prenom'].' '.$responsable['nom'].' ('.$responsable['equipe'].')' ?>
            </div>
            <div class="mt-2">
                Client : <?php echo $projet['client'] ?>
            </div>
            <div class="mt-2">
                Budget : <?php echo number_format($projet['budget'], 2, ',', ' ')  ?> €
            </div>
            <div class="mt-2">
                Priorité : <?php echo $projet['priorite'] ?>
            </div>
        </main>
    </body>
</html>
