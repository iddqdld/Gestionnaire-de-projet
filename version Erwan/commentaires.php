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

$query = "SELECT * FROM commentaire WHERE projet_id = :idProjet ORDER BY date_creation DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
$stmt->execute();
$commentaires = $stmt->fetchAll(PDO::FETCH_BOTH);

$utilisateurs = array();
foreach ($commentaires as $commentaire) {
    $query = "SELECT * FROM utilisateur WHERE id = :idUtilisateur";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idUtilisateur', $commentaire['utilisateur_id'], PDO::PARAM_STR);
    $stmt->execute();
    $utilisateurs[$commentaire['utilisateur_id']] = $stmt->fetch(PDO::FETCH_ASSOC);
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
                <a class="nav-link" href="taches.php?projet=<?php echo $projet['id'] ?>">Tâches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Commentaires</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="documents.php?projet=<?php echo $projet['id'] ?>">Documents</a>
                </li>
            </ul>
            <div class="mt-2">
                <a class="btn btn-primary" href="creerCommentaireProjet.php?projet=<?php echo $projet['id'] ?>"><i class="las la-plus"></i> Commentaire</a>
            </div>
            <div class="mt-2">
                <?php if (count($commentaires) == 0) : ?>
                    aucun commentaire
                <?php else : ?>
                    <?php foreach ($commentaires as $commentaire) : ?>
                        <div class="border m-3 p-1">
                            <div class="row d-flex align-items-center mt-2">
                                <div class="col">
                                    <i class="las la-2x la-comment"></i><b><?php echo $utilisateurs[$commentaire['utilisateur_id']]['prenom'].' '.$utilisateurs[$commentaire['utilisateur_id']]['nom'] ?></b> le <?php echo date_format(date_create($commentaire['date_creation']),"d/m/Y")?>
                                </div>
                                <div class="col-2">
                                    <a title="supprimer le commentaire" class="btn btn-sm btn-danger" href="supprimerCommentaire.php?commentaire=<?php echo $commentaire['id'] ?>"><i class="las la-trash"></i></a>
                                </div>
                            </div>
                            <div class="ms-5" style="white-space:pre-line;">
                                <?php echo $commentaire['contenu'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </body>
</html>
