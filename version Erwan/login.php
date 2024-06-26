<?php
session_start();
include('db.php');

if (array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $passwordSha256 = hash('sha256', $password);

    // Vérifiez les informations d'identification dans la base de données
    $query = "SELECT * FROM utilisateur WHERE mail = :mail AND password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->bindParam(':password', $passwordSha256, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user != null) {
        if ($user['bloque'] == 1) {
            $error_message = 'Le compte est bloqué';
        } else {
            $_SESSION['login'] = $user['mail'];
            $_SESSION['admin'] = 0;
            if ($user['profil'] == 'administrateur') {
                $_SESSION['admin'] = 1;
            }
            header('Location: index.php');
            exit();
        }
    } else {
        $error_message = 'Identifiants incorrects';
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
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Connexion</h5>
                        <?php if (isset($error_message)) : ?>
                            <p class="text-danger"><?php echo $error_message; ?></p>
                        <?php endif; ?>
                        <div>
                            Pas encore inscrit ? <a href="inscription.php">S'inscrire</a>
                        </div>

                        <form method="post" action="login.php">
                            <div class="mb-3">
                                <label for="mail" class="form-label">Email</label>
                                <input type="text" name="mail" required class="form-control" id="mail" aria-describedby="username">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" required class="form-control" id="password">
                            </div>
                            <button type="submit" class="btn btn-primary">Se connecter</button> 
                        </form>
                    </div>
                </div>
            </div>
        </main>

    </body>
</html>
