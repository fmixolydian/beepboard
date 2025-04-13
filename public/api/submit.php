<?php
	
	require '../../config.php';
	require '../../functions.php';
	
	if (!array_key_exists("songname", $_POST) ||
	    !array_key_exists("url", $_POST)) {
	    http_response_code(400);
		echo 'bad parameters';
	    die;
	}
	
	BB_default($_POST['tags'],     "");
	BB_default($_POST['summary'],  "(no summary)");
	BB_default($_POST['songdesc'], "(no description)");
	
	# do a bunch of verification
	
	# url
	filter_var($_POST['url'], FILTER_SANITIZE_URL);
	if (!filter_var($_POST['url'], FILTER_VALIDATE_URL)) {
		http_response_code(400);
		echo "invalid url";
		die;
	}
	
	# check if url is one of the beepmods
	$urlvalid = false;
	foreach (BEEPMODS_URL as $mod => $modurl) {
		if (str_starts_with($_POST['url'], $modurl)) {
			$urlvalid = true;
			break;
		}
	}
	if (!$urlvalid) {
		http_response_code(400);
		echo "invalid url";
		die;
	}
	
	if (count(explode(",", $_POST['tags'])) > 16) {
		http_response_code(400);
		echo "too many tags";
		die;
	 }
	
	# first, get authorid from token
	if (!array_key_exists("token", $_COOKIE)) {
		header("Location: /login.php");
		die;
	}
	
	$authorid = BB_getUserdataByToken($_COOKIE['token'])['userid'];
	
	if ($authorid == NULL) {
		header("Location: /login.php");
		die;
	}
	
	$songid = uniqid('s_');
	
	$st = $db->prepare("INSERT INTO songs (songid, authorid, songurl, createdtime, tags, name, summary, description)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
	$st->bindParam(1, $songid, SQLITE3_TEXT);
	$st->bindParam(2, $authorid, SQLITE3_TEXT);
	$st->bindParam(3, $_POST['url'], SQLITE3_TEXT);
	$st->bindValue(4, time(), SQLITE3_TEXT);
	$st->bindParam(5, $_POST["tags"], SQLITE3_TEXT);
	$st->bindParam(6, $_POST["songname"], SQLITE3_TEXT);
	$st->bindParam(7, $_POST["summary"], SQLITE3_TEXT);
	$st->bindParam(8, $_POST["songdesc"], SQLITE3_TEXT);
	$st->execute();
							
	header("Location: /viewsong.php?id=" . $songid);
?>