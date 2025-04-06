<?php

require '../config.php';

$db = new SQLite3(DB_PATH);

if (array_key_exists("token", $_COOKIE)) {
	$name = $db->querySingle("SELECT username FROM users WHERE token = '" .
	                          $_COOKIE['token'] . "'");
	if ($name == NULL) {
		header('Location: /login.php');
		die;
	}
} else {
	header('Location: /login.php');
	die;
}

require '../header.php';

?>

<style>
	article {
		background-color: #222;
		width: calc(800px - 10px * 2);
		padding: 10px;
		min-height: 200px;
	}
</style>

<article>
	<form method="post" action="/api/submit.php">
	
		<label for="songname">Song Name</label>
		<input required="true" type="text" name="songname">
		
		<br>
		<br>
		
		<label for="summary">Song Summary</label>
		<input name="summary" type="text"></textarea>
		
		<br>
		<br>
		
		<label for="songdesc">Song Description</label>
		<textarea name="songdesc"></textarea>
		
		<br>
		<br>
		
		<label for="url">URL</label>
		<textarea required="true" name="url"></textarea>
		
		<br>
		<br>
		
		<label for="tags">Tags (comma separated)</label>
		<input type="text" name="tags">
		
		<br>
		<br>
		
		<input type="submit">

	</form>
</article>
			
		</main>
	</body>
</html>