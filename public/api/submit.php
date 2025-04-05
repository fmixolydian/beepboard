<?php
	
	require '../../config.php';
	
	if (!array_key_exists("songname", $_POST) ||
	    !array_key_exists("url", $_POST)) {
	    http_response_code(400);
		echo 'bad parameters';
	    die;
	}
	
	if (!array_key_exists("tags", $_POST)) $_POST['tags'] = "";
	if (!array_key_exists("desc", $_POST)) $_POST['desc'] = "";
	
	$db = new SQLite3(DB_PATH);
	
	# first, get authorid from token
	if (!array_key_exists("token", $_COOKIE)) {
		header("Location: /login.php");
		die;
	}
	
	$st = $db->prepare("SELECT userid FROM users WHERE token = :token");
	$st->bindParam(":token", $_COOKIE['token'], SQLITE3_TEXT);
	$q = $st->execute();
	$authorid = $q->fetchArray()[0];
	
	if ($authorid == NULL) {
		header("Location: /login.php");
		die;
	}
	
	$songid = uniqid('s_');
	
	$st = $db->prepare("INSERT INTO songs (songid, authorid, songurl, createdtime, tags, name, desc)
						VALUES (?, ?, ?, ?, ?, ?, ?)");
	$st->bindParam(1, $songid, SQLITE3_TEXT);
	$st->bindParam(2, $authorid, SQLITE3_TEXT);
	$st->bindParam(3, $_POST['url'], SQLITE3_TEXT);
	$st->bindValue(4, time(), SQLITE3_TEXT);
	$st->bindParam(5, $_POST["tags"], SQLITE3_TEXT);
	$st->bindParam(6, $_POST["songname"], SQLITE3_TEXT);
	$st->bindParam(7, $_POST["songdesc"], SQLITE3_TEXT);
	$st->execute();
	
	echo "Song successfully submitted."
?>