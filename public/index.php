<?php

require '../header.php';

$db = new SQLite3(DB_PATH);

$no_users = $db->querySingle("SELECT COUNT(*) as count FROM users");
$no_songs = $db->querySingle("SELECT COUNT(*) as count FROM songs");

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
	<h1>Welcome to Beepboard!</h1>
	<p>Beepboard is a site where users can share beepbox songs, make playlists,
		and check the status of the Beepbox radio run by Impasaurus.</p>
	
	<p>We also reccomend you join <a href="https://discord.gg/fdZxm5SpAn">the beepbox discord server</a>,
		where you can talk with other people who make chiptunes such as those found here.</p>
	
	<h2>Stats</h2>
	<p><strong><?= $no_users ?></strong> users registered</p>
	<p><strong><?= $no_songs ?></strong> songs uploaded</p>
</article>
			
		</main>
	</body>
</html>