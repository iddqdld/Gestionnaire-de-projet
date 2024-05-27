<?php
session_start();
?>

<div class="container-fluid">
    <a class="navbar-brand" href="./index.php"><i class="las la-2x la-cogs"></i></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <i class="las la-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
            <li class="nav-item">
                <a class="nav-link <?php if (!array_key_exists('login', $_SESSION)) : ?>disabled<?php endif; ?>" aria-current="page" href="./projets.php">Projets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if (!array_key_exists('login', $_SESSION) || $_SESSION['admin'] != '1') : ?>disabled<?php endif; ?>" href="./utilisateurs.php">Utilisateurs</a>
            </li>
        </ul>
        <?php if (array_key_exists('login', $_SESSION)) : ?>
            <form id="recherche" class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Recherche" aria-label="Search">
                <button class="btn btn-outline-success" type="submit"><i class="las la-search"></i></button>
            </form>
            <div class="dropdown ms-1">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownUtilisateur" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="las la-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end w-auto" aria-labelledby="dropdownUtilisateur">
                    <li>connecté avec le compte <?php echo $_SESSION['login'] ?></li>
                    <li><a href="logout.php">Se déconnecter</li>
                    <li><a href="changerMotPasse.php">Changer le mot de passe</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
