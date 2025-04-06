<?php
	
	require 'config.php';
	
	$db = new SQLite3(DB_PATH);
	
	# get user data
	$st = $db->prepare("SELECT * FROM users WHERE token = :t");
	$st->bindParam(':t', $_COOKIE['token'], SQLITE3_TEXT);
	$data = $st->execute()->fetchArray();
	
	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>beepboard</title>
		<link rel="stylesheet" href="/style.css">
		<style>
		body {
			background-color: #111;
		}
		</style>
	</head>
	
	<body>
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
