<?php
	
	require '../../config.php';
	require '../../functions.php';
	
	if (!array_key_exists("content", $_POST) ||
	    !array_key_exists("songid", $_POST)
	    ) {
	    http_response_code(400);
		echo 'bad parameters';
	    die;
	}
	
	# first, get authorid from token
	if (!array_key_exists("token", $_COOKIE)) {
		header("Location: /login.php");
		die;
	}
	
	$userid = BB_getUserdataByToken($_COOKIE['token'])['userid'];
	
	if ($userid == NULL) {
		header("Location: /login.php");
		die;
	}
	
	$commentid = uniqid('c_');
	
	# add comment
	BB_sqlStatement("INSERT INTO comments (timestamp, commentid, userid, songid, content)
					VALUES (:t, :comment, :user, :song, :data)", array(
						':t'       => time(),
						':comment' => $commentid,
						':user'    => $userid,
						':song'    => $_POST['songid'],
						':data'    => $_POST['content'],
					));
	
	header("Location: /viewsong.php?id=" . $_POST['songid']);
?>