<?php
    session_start();

   // ini_set('session.gc_maxlifetime', 3*60);
   if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    }

    require 'database.php';

    if(!empty($_POST['email']) && !empty($_POST['password'])){
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $message = "Veuillez saisir un email valide";
          }else{

        $_SESSION['attempts']++;
        $records = $connection->prepare('SELECT id, email, password FROM users WHERE email=:email');
        $records->bindParam(':email', $_POST['email']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        $message = '';

        if(count($results)> 0 && password_verify($_POST['password'], $results['password'])){
            $_SESSION['user_id'] = $results['id'];
            $_SESSION['attempts'] = 0;
            header('location: /Secu/Secu_tp1/index.php');
        }else{
            $message = "Erreur de lors de la saisie de votre mot de passe";
            if ($_SESSION['attempts'] >= 3) {
            // Block the user for 5 minutes
            sleep(5);
            $_SESSION['attempts'] = 0;
            }
        }
    }
    }

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3*60)) {
        session_unset();
        session_destroy();
        header("location: /Secu/Secu_tp1/index.php");
        exit();
    }

    $_SESSION['LAST_ACTIVITY'] = time();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
    <?php require 'partials/header.php' ?>
    <h1>Page de Connexion</h1>
    
    <?php if (!empty($message)):?>
        <p><?= $message ?></p>
    <?php endif; ?>
    
    <form action="login.php" method="post">
        <input type="text" name="email" placeholder="Entrer votre Email">
        <input type="password" name="password" placeholder="Entrer votre Mot de passe">
        <input type ="reset" value="reset">
        <input type="submit" value="valider">
    </form>

    <form action="singup.php">
        <input type ="submit" value="inscription"></input>
    </form>

</body>
</html>