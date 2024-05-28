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

$query = "SELECT * FROM document WHERE projet_id = :idProjet ORDER BY titre ASC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
$stmt->execute();
$documents = $stmt->fetchAll(PDO::FETCH_BOTH);

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Documents</title>

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
                <a class="nav-link" href="taches.php?projet=<?php echo $projet['id'] ?>">Tâches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="commentaires.php?projet=<?php echo $projet['id'] ?>">Commentaires</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Documents</a>
                </li>
            </ul>
            <div class="mt-2">
                <a class="btn btn-primary" href="ajouterDocument.php?projet=<?php echo $projet['id'] ?>"><i class="las la-plus"></i> Document</a>
            </div>
            <div class="mt-2">
                <?php if (count($documents) == 0) : ?>
                    aucun document
                <?php else : ?>
                    <div class="row mt-1">
                        <div class="col">
                            Titre
                        </div>
                        <div class="col">
                            Type
                        </div>
                        <div class="col-1">
                        </div>
                    </div>
                    <?php foreach ($documents as $document) : ?>
                        <div class="row mt-1">
                            <div class="col">
                                <a target="_blank" href="./documents/<?php echo $document['fichier'] ?>"><?php echo $document['titre'] ?></a>
                            </div>
                            <div class="col">
                                <?php echo $document['type'] ?>
                            </div>
                            <div class="col-1">
                                <a title="supprimer le document" class="btn btn-sm btn-danger" href="supprimerDocument.php?document=<?php echo $document['id'] ?>"><i class="las la-trash"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </body>
</html>
