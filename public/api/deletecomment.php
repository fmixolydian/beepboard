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
	
	# check if comment belongs to user
	$data = BB_sqlStatement("SELECT * FROM comments WHERE commentid = :comment AND userid = :user",
									array(
										':user' => $userdata['userid'],
										':comment' => $_GET['id']
									))->fetchArray();
	
	if (!$data) {
		http_response_code(403);
		echo 'you may not delete this comment.';
		die;
	}
	
	BB_sqlStatement("DELETE FROM comments WHERE commentid = :comment",
						array(
							':comment' =>  $_GET['id']
						));
	
	header("Location: /viewsong.php?id=" . $data['songid']);
?>