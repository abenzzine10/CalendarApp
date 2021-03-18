<?php
    require 'database.php';
    ini_set("session.cookie_httponly",1);
    session_start();
    header("Content-Type: application/json");

    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str,true);

    $eventId = $json_obj['eventid'];
    // $token = $json_obj['token'];
    //Found request forgery
    // if(!hash_equals($_SESSION['token'],$token)){
    //     die("Found CSRF attack");
    // }

    $stmt = $mysqli->prepare("delete from events where eventid = ?");

    if(!$stmt){
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
    $stmt->bind_param('i',$eventId);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true
    ));

    exit;
?>