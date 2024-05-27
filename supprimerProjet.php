<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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

    $query = "DELETE FROM projet WHERE id = :idProjet";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: projets.php');
    exit();

}
?>
