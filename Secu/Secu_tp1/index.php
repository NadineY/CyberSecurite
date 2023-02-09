<?php
    session_start();
    require 'database.php';
    if(isset($_SESSION['user_id'])){
        $records = $connection->prepare('SELECT id, email, password FROM users WHERE id = :id');
        $records->bindParam(':id', $_SESSION['user_id']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        $user = null;

        if(count($results)>0){
            $user = $results;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php require 'partials/header.php' ?>
        <?php if(!empty($user)):?>
        <br>Bienvenue. <?= $user['email'] ?>
        <br>Vous etes bien connecté <a href="logout.php">Deconnexion</a>
    <?php else: ?>
        <form action="login.php">
        <h3>Déja inscrit ?</h3>
        <input type ="submit" value="Je me connecte"></input>
        </form>
<br>
        <form action="singup.php">
        <h3>Premiere fois dans le site ?</h3>
        <input type ="submit" value="Je m'inscrit"></input>
        </form>

    <?php endif; ?>
</body>
</html>