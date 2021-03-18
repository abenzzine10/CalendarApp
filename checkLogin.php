<?php
    require 'database.php';
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly",1);
    session_start();

    if (isset($_SESSION['userid']) && !empty($_SESSION['userid'])) {

        echo json_encode(array(
            "success" => true,
            "usr" => $_SESSION['usr']
        ));
        exit;
    }else{
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
?>