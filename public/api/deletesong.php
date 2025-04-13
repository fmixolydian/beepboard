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
	$userdata = BB_getUserdataByToken($_COOKIE['token']);
	
	if (!$userdata) {
		http_response_code(400);
		echo 'invalid token, try relogging (maybe the servers are being shit)';
		die;
	}
	
	# check if song belongs to user
	$data = BB_sqlStatement("SELECT * FROM songs WHERE songid = :song AND authorid = :user",
									array(
										':user' => $userdata['userid'],
										':song' => $_GET['id']
									)
							) -> fetchArray();
	
	if (!$data) {
		http_response_code(403);
		echo 'you may not delete this song.';
		die;
	}
	
	BB_sqlStatement("DELETE FROM songs WHERE songid = :id",
						array(
							':id' =>  $_GET['id']
						)
					);
	
	header("Location: /mysongs.php");
?>