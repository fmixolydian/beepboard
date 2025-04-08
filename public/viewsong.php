<?php

require '../config.php';

if (!array_key_exists('id', $_GET)) {
	http_response_code(400);
	echo "You must specify an ID!";
	die;
}

require '../header.php';

BB_sqlStatement("UPDATE songs SET views = views + 1 WHERE songid = :id",
					array(':id' => $_GET['id'])
				);

$data = BB_getSongdataById($_GET['id']);
$author = BB_getUserdataById($data['authorid'])['username'];

?>

<style>
	article {
		background-color: #222;
		width: calc(800px - 10px * 2);
		padding: 10px;
		min-height: 200px;
		position: relative;
	}
</style>

<article>
	
	<div class="SongLinks">
		<a target=_blank href="/api/downloadsong.php?id=<?= $data['songid'] ?>">
			<img class="SongPlatform" src="/assets/beepbox.png"/>
		</a>
	</div>
	
	<h1><?= $data['name'] ?> </h1>
	<p><?= $data['description'] ?> </p>
	
	<div class="horizontal">
		<img class="SongInteract" src="/assets/likes.png">
		
		<?php
			
			if (array_key_exists('token', $_COOKIE)) {
				$userid = BB_getUserdataByToken($_COOKIE['token']);
				if ($userid) {
					$userid = $userid['userid'];
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
	</div>
	
	<div class="Comments">
		
<?php
	$q = BB_sqlStatement('SELECT * FROM comments WHERE songid = :song',
							array(':song' => $_GET['id']));
	
	while ($data = $q->fetchArray(SQLITE3_ASSOC)) {
		# get username from userid
		$userdata = BB_getUserdataById($data['userid']);
		if (!$userdata) continue;
		$username = $userdata['username'];
		
		echo '
		<div class="Comment">
			<div class="CommentAuthor">' . $username . '</div>
			<div class="vertical">
				<div class="CommentBody">' . $data['content'] . '</div>
				<div class="CommentDate" title="' . date(DATE_RFC2822, $data['timestamp']) .
					'">' . BB_time_ago($data['timestamp']) .
				'</div>
			</div>
		</div>
		';
	}
?>
	</div>
	
	<div class="horizontal">
		<a class="SongLike" href="javascript:history.back()"><img src="/assets/back.png"></a>
		<p>&nbsp;Go back</p>
	</div>
	
	
</article>