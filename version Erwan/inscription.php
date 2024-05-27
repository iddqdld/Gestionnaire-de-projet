<?php
session_start();
include('db.php');

if (array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $password = $_POST['password'];
    $confirmationPassword = $_POST['confirmPassword'];

    if ($password != $confirmationPassword) {
        $error_message = 'les 2 mots de passe sont différents';
    } else {
        $query = "SELECT * FROM utilisateur WHERE prenom = :prennom";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->execute();
        $comptes = $stmt->fetchAll();
        if (count($comptes) > 0) {
            $error_message = 'il existe dèjà un compte avec ce prenom. En choisir un autre.';
        } else {
            $query = "SELECT * FROM utilisateur WHERE nom = :nom";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->execute();
            $comptes = $stmt->fetchAll();
            if (count($comptes) > 0) {
                $error_message = 'ce nom est déjà associé à un compte';
            } else {
                $passwordSha256 = hash('sha256', $password);

                // Vérifiez les informations d'identification dans la base de données
                $query = "INSERT INTO utilisateur(username,password,mail,admin,bloquer) values(:username,:password,:email,1,0)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $stmt->bindParam(':password', $passwordSha256, PDO::PARAM_STR);
                $stmt->execute();

                header('Location: index.php');
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
<h2>Inscription</h2>
<?php if (isset($error_message)) : ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>

<form method="post" action="">
    <label for="prenonm">Prenom:</label>
    <input type="text" name="prenom" required><br>
    <label for="password">Nom:</label>
    <input type="text" name="nom" required><br>
    <label for="password">Password:</label>
    <input type="password" name="password" required><br>
    <label for="password">Confirmation Password:</label>
    <input type="password" name="confirmPassword" required><br>

    <button type="submit">S'inscrire</button>
    <div>
        Vous avez deja un compte ? <a href="login.php">Annuler</a>
    </div>
</form>

</body>
</html>