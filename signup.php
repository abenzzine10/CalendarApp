<?php
	require 'database.php';
	header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

	//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
	$json_str = file_get_contents('php://input');
	//This will store the data into an associative array
	$json_obj = json_decode($json_str, true);

	//Variables can be accessed as such:
	$usr = htmlentities($json_obj['usr']);
	// To sanitize the output
	if( !preg_match('/^[\w_\.\-]+$/', $usr) ){
		echo json_encode(array(
			"success" => false,
			"message" => "Invalid Username"
		));
		exit;
	}

	$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $usr);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	// Compare the submitted password to the actual password hash
	if($count == 0){
		$stmt2 = $mysqli->prepare("insert into users (username, password) values (?, ?)");
		if(!$stmt2) {
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$pwd = htmlentities($json_obj['pwd']);
		$hashed_pwd = password_hash($pwd, PASSWORD_BCRYPT);
		$stmt2->bind_param('ss', $usr, $hashed_pwd);
		$stmt2->execute();
		$stmt2->close();
		
		$stmt3 = $mysqli->prepare("select id from users where username=?");
		$stmt3->bind_param('s', $usr);
		$stmt3->execute();
		$stmt3->bind_result($userid);
		$stmt3->fetch();
		$stmt3->close();
		
		ini_set("session.cookie_httponly", 1);
		session_start();
		$_SESSION['usr'] = $usr;
		$_SESSION['userid'] = $userid;
		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

		echo json_encode(array(
			"success" => true,
			"usr" => $usr,
			"token" => $_SESSION['token']
		));
		exit;
	}else if($count == 1){
		echo json_encode(array(
			"success" => false,
			"message" => "The username you entered already exists",
		));
		exit;
	}else{
		echo json_encode(array(
			"success" => false,
			"message" => "Invalid username or password"
		));
		exit;
	}
?>