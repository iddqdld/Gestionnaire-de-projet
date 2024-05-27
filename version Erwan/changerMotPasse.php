<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM utilisateur WHERE login = :username";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $_SESSION['login'], PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['login'];
    $ancienPassword = $_POST['ancien_password'];
    $password = $_POST['password'];
    $confirmationPassword = $_POST['confirmation_password'];
    $passwordSha256 = hash('sha256', $ancienPassword);

    // Vérifiez les informations d'identification dans la base de données
    $query = "SELECT * FROM utilisateur WHERE login = :username AND password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $_SESSION['login'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $passwordSha256, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user != null) {
        if ($password != $confirmationPassword) {
            $error_message = 'les 2 mots de passe sont différents';
        } else {
            $error_message = null;
            $passwordSha256 = hash('sha256', $password);
            $query = "UPDATE utilisateur SET password = :password WHERE login = :username";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $_SESSION['login'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $passwordSha256, PDO::PARAM_STR);
            $stmt->execute();
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
        <title>Changement Mot passe</title>

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
                        <h5 class="card-title">Changement de mot de passe</h5>
                        <?php if (isset($error_message)) : ?>
                            <p class="text-danger"><?php echo $error_message; ?></p>
                        <?php endif; ?>
                        <form method="post" action="changerMotPasse.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>  
                                <input type="text" name="username" class="form-control" disabled id="username" aria-describedby="username" value="<?php echo $_SESSION['login'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="ancien_password" class="form-label">Ancien mot de passe</label>
                                <input type="password" name="ancien_password" required class="form-control" id="ancien_password">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="password" required class="form-control" id="password">
                            </div>
                            <div class="mb-3">
                                <label for="confirmation_password" class="form-label">Confirmation nouveau mot de passe</label>
                                <input type="password" name="confirmation_password" required class="form-control" id="confirmation_password">
                            </div>
                            <button type="submit" class="btn btn-primary">Changer le mot de passe</button> 
                        </form>
                    </div>
                </div>
            </div>
        </main>

    </body>
</html>
