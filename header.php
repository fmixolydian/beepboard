<?php
	
	require 'config.php';
	
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
			<nav>
				<a href="/songs.php">song list</a>
				<a href="/submit.php">submit</a>
				<a href="/login.php">register / login</a>
				<a href="/about.php">about</a>
			</nav>
