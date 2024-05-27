<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION) || $_SESSION['admin'] != 1) {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM utilisateur ORDER BY login";
$stmt = $pdo->prepare($query);
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_BOTH);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Utilisateurs</title>

        <!-- line awesome cdn link  -->
        <link rel="stylesheet" href="line-awesome-1.3.0/css/line-awesome.min.css">
        <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">

        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="bootstrap-5.0.2-dist/js/bootstrap.js"></script>
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

            <div>
                <h2>Utilisateurs</h2>
                <div class="row border-bottom d-flex align-items-center">
                    <div class="col">
                        Login
                    </div>
                    <div class="col">
                        Identité
                    </div>
                    <div class="col">
                        Profil
                    </div>
                    <div class="col">
                        Equipe
                    </div>
                    <div class="col">
                        Etat du compte
                    </div>
                    <div class="col">
                    </div>
                </div>
                <?php foreach ($utilisateurs as $utilisateur) : ?>
                    <div class="row d-flex align-items-center">
                        <div class="col">
                            <?php echo $utilisateur['login'] ?>
                        </div>
                        <div class="col">
                            <?php echo ($utilisateur['prenom'].' '.$utilisateur['nom']) ?>
                        </div>
                        <div class="col">
                            <?php echo $utilisateur['profil'] ?>
                        </div>
                        <div class="col">
                            <?php echo $utilisateur['equipe'] ?>
                        </div>
                        <div class="col">
                            <?php if ($utilisateur['bloque'] == 1) : ?>
                                <span class="text-danger"><i class="las la-ban"></i></span>
                            <?php else : ?>
                                <span class="text-success"><i class="las la-check"></i></span>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <?php if ($_SESSION['login'] != $utilisateur['login']) : ?>
                                <?php if ($utilisateur['bloque'] == 1) : ?>
                                    <a title="valider l'utilisateur" class="btn btn-success" href="validerUtilisateur.php?login=<?php echo $utilisateur['login'] ?>"><i class="las la-check"></i></a>
                                <?php else : ?>
                                    <a title="bloquer l'utilisateur" class="btn btn-danger" href="bloquerUtilisateur.php?login=<?php echo $utilisateur['login'] ?>"><i class="las la-ban"></i></a>
                                <?php endif; ?>
                                <a title="modifier l'utilisateur" class="btn btn-light" href="modifierUtilisateur.php?login=<?php echo $utilisateur['login'] ?>"><i class="las la-pen"></i></a>
                                <a title="supprimer l'utilisateur" class="btn btn-danger" href="supprimerUtilisateur.php?login=<?php echo $utilisateur['login'] ?>"><i class="las la-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>

    </body>
</html>
