<?php
session_start();
include('db.php');

if (!array_key_exists('login', $_SESSION)) {
    header('Location: index.php');
    exit();
}

$query = "SELECT * FROM utilisateur ORDER BY mail ASC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_BOTH);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $dateDebut = $_POST['date_debut'];
    $dateFinPrevue = $_POST['date_fin_prevue'];
    $responsable = $_POST['responsable'];
    $client = $_POST['client'];
    $budget = $_POST['budget'];
    $priorite = $_POST['priorite'];

    if (strtotime($dateDebut) >= strtotime($dateFinPrevue)) {
        $error_message = 'la date de fin prévue doit être postérieure à la date de début';
    } else {

        $query = "INSERT INTO projet(titre,description,date_debut,date_fin_prevue,responsable,client,budget,priorite) VALUES(:titre,:description,:date_debut,:date_fin_prevue,:responsable,:client,:budget,:priorite)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':date_debut', $dateDebut, PDO::PARAM_STR);
        $stmt->bindParam(':date_fin_prevue', $dateFinPrevue, PDO::PARAM_STR);
        $stmt->bindParam(':responsable', $responsable, PDO::PARAM_INT);
        $stmt->bindParam(':client', $client, PDO::PARAM_STR);
        $stmt->bindParam(':budget', $budget, PDO::PARAM_STR);
        $stmt->bindParam(':priorite', $priorite, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: projets.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Création Projet</title>

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

            <h2>Création d'un projet</h2>
            <?php if (isset($error_message)) : ?>
                <p class="text-danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre</label>  
                    <input type="text" name="titre" class="form-control" aria-describedby="titre">
                </div>
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
                    <label for="responsable" class="form-label">Responsable</label>
                    <select class="form-select" name="responsable" id="responsable">
                        <option selected value="0">sélectionner un responsable</option>
                        <?php foreach ($utilisateurs as $utilisateur) : ?>
                            <option value="<?php echo $utilisateur['id']?>"><?php echo $utilisateur['mail'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="client" class="form-label">Client</label>
                    <input type="text" name="client" class="form-control" id="client">
                </div>
                <div class="mb-3">
                    <label for="budget" class="form-label">Budget</label>
                    <input type="text" name="budget" class="form-control" id="budget">
                </div>
                <div class="mb-3">
                    <label for="priorite" class="form-label">Priorite</label>
                    <select class="form-select" name="priorite" aria-label="priorite">
                        <option selected>Sélectionner une priorité</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Créer le projet</button> 
                <a class="btn btn-light" href="projets.php">Annuler</button> 
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
