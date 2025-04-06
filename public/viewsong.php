<?php

require '../config.php';

if (!array_key_exists('id', $_GET)) {
	http_response_code(400);
	echo "You must specify an ID!";
	die;
}

require '../header.php';

$db = new SQLite3(DB_PATH);

$st = $db->prepare("UPDATE songs SET views = views + 1 WHERE songid = :id");
$st->bindParam(':id', $_GET['id'], SQLITE3_TEXT);
$st->execute();

$st = $db->prepare("SELECT * FROM songs WHERE songid = :id");
$st->bindParam(':id', $_GET['id'], SQLITE3_TEXT);
$data = $st->execute()->fetchArray(SQLITE3_ASSOC);

$author = $db->querySingle("SELECT username FROM users WHERE userid = '" . $data['authorid'] . "'");

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
	<h1><?= $data['name'] ?> </h1>
	<p><?= $data['description'] ?> </p>
	<div class="horizontal">
		<img class="SongInteract" src="/assets/likes.png">
		
		<?php
			
			if (array_key_exists('token', $_COOKIE)) {
				$st = $db->prepare("SELECT userid FROM users WHERE token = :t");
				$st->bindParam(":t", $_COOKIE['token']);
				$userid = $st->execute()->fetchArray();
				if ($userid) {
					$userid = $userid[0];
					if (!$db->querySingle("SELECT timestamp FROM interactions WHERE type = 'like' "
						 				. "AND songid = '" . $data['songid']
						 				. "' AND userid = '" . $userid . "'")) {
						echo '<a class="SongLike" target=null onclick="reload()" href="/api/interact.php?type=like&id=' . $data['songid'] . '">
									<img src="/assets/upvote.png">
								</a>';
					}
				}
			}
			
		?>
		
		<p class="SongCounter"> <?= $data['likes'] ?> </p>
		
		<img class="SongInteract" src="/assets/downloads.png">
		<p class="SongCounter"> <?= $data['downloads'] ?> </p>
		
		<img class="SongInteract" src="/assets/views.png">
		<p class="SongCounter"> <?= $data['views'] ?> </p>
	</div>
</article>