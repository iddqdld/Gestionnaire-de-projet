

<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $idCommentaire = $_GET["commentaire"];

    $query = "SELECT * FROM commentaire WHERE id = :idCommentaire";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idCommentaire', $idCommentaire, PDO::PARAM_STR);
    $stmt->execute();
    $commentaire = $stmt->fetch(PDO::FETCH_ASSOC);
    $idProjet = $commentaire['projet_id'];

    if ($commentaire == null) {
        header('Location: projets.php');
        exit();
    }

    $query = "DELETE FROM commentaire WHERE id = :idCommentaire";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idCommentaire', $idCommentaire, PDO::PARAM_STR);
    $stmt->execute();

    $location = 'commentaires.php?projet='.$idProjet;
    header('Location: '.$location);
    exit();

}
?>
