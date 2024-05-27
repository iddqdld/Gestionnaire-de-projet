<?php
session_start();
include('db.php');

$query = "SELECT * FROM projet";
$stmt = $pdo->prepare($query);
$stmt->execute();
$projets = $stmt->fetchAll(PDO::FETCH_BOTH);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Projets</title>

        <!-- line awesome cdn link  -->
        <link rel="stylesheet" href="line-awesome-1.3.0/css/line-awesome.min.css">
        <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">

        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="bootstrap-5.0.2-dist/js/bootstrap.js"></script>
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

            <div>
                <h2>Projets</h2>
                <div>
                    <a class="btn btn-primary" href="creerProjet.php"><i class="las la-plus"></i> Nouveau Projet</a>
                </div>
                <?php if (count($projets) == 0) : ?>
                    aucun projet
                <?php else : ?>
                    <div class="row border-bottom d-flex align-items-center">
                        <div class="col">
                            Titre
                        </div>
                        <div class="col">
                            Description
                        </div>
                        <div class="col">
                            Date début
                        </div>
                        <div class="col">
                            Date fin prévue
                        </div>
                        <div class="col">
                            Responsable
                        </div>
                        <div class="col">
                            Client
                        </div>
                        <div class="col">
                            Budget
                        </div>
                        <div class="col">
                            Priorité
                        </div>
                        <div class="col">
                        </div>
                    </div>
                    <?php foreach ($projets as $projet) : ?>
                        <div class="row d-flex align-items-center">
                            <div class="col">
                                <a title="voir le projet" href="projet.php?projet=<?php echo $projet['id'] ?>"><?php echo $projet['titre'] ?></a>
                            </div>
                            <div title="<?php echo $projet['description'] ?>" class="col text-truncate">
                                <?php echo $projet['description'] ?>
                            </div>
                            <div class="col">
                                <?php echo date_format(date_create($projet['date_debut']),"d/m/Y") ?>
                            </div>
                            <div class="col">
                                <?php echo date_format(date_create($projet['date_fin_prevue']),"d/m/Y") ?>
                            </div>
                            <div class="col">
                                <?php echo $projet['responsable'] ?>
                            </div>
                            <div class="col">
                                <?php echo $projet['client'] ?>
                            </div>
                            <div class="col">
                                <?php echo number_format($projet['budget'],2, ',', ' ') ?> €
                            </div>
                            <div class="col">
                                <?php echo $projet['priorite'] ?>
                            </div>
                            <div class="col">
                                <a title="modifier le projet" class="btn btn-light" href="modifierProjet.php?projet=<?php echo $projet['id'] ?>"><i class="las la-pen"></i></a>
                                <a title="supprimer le projet" class="btn btn-danger" href="supprimerProjet.php?projet=<?php echo $projet['id'] ?>"><i class="las la-trash"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>

    </body>
</html>
