<?php
	require '../../config.php';
	
	if (!array_key_exists("id", $_GET)) {
		http_response_code(400);
		echo "You must provide <code>id</code>.";
		die;
	}
	
	$db = new SQLite3(DB_PATH);
	
	# update downloads counter
	$st = $db->prepare("UPDATE songs SET downloads = downloads + 1 WHERE songid = :id");
	$st->bindParam(':id', $_GET['id'], SQLITE3_TEXT);
	$st->execute();
	
	# get song url
	$st = $db->prepare("SELECT songurl FROM songs WHERE songid = :id");
	$st->bindParam(':id', $_GET['id'], SQLITE3_TEXT);
	$url = $st->execute()->fetchArray()[0];
	
	# try redirecting
	if ($url) {
		header('Location: ' . $url);
		echo "Redirecting...";
	} else {
		http_response_code(404);
		echo "Song not found.";
	}
?>