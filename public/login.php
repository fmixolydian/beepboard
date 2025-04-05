<?php

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
	<form method="post">
		<label for="username">Username</label>
		<input required="true" type="text" id="username" name="username">
		<br>
		<label for="password">Password</label>
		<input required="true" type="password" id="password" name="password">
		<br>
		<input type="submit" formaction="/api/login.php"    value="Login">
		<input type="submit" formaction="/api/register.php" value="Register">
	</form>
</article>
			
		</main>
	</body>
</html>