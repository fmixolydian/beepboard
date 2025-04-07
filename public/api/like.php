<?php
	
	require '../../config.php';
	require '../../functions.php';
	
	if (!array_key_exists("id", $_GET)) {
	    http_response_code(400);
		echo 'bad request';
	    die;
	}
	
	# check if token is valid
	if (!array_key_exists("token", $_COOKIE)) {
		header("Location: /login.php");
		die;
	}
	
	# get userid of user
	$data = BB_getUserdataByToken($_COOKIE['token']);
	
	if (!$data) {
		http_response_code(400);
		echo 'invalid token, try relogging (maybe the servers are being shit)';
		die;
	}
	
	# check if user has already liked the song
	$interaction = BB_sqlStatement("SELECT timestamp FROM interactions WHERE type = 'like'
							 AND songid = :song AND userid = :user",
							 array(
							 	':song' => $_GET['id'],
							 	':user' => $data['userid']
							 ))->fetchArray();
	
	if ($interaction) {
		http_response_code(400);
		echo 'you already liked this post.';
		die;
	}
	
	# add interaction
	BB_sqlStatement("INSERT INTO interactions (timestamp, type, songid, userid)
	VALUES (:tick, 'like', :song, :user)", array(
		':tick' => time(),
		':song' => $_GET['id'],
		':user' => $data['userid']
	));
	
	# increment likes counter
	BB_sqlStatement("UPDATE songs SET likes = likes + 1 WHERE songid = :id", 
		array(':id' => $_GET['id'])
	);
?>