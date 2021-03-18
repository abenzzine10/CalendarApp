<?php
    $mysqli = new mysqli('localhost', 'mod5_usr', 'mod5_pwd', 'mod5');

    if($mysqli->connect_errno) {
        printf("Connection Failed: %s\n", $mysqli->connect_error);
        exit;
    }
?>