<?php
session_start();
include('db.php');

if (array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = $_POST['password'];
    $confirmationPassword = $_POST['confirmPassword'];
    $equipe = $_POST['equipe'];
    $profil = 'utilisateur';
    $bloque = 1;

    if ($password != $confirmationPassword) {
        $error_message = 'les 2 mots de passe sont différents';
    } else {
        $query = "SELECT * FROM utilisateur WHERE mail = :mail";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();
        $comptes = $stmt->fetchAll();
        if (count($comptes) > 0) {
            $error_message = 'il existe dèjà un compte avec ce mail. En choisir un autre.';
        } else {
            $passwordSha256 = hash('sha256', $password);

            $query = "INSERT INTO utilisateur(mail,nom,prenom,password,profil,equipe,bloque) VALUES (:mail,:nom,:prenom,:password,:profil,:equipe,:bloque)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':password', $passwordSha256, PDO::PARAM_STR);
            $stmt->bindParam(':profil', $profil, PDO::PARAM_STR);
            $stmt->bindParam(':equipe', $equipe, PDO::PARAM_STR);
            $stmt->bindParam(':bloque', $bloque, PDO::PARAM_INT);
            $stmt->execute();

            header('Location: index.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
    <style>
    </style>

</head>

<body>
<nav  id="bandeau" class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
</nav>
<main class="container">

    <div class="d-flex justify-content-center">
        <div class="card" style="width: 36rem;">
            <div class="card-body">
                <h5 class="card-title">Inscription</h5>
                <?php if (isset($error_message)) : ?>
                    <p class="text-danger"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <form method="post" action="">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="mail" class="form-label">Email</label>
                                <input type="text" name="mail" required class="form-control" id="mail" aria-describedby="mail">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" required class="form-control" id="password">
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmation Mot de passe</label>
                                <input type="password" name="confirmPassword" required class="form-control" id="confirmPassword">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" name="nom" required class="form-control" id="nom" aria-describedby="nom">
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" name="prenom" required class="form-control" id="prenom" aria-describedby="prenom">
                            </div>
                            <div class="mb-3">
                                <label for="equipe" class="form-label">Equipe</label>
                                <input type="text" name="equipe" required class="form-control" id="equipe" aria-describedby="equipe">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                    <a class="btn btn-light" href="login.php">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</main>

</body>
</html>