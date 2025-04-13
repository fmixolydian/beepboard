<?php

require '../config.php';
require '../functions.php';

function BB_404_song() {
	echo '<h2>404 (no such song)</h2>';
	echo '<p>You either specified an invalid ID, or the servers bugged somewhere
			and forgot to create the song.</p>';
}

function BB_410_song() {
	echo '<h2>410 (gone)</h2>';
	echo '<p>The song you tried to access was deleted by its owner, or staff.</p>';
	echo '<p>It can still be recovered by its author; however in a few weeks,
			it will be PURGED, and will show up as a <strong>404</strong>,
			lost forever, to the sands of time...</p>';
}

if (!array_key_exists('id', $_GET)) {
	http_response_code(400);
	echo "You must specify an ID!";
	die;
}


BB_sqlStatement("UPDATE songs SET views = views + 1 WHERE songid = :id",
					array(':id' => $_GET['id'])
				);

$data = BB_getSongdataById($_GET['id']);
if (!$data) {
	http_response_code(404);
	require '../header.php';
	BB_404_song();
	goto missing_song;
} 

if ($data['deleted']) {
	http_response_code(410);
	require '../header.php';
	BB_410_song();
	goto missing_song;
}

require '../header.php';

$author = BB_getUserdataById($data['authorid'])['username'];
$commentno = $db->querySingle('SELECT COUNT(*) FROM comments WHERE songid = \'' . $data['songid'] . '\'');

?>

<style>
</style>

<article class="song">


	<div class="SongLinks">
		<p>by <em><?= $author ?></em></p>
		<a target=_blank href="/api/downloadsong.php?id=<?= $data['songid'] ?>">
			<img class="SongPlatform" src="/assets/beepbox.png"/>
		</a>
	</div>
	
	
	<h1><?php echo $data['name'] ?> </h1>
	<p><?= $data['description'] ?> </p>
	
	<div class="horizontal">
		<img class="SongInteract" src="/assets/likes.png">
		
		<?php
			
			if (array_key_exists('token', $_COOKIE)) {
				$userdata = BB_getUserdataByToken($_COOKIE['token']);
				if ($userdata) {
					$userid = $userdata['userid'];
					if (!$db->querySingle("SELECT timestamp FROM interactions WHERE type = 'like' "
						 				. "AND songid = '" . $data['songid']
						 				. "' AND userid = '" . $userid . "'")) {
						echo '<a class="SongLike" href="/api/like.php?id=' . $data['songid'] . '">
									<img src="/assets/upvote.png">
								</a>';
					} else {
						echo '<a class="SongLike" href="/api/unlike.php?id=' . $data['songid'] . '">
									<img src="/assets/downvote.png">
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
		
		<img class="SongInteract" src="/assets/comments.png">
		<p class="SongCounter"> <?= $commentno ?> </p>
	</div>
	
	<div class="Comments">
	
		
<?php
	$q = BB_sqlStatement('SELECT * FROM comments WHERE songid = :song',
							array(':song' => $_GET['id']));
	
	if (array_key_exists('token', $_COOKIE)) {
		$clientuserdata = BB_getUserdataByToken($_COOKIE['token']);
		if ($clientuserdata) {
			$clientid = $clientuserdata['userid'];
		} else {
			$clientid = false;
		}
	} else {
		$clientid = false;
	}
	
	while ($data = $q->fetchArray(SQLITE3_ASSOC)) {
		# get username from userid
		$userdata = BB_getUserdataById($data['userid']);
		if (!$userdata) continue;
		$username = $userdata['username'];
		
		$content = '
		<div class="Comment">
			<div class="CommentAuthor">' . htmlentities($username) . '</div>
			<div class="CommentBody">
				<div class="CommentText">' . htmlentities($data['content']) . '</div>
				<div class="CommentDate" title="' . date(DATE_RFC2822, $data['timestamp']) .
					'">' . BB_time_ago($data['timestamp']);
		
		if ($clientid == $data['userid']) {
			$content .= " · <a href='/api/deletecomment.php?id=" . $data['commentid'] . "'>delete</a>";
		}
		
		$content .=
				'</div>
			</div>
		</div>
		';
		
		echo $content;
	}

/*
 .
				' · ' . '</div>
			</div>
		</div>
		'
*/

?>
	</div>
	
	
	<form action="/api/comment.php" method="post" class="Comment">
		<textarea name="content"></textarea>
		<input type="submit" value="Post Comment">
		<input type="hidden" name="songid" value="<?= $_GET['id'] ?>">
	</form>
	
<?php
	
	if (array_key_exists('token', $_COOKIE)) {
		$userdata = BB_getUserdataByToken($_COOKIE['token']);
		if ($userdata) {
			$userid = $userdata['userid'];
			
		}
	} else {
		echo '<p><a href="/login.php">Login</a> to post comments</p>';
	}

missing_song:

?>
	
	<div class="horizontal">
		<a class="SongLike" href="javascript:history.back()"><img src="/assets/back.png"></a>
		<p>&nbsp;Go back</p>
	</div>
	
</article>