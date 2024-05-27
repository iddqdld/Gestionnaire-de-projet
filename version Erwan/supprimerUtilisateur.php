<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION) || $_SESSION['admin'] != 1) {
    header('Location: login.php');
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

    $query = "DELETE FROM utilisateur WHERE login = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $login, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: utilisateurs.php');
    exit();

}
?>