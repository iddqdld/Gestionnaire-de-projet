<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

$query = "SELECT * FROM utilisateur WHERE mail = :mail";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':mail', $_SESSION['login'], PDO::PARAM_STR);
$stmt->execute();
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

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
    $contenu = $_POST['contenu'];
    $dateCreation = date_format(new \DateTime(), 'Y-m-d');

    $query = "INSERT INTO commentaire(projet_id,utilisateur_id,date_creation,contenu) VALUES(:idProjet,:idUtilisateur,:date_creation,:contenu)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
    $stmt->bindParam(':idUtilisateur', $utilisateur['id'], PDO::PARAM_STR);
    $stmt->bindParam(':date_creation', $dateCreation, PDO::PARAM_STR);
    $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
    $stmt->execute();

    $location = 'commentaires.php?projet='.$idProjet;
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
        <title>Création commentaire projet</title>

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

            <h2>Création d'un commentaire pour le projet : <?php echo $projet['titre'] ?></h2>
            <?php if (isset($error_message)) : ?>
                <p class="text-danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="projet_id" required class="form-control" value="<?php echo $projet['id'] ?>">
                <div class="mb-3">
                    <label for="contenu" class="form-label">Commentaire</label>
                    <textarea name="contenu" required class="form-control" id="contenu"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Créer le commentaire</button> 
                <a class="btn btn-light" href="commentaires.php?projet=<?php echo $projet['id'] ?>">Annuler</button> 
            </form>
        </main>
    </body>
</html>
