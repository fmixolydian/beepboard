<?php

require '../config.php';
require '../functions.php';

if (array_key_exists("token", $_COOKIE)) {
	
	$data = BB_getUserdataByToken($_COOKIE['token']);
	
	if ($data == NULL) {
		header('Location: /login.php');
		die;
	}
} else {
	header('Location: /login.php');
	die;
}

$song_count = $db->querySingle("SELECT COUNT(*) as count FROM songs WHERE authorid = '"
					. $data['userid'] . "' AND deleted = 0");

BB_default($_GET['after'], 0);

$q = BB_sqlStatement("SELECT * FROM songs WHERE authorid = :user AND deleted = 0 LIMIT 10 OFFSET :offset",
					array(':offset' => $_GET['after'], ':user' => $data['userid']));


require '../header.php';

?>
			<h2>my songs</h2>
			<div class="songs">

<?php
	if ($song_count == 0) {
		echo '
			<p class="textcenter">
				Looks like you haven\'t uploaded any songs yet.<br>
				<img src="/assets/downvote.png"> Click this link  to submit one! <img src="/assets/downvote.png">
			</p>
		';
	}
?>

<?php

while ($result = $q->fetchArray(SQLITE3_ASSOC)) {
	
	$username = $db->querySingle('SELECT username FROM users WHERE userid = \'' .
		$result['authorid'] . '\'');
	
	$commentno = $db->querySingle('SELECT COUNT(*) FROM comments WHERE songid = \'' .
		$result['songid'] . '\'');
	
	echo '
<article class="song">
	<div class="SongMeta">
		<div class="vertical">
			<p class="SongName"><a href="/viewsong.php?id=' . $result['songid'] . '">' .
				htmlentities($result['name']) . '</a></p>
			<p class="SongDesc">' . htmlentities($result['summary']) . '</p>
		</div>
		<div class="horizontal">
			<img title="likes" class="SongInteract" src="/assets/likes.png">
			<p title="likes" class="SongCounter">' . htmlentities($result['likes']) . '</p>
			<img title="clicks" class="SongInteract" src="/assets/downloads.png">
			<p title="clicks" class="SongCounter">' . htmlentities($result['downloads']) . '</p>
			<img title="page views" class="SongInteract" src="/assets/views.png">
			<p title="page views" class="SongCounter">' . htmlentities($result['views']) . '</p>
			<img title="comments" class="SongInteract" src="/assets/comments.png">
			<p title="comments" class="SongCounter">' . htmlentities($commentno) . '</p>
			<p class="dim"><span title="' . date(DATE_RFC2822, $result['createdtime']) .
			'">' . BB_time_ago($result['createdtime']) . '</span>
			· <a href="/api/deletesong.php?id=' . $result['songid'] . '"
				onclick="return confirm(\'Are you sure you want to delete this song?\')">delete</a>
			</p>
		</div>
	</div>
	
	<div class="SongData">
		<div class="Platforms">
			<a target=_blank href="/api/downloadsong.php?id=' . $result['songid'] . '">
				<img class="SongPlatform" src="/assets/beepbox.png"/>
			</a>
		</div>
	</div>
</article>
';
}

?>
			</div>
			
			<p class="textcenter"><a href="/submit.php">submit song</a></p>
			
			<footer>
				<p><a href=<?='songs.php?after='.max(($_GET['after']-10), 0) ?>>&lt;</a>
					Page <?= $_GET['after'] / 10 + 1 ?>
					<a href=<?='songs.php?after='.min(($_GET['after']+10), 0) ?>>&gt;</a>
			</footer>
		</main>
	</body>
</html>