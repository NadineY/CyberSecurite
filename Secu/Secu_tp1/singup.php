<?php
    require "database.php";

    $message = '';

    if(!empty($_POST["email"])&& !empty($_POST["password"])){
        $sql_0 = "SELECT * FROM users WHERE email = ? ";
        $stmt = $connection->prepare($sql_0);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute(array($_POST['email']));
        $result = $stmt->fetch();

        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $pattern = '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])[0-9A-Za-z!@#$%^&*]{8,}$/';

        if ($result == false && $result->num_rows <= 0) {
            
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $message = "Veuillez saisir un email valide";
            }else{

                if (preg_match($pattern, $password)) {
            
                    if ($_POST['password'] == $_POST['confirm_password']) {
                        $sql = "INSERT INTO users (email, password) VALUES (:email, :password )";
                        $statement = $connection->prepare($sql);
                        $statement->bindParam(':email', $_POST['email']);
                        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                        $statement->bindParam(':password', $password);
                    
                            if($statement->execute()){
                                $message = 'vous etes bien inscrit ';
                                header('location: /Secu/Secu_tp1/login.php');
                            }else{
                                $message = 'Erreur lors de votre inscription';
                            }
                    }else{
                        $message = "Erreur de lors de la saisie de votre mot de passe";
                    }

                }else{
                    $message = "Mot de passe doit contenir au min [ 8 character, 1 Majuscule, 1 minuscule, 1 chiffre, 1 character spéciale ] ";
                }
            }
    }else{
        $message = "Email deja existant";
    }

}else{
    $message = "Veuillez remplir les champs";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
    <?php require 'partials/header.php' ?>

    <h1>Page d'inscription</h1>

    <?php if(!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form action="singup.php" method="post">
        <input type="text" name="email" placeholder="Entrer votre Email">
        <input type="password" name="password" placeholder="Entrer votre Mot de passe">
        <input type="password" name="confirm_password" placeholder="Confirmer votre Mot de passe">
        <input type ="reset" value="reset">
        <input type="submit" value="valider">
    </form>

    <form action="login.php">
        <input type ="submit" value="connexion"></input>
    </form>
</body>
</html>