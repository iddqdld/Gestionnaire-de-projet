

<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $idTache = $_GET["tache"];
    $idUtilisateur = $_GET["utilisateur"];

    $query = "SELECT * FROM contribution WHERE tache_id = :idTache AND utilisateur_id = :idUtilisateur";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idTache', $idTache, PDO::PARAM_STR);
    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_STR);
    $stmt->execute();
    $contribution = $stmt->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM tache WHERE id = :idTache";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idTache', $idTache, PDO::PARAM_STR);
    $stmt->execute();
    $tache = $stmt->fetch(PDO::FETCH_ASSOC);

    $idProjet = $tache['projet_id'];

    if ($contribution == null) {
        header('Location: projets.php');
        exit();
    }

    $query = "DELETE FROM contribution  WHERE tache_id = :idTache AND utilisateur_id = :idUtilisateur";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idTache', $idTache, PDO::PARAM_STR);
    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_STR);
    $stmt->execute();

    $location = 'taches.php?projet='.$idProjet;
    header('Location: '.$location);
    exit();

}
?>
