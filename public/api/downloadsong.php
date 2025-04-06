<?php
	require '../../config.php';
	require '../../functions.php';
	
	if (!array_key_exists("id", $_GET)) {
		http_response_code(400);
		echo "You must provide <code>id</code>.";
		die;
	}
	
	$db = new SQLite3(DB_PATH);
	
	# update downloads counter
	BB_sqlStatement("UPDATE songs SET downloads = downloads + 1 WHERE songid = :id",
						array(':id' => $_GET['id'])
					);
	
	# get song url
	$result = BB_getSongdataById($_GET['id']);
	
	# try redirecting
	if ($result) {
		header('Location: ' . $result['url']);
		echo "Redirecting...";
	} else {
		http_response_code(404);
		echo "Song not found.";
	}
?>