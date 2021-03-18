<?php
    require 'database.php';
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly", 1);
    session_start();
    $json_str = file_get_contents('php://input');

    $json_obj = json_decode($json_str, true);
    $month = htmlentities($json_obj['month']);
    $day = htmlentities($json_obj['day']);
    $userId = $_SESSION['userid'];

    $events = [];
    $stmt = $mysqli->prepare("SELECT eventid, title, description, category, date, time, is_public FROM events WHERE (userid=? or is_public=true) and (MONTH(date)=?) and (DAY(date)=?)");

    $stmt->bind_param('iss', $userId, $month, $day);
    $stmt->execute();
    $stmt->bind_result($eventId, $title, $description, $category, $date, $time, $isPublic);

    while($stmt->fetch()){
        array_push($events, array(
            "eventid" => $eventId,
            "title" => $title,
            "description" => $description,
            "category" => $category,
            "date" => (String)$date,
            "time" => (String)$time,
            "is_public" => $isPublic
        ));
    }

    echo json_encode(array(
        "success" => true,
        "events" => $events
    ));
    exit;
    
?>