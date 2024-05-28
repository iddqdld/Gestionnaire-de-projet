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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idProjet = $_POST['projet_id'];
    $titre = $_FILES['fichier']['name'];
    $type = $_POST['type'];
    $fichier = md5(session_id().microtime());
    $target_file = './documents/'.$fichier;

    move_uploaded_file($_FILES["fichier"]["tmp_name"], $target_file);

    $query = "INSERT INTO document(projet_id,titre,fichier,type) VALUES(:idProjet,:titre,:fichier,:type)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':fichier', $fichier, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->execute();

    $location = 'documents.php?projet='.$projet['id'];
    header('Location: '.$location);
    exit();
}
?>

<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ajout Document</title>

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

            <h2>Ajout d'un document pour le projet : <?php echo $projet['titre'] ?></h2>
            <?php if (isset($error_message)) : ?>
                <p class="text-danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="projet_id" required class="form-control" value="<?php echo $projet['id'] ?>">
                <div class="mb-3">
                    <label for="fichier" class="form-label">Document</label>
                    <input name="fichier" class="form-control" type="file" id="fichier">
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" name="type" required class="form-control" id="type">
                </div>
                <button type="submit" class="btn btn-primary">Ajouter le document</button> 
                <a class="btn btn-light" href="documents.php?projet=<?php echo $projet['id'] ?>">Annuler</button> 
            </form>
        </main>
    </body>
</html>
