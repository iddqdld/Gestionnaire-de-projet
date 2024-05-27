<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION) || $_SESSION['admin'] != 1) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $login = $_GET["login"];

    $query = "SELECT * FROM utilisateur WHERE login = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $login, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user == null) {
        header('Location: utilisateurs.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $profil = $_POST['profil'];
    $equipe = $_POST['equipe'];

    // Vérifiez les informations d'identification dans la base de données
    $query = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, profil = :profil, equipe = :equipe WHERE login = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':profil', $profil, PDO::PARAM_STR);
    $stmt->bindParam(':equipe', $equipe, PDO::PARAM_STR);
    $stmt->execute();
    header('Location: utilisateurs.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modification Utilisateur</title>

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

            <div class="d-flex justify-content-center">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Modification d'un utilisateur</h5>
                        <?php if (isset($error_message)) : ?>
                            <p class="text-danger"><?php echo $error_message; ?></p>
                        <?php endif; ?>
                        <form method="post" action="modifierUtilisateur.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>  
                                <input type="text" name="username_affiche" class="form-control" disabled aria-describedby="username" value="<?php echo $login ?>">
                                <input type="hidden" name="username" class="form-control" id="username" aria-describedby="username" value="<?php echo $login ?>">
                            </div>
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" name="nom" required class="form-control" id="nom" value="<?php echo $user['nom'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" name="prenom" required class="form-control" id="prenom" value="<?php echo $user['prenom'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="profil" class="form-label">Profil</label>
                                <input type="text" name="profil" required class="form-control" id="profil" value="<?php echo $user['profil'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="equipe" class="form-label">Equipe</label>
                                <input type="text" name="equipe" required class="form-control" id="equipe" value="<?php echo $user['equipe'] ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Modifier l'utilisateur</button> 
                        </form>
                    </div>
                </div>
            </div>
        </main>

    </body>
</html>
