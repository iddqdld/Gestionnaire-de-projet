<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProjet = $_POST['projet_id'];
    $description = $_POST['description'];
    $dateDebut = $_POST['date_debut'];
    $dateFinPrevue = $_POST['date_fin_prevue'];
    $statut = $_POST['statut'];

    // Vérifiez les informations d'identification dans la base de données
    $query = "INSERT INTO tache(projet_id,description,date_debut,date_fin_prevue,statut) VALUES(:idProjet,:description,:date_debut,:date_fin_prevue,:statut)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':date_debut', $dateDebut, PDO::PARAM_STR);
    $stmt->bindParam(':date_fin_prevue', $dateFinPrevue, PDO::PARAM_STR);
    $stmt->bindParam(':statut', $statut, PDO::PARAM_INT);
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

    if ($dateMin != null) {
        $query = "UPDATE projet SET date_debut = :date_debut WHERE id = :idProjet";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
        $stmt->bindParam(':date_debut', date('Y-m-d', $dateMin), PDO::PARAM_STR);
        $stmt->execute();
    }
    if ($dateMax != null) {
        $query = "UPDATE projet SET date_fin_prevue = :date_fin_prevue WHERE id = :idProjet";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_STR);
        $stmt->bindParam(':date_fin_prevue', date('Y-m-d', $dateMax), PDO::PARAM_STR);
        $stmt->execute();
    }
    $location = 'taches.php?projet='.$projet['id'];
    header('Location: '.$location);
    exit();
}
?>

<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Création tâche</title>

        <!-- line awesome cdn link  -->
        <link rel="stylesheet" href="line-awesome-1.3.0/css/line-awesome.min.css">
        <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="jquery-ui-bootstrap-jquery-ui-bootstrap-71f2e47/css/custom-theme/jquery-ui-1.9.2.custom.css">

        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="bootstrap-5.0.2-dist/js/bootstrap.js"></script>
        <script src="jquery-ui-bootstrap-jquery-ui-bootstrap-71f2e47/js/jquery-ui-1.9.2.custom.min.js"></script>
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

            <h2>Création d'une tâche pour le projet : <?php echo $projet['titre'] ?></h2>
            <?php if (isset($error_message)) : ?>
                <p class="text-danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="projet_id" required class="form-control" value="<?php echo $projet['id'] ?>">
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" required class="form-control" id="description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="date_debut" class="form-label">Date de début</label>
                    <input type="text" name="date_debut" required class="form-control" id="date_debut">
                </div>
                <div class="mb-3">
                    <label for="date_fin_prevue" class="form-label">Date de fin prévue</label>
                    <input type="text" name="date_fin_prevue" class="form-control" id="date_fin_prevue">
                </div>
                <div class="mb-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-select" name="statut" aria-label="statut">
                        <option selected>Sélectionner un statut</option>
                        <option value="1">Initialisée</option>
                        <option value="2">En cours</option>
                        <option value="3">Terminé</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Créer la tâche</button> 
                <a class="btn btn-light" href="taches.php?projet=<?php echo $projet['id'] ?>">Annuler</button> 
            </form>
        </main>
        <script>
            $(document).ready(function () {
                $(function () {
                    $("#date_debut").datepicker({
                        altField: "#datepicker",
                        closeText: 'Fermer',
                        prevText: 'Précédent',
                        nextText: 'Suivant',
                        currentText: 'Aujourd\'hui',
                        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
                        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                        weekHeader: 'Sem.',
                        dateFormat: 'yy-mm-dd'
                    });
                    $("#date_fin_prevue").datepicker({
                        altField: "#datepicker",
                        closeText: 'Fermer',
                        prevText: 'Précédent',
                        nextText: 'Suivant',
                        currentText: 'Aujourd\'hui',
                        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
                        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                        weekHeader: 'Sem.',
                        dateFormat: 'yy-mm-dd'
                    });                });
            });
        </script> 

    </body>
</html>
