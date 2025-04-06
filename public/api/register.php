<?php
	
	require '../../config.php';
	require '../../functions.php';
	
	if (!array_key_exists("username", $_POST) ||
	    !array_key_exists("password", $_POST)) {
	    http_response_code(400);
		echo 'login failure';
	    die;
	}
	
	$db = new SQLite3(DB_PATH);
	
	# check if user already exists
	$st = $db->prepare("SELECT userid FROM users WHERE username = :name");
	$st->bindParam(':name', $_POST['username'], SQLITE3_TEXT);
	if ($st->execute()->fetchArray()) {
		// user already exists
		http_response_code(400);
		echo 'user already exists';
		die;
	}
	
	# hash the password
	$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
	
	# generate new user id
	$userid = uniqid('u_');
	
	# create new user
	$st = $db->prepare("INSERT INTO users (userid, createdtime, username, password) VALUES
	(?, ?, ?, ?)");
	
	$st->bindParam(1, $userid, SQLITE3_TEXT);
	$st->bindValue(2, time(), SQLITE3_INTEGER);
	$st->bindParam(3, $_POST["username"], SQLITE3_TEXT);
	$st->bindParam(4, $hash, SQLITE3_TEXT);
	
	$st->execute();
	
	# todo: redirect to user page
	header("Location: /");
?>