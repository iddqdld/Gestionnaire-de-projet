

<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $idDocument = $_GET["document"];

    $query = "SELECT * FROM document WHERE id = :idDocument";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idDocument', $idDocument, PDO::PARAM_STR);
    $stmt->execute();
    $document = $stmt->fetch(PDO::FETCH_ASSOC);
    $idProjet = $document['projet_id'];

    if ($document == null) {
        header('Location: projets.php');
        exit();
    }

    unlink('./documents/'.$document['fichier']);

    $query = "DELETE FROM document WHERE id = :idDocument";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idDocument', $idDocument, PDO::PARAM_STR);
    $stmt->execute();

    $location = 'documents.php?projet='.$idProjet;
    header('Location: '.$location);
    exit();

}
?>
