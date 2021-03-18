<?php
    require "database.php"; 
    header("Content-Type: application/json"); 
    ini_set("session.cookie_httponly", 1);
    session_start(); 

    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);

    $eventId = htmlentities($json_obj['eventid']);
    $title = htmlentities($json_obj['title']);
    $description = htmlentities($json_obj['description']);
    $date = htmlentities($json_obj['date']);
    $time = htmlentities($json_obj['time']);
    $isPublic = htmlentities($json_obj['is_public']);
    $category = htmlentities($json_obj['category']);
    /*$token = htmlentities($json_obj['token']);
    if (!hash_equals($_SESSION["token"], $token)){
    echo json_encode(array(
        "success" => false,
        "message" => "CSRF Attack found"
    ));
    exit; 
    }*/

    $stmt = $mysqli->prepare("update events SET title=?, description=?, date=?, time=?, is_public=?, category=? where eventid=?");
    
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed"
        ));
        exit;
    }
    //Bind parameters
    $stmt->bind_param('ssssssi', $title, $description, $date, $time, $isPublic, $category, $eventId);      
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true,
        "message" => "Edited Successfully."
    ));
    exit; 
?>