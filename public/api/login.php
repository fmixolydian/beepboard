<?php
	
	require '../../config.php';
	
	if (!array_key_exists("username", $_POST) ||
	    !array_key_exists("password", $_POST)) {
	    http_response_code(400);
		echo 'login failure';
	    die;
	}
	
	$db = new SQLite3(DB_PATH);
	
	# grab password hash
	$st = $db->prepare("SELECT password FROM users WHERE username = :name");
	$st->bindParam(':name', $_POST['username'], SQLITE3_TEXT);
	$hash = $st->execute()->fetchArray()[0];
	
	if (password_verify($_POST['password'], $hash)) {
		$token = uniqid('t_', true);
		
		# save token
		$st = $db->prepare("UPDATE users SET token = '$token' WHERE username = :name");
		$st->bindParam(':name', $_POST['username'], SQLITE3_TEXT);
		$st->execute();
		
		# store it in a cookie (yummy)
		setcookie('token', $token, time()+60*60*24*30, '/');
		
		echo 'logged in successfully! token: ' . $token;
	} else {
	    http_response_code(403);
	    echo 'wrong credentials.';
	}
	
?>