<?php
	
	require 'config.php';
	require 'functions.php';
	
	# get user data
	if (array_key_exists('token', $_COOKIE)) {
		$data = BB_getUserdataByToken($_COOKIE['token']);
	} else {
		$data = null;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
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
		<iframe id="null" name="null"></iframe>
		<main>
			<header>
				<h1>beepboard<sub>0.1</sub></h1>
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

			</nav>
