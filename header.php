<?php
	
	require 'config.php';
	require 'functions.php';
	
	# get user data
	if (array_key_exists('token', $_COOKIE)) {
		$data = BB_getUserdataByToken($_COOKIE['token']);
	} else {
		$data = null;
	}
	
	# get user theme
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="a bulletin board for beepbox songs">
		<meta name="keywords" content="beepbox, ultrabox, bulletin board, songs, social network,
		chiptune, instrumental, music, song, melody, composition, tool, free, online, square wave, NES, NSF, ultrabox, beepbox, jummbox, pandorasbox, modbox, sandbox, goldbox, wackybox, todbox">
		<meta name="application-name" content="beepboard">
		<meta name="author" content="fmixolydian">
		<title>beepboard</title>
		<link rel="stylesheet" href="/style.css">
		<style>
		body {
			background-color: #111;
		}
		</style>
		
		<script>
			function reload(url) {
				if (url != undefined) {
					window.location.href = url;
				}
				window.location.reload(true);
			}
		</script>
	</head>
	
	<body>
		<script src="/mods.js"></script>
		<iframe id="null" name="null"></iframe>
		<main>
			<header>
				<h1>beepboard<sub>0.3<sup>b2</sup></sub></h1>
				<p>a bulletin board for beepbox songs<br>
				<small>sorry if the ui is terrible, i suck at frontend design lmao</small></p>
			</header>
			
			<div class="HeadInfo">

<div class="left">
	<?php
		if ($data) {
			echo "<p>" . $data['username'] . "</p>";
		} else {
			echo "<p>You're not logged in.</p>";
		}
	?>
</div>

<div class="right">
	<?php
		if ($data) {
			echo "<p><a href='/api/logout.php'>Logout</a></p>";
		} else {
			echo "<p><a href='/login.php'>Login</a></p>";
		}
	?>
</div>
			</div>
			
			<nav>
				<a href="/index.php">home</a>
				<a href="/songs.php">song list</a>
				<a href="/submit.php">submit</a>
				<a href="/about.php">about</a>

			</nav>
