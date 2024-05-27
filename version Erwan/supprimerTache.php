

<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $idTache = $_GET["tache"];

    $query = "SELECT * FROM tache WHERE id = :idTache";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idTache', $idTache, PDO::PARAM_STR);
    $stmt->execute();
    $tache = $stmt->fetch(PDO::FETCH_ASSOC);
    $idProjet = $tache['projet_id'];

    if ($tache == null) {
        header('Location: projets.php');
        exit();
    }

    $query = "DELETE FROM tache WHERE id = :idTache";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idTache', $idTache, PDO::PARAM_STR);
    $stmt->execute();

    $query = "SELECT * FROM tache WHERE projet_id = :idProjet";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
    $stmt->execute();
    $taches = $stmt->fetchAll(PDO::FETCH_BOTH);

    $dateMin = null;
    $dateMax = null;
    foreach ($taches as $tache) {
        if ($dateMin == null || $dateMin > strtotime($tache['date_debut'])) {
            $dateMin = strtotime($tache['date_debut']);
        }
    if ($dateMax == null || $dateMax < strtotime($tache['date_fin_prevue'])) {
            $dateMax = strtotime($tache['date_fin_prevue']);
        }
    }

    $query = "UPDATE projet SET date_debut = :date_debut, date_fin_prevue = :date_fin_prevue WHERE id = :idProjet";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
    $stmt->bindParam(':date_debut', date('Y-m-d', $dateMin), PDO::PARAM_STR);
    $stmt->bindParam(':date_fin_prevue', date('Y-m-d', $dateMax), PDO::PARAM_STR);
    $stmt->execute();

    $location = 'taches.php?projet='.$idProjet;
    header('Location: '.$location);
    exit();

}
?>
