<?php
// login.php
require 'database.php';
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$usr = htmlentities($json_obj['usr']);
//Sanitize
if( !preg_match('/^[\w_\.\-]+$/', $usr) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid Username"
	));
	exit;
}
$pwd = htmlentities($json_obj['pwd']);
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

// Check to see if the username and password are valid. 
$stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('s', $usr);
$stmt->execute();
$stmt->bind_result($count, $id, $hashed_pwd);
$stmt->fetch();

// Compare the submitted password to the actual password hash
if($count == 1 && password_verify($pwd, $hashed_pwd)){
	ini_set("session.cookie_httponly", 1);
	session_start();
	$_SESSION['usr'] = $usr;
	$_SESSION['userid'] = $id;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

	echo json_encode(array(
        "success" => true,
		"usr" => $usr,
		"token" => $_SESSION['token']
	));
	exit;
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}
?>