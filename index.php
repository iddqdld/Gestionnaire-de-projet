
<?php
    session_start();
    include('db.php');
    if (array_key_exists('login',$_SESSION)) {
        header('Location: projets.php');exit;
    }
    header('Location: login.php');exit;
?>

