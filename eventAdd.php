<?php
    require 'database.php';
    ini_set("session.cookie_httponly",1);
    session_start();
    header("Content-Type: application/json");

    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);

    $stmt = $mysqli->prepare("insert into events (userid, title, description, category, date, time, is_public) values(?,?,?,?,?,?,?)");


    if(!$stmt) {
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
    $userId = $_SESSION['userid'];
    $title = htmlentities($json_obj['title']);
    $description = htmlentities($json_obj['description']);
    $date = htmlentities($json_obj['date']);
    $time = htmlentities($json_obj['time']);
    $isPublic = htmlentities($json_obj['is_public']);
    $category = htmlentities($json_obj['category']);
    $stmt->bind_param('sssssss', $userId, $title, $description, $category, $date, $time, $isPublic);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true
    ));
    exit;
?>
