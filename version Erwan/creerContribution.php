<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

$idTache = $_GET["tache"];

$query = "SELECT * FROM tache WHERE id = :idTache";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idTache', $idTache, PDO::PARAM_STR);
$stmt->execute();
$tache = $stmt->fetch(PDO::FETCH_ASSOC);

if ($tache == null) {
    header('Location: projets.php');
    exit();
}

$idProjet = $tache['projet_id'];

$query = "SELECT * FROM projet WHERE id = :idProjet";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
$stmt->execute();
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT * FROM utilisateur ORDER BY mail ASC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_BOTH);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTache = $_POST['tache_id'];
    $idUtilisateur = $_POST['utilisateur'];
    $role = $_POST['role'];
    $permission = $_POST['permission'];

    $query = "SELECT * FROM contribution WHERE tache_id = :idTache AND utilisateur_id = :idUtilisateur";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idTache', $idTache, PDO::PARAM_STR);
    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_STR);
    $stmt->execute();
    $contributions = $stmt->fetchAll(PDO::FETCH_BOTH);

    if (count($contributions) > 0) {
        $error_message = 'une contribution à cette tâche avec cet utilisateur existe déjà';
    } else {

        $query = "INSERT INTO contribution(tache_id,utilisateur_id,role,permission) VALUES(:idTache,:idUtilisateur,:role,:permission)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idTache', $idTache, PDO::PARAM_STR);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':permission', $permission, PDO::PARAM_STR);
        $stmt->execute();

        $location = 'taches.php?projet='.$idProjet;
        header('Location: '.$location);
        exit();
    }
}
?>

<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Création Contribution</title>

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

            <h2>Création d'une contribution de tâche pour le projet : <?php echo $projet['titre'] ?></h2>
            <?php if (isset($error_message)) : ?>
                <p class="text-danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="tache_id" required class="form-control" value="<?php echo $tache['id'] ?>">
                <div class="mb-3">
                    <label for="utilisateur" class="form-label">Utilisateur</label>
                    <select class="form-select" name="utilisateur" id="utilisateur">
                        <option selected value="0">sélectionner un utilisateur</option>
                        <?php foreach ($utilisateurs as $utilisateur) : ?>
                            <option value="<?php echo $utilisateur['id']?>"><?php echo $utilisateur['prenom'].' '.$utilisateur['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <input type="text" name="role" required class="form-control" id="role">
                </div>
                <div class="mb-3">
                    <label for="permission" class="form-label">Permission</label>
                    <select class="form-select" name="permission" aria-label="permission">
                        <option selected>Sélectionner une permission</option>
                        <option value="1">Initialisée</option>
                        <option value="2">En cours</option>
                        <option value="3">Terminé</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Créer la contribution</button> 
                <a class="btn btn-light" href="taches.php?projet=<?php echo $idProjet ?>">Annuler</button> 
            </form>
        </main>
    </body>
</html>
