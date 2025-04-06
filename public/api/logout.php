<?php
	
	require '../../config.php';
	require '../../functions.php';
	
	$db = new SQLite3(DB_PATH);
	
	if (!array_key_exists('token', $_COOKIE)) {
		http_response_code(400);
		echo "You're not logged in.";
		die;
	}
	
	# check if token is valid
	if (BB_getUserdataByToken($_COOKIE['token'])) {
		# token valid
		$st = $db->prepare("UPDATE users SET token = NULL WHERE token = :t");
		$st->bindParam(':t', $_COOKIE['token'], SQLITE3_TEXT);
		$st->execute();
		
		unset($_COOKIE['token']);
		setcookie('token', '', -1, '/');
		
		header('Location: /');
	}
?>