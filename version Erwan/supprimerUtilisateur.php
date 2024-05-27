<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION) || $_SESSION['admin'] != 1) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $mail = $_GET["mail"];

    $query = "SELECT * FROM utilisateur WHERE mail = :mail";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user == null) {
        header('Location: utilisateurs.php');
        exit();
    }

    $query = "DELETE FROM utilisateur WHERE mail = :mail";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: utilisateurs.php');
    exit();

}
?>