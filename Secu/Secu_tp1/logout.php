<?php
    session_start();
    session_unset();
    session_destroy();
    header('location: /Secu/Secu_tp1/index.php');


?>


